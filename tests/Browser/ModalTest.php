<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModalTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testInfrastructureModal(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->scrollIntoView('#infrastructure');
            
            // Wait for element visibility
            $browser->waitFor('#infrastructure .bento-card[data-title]')
                    ->assertVisible('#infrastructure .bento-card[data-title]')
                    // Click the first one
                    ->click('#infrastructure .bento-card[data-title]')
                    ->waitForText('GO BACK', 10) // Wait up to 10s
                    ->assertVisible('.fixed.inset-0.z-50')
                    ->press('GO BACK')
                    ->waitUntilMissing('.fixed.inset-0.z-50');
        });
    }
}
