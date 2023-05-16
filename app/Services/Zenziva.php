<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Zenziva {

    const main_url = 'https://console.zenziva.net';

    public function whatsappRegUrl()
    {
        return self::main_url . '/wareguler/api';
    }

    public function sendTextUrl()
    {
        return $this->whatsappRegUrl() . '/sendWA';
    }

    /**
     * Function to send message
     * @param string via
     * @param string phone
     * @param string message
     */
    public function send_message($via = 'whatsapp', $phone, $message)
    {
        $user = env('ZENZIVA_USER');
        $key = env('ZENZIVA_KEY');
        $url = $this->sendTextUrl();

        $res = Http::acceptJson()
            ->post($url, [
                'userkey' => $user,
                'passkey' => $key,
                'to' => $phone,
                'message' => $message,
            ]);

        Log::debug('Send otp whatsapp res' , [$res->body()]);

        return [
            'res' => $res->body() ? json_decode($res->body(), true) : '-',
            'status' => $res->successful() ? 200 : 500,
        ];
    }
}
