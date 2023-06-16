<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Tripay {
    public const APIKEY = 'DEV-JG2oXYVbH8UYegzMIRhl9z8ichds2lKXsukDniXU';
    public const PRIVKEY = 'qHkoc-QQoxN-NbJvX-pVCsk-5I3H2';
    public const MERCHANTCODE = 'T22672';

    public $curl;
    public $confirmPayment;

    public function __construct()
    {
        $this->curl = new Curl;
        $this->confirmPayment = new ConfirmPayment;
    }

    public function createSignature($ref, $amount)
    {
        return hash_hmac(
            'sha256',
            self::MERCHANTCODE.$ref.$amount,
            self::PRIVKEY
        );
    }

    public function sandboxUrl()
    {
        return 'https://tripay.co.id/api-sandbox';
    }

    public function createPayloadRequestTransaction($product, $user, $channel, $trxUuid, $returnUrl = null)
    {
        return [
            'method'         => $channel,
            'merchant_ref'   => $trxUuid,
            'amount'         => (float) $product[0]['price'],
            'customer_name'  => $user['username'],
            'customer_email' => $user['email'],
            'customer_phone' => $user['phone'],
            'order_items'    => $product,
            'return_url'   => $returnUrl,
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => $this->createSignature($trxUuid, (float) $product[0]['price'])
        ];
    }

    public function requestTransaction($payload)
    {
        // $out = $this->curl->makeRequest(
        //     'https://tripay.co.id/api-sandbox/transaction/create',
        //     'POST',
        //     ['Authorization: Bearer '.self::APIKEY],
        //     $payload,
        // );

        $out = Http::withHeaders([
                'Authorization' => 'Bearer '.self::APIKEY
            ])
            ->acceptJson()
            ->post('https://tripay.co.id/api-sandbox/transaction/create', $payload);

        return json_decode($out->body(), true);
    }

    public function callback(Request $request)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, self::PRIVKEY);

        if ($signature !== (string) $callbackSignature) {
            return [
                'success' => false,
                'message' => 'Invalid signature',
            ];
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return [
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ];
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return [
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ];
        }

        $invoiceId = $data->merchant_ref;
        $tripayReference = $data->reference;
        $status = strtoupper((string) $data->status);

        Log::debug('data callback', [$data]);

        if ($data->is_closed_payment === 1) {
            Log::debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.');
            Log::debug('status', [$data->status]);
            Log::debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.');
            if ($data->status == 'PAID') {
                Log::debug('checkcheckoke');
                $this->confirmPayment->doConfirm(base64url_encode($invoiceId));
            }

            return [
                'success' => true,
            ];
        }
    }

    /**
     * Function to generate channel on tripay
     */
    public function generateServerChannel()
    {
        $url = $this->sandboxUrl() . '/merchant/payment-channel';
        $response = $this->curl->makeRequest(
            $url,
            'GET',
            ['Authorization: Bearer '.self::APIKEY],
        );

        return json_decode($response, TRUE);
    }
}
