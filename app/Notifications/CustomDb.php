<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CustomDb {
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toDatabase($notifiable);
        $type_n = $data['type_n'];
        unset($data['type_n']);
        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type_n' => $type_n,
            'type' => get_class($notification),
            'data' => $data,
            'read_at' => null,
        ]);
    }
}
