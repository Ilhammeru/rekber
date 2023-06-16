<?php

namespace App\Services;

use App\Jobs\ConfirmedDepositJob;
use App\Jobs\DeclineDepositJob;
use App\Models\AutomaticDeposit;
use App\Models\DepositManual;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DepositService extends Service
{
    public const DESC_DEPOSIT = 'Top up wallet';
    public $transaction;
    public $gateaway;
    public $charge;
    public $tripay;

    public function __construct()
    {
        $this->transaction = new TransactionService;
        $this->gateaway = new PaymentGateawayService;
        $this->charge = new ChargeService;
        $this->tripay = new Tripay;
    }

    public function model(): Model
    {
        // Object Model
        return new DepositManual();
    }

    public function getUserField($trx)
    {
        $charge = \App\Models\Charge::select('payment_gateaway_id')
            ->where('trx_id', decrypt($trx))->first();
        $gateaway = \App\Models\PaymentGateaway\PaymentGateawayDetail::select('user_field')
            ->find($charge->payment_gateaway_id);
        $fields = json_decode($gateaway->user_field, true);

        return $fields;
    }

    public function getTransactionDetail($trx)
    {
        $data = \App\Models\Transaction::with(['charge.gateaway'])
            ->where('uuid', decrypt($trx))
            ->first();

        return $data;
    }

    /**
     * Store new user deposit
     */
    public function deposit(\Illuminate\Http\Request $request)
    {
        DB::beginTransaction();

        try {
            $transactionType = $this->gateaway->getTransactionType($request->payment, 'child');

            /**
             * Create transaction
             */
            $user = \App\Models\User::find(Auth::id());

            /**
             * This function will return uuid of transaction table
             */
            $trx = $user->deposit((float) $request->amount, self::DESC_DEPOSIT, \App\Models\Transaction::TYPE_DEBIT, false);

            /**
             * Create Charge
             */
            $request['trx_id'] = $trx;
            $this->charge->store($request);

            /**
             * Take decision to user
             * If transaction type is 'manual' then direct customer to payment proof page
             * Otherwise create direct transaction via automatic gateaway
             */
            if ($transactionType == \App\Models\PaymentGateaway\PaymentGateawaySetting::MANUAL_TYPE) {
                /**
                 * Create manual deposit
                 */
                DepositManual::create([
                    'user_id' => auth()->id(),
                    'trx_id' => $trx,
                    'amount' => (float) $request->amount,
                    'payment_gateaway_id' => base64url_decode($request->payment),
                    'status' => DepositManual::WAITING_UPLOAD,
                    'field_value' => null,
                ]);

                $out = [
                    'data' => [
                        'url' => route('deposit.user-proof-form', encrypt($trx)),
                    ],
                    'status' => 200,
                    'message' => __('global.deposit_manual_pending'),
                ];
                DB::commit();
                goto out;
            } else {
                $out = $this->automaticDeposit($request, $trx);
            }

            out:

            return $out;
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'status' => 500,
                'message' => generate_message($th, __('global.deposit_failed')),
                'data' => [],
            ];
        }
    }

    private function automaticDeposit(\Illuminate\Http\Request $request, $trx)
    {
        // get channel detail
        $channel = $this->gateaway->getAutomaticChannel(
            base64url_decode($request->payment),
            $request->channel
        );

        $product = [
            [
                'sku'         => 'FB-06',
                'name'        => 'Deposit - ' . auth()->user()->email,
                'price'       => $request->payable,
                'quantity'    => 1,
                'product_url' => null,
                'image_url'   => null,
            ],
        ];
        $user = [
            'email' => auth()->user()->email,
            'username' => auth()->user()->username,
            'phone' => auth()->user()->phone,
        ];

        $return_url = route('deposit.payment-instruction', base64url_encode($trx));

        // communicate the third party
        $payload = $this->tripay->createPayloadRequestTransaction($product, $user, $request->channel, $trx, $return_url);
        $out = $this->tripay->requestTransaction($payload);

        if (!$out['success']) {
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $out['message'],
                'data' => [],
            ];
        }

        // save
        AutomaticDeposit::create([
            'trx_id' => $trx,
            'payment_gateaway_id' => base64url_decode($request->payment),
            'channel_code' => $request->channel,
            'channel_name' => $channel['name'],
            'channel_detail' => json_encode($channel),
            'user_id' => auth()->id(),
            'status' => AutomaticDeposit::WAITING_PAYMENT,
            'amount' => $request->amount,
            'request_transaction_payload' => json_encode($payload),
            'request_transaction_response' => json_encode($out),
        ]);

        DB::commit();
        return [
            'status' => 200,
            'message' => 'Success create transaction',
            'data' => $out['data'],
        ];
    }

    public function calculatePayable($amount, $gateawayId)
    {
        $payment = \App\Models\PaymentGateaway\PaymentGateawayDetail::with('payment:id,type')
            ->find(base64url_decode($gateawayId));

        $percent = (float) $amount * $payment->percent_charge / 100;
        $out = ((float) $amount + (float) $percent) + (float) $payment->fixed_charge;

        return [
            'payable' => $out,
            'fixed_charge' => $payment->fixed_charge,
            'percent_charge' => $payment->percent_charge,
            'total_charge' => $out - (float) $amount,
        ];
    }

    public function datatable($request)
    {
        $query = \App\Models\DepositManual::query();
        $query->selectRaw('
                trx_id,user_id,amount,payment_gateaway_id,status,id
            ')
            ->with(['gateaway:payment_gateaway_setting_id,id', 'gateaway.payment:id,name,type', 'user:id,email']);
        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        $manual = $query->get()->toArray();

        $query = \App\Models\AutomaticDeposit::query();
        $query->selectRaw('
                trx_id,user_id,payment_gateaway_id,status,amount,id
            ')
            ->with(['gateaway:payment_gateaway_setting_id,id', 'gateaway.payment:id,name,type', 'user:id,email']);

        if (auth()->user()->hasRole('user')) {
            $query->where('user_id', auth()->id());
        }
        $auto = $query->get()->toArray();

        $data = array_merge($manual, $auto);

        return DataTables::of($data)
            ->addColumn('payment_gateaway', function ($d) {
                return '<p class="fw-bolder mb-0">'. $d['gateaway']['payment']['name'] .'</p>';
            })
            ->editColumn('amount', function ($d) {
                return number_format($d['amount']);
            })
            ->editColumn('status', function ($d) {
                $out = $d['gateaway']['payment']['type'] == 'manual' ? $this->manualDepositStatus($d['status']) : $this->automaticDepositStatus($d['status']);

                return $out;
            })
            ->addColumn('action', function ($d) {
                $url = url('deposit/users') . '/' . encrypt($d['trx_id']) . '?a=' . $d['user']['email'] . '&via=web';
                $urlUpload = url('deposit/users/confirm') . '/' . encrypt($d['trx_id']);

                $btnUpload = '
                    <a class="btn btn-sm border border-warning text-warning"
                        href="'. $urlUpload .'">
                        <i class="fa fa-upload text-warning"></i>
                        Upload
                    </a>
                ';

                $btnDetail = '
                    <a class="btn bg-transparent btn-sm border border-primary text-primary"
                        href="'. $url .'">
                        <i class="fa fa-eye text-primary"></i>
                        '. __('global.detail') .'
                    </a>
                ';

                $out = $btnDetail;
                if ($d['status'] == 2 && $d['gateaway']['payment']['type'] == 'manual') {
                    $out .= $btnUpload;
                }

                return $out;
            })
            ->rawColumns(['amount', 'status', 'payment_gateaway', 'action'])
            ->make(true);
    }

    private function manualDepositStatus($data)
    {
        if ($data == \App\Models\DepositManual::WAITING_UPLOAD) {
            return '<span class="badge badge-dange">'. __('global.waiting_upload') .'</span>';
        } elseif ($data == \App\Models\DepositManual::APPROVE) {
            return '<span class="badge badge-success">'. __('global.confirmed') .'</span>';
        } elseif ($data == \App\Models\DepositManual::PENDING) {
            return '<span class="badge badge-warning">'. __('global.waiting_confirm') .'</span>';
        } elseif ($data == \App\Models\DepositManual::DECLINE) {
            return '<span class="badge badge-danger">'. __('global.decline') .'</span>';
        }
    }

    private function automaticDepositStatus($data)
    {
        if ($data == \App\Models\AutomaticDeposit::SUCCESS) {
            return '<span class="badge badge-success">'. __('global.confirmed') .'</span>';
        } else if ($data == \App\Models\AutomaticDeposit::WAITING_PAYMENT) {
            return '<span class="badge badge-warning">'. __('global.waiting_payment') .'</span>';
        } else if ($data == \App\Models\AutomaticDeposit::FAILED) {
            return '<span class="badge badge-danger">'. __('global.failed') .'</span>';
        }
    }

    public function doConfirm($trxId)
    {
        DB::beginTransaction();

        try {
            // check type of transaction
            $payment_gateaway_id = \App\Models\Charge::select('payment_gateaway_id')
                ->where('trx_id', base64url_decode($trxId));
            $paymentType = $this->gateaway->getTransactionType(base64url_encode($payment_gateaway_id->payment_gateaway_id));

            if ($paymentType == 'manual') {
                $data = $this->model()->where('trx_id', base64url_decode($trxId))->first();
                $data->status = 1;
                $data->save();
            } else {
                $data = \App\Models\AutomaticDeposit::where("trx_id", base64url_decode($trxId));
                $data->status = \App\Models\AutomaticDeposit::SUCCESS;
                $data->save();
            }

            $user = User::find($data->user_id);
            $user->confirmDeposit($trxId);

            // send notification to user
            ConfirmedDepositJob::dispatch($user, $data)->afterCommit();

            DB::commit();

            return [
                'status' => 200,
                'message' => __('global.success_confirm_deposit'),
                'data' => [],
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'status' => 500,
                'message' => generate_message($th, __('global.failed_confirm_deposit')),
                'data' => [],
            ];
        }
    }

    public function declineDeposit(Request $request, $trx)
    {
        $data = $this->model()->where('trx_id', decrypt($trx))->first();
        $data->status = DepositManual::DECLINE;
        $data->reason = $request->reason;
        $data->save();

        // send notification to user
        DeclineDepositJob::dispatch($trx);

        return [
            'status' => 200,
            'message' => __("global.success_decline_deposit"),
            'data' => [
                'url' => route('deposit.index')
            ],
        ];
    }

    public function all()
    {

    }

    public function show($id)
    {

    }

    public function update($request, $id)
    {

    }

    public function store($request, $additional = null)
    {

    }

    public function destroy($id)
    {

    }
}
