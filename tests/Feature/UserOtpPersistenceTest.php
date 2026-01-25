<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class UserOtpPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_otps_created_on_registration()
    {
        // Register a user (email optional)
        $payload = [
            'name' => 'Test Persist',
            'phone' => '0773000000',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $payload);

        $this->assertDatabaseHas('user_otps', [
            'phone_number' => '0773000000',
        ]);
    }

    public function test_user_otps_deleted_on_successful_verification()
    {
        $user = User::factory()->create([
            'phone' => '0773111111',
        ]);

        $otp = '321654';
        $hash = Hash::make($otp);

        // Persist OTP to both cache and DB as the controller expects
        Cache::put('phone_otp_hash_'.$user->phone, $hash, now()->addMinutes(10));
        UserOtp::create([
            'phone_number' => $user->phone,
            'otp_hash' => $hash,
            'expired_at' => now()->addMinutes(10),
        ]);

        $this->actingAs($user)
            ->post('/verify-phone', ['otp' => $otp])
            ->assertRedirect(route('pending'));

        $this->assertDatabaseMissing('user_otps', ['phone_number' => $user->phone]);
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }
}
