<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateaway\PaymentGateawaySetting;
use App\Services\PaymentGateawayService;
use App\Services\Tripay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentGateawaySettingController extends Controller
{
    public $service;
    public $tripay;

    const RESOURCE = 'payment_gateaway.';

    public function __construct()
    {
        $this->service = new PaymentGateawayService;
        $this->tripay = new Tripay;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $pageTitle = $type == 'manual' ? __('global.manual_gateaway') : __('global.automatic_gateaway');
        $source = self::RESOURCE . 'manual.index';
        if ($type == 'automatic') {
            $source = self::RESOURCE . 'automatic.index';
        }
        return view($source, compact('pageTitle', 'type'));
    }

    /**
     * Show datatable
     *
     * @return \Yajra\DataTables
     */
    public function ajax($type)
    {
        return $this->service->datatable($type);
    }

    /**
     * Show datatable for automatic payment gateaway
     *
     * @return \Yajra\DataTables
     */
    public function ajaxAutomatic()
    {
        return $this->service->datatableAutomatic();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // delete current cache if exist
        Cache::forget('user_data_cache');

        $pageTitle = __('global.create') . ' ' . __('global.payment_gateaway');
        $data = null;
        $type = 'manual';
        $id = 0;

        return view(self::RESOURCE . 'manual.edit', compact('pageTitle', 'data', 'type', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'currency' => 'required',
            'rate' => 'required|integer',
            'minimum' => 'required|integer',
            'maximum' => 'required|integer',
            'fix_charge' => 'required|integer',
            'percent_charge' => 'required|integer',
            'instruction' => 'required',
            'user_data' => 'required',
        ]);

        return $this->notify($this->service->store($request, 'manual'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentGateaway\PaymentGateawaySetting  $paymentGateawaySetting
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentGateawaySetting $paymentGateawaySetting)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentGateaway\PaymentGateawaySetting  $paymentGateawaySetting
     * @return \Illuminate\Http\Response
     */
    public function editAutomatic($id)
    {
        $data = $this->service->show($id, PaymentGateawaySetting::AUTO_TYPE);
        $pageTitle = $data->name;
        $configuration = json_decode($data->configuration, TRUE);

        return view(self::RESOURCE . 'automatic.edit', compact('data', 'id', 'pageTitle', 'configuration'));
    }

    public function updateCurrency($id)
    {
        $all_currency = [
            'USD',
            'IDR',
            'JPY',
            'INR',
        ];
        $data = $this->service->show($id, PaymentGateawaySetting::AUTO_TYPE, ['id']);
        $currency = collect($data->details)->pluck('currency')->all();

        foreach ($currency as $c) {
            $search = array_search($c, $all_currency);
            if ($search >= 0) {
                unset($all_currency[$search]);
            }
        }
        $all_currency = array_values($all_currency);

        return $this->success('Success', $all_currency);
    }

    public function initDetailCurrency($id)
    {
        $data = $this->service->show($id, PaymentGateawaySetting::AUTO_TYPE, ['id']);
        $details = $data->details;
        $view = view(self::RESOURCE . 'automatic.detail-currency', compact('details'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    /**
     * Function to show all tripay channel that has been saved in local database
     * @param string id (encrpted id)
     *
     * @return \Illuminate\Http\Response
     */
    public function localChannelTripay($id)
    {
        $data = $this->service->show($id, PaymentGateawaySetting::AUTO_TYPE, ['channel', 'id'], 'single');
        $channels = json_decode($data->channel, TRUE) ?? [];
        $view = view(self::RESOURCE . 'automatic.tripay.channel-detail', compact('channels', 'id'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    /**
     * Function to show all available channel by hitting tripay endpoint
     *
     * @param string id (encypted id)
     *
     * @return \Illuminate\Http\Response
     */
    public function generateTripayChannel($id)
    {
        $raw = $this->tripay->generateServerChannel();
        if (!$raw['success']) {
            session()->flash('failed_generate_tripay_channel', $raw['message']);
        } else {
            session()->forget('failed_generate_tripay_channel');
        }

        $channels = $raw['data'] ?? [];
        if (count($channels) > 0) $this->service->saveServerTripayChannel($channels, $id);

        $view = view(self::RESOURCE . 'automatic.tripay.channel-detail', compact('channels', 'id'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    public function addCurrencyForm(Request $request, $id)
    {
        $currency = $request->currency;
        $data = $this->service->show($id, PaymentGateawaySetting::AUTO_TYPE, ['id', 'name']);
        $details = $data->details;
        $view = view(self::RESOURCE . 'automatic.new-currency-form', compact('details', 'currency', 'data'))->render();

        //update currency
        $all_currency = $this->service->updateCurrency($id, $currency);

        return $this->success('Success', ['view' => $view, 'all_currency' => $all_currency]);
    }

    public function storeAutomatic(Request $request, $id)
    {
        // validate detail
        $details = $request->details;
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
        ]);
        $this->validateDetailCurrency($details, $validator);

        if ($validator->fails()) return $this->validationErrors($validator);

        return $this->notify($this->service->storeAutomatic($request, $id));
        // return $this->success('success', $request->all());
    }

    /**
     * In case request details is has empty field, add error to current validator bags
     *
     * @param array $data
     * @param \Illuminate\Support\Facades\Validator
     */
    private function validateDetailCurrency($data, $validator)
    {
        $name = collect($data)->pluck('name')->all();
        $minimum = collect($data)->pluck('minimum')->all();
        $maximum = collect($data)->pluck('maximum')->all();
        $fix_charge = collect($data)->pluck('fix_charge')->all();
        $percent_charge = collect($data)->pluck('percent_charge')->all();
        $rate = collect($data)->pluck('rate')->all();
        $symbol = collect($data)->pluck('symbol')->all();

        if (
            count($data) != count(array_filter($name)) ||
            count($data) != count(array_filter($minimum)) ||
            count($data) != count(array_filter($maximum)) ||
            count($data) != count(array_filter($fix_charge)) ||
            count($data) != count(array_filter($percent_charge)) ||
            count($data) != count(array_filter($rate)) ||
            count($data) != count(array_filter($symbol))
        ) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'details', 'Make sure all field in the currency section in filled'
                );
            });
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentGateaway\PaymentGateawaySetting  $paymentGateawaySetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        // delete current cache if exist
        Cache::forget('user_data_cache');

        $data = $this->service->show($id, $type);
        $pageTitle = $data->name;

        $details = $this->service->getUserField($id);
        if ($details) {
            Cache::put('user_data_cache', $details);
        }

        return view(self::RESOURCE . 'manual.edit', compact('pageTitle', 'data', 'type', 'id'));
    }

    public function userDataForm($id)
    {
        $types = \App\Models\PaymentGateaway\PaymentGateawaySetting::getAllType();
        $field = null;
        $key = null;

        $view = view(self::RESOURCE . 'manual.user-data-form', compact('id', 'types', 'field', 'key'))->render();

        return $this->success('Success', ['view' => $view]);
    }

    public function storeUserData(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'name' => 'required',
            'is_required' => 'required',
        ]);

        switch ($request->type) {
            case PaymentGateawaySetting::TEXT_TYPE:
                $type = 'Text';
                break;

            case PaymentGateawaySetting::FILE_TYPE:
                $type = 'File';
                break;

            case PaymentGateawaySetting::TEXTAREA_TYPE:
                $type = 'Textarea';
                break;

            default:
                $type = null;
                break;
        }

        $user_data_cache = Cache::get('user_data_cache');
        if (!$user_data_cache) {
            $user_data_cache = [
                [
                    'type' => $type,
                    'label' => $request->name,
                    'is_required' => $request->is_required,
                ]
            ];
        } else {
            if ($request->current_key != null) { // if user edit the user data
                $selected = $user_data_cache[$request->current_key];
                $selected['type'] = $type;
                $selected['label'] = $request->name;
                $selected['is_required'] = $request->is_required;

                $user_data_cache[$request->current_key] = $selected;
            } else { // if user add a new user data
                $user_data_cache = array_merge(
                    $user_data_cache,
                    [
                        [
                            'type' => $type,
                            'label' => $request->name,
                            'is_required' => $request->is_required,
                        ],
                    ],
                );
            }
        }
        Cache::forget('user_data_cache');
        Cache::put('user_data_cache', $user_data_cache);

        return $this->success(__('global.success_create_user_data'));
    }

    public function deleteUserData($key)
    {
        $details = [];

        $data = Cache::get('user_data_cache');
        if ($data) {
            unset($data[$key]);

            $details = array_values($data);

            if (count($details) == 0) {
                Cache::forget('user_data_cache');
            } else {
                Cache::put('user_data_cache', $details);
            }
        }

        $id = 0;
        $view = view(self::RESOURCE . 'manual.user-data', compact('details', 'id'))->render();

        return $this->success(__('global.success_delete_user_data'), $view);
    }

    public function userData($id)
    {
        $details = Cache::get('user_data_cache');
        $view = view(self::RESOURCE . 'manual.user-data', compact('details', 'id'))->render();

        return $this->success('success', $view);
    }

    public function userDataEdit($id, $key)
    {
        $details = Cache::get('user_data_cache');
        $field = $details[$key];
        $id = 0;

        $types = \App\Models\PaymentGateaway\PaymentGateawaySetting::getAllType();

        $view = view(self::RESOURCE . 'manual.user-data-form', compact('types', 'field', 'id', 'key'))->render();

        return $this->success('success', ['view' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentGateaway\PaymentGateawaySetting  $paymentGateawaySetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentGateawaySetting $paymentGateawaySetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentGateaway\PaymentGateawaySetting  $paymentGateawaySetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->notify($this->service->destroy($id));
    }
}
