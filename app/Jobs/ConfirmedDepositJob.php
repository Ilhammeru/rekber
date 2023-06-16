<?php

namespace App\Jobs;

use App\Notifications\ConfirmedDepositNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConfirmedDepositJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $trx;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $trx)
    {
        $this->user = $user;
        $this->trx = $trx;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new ConfirmedDepositNotification($this->trx));
    }
}
