<?php

namespace App\Traits;

use App\Services\TransactionService;

trait HasTransaction {
    public function statusTransaction($trx_id)
    {
        $service = new TransactionService;
        $data = $service->getTransactionDetail($trx_id);

        return $data;
    }
}
