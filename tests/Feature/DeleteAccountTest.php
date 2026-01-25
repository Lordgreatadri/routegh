<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_delete_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('password', null, 'userDeletion');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_can_delete_account_with_correct_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'password' => 'secret',
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
