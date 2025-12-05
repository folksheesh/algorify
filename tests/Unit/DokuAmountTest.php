<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\DokuSignatureService;

class DokuAmountTest extends TestCase
{
    public function test_numeric_amount_is_kept()
    {
        [$amount, $err] = DokuSignatureService::normalizeAmount(25000.00);
        $this->assertNull($err);
        $this->assertEquals(25000, $amount);
    }

    public function test_comma_decimal_and_dot_thousand_formats()
    {
        [$amount, $err] = DokuSignatureService::normalizeAmount('25.000,50');
        $this->assertNull($err);
        $this->assertEquals(25001, $amount); // rounded

        [$amount2, $err2] = DokuSignatureService::normalizeAmount('25,000.50');
        $this->assertNull($err2);
        $this->assertEquals(25001, $amount2);
    }

    public function test_zero_or_empty_is_invalid()
    {
        [$a, $e] = DokuSignatureService::normalizeAmount(0);
        $this->assertNotNull($e);

        [$a2, $e2] = DokuSignatureService::normalizeAmount('');
        $this->assertNotNull($e2);
    }

    public function test_amount_too_large()
    {
        [$a, $e] = DokuSignatureService::normalizeAmount('1000000000000');
        $this->assertNotNull($e);
    }
}
