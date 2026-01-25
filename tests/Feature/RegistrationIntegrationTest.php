<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserOtp;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RegistrationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_registration_and_verification_flow_using_fake_sms()
    {
        // Replace binding for SmsService with a fake that captures the OTP
        $fake = new \Tests\Support\FakeSmsService();
        $this->app->instance(\App\Services\SmsService::class, $fake);

        // Register user
        $payload = [
            'name' => 'Integration User',
            'phone' => '0774000000',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $payload);

        // SmsService should have captured the OTP
        $this->assertArrayHasKey('0774000000', $fake->captured);
        $otp = $fake->captured['0774000000'];

        // Verify
        $user = User::where('phone', '0774000000')->firstOrFail();

        $this->actingAs($user)
            ->post('/verify-phone', ['otp' => $otp])
            ->assertRedirect(route('pending'));

        $this->assertDatabaseMissing('user_otps', ['phone_number' => $user->phone]);
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }
}
