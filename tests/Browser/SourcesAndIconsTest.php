<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SourcesAndIconsTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testSourcesAndIcons(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(1000)
                    ->assertSee('Source')
                    ->assertSee('DTI Region 6') // Hero source
                    ->screenshot('hero_source_verification')
                    ->scrollIntoView('#drivers')
                    ->pause(500)
                    ->screenshot('drivers_icons_verification')
                    ->assertSee('Source'); // Drivers source
        });
    }
}
