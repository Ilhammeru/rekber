<?php

namespace App\Jobs;

use App\Notifications\DeclineDepositNotification;
use App\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeclineDepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $trxId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($trxId)
    {
        $this->trxId = $trxId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new TransactionService;
        $data = $service->getTransactionDetail($this->trxId);

        $deposit = \App\Models\DepositManual::select('user_id')
            ->where('trx_id', decrypt($this->trxId))
            ->first();
        $user = \App\Models\User::find($deposit->user_id);
        $user->notify(new DeclineDepositNotification($data));
    }
}
