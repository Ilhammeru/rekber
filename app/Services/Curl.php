<?php

namespace App\Services;

class Curl {
    public function makeRequest($url, $type = 'GET', $headers = null, $payload = null)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        if ($headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($curl, CURLOPT_POST, $type == 'GET' ? false : true);
        if ($type == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        }
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return $response;
    }
}
