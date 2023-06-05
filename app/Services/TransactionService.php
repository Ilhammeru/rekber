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
        string $type
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
            $model->confirmed = true;
            $model->uuid = uuid_create();

            if ($model->save()) {
                // update balance
                if ($type == Transaction::TYPE_DEBIT) {
                    $wallet->balance = $wallet->balance + floatval($amount);
                } else {
                    $wallet->balance = $wallet->balance - floatval($amount);
                }

                $wallet->save();
            }
            DB::commit();

            return $wallet->balance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new AddDeductBalanceException($th->getMessage());
        }
    }

    public function listTransaction($id)
    {

    }
}
