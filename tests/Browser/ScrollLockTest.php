<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ScrollLockTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testScrollLock(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('#hero .lg\\:col-span-2') // Open modal
                    ->pause(500)
                    ->assertPresent('body.overflow-hidden') // Check for class
                    ->click('.absolute.top-10.right-10') // Close modal
                    ->pause(500)
                    ->assertMissing('body.overflow-hidden'); // Check class removed
        });
    }
}
