<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CheckManualDepositNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EmailNotificationForCheckPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $payload = [])
    {
        $this->data = $data;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::role('admin')->get();
        // check this transaction has a file to attach or not
        $fields = json_decode($this->data->charge->gateaway->user_field, TRUE);
        $has_attachments = false;
        $attachment_label = [];
        foreach ($fields as $field) {
            $label = $field['label'];
            $slug = strtolower(implode('_', explode(' ', $label)));
            if (strtolower($field['type']) == 'file') {
                $has_attachments = true;
                $attachment_label[] = $slug;
            }
        }
        // then generate file to attach
        $path = [];
        if ($has_attachments) {
            $values = json_decode($this->data->depositManual->field_value, TRUE);
            foreach ($attachment_label as $attachLabel) {
                $path[] = $values[$attachLabel];
            }
        }


        foreach ($users as $user) {
            $url = url('deposit/users/' . encrypt($this->data->charge->trx_id)) . '?a=' . $user->email;
            $user->notify(new CheckManualDepositNotification($this->data, $path, $url));
        }
    }
}
