<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBan;
use App\Models\UserLoginHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class UserService extends Service
{

    public function model(): Model
    {
        return new User();
    }

    public function datatableTransaction($id)
    {
        $data = \App\Models\Transaction::with(['user:id,email'])
            ->where('payable_id', decrypt($id))
            ->orderBy('id', 'asc')
            ->get();

        $res = [];
        foreach ($data as $key => $d) {
            $balance = 0;
            if ($key == 0) {
                $balance = $data[0]->amount;
            } else if ($key > 0) {
                $amt = [];
                for ($a = 0; $a < $key; $a++) {
                    $amt[] = $data[$a+1]->type == 'debit' ? floatval($data[$a+1]->amount) : floatval(-$data[$a+1]->amount);
                }
                $amt = array_merge([floatval($data[0]->amount)], $amt);

                $balance = array_sum($amt);
            }
            $res[] = [
                'id' => $d->id,
                'user' => $d->user->email,
                'trx' => $d->uuid,
                'description' => $d->description,
                'amount' => number_format($d->amount),
                'type' => $d->type,
                'post_balance' => number_format($balance),
            ];
        }

        return DataTables::of($res)
            ->editColumn('amount', function ($d) {
                $out = '<span class="text-success">'. $d['amount'] .'</span>';
                if ($d['type'] == \App\Models\Transaction::TYPE_CREDIT) {
                    $out = '<span class="text-danger">'. '(' . $d['amount'] . ')' .'</span>';
                }

                return $out;
            })
            ->rawColumns(['amount'])
            ->make(true);
    }

    public function datatableLoginHistory($id)
    {
        $data = UserLoginHistory::with('user:id,email,username')
            ->where('user_id', decrypt($id))
            ->get();

        return DataTables::of($data)
            ->addColumn('user', function ($d) use ($id) {
                return '
                    <p class="mb-0" style="font-size: 12px; font-weight: bold;">'. $d->user->username .'</p>
                    <a href="'. route('users.show', $id) .'" class="mb-0" style="font-size: 11px; text-decoration: none;">'. $d->user->email .'</a>
                ';
            })
            ->editColumn('login_at', function ($d) {
                return '
                    <div class="text-center">
                        <p class="mb-0" style="font-size: 12px;">'. date('Y-m-d H:i:s', strtotime($d->login_at)) .'</p>
                        <p class="mb-0" style="font-size: 10px;">'. Carbon::parse($d->login_at)->diffForHumans() .'</p>
                    </div>
                ';
            })
            ->rawColumns(['user', 'login_at'])
            ->make(true);
    }

    public function datatable($request)
    {
        $query = $this->model()->query();

        if ($request['status'] == 'active') {
            $query->where('email_verified_at', '!=', null)
                ->where('phone_verified_at', '!=', null)
                ->where('kyc_verified_at', '!=', null)
                ->where('status', User::ACTIVE);
        }

        Log::debug('query', [$query]);

        if ($request['status'] == 'banned') {
            $query->where('status', User::BANNED);
        }

        if ($request['status'] == 'inactive') {
            $query->where('status', User::INACTIVE);
        }

        if ($request['status'] == 'ue') {
            $query->where('email_verified_at', null);
        }

        if ($request['status'] == 'up') {
            $query->where('phone_verified_at', null);
        }

        if ($request['status'] == 'uk') {
            $query->where('kyc_verified_at', null);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('namedata', function ($data) {
                return '
                    <p class="mb-0">'. $data->fullname .'</p>
                    <a href="'. route('users.show', encrypt($data->id)) .'">'. $data->email .'</a>
                ';
            })
            ->addColumn('action', function ($data) {
                return '
                    <a class="btn btn-primary btn-sm cursor-pointer"
                        href="'. route('users.show', encrypt($data->id)) .'">
                        <i class="fa fa-eye"></i> '. __('global.detail') .'
                    </a>
                ';
            })
            ->addColumn('emaildata', function ($data) {
                return '
                    <p class="mb-0 text-dark-grey">'. $data->username .'</p>
                    <p class="mb-0 text-dark-grey fs-12">'. $data->phone .'</p>
                ';
            })
            ->addColumn('balance', function ($data) {
                return $data->balance;
            })
            ->addColumn('joined_at', function ($data) {
                return '
                    <p class="mb-0">'. date('Y-m-d H:i:s', strtotime($data->created_at)) .'</p>
                    <p class="mb-0">'. Carbon::parse($data->created_at)->diffForHumans() .'</p>
                ';
            })
            ->rawColumns(['action', 'namedata', 'emaildata', 'balance', 'joined_at'])
            ->make(true);
    }

    public function all()
    {

    }

    public function show($id)
    {
        $id = decrypt($id);
        $user = $this->model()
            ->with(['country:id,name', 'state:id,name', 'city:id,name'])
            ->find($id);

        return $user;
    }

    public function ban(Request $request, $id)
    {
        $user = $this->model()->find(decrypt($id));
        $user->status = User::BANNED;

        if ($user->save()) {
            $ban = new UserBan();
            $ban->user_id = decrypt($id);
            $ban->reason = $request->reason;
            $ban->save();
        }

        return [
            'message' => __('global.success_update_user_status'),
            'data' => ['id' => $id],
            'status' => 200,
        ];
    }

    public function update($request, $id)
    {
        $id = decrypt($id);
        $user = $this->model()->find($id);
        $current_email = $user->email;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->country_id = $request->country;
        $user->state_id = $request->state;
        $user->city_id = $request->city;
        $user->postalcode = $request->postal;

        if ($request->ev) {
            $user->email_verified_at = Carbon::now();
        }

        if ($request->pv) {
            $user->phone_verified_at = Carbon::now();
        }

        if ($request->kv) {
            $user->kyc_verified_at = Carbon::now();
        }

        if ($request->fav) {
            $user->is_two_factor = true;
        }

        $user->save();

        return [
            'message' => __('global.success_update_user'),
            'data' => [
                'view' => route("users.show", encrypt($user->id)),
            ],
            'status' => 200,
        ];
    }

    public function updateBalance($id)
    {
        $user = User::find(decrypt($id));
        $balance = $user->balance;

        return [
            'message' => 'Success update balance',
            'data' => $balance,
            'status' => 200,
        ];
    }

    public function submitBalance($request, $id)
    {
        $user = $this->model()->find(decrypt($id));

        if ($request->type == 'add') {
            $user->deposit($request->balance, $request->description ?? null, Transaction::TYPE_DEBIT);
        } else {
            $user->deposit($request->balance, $request->description ?? null, Transaction::TYPE_CREDIT);
        }

        return [
            'message' => 'Success submit balance',
            'data' => [],
            'status' => 200,
        ];
    }

    public function store($request)
    {

    }

    public function destroy($id)
    {

    }
}
