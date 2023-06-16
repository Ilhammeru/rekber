<?php

namespace App\Services;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use App\Models\PaymentGateaway\PaymentGateawaySetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentGateawayService extends Service
{

    public function model(): Model
    {
        // Object Model
        return new PaymentGateawaySetting();
    }

    public function datatable($type)
    {
        $data = $this->model()->where('type', $type)
            ->get();

        return DataTables::of($data)
            ->editColumn('status', function ($d) {
                if ($d->status) {
                    return '<span class="badge badge-success">'. __('global.active') .'</span>';
                } else {
                    return '<span class="badge badge-danger">'. __('global.disable') .'</span>';
                }
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->id);
                return '
                    <a class="btn btn-primary btn-sm"
                        href="'. route('payment-gateaway-edit', ['id' => $id, 'type' => $d->type]) .'">
                        <i class="fa fa-pen"></i> Edit
                    </a>
                    <button class="btn btn-warning btn-sm" type="button" onclick="confirmDelete(`'. $id .'`)">
                        <i class="fa fa-trash"></i> '. __('global.delete') .'
                    </button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Function to get channel by channel code
     */
    public function getAutomaticChannel($id, $channel_code)
    {
        $raw = PaymentGateawayDetail::select('payment_gateaway_setting_id')
            ->with('payment:id,channel')
            ->find($id);
        $data = [];
        if ($raw->payment) {
            $data = json_decode($raw->payment->channel, TRUE) ?? [];
        }

        $out = collect($data)->filter(function ($item) use ($channel_code) {
            return $item['code'] == $channel_code;
        })->values();
        $out = count($out) > 0 ? $out[0] : [];

        return $out;
    }

    public function datatableAutomatic()
    {
        $data = PaymentGateawaySetting::where('type', 'automatic')
            ->with('details')
            ->get();

        return DataTables::of($data)
            ->addColumn('enable_currency', function ($d) {
                return count($d->details);
            })
            ->editColumn('status', function ($d) {
                if ($d->status) {
                    return '<span class="badge badge-success">'. __('global.active') .'</span>';
                } else {
                    return '<span class="badge badge-danger">'. __('global.disable') .'</span>';
                }
            })
            ->addColumn('action', function ($d) {
                $id = encrypt($d->id);
                $text = __('global.disable');
                $icon = 'fa-eye-slash';
                if (!$d->status) {
                    $text = __('global.enable');
                    $icon = 'fa-eye';
                }

                return '
                    <a class="btn btn-primary btn-sm"
                        href="'. route('payment-gateaway.automatic.edit', ['id' => $id]) .'">
                        <i class="fa fa-pen"></i> Edit
                    </a>
                    <button class="btn btn-warning btn-sm" type="button" onclick="confirmDelete(`'. $id .'`)">
                        <i class="fa '. $icon .'"></i> '. $text .'
                    </button>
                ';
            })
            ->rawColumns(['status', 'action', 'enable_currency'])
            ->make(true);
    }

    public function all($select = '*')
    {
        return $this->model()->select($select)
            ->active()
            ->get();
    }

    public function userDataDetail($id, $key = null)
    {
        $data = PaymentGateawaySetting::select("type", 'id')
            ->find(decrypt($id));
        $details = null;
        $selected_field = null;
        if ($data->type == PaymentGateawaySetting::MANUAL_TYPE) {
            $details = $data->detail->user_field ? json_decode($data->detail->user_field, true) : [];
        }

        if ($key != null && $details) {
            $selected_field = $details[(int)$key];
            switch (strtolower($selected_field['type'])) {
                case 'text':
                    $id_type = PaymentGateawaySetting::TEXT_TYPE;
                    break;

                case 'file':
                    $id_type = PaymentGateawaySetting::FILE_TYPE;
                    break;

                case 'textarea':
                    $id_type = PaymentGateawaySetting::TEXTAREA_TYPE;
                    break;

                default:
                    $id_type = 0;
                    break;
            }

            $selected_field['type'] = $id_type;
        }

        return [
            'data' => $data,
            'details' => $details,
            'field' => $selected_field,
            'key' => $key,
        ];
    }

    /**
     * Function to get user field in manual payment
     * @param string $id
     */
    public function getUserField($id)
    {
        $data = PaymentGateawayDetail::select('user_field')->where('payment_gateaway_setting_id', decrypt($id))
            ->first();
        $field = $data->user_field ? json_decode($data->user_field, true) : null;

        return $field;
    }

    public function show($id, $type = null, $select = '*', $relation = 'all')
    {
        $query = $this->model()->query();
        if ($relation == 'all') {
            if ($type == PaymentGateawaySetting::MANUAL_TYPE) {
                $query->with('detail');
            } else {
                $query->with('details');
            }
        }
        $query->select($select);
        return $query->find(decrypt($id));
    }

    /**
     * Function to get transaction type, it came from manual gateaway or automatic gateaway
     * if type parent then search data directly in the \App\Models\PaymentGateaway\PaymentGateawaySetting model
     * other way search in \App\Models\PaymentGateaway\PaymentGateawayDetail model
     */
    public function getTransactionType($id, $type = 'parent')
    {
        $data = $this->model()->select('type')->find(base64url_decode($id));
        if (!$data) {
            $data = \App\Models\PaymentGateaway\PaymentGateawayDetail::select('id', 'payment_gateaway_setting_id')
                ->with('payment:id,type')
                ->find(base64url_decode($id));
            $type = $data->payment->type;
        } else {
            $type = $data->type;
        }


        return $type;

        return null;
    }

    public function showAllPaymentWithCurrency()
    {
        $data = $this->model()->with('details')
            ->get();

        $out = [];
        foreach ($data as $item) {
            foreach ($item->details as $detail) {
                $out[] = (object) [
                    'id' => base64url_encode($item->id),
                    'detail_id' => base64url_encode($detail->id),
                    'currency' => $detail->currency,
                    'name' => $item->name . ' ' . $detail->currency,
                ];
            }
        }

        return $out;
    }

    /**
     * Function to update manual payment gateaway
     */
    public function update($request, $id)
    {
        $gateaway = PaymentGateawaySetting::find(decrypt($id));
        $gateaway->name = $request->name;
        $gateaway->status = true;

        if ($gateaway->save()) {
            $userData = collect($request->user_data)->map(function ($item) {
                if ($item['is_required'] == 1) {
                    $item['is_required'] = true;
                } else {
                    $item['is_required'] = false;
                }

                return $item;
            })->all();
            PaymentGateawayDetail::where('payment_gateaway_setting_id', decrypt($id))
                ->update([
                    'currency' => $request->currency,
                    'rate' => $request->rate,
                    'minimum_trx' => $request->minimum,
                    'maximum_trx' => $request->maximum,
                    'fixed_charge' => $request->fix_charge,
                    'percent_charge' => $request->percent_charge,
                    'deposit_instruction' => $request->instruction_real,
                    'user_field' => json_encode($userData),
                ]);
        }

        return [
            'status' => 200,
            'message' => __("global.success_update_manual_gateaway"),
            'data' => [
                'url' => route('payment-gateaway.index', 'manual'),
            ],
        ];
    }

    public function updateCurrency($id, $currency = null)
    {
        $all_currency = [
            'USD',
            'IDR',
            'JPY',
            'INR',
        ];
        $data = $this->show($id, PaymentGateawaySetting::AUTO_TYPE, ['id']);
        $currency = collect($data->details)->pluck('currency')->all();

        foreach ($currency as $c) {
            $search = array_search($c, $all_currency);
            if ($search >= 0) {
                unset($all_currency[$search]);
            }
        }

        $all_currency = array_values($all_currency);

        if ($currency) {
            if (array_search($currency, $all_currency) >= 0) {
                unset($all_currency[array_search($currency, $all_currency)]);
            }
        }
        $all_currency = array_values($all_currency);

        return $all_currency;
    }

    public function storeAutomatic(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = PaymentGateawaySetting::find(decrypt($id));
            $channels = json_decode($data->channel, TRUE) ?? [];
            if (count($channels) == 0) {
                if ($request->channels) {
                    $data->channel = json_encode($request->channels);
                }
            } else {
                $request_channel = $request->channels;
                foreach ($request_channel as $rc) {
                    $codes = collect($channels)->pluck('code')->all();
                    $search = array_search($rc['code'], $codes);
                    $channel_status = false;
                    if (isset($rc['status'])) $channel_status = true;
                    $channels[$search]['status'] = $channel_status;
                }
            }
            $data->channel = json_encode($channels);

            $configuration = $request->config;
            $config = json_decode($data->configuration, TRUE);
            $config = collect($config)->map(function ($item) use ($configuration) {
                foreach ($configuration as $conf) {
                    if ($item['slug'] == $conf['slug']) {
                        $item['value'] = $conf[$item['slug']];
                    }
                }

                return $item;
            })->all();
            $data->configuration = json_encode($config);
            $data->save();
            $gateId = $data->id;

            PaymentGateawayDetail::where('payment_gateaway_setting_id', decrypt($id))->delete();
            $details = collect($request->details)->map(function ($item) use ($gateId) {
                $item['payment_gateaway_setting_id'] = $gateId;
                $item['fixed_charge'] = $item['fix_charge'];
                $item['minimum_trx'] = $item['minimum'];
                $item['maximum_trx'] = $item['maximum'];

                unset($item['minimum']);
                unset($item['maximum']);
                unset($item['fix_charge']);

                return $item;
            })->all();

            foreach ($details as $d) {
                PaymentGateawayDetail::create($d);
            }
            DB::commit();

            return [
                'status' => 200,
                'message' => __('global.success_update_auto_gateaway'),
                'data' => [],
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'status' => 500,
                'message' => env('APP_ENV') == 'local' ? $th->getMessage() . $th->getLine() . $th->getFile() : __('global.failed_to_update_payment_gateaway'),
                'data' => [],
            ];
        }
    }

    public function saveServerTripayChannel($data, $id)
    {
        PaymentGateawaySetting::where('id', decrypt($id))
            ->update([
                'channel' => json_encode($data)
            ]);
    }

    /**
     * Function to save manual payment
     * @param string $type
     *
     * Detail data on request variabel
     * @param string name
     * @param string currency
     * @param int rate
     * @param int minimum
     * @param int maximum
     * @param int fix_charge
     * @param int percent_charge
     * @param string instruction
     * @param string instruction_real
     * @param array user_data
     *
     * @return array
     */
    public function store($request, $additional = null)
    {
        if ($request->current_id) {
            return $this->update($request, $request->current_id);
        }

        $gateaway = new PaymentGateawaySetting();
        $gateaway->type = PaymentGateawaySetting::MANUAL_TYPE;
        $gateaway->name = $request->name;
        $gateaway->status = true;

        if ($gateaway->save()) {
            $payload_detail = [
                'payment_gateaway_setting_id' => $gateaway->id,
                'currency' => $request->currency,
                'rate' => $request->rate,
                'minimum_trx' => $request->minimum,
                'maximum_trx' => $request->maximum,
                'fixed_charge' => $request->fix_charge,
                'percent_charge' => $request->percent_charge,
                'deposit_instruction' => $request->instruction_real,
                'user_field' => json_encode($request->user_data),
            ];
            PaymentGateawayDetail::create($payload_detail);
        }

        return [
            'status' => 200,
            'message' => __("global.success_create_manual_gateaway"),
            'data' => [
                'url' => route('payment-gateaway.index', $additional),
            ],
        ];
    }

    public function destroy($id)
    {
        PaymentGateawaySetting::where('id', decrypt($id))->delete();
        PaymentGateawayDetail::where('payment_gateaway_setting_id', decrypt($id))->delete();

        return [
            'status' => 200,
            'message' => __('global.success_delete_payment_gateaway'),
            'data' => [],
        ];
    }
}
