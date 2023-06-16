<?php

namespace App\Traits;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;

trait HasWallet {
    public function transactionService()
    {
        return new TransactionService;
    }

    /**
     * Function to deposit balance
     * @param int|string $amount
     * @param string $description
     *
     */
    public function deposit(
        int|string $amount = 0,
        string $description = '',
        string $type,
        $confirmed = true
    ) {
        $wallet = $this->getWallet($this->id);
        return $this->transactionService()->makeOne(
            $wallet,
            $amount,
            $description,
            get_class($this),
            $this->id,
            $type,
            $confirmed
        );
    }

    public function confirmDeposit(
        string $trxId
    ) {
        $trx = Transaction::where('uuid', base64url_decode($trxId))->first();
        $trx->confirmed = 1;
        $trx->save();

        $wallet = $this->getWallet($this->id);
        $wallet->balance = $wallet->balance + floatval($trx->amount);
        $wallet->save();

        return $wallet;
    }

    public function currentBalance()
    {
        $wallet = $this->getWallet();
        if (!$wallet) {
            $wallet = $this->createWallet($this->id, get_class($this));
        }
        return $wallet->balance;
    }

    public function getWallet()
    {
        // make a new wallet if wallet not found
        $wallet = Wallet::where('holder_id', $this->id)->first();
        if (!$wallet) {
            $wallet = $this->createWallet(
                $this->id, get_class($this)
            );
        }

        return $wallet;
    }

    public function createWallet(
        int|string $userId,
        string $classType,
        string $name = 'Default Wallet',
        string $slug = 'default',
    ) {
        return Wallet::create([
            'holder_type' => $classType,
            'holder_id' => $userId,
            'name' => $name,
            'slug' => implode('_', explode(' ', $name)),
            'uuid' => uuid_create(),
        ]);
    }
}
