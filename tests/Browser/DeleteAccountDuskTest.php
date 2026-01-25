<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class DeleteAccountDuskTest extends DuskTestCase
{
    /** @test */
    public function modal_closes_and_toast_shows_after_deletion()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('profile.edit')
                ->waitForText('Edit Profile')
                ->press('Delete Account')
                ->whenAvailable('@confirm-user-deletion', function (Browser $modal) {
                    $modal->type('password', 'secret')
                          ->press('Delete Account');
                })
                // After deletion, expect redirect to home and a flash may be present
                ->waitForLocation('/')
                ->assertSee('Account deleted.');
        });
    }
}
