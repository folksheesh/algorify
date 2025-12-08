<?php

namespace Tests\Unit;

use App\Services\DokuSignatureService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class DokuSignatureServiceTest extends TestCase
{
    public function test_generate_builds_expected_signature_and_headers(): void
    {
        // Freeze time & UUID so signature deterministic
        Carbon::setTestNow(Carbon::parse('2025-12-08 12:00:00', 'UTC'));
        Str::createUuidsUsing(fn () => Uuid::fromString('11111111-2222-3333-4444-555555555555'));

        Config::set('doku.client_id', 'client-123');
        Config::set('doku.secret_key', 'secret-key');

        $body = ['foo' => 'bar'];
        $path = '/test-path';

        $result = DokuSignatureService::generate($body, $path);

        // Compute expected pieces
        $expectedJson = '{"foo":"bar"}';
        $expectedDigest = base64_encode(hash('sha256', $expectedJson, true));

        $stringToSign = "Client-Id:client-123\n"
            ."Request-Id:11111111-2222-3333-4444-555555555555\n"
            ."Request-Timestamp:2025-12-08T12:00:00Z\n"
            ."Request-Target:/test-path\n"
            ."Digest:{$expectedDigest}";

        $expectedSignature = 'HMACSHA256=' . base64_encode(
            hash_hmac('sha256', $stringToSign, 'secret-key', true)
        );

        $this->assertSame($expectedSignature, $result['signature']);
        $this->assertSame('11111111-2222-3333-4444-555555555555', $result['request_id']);
        $this->assertSame('2025-12-08T12:00:00Z', $result['request_timestamp']);
        $this->assertSame($expectedDigest, $result['digest']);
        $this->assertSame($expectedJson, $result['json_body']);

        // Restore global state
        Str::createUuidsNormally();
        Carbon::setTestNow();
    }
}
