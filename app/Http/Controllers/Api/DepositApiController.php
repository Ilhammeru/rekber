<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DepositService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositApiController extends Controller
{
    public $service;

    public function __construct()
    {
        $this->service = new DepositService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function updateRule($gateawayId)
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

        // calculate payable amount
        $payable = $this->service->calculatePayable($request->amount, $request->payment);
        $request['payable'] = $payable['payable'];
        $request['fixed_charge'] = $payable['fixed_charge'];
        $request['percent_charge'] = $payable['percent_charge'];
        $request['charge_total'] = $payable['total_charge'];

        return $this->notify($this->service->deposit($request));
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
