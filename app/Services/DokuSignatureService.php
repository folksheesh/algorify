<?php

namespace App\Services;

use Illuminate\Support\Str;

class DokuSignatureService
{
    public static function generate(array $body, string $path)
    {
        $clientId  = config('doku.client_id');
        $secretKey = config('doku.secret_key');

        // Wajib UTC, format ISO8601, pakai Z di belakang
        $requestId        = (string) Str::uuid();
        $requestTimestamp = now('UTC')->format('Y-m-d\TH:i:s\Z');

        // JSON body & Digest (SHA256 + base64)
        $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
        $digest   = base64_encode(hash('sha256', $jsonBody, true));

        // Susun stringToSign (per baris, pakai \n, tanpa \n di akhir)
        $stringToSign = "Client-Id:{$clientId}\n"
            ."Request-Id:{$requestId}\n"
            ."Request-Timestamp:{$requestTimestamp}\n"
            ."Request-Target:{$path}\n"
            ."Digest:{$digest}";

        // HMAC-SHA256 + base64
        $hmac = base64_encode(
            hash_hmac('sha256', $stringToSign, $secretKey, true)
        );

        // Header Signature final
        $signature = "HMACSHA256={$hmac}";

        return [
            'signature'         => $signature,
            'request_id'        => $requestId,
            'request_timestamp' => $requestTimestamp,
            'digest'            => $digest,
            'json_body'         => $jsonBody,
        ];
    }
}
