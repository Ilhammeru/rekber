<?php

namespace App\Http\Controllers;

use App\Jobs\EmailNotificationForCheckPayment;
use App\Models\User;
use App\Services\DepositService;
use App\Services\PaymentGateawayService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    public $trx;
    public $service;
    public $payment;

    public function __construct()
    {
        $this->trx = new TransactionService;
        $this->payment = new PaymentGateawayService;
        $this->service = new DepositService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = __('global.deposit');
        return view('deposit.index', compact('pageTitle'));
    }

    public function ajax()
    {
        $id = auth()->id();
        $param['id'] = $id;

        return $this->service->datatable($param);
    }

    public function updateDepositRule($gateawayId)
    {
        $default_currency = 'IDR';
        $data = \App\Models\PaymentGateaway\PaymentGateawayDetail::with('payment:id,type')
            ->find(base64url_decode($gateawayId));
        $is_manual = $data->payment->type == \App\Models\PaymentGateaway\PaymentGateawaySetting::MANUAL_TYPE ? true : false;
        $need_to_rate = $data->currency != $default_currency ? true : false;

        if (!$is_manual) {
            // get channel
            $payment_setting = \App\Models\PaymentGateaway\PaymentGateawaySetting::select('id', 'channel')
                ->find($data->payment_gateaway_setting_id);
            $channel = json_decode($payment_setting->channel, true) ?? [];
        }

        return $this->success('success', [
            'detail' => $data,
            'is_manual' => $is_manual,
            'need_to_rate' => $need_to_rate,
            'detail_currency' => $data->currency,
            'channel' => $channel ?? [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = __("global.deposit");
        $payments = $this->payment->showAllPaymentWithCurrency();

        return view('deposit.create', compact('payments', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment' => 'required',
        ]);
        $minimum = $request->minimum_value;
        $maximum = $request->maximum_value;
        if ((float) $request->amount < (float) $minimum) {
            $validator->after(function ($validator) {
                $validator->errors()->add('amount', __('global.amount_should_be_greater'));
            });
        }
        if ((float) $request->amount > (float) $maximum) {
            $validator->after(function ($validator) {
                $validator->errors()->add('amount', __('global.amount_should_be_less'));
            });
        }

        if ($validator->fails()) {
            return $this->validationErrors($validator);
        }

        return $this->notify($this->service->deposit($request));
        // return $this->success('success', [
        //     'amount' => (float) $request->amount,
        //     'minimum' => (float) $minimum,
        //     'maximum' => (float) $maximum,
        //     'all' => $request->all(),
        // ]);
    }

    /**
     * Function to show detail request deposit user
     * This form is showed for admin to take an action
     */
    public function detailUserDeposit($trx)
    {
        $user = User::select('id')->where('email', request()->get('a'))->first();
        if (!$user) {
            $user = auth()->user();
        }
        $via = request()->get('via');

        /**
         * If request came from email notification
         * Then create manual login
         */
        if (!$via) {
            if (Auth::id() != $user->id) {
                Auth::loginUsingId($user->id);
            }
        }

        $data = $this->trx->getTransactionDetail($trx);
        $isManualPayment = true;
        if ($data['depositAutomatic']) {
            $isManualPayment = false;
        }
        $transaction_data = [
            'request_amount' => $isManualPayment ? number_format($data['depositManual']['amount']) : number_format($data['depositAutomatic']['amount']),
            'transfer_amount' => $isManualPayment ? number_format((float) $data['depositManual']['amount'] + (float) $data['charge']['total_charge']) : number_format((float) $data['depositAutomatic']['amount'] + (float) $data['charge']['total_charge']),
        ];

        // build status
        $status = $isManualPayment ? $this->manualDepositStatus($data) : $this->automaticDepositStatus($data);

        // return $data;
        $pageTitle = $user->hasRole('admin') ? __('global.confirm') : __('global.detail_deposit');

        return view('deposit.detail', compact('data', 'pageTitle', 'isManualPayment', 'transaction_data', 'status'));
    }

    private function manualDepositStatus($data)
    {
        if ($data->depositManual->status == \App\Models\DepositManual::WAITING_UPLOAD) {
            return '<span class="badge badge-dange">'. __('global.waiting_upload') .'</span>';
        } elseif ($data->depositManual->status == \App\Models\DepositManual::APPROVE) {
            return '<span class="badge badge-success">'. __('global.confirmed') .'</span>';
        } elseif ($data->depositManual->status == \App\Models\DepositManual::PENDING) {
            return '<span class="badge badge-warning">'. __('global.waiting_confirm') .'</span>';
        } elseif ($data->depositManual->status == \App\Models\DepositManual::DECLINE) {
            return '<span class="badge badge-danger">'. __('global.decline') .'</span>';
        }
    }

    private function automaticDepositStatus($data)
    {
        if ($data->depositAutomatic->status == \App\Models\AutomaticDeposit::SUCCESS) {
            return '<span class="badge badge-success">'. __('global.confirmed') .'</span>';
        } else if ($data->depositAutomatic->status == \App\Models\AutomaticDeposit::WAITING_PAYMENT) {
            return '<span class="badge badge-warning">'. __('global.waiting_payment') .'</span>';
        } else if ($data->depositAutomatic->status == \App\Models\AutomaticDeposit::FAILED) {
            return '<span class="badge badge-danger">'. __('global.failed') .'</span>';
        }
    }

    public function declineForm($trx)
    {
        $view = view('deposit.decline-form', compact('trx'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    public function submitDeclineDeposit(Request $request, $trx)
    {
        return $this->notify($this->service->declineDeposit($request, $trx));
    }

    public function showDetailImage()
    {
        $image = decrypt(request()->get('img'));
        $view = view('deposit.detail-image', compact('image'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    /**
     * Function to confirm deposit from admin account
     * @param string trx
     */
    public function doConfirmDeposit($trx)
    {
        return $this->notify($this->service->doConfirm($trx));
    }

    /**
     * Function to show confiration payment only for Manual Gateaway
     * @param string trx
     */
    public function confirmManualPayment($trx)
    {
        $pageTitle = 'Confirm Payment';
        $raw = $this->trx->getTransactionDetail($trx);

        if ($raw['depositManual']['status'] != 2) {
            return redirect()->to(route('deposit.index'));
        }
        // return $raw;
        // EmailNotificationForCheckPayment::dispatch($raw);
        $fields = json_decode($raw['charge']['gateaway']['user_field'], TRUE);

        return view('deposit.proof_form', compact('pageTitle', 'fields', 'trx', 'raw'));
    }

    public function confirmPayment(Request $request, $trx)
    {
        DB::beginTransaction();
        try {
            $raw = $this->trx->getTransactionDetail($trx);
            $fields = json_decode($raw['charge']['gateaway']['user_field'], TRUE);

            $payload = [];
            $out = [];
            foreach ($fields as $key => $field) {
                $label = $field['label'];
                $slug = strtolower(implode('_', explode(' ', $label)));
                if (strtolower($field['type']) == 'file') {
                    $file = $request->file($slug);
                    $filename = date('YmdHis') . '_uf_trx.' . $file->getClientOriginalExtension();
                    Storage::putFileAs('public/deposit/trx', $file, $filename);
                    $payload[$slug] = 'storage/deposit/trx/' . $filename;
                }
                if (strtolower($field['type']) == 'text' || strtolower($field['type']) == 'textarea') {
                    $payload[$slug] = $request->$slug;
                }
            }

            // $out['trx_id'] = decrypt($trx);
            $out['user_id'] = Auth::id();
            $out['amount'] = $raw['amount'];
            $out['status'] = \App\Models\DepositManual::PENDING;
            $out['payment_gateaway_id'] = $raw['charge']['payment_gateaway_id'];
            $out['field_value'] = json_encode($payload);
            \App\Models\DepositManual::where('trx_id', decrypt($trx))
                ->update($out);

            // send notification to admin to check this payment
            EmailNotificationForCheckPayment::dispatch($raw, $out)->afterCommit();

            DB::commit();

            return $this->success(__('global.success_upload_payment'), ['url' => route('users.transaction.get-status', $trx)]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->error(
                generate_message($th, __('global.failed_to_upload_payment'))
            );
        }
    }

    public function paymentInstruction($trx)
    {
        $pageTitle = 'Payment Instructino';
        $data = $this->trx->getTransactionDetail($trx, true);
        // validasi user
        if (auth()->id() != $data['user']['id']) {
            abort(419);
        }

        $channels = json_decode($data['charge']['gateaway']['payment']['channel'], true);
        $third_party_response = json_decode($data['depositAutomatic']['request_transaction_response'], true);
        $payment_method = $third_party_response['data']['payment_method'];
        $selected_channel = collect($channels)->filter(function ($item) use ($payment_method) {
            return $item['code'] == $payment_method;
        })->values()[0];

        $instructions = $third_party_response['data']['instructions'];
        $expire = Carbon::parse($third_party_response['data']['expired_time'])->timezone('Asia/Jakarta')->format('D, d F Y H:i');
        $expire_raw = Carbon::parse($third_party_response['data']['expired_time'])->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $seconds = Carbon::parse($third_party_response['data']['expired_time'])->timezone('Asia/Jakarta')->secondsSinceMidnight();

        $urlStatus = url('deposit/users') . '/' . encrypt(base64url_decode($trx)) . '?via=web';

        return view('deposit.payment-instruction', compact(
            'pageTitle', 'instructions',
            'third_party_response', 'expire',
            'seconds', 'selected_channel',
            'trx', 'urlStatus', 'expire_raw'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
