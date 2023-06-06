<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Tripay {
    public const APIKEY = 'DEV-JG2oXYVbH8UYegzMIRhl9z8ichds2lKXsukDniXU';
    public const PRIVKEY = 'qHkoc-QQoxN-NbJvX-pVCsk-5I3H2';
    public const MERCHANTCODE = 'T22672';

    public function createSignature()
    {
        return hash_hmac(
            'sha256',
            self::MERCHANTCODE.'INV7701000000',
            self::PRIVKEY
        );
    }

    public function requestTransaction()
    {
        $data = [
            'method'         => 'BRIVA',
            'merchant_ref'   => 'INV770',
            'amount'         => 1000000,
            'customer_name'  => 'Nama Pelanggan',
            'customer_email' => 'emailpelanggan@domain.com',
            'customer_phone' => '081234567890',
            'order_items'    => [
                [
                    'sku'         => 'FB-06',
                    'name'        => 'Nama Produk 1',
                    'price'       => 500000,
                    'quantity'    => 1,
                    'product_url' => 'https://tokokamu.com/product/nama-produk-1',
                    'image_url'   => 'https://tokokamu.com/product/nama-produk-1.jpg',
                ],
                [
                    'sku'         => 'FB-07',
                    'name'        => 'Nama Produk 2',
                    'price'       => 500000,
                    'quantity'    => 1,
                    'product_url' => 'https://tokokamu.com/product/nama-produk-2',
                    'image_url'   => 'https://tokokamu.com/product/nama-produk-2.jpg',
                ]
            ],
            'return_url'   => 'https://domainanda.com/redirect',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => $this->createSignature()
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.self::APIKEY],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return [
            'data' => json_decode($response, true),
            'message' => 'Oke',
            'status' => 200,
        ];
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
            return [
                'success' => true,
            ];
        }
    }
}
