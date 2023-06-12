<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositService extends Service
{
    public const DESC_DEPOSIT = 'Top up wallet';
    public $transaction;
    public $gateaway;
    public $charge;

    public function __construct()
    {
        $this->transaction = new TransactionService;
        $this->gateaway = new PaymentGateawayService;
        $this->charge = new ChargeService;
    }

    public function model(): Model
    {
        // Object Model
        return new Transaction();
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
            $trx = $user->deposit((float) $request->payable, self::DESC_DEPOSIT, \App\Models\Transaction::TYPE_DEBIT, false);

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
                $out = [
                    'data' => [
                        'url' => route('deposit.user-proof-form', encrypt($trx)),
                    ],
                    'status' => 200,
                    'message' => __('global.deposit_manual_pending'),
                ];
                goto out;
            }

            out:
            DB::commit();

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

    public function datatable($request)
    {

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
