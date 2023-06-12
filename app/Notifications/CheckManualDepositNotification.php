<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckManualDepositNotification extends Notification
{
    use Queueable;

    public $data;
    public $files;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data, $files, $url = null)
    {
        $this->data = $data;
        $this->files = $files;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', CustomDb::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->markdown('emails.deposit.check_manual', ['data' => $this->data, 'files' => $this->files, 'url' => $this->url]);
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $mail->attach(public_path() . '/' . $file);
            }
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $out = $this->data;
        $out['type_n'] = 'admin-confirm-deposit';

        return collect($out)->toArray();
    }

    /*
     * It's important to define toDatabase method due
     * it's used in notification channel. Of course,
     * you can change it in GroupedDbChannel.
     */
    public function toDatabase($notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
