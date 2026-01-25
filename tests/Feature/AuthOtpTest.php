<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_generates_and_stores_hashed_otp()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '08000000001',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/verify-phone');

        $user = User::first();
        $this->assertNotNull($user);

        $this->assertTrue(Cache::has('phone_otp_hash_'.$user->phone));
    }

    public function test_verify_phone_with_valid_otp_marks_pending_and_sets_verified_at()
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Test User',
            'phone' => '08000000002',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::first();
        $this->assertNotNull($user);

        // grab OTP from log via cache hash (we can't get raw OTP) => simulate by creating OTP and hash
        $otp = '123456';
        Cache::put('phone_otp_hash_'.$user->phone, \Illuminate\Support\Facades\Hash::make($otp), now()->addMinutes(10));

        $response = $this->actingAs($user)->post('/verify-phone', ['otp' => $otp]);
        $response->assertRedirect('/pending-approval');

        $user->refresh();
        $this->assertEquals('pending', $user->status);
        $this->assertNotNull($user->phone_verified_at);
    }
}
