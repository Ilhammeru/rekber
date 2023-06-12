<?php

namespace App\Http\Controllers;

use App\Jobs\EmailNotificationForCheckPayment;
use App\Models\User;
use App\Services\DepositService;
use App\Services\PaymentGateawayService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    }

    public function updateDepositRule($gateawayId)
    {
        $default_currency = 'IDR';
        $data = \App\Models\PaymentGateaway\PaymentGateawayDetail::with('payment:id,type')
            ->find(decrypt($gateawayId));
        $is_manual = $data->payment->type == \App\Models\PaymentGateaway\PaymentGateawaySetting::MANUAL_TYPE ? true : false;
        $need_to_rate = $data->currency != $default_currency ? true : false;

        return $this->success('success', [
            'detail' => $data,
            'is_manual' => $is_manual,
            'need_to_rate' => $need_to_rate,
            'detail_currency' => $data->currency,
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
        if (Auth::id() != $user->id) {
            Auth::loginUsingId($user->id);
        }
        $data = $this->trx->getTransactionDetail($trx);
        // return $data;
        $pageTitle = __('global.confirm');

        return view('deposit.detail', compact('data', 'pageTitle'));
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

    }

    /**
     * Function to show confiration payment only for Manual Gateaway
     * @param string trx
     */
    public function confirmManualPayment($trx)
    {
        $pageTitle = 'Confirm Payment';
        $raw = $this->trx->getTransactionDetail($trx);
        // EmailNotificationForCheckPayment::dispatch($raw);
        $fields = json_decode($raw->charge->gateaway->user_field, TRUE);

        return view('deposit.proof_form', compact('pageTitle', 'fields', 'trx'));
    }

    public function confirmPayment(Request $request, $trx)
    {
        DB::beginTransaction();
        try {
            $raw = $this->trx->getTransactionDetail($trx);
            $fields = json_decode($raw->charge->gateaway->user_field, TRUE);

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

            $out['trx_id'] = decrypt($trx);
            $out['user_id'] = Auth::id();
            $out['amount'] = $raw->amount;
            $out['status'] = \App\Models\DepositManual::PENDING;
            $out['payment_gateaway_id'] = $raw->charge->payment_gateaway_id;
            $out['field_value'] = json_encode($payload);
            \App\Models\DepositManual::create($out);

            // send notification to admin to check this payment
            EmailNotificationForCheckPayment::dispatch($raw, $out)->afterCommit();

            DB::commit();

            return $this->success('success', ['url' => route('users.transaction.get-status', $trx)]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
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
