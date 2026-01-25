<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthVerifyOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_attempts_increment_and_invalidate_after_max()
    {
        $user = User::factory()->create([
            'phone' => '0771111111',
        ]);

        // Put a hashed OTP in cache
        $otp = '123456';
        Cache::put('phone_otp_hash_'.$user->phone, Hash::make($otp), now()->addMinutes(10));

        // Make 5 invalid attempts
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->actingAs($user)->post(route('phone.verify.post'), ['otp' => '000000']);
            if ($i < 5) {
                $response->assertSessionHasErrors('otp');
            } else {
                // On the 5th attempt it should invalidate and return error
                $response->assertSessionHasErrors('otp');
                $this->assertNull(Cache::get('phone_otp_hash_'.$user->phone));
            }
        }
    }

    public function test_successful_verification_clears_cache_and_sets_pending()
    {
        $user = User::factory()->create([
            'phone' => '0772222222',
        ]);

        $otp = '654321';
        Cache::put('phone_otp_hash_'.$user->phone, Hash::make($otp), now()->addMinutes(10));

        $response = $this->actingAs($user)->post(route('phone.verify.post'), ['otp' => $otp]);

        $response->assertRedirect(route('pending'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => 'pending']);
        $this->assertNull(Cache::get('phone_otp_hash_'.$user->phone));
    }
}
