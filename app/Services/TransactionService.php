<?php

namespace App\Services;

use App\Exceptions\AddDeductBalanceException;
use App\Exceptions\InsufficientFundsException;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class TransactionService {
    public function model(): Model
    {
        return new Transaction;
    }

    public function makeOne(
        Wallet $wallet,
        int|string $amount,
        string $description,
        $userClass,
        int|string $payableId,
        string $type,
        $confirmed = true,
    ) {
        DB::beginTransaction();

        try {
            $model = $this->model();
            $model->payable_type = $userClass;
            $model->payable_id = $payableId;
            $model->wallet_id = $wallet->id;
            $model->type = $type;
            $model->description = $description;
            $model->amount = $amount;
            $model->confirmed = $confirmed;
            $model->uuid = uuid_create();
            $model->save();

            if ($confirmed) {
                // update balance
                if ($type == Transaction::TYPE_DEBIT) {
                    $wallet->balance = $wallet->balance + floatval($amount);
                } else {
                    $wallet->balance = $wallet->balance - floatval($amount);
                }

                $wallet->save();
            }

            DB::commit();

            return $confirmed ? $wallet->balance : $model->uuid;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new AddDeductBalanceException($th->getMessage());
        }
    }

    /**
     * Function to get transaction detail
     * @param string $trx
     * @param boolean $isNewEncode
     *
     * @return array
     */
    public function getTransactionDetail($trx, $isNewEncode = false)
    {
        if ($isNewEncode) {
            $trxId = base64url_decode($trx);
        } else {
            $trxId = decrypt($trx);
        }

        $data = \App\Models\Transaction::with(['charge.gateaway.payment', 'user:id,username,email', 'depositManual', 'depositAutomatic'])
            ->where('uuid', $trxId)
            ->first();

        // check this transaction has a file to attach or not
        $fields = json_decode($data->charge->gateaway->user_field, TRUE) ?? null;
        $has_attachments = false;
        $attachment_label = [];
        $path = [];

        if ($fields) {
            foreach ($fields as $field) {
                $label = $field['label'];
                $slug = strtolower(implode('_', explode(' ', $label)));
                if (strtolower($field['type']) == 'file') {
                    $has_attachments = true;
                    $attachment_label[] = $slug;
                }
            }
            // then generate file to attach
            if ($has_attachments) {
                $values = json_decode($data->depositManual->field_value, TRUE) ?? null;
                if ($values) {
                    foreach ($attachment_label as $attachLabel) {
                        $path[] = $values[$attachLabel];
                    }
                }
            }
        }

        $data['proof_files'] = $path;

        return $data;
    }

    public function listTransaction($id)
    {

    }
}
