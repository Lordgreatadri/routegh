<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthResendOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_resend_allows_up_to_max_attempts_then_blocks()
    {
        Config::set('services.sms.resend_max_attempts', 2);
        Config::set('services.sms.resend_decay_seconds', 60);

        $user = User::factory()->create([
            'phone' => '0770000000',
        ]);

        $this->actingAs($user)
            ->post(route('phone.resend'))
            ->assertSessionHas('status');

        // second allowed attempt
        $this->actingAs($user)
            ->post(route('phone.resend'))
            ->assertSessionHas('status');

        // third should be blocked
        $response = $this->actingAs($user)
            ->post(route('phone.resend'));

        $response->assertSessionHasErrors('otp');
    }

    public function test_unauthenticated_cannot_resend()
    {
        $this->post(route('phone.resend'))
            ->assertRedirect(route('login'));
    }
}
