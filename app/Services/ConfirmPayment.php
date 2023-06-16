<?php

namespace App\Services;

use App\Jobs\ConfirmedDepositJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmPayment {
    public function doConfirm($trxId)
    {
        DB::beginTransaction();

        try {
            // check type of transaction
            $payment_gateaway_id = \App\Models\Charge::select('payment_gateaway_id')
                ->where('trx_id', base64url_decode($trxId));
            $paymentType = $this->getTransactionType(base64url_encode($payment_gateaway_id->payment_gateaway_id));

            if ($paymentType == 'manual') {
                $data = \App\Models\DepositManual::where('trx_id', base64url_decode($trxId))->first();
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

            Log::debug('error confirm', [$th->getMessage() . $th->getLine() . $th->getFile()]);

            return [
                'status' => 500,
                'message' => generate_message($th, __('global.failed_confirm_deposit')),
                'data' => [],
            ];
        }
    }

    /**
     * Function to get transaction type, it came from manual gateaway or automatic gateaway
     * if type parent then search data directly in the \App\Models\PaymentGateaway\PaymentGateawaySetting model
     * other way search in \App\Models\PaymentGateaway\PaymentGateawayDetail model
     */
    public function getTransactionType($id, $type = 'parent')
    {
        $data = \App\Models\PaymentGateaway\PaymentGateawaySetting::select('type')->find(base64url_decode($id));
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
}
