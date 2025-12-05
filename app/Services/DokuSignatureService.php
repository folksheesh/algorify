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

    /**
     * Normalize and validate an amount intended for DOKU.
     * Accepts decimals or formatted strings and returns integer amount
     * suitable for DOKU (no fractional part). Returns [int|null, string|null]
     * where the first element is the integer amount or null when invalid,
     * and the second element is an optional error message.
     */
    public static function normalizeAmount($rawAmount): array
    {
        // If nothing provided, treat as invalid
        if ($rawAmount === null || $rawAmount === '') {
            return [null, 'Amount is empty'];
        }

        // Convert to string and normalize separators:
        // - Many systems use 1.000.000,00 (dot thousands, comma decimal) or 1,000,000.00
        // Try a safe heuristic: replace commas with dots then cast to float.
        $s = (string) $rawAmount;

        // Remove any non-numeric except dot and comma
        // Keep digits, commas and dots only
        $s = preg_replace('/[^0-9.,\-]/', '', $s);

        // If there are both comma and dot present and dot appears before comma
        // it's likely dot is thousand separator and comma is decimal (e.g. 1.234,56)
        if (strpos($s, '.') !== false && strpos($s, ',') !== false && strpos($s, '.') < strpos($s, ',')) {
            // remove dots (thousand sep) and replace final comma with dot
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } else {
            // Case where comma appears before dot (e.g. 25,000.50) — commas are thousand separators
            // remove commas in that case so the remaining dot acts as decimal separator
            if (strpos($s, ',') !== false && strpos($s, '.') !== false && strpos($s, ',') < strpos($s, '.')) {
                $s = str_replace(',', '', $s);
            } else {
                // replace commas with dots so casting works for 1234,56
                $s = str_replace(',', '.', $s);
            }
        }

        // Cast to float then to int (DOKU expects integer amount for IDR)
        if (!is_numeric($s)) {
            return [null, 'Amount is not numeric after normalization'];
        }

        $float = (float) $s;
        $amount = (int) round($float);

        // Validation: follow DOKU docs — at least 1 and less than 999,999,999,999
        if ($amount < 1) {
            return [null, 'Amount must be at least 1'];
        }

        if ($amount >= 999999999999) {
            return [null, 'Amount exceeds allowed maximum'];
        }

        return [$amount, null];
    }
}
