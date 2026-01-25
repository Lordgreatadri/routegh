<?php

namespace Tests\Support;

use App\Services\SmsService;

class FakeSmsService extends SmsService
{
    public array $captured = [];

    public function sendOtp(string $phone, string $otp): bool
    {
        $this->captured[$phone] = $otp;
        return true;
    }
}
