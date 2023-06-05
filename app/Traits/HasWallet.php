<?php

namespace App\Traits;

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
    public function deposit(int|string $amount = 0, string $description = '', string $type)
    {
        $wallet = $this->getWallet($this->id);
        return $this->transactionService()->makeOne(
            $wallet,
            $amount,
            $description,
            get_class($this),
            $this->id,
            $type,
        );
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
        return Wallet::where('holder_id', $this->id)->first();
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
