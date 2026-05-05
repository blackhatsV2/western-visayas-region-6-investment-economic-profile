<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HistoricalDataTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testYearFiltering(): void
    {
        $this->browse(function (Browser $browser) {
            // Test Default (2024)
            $browser->visit('/')
                    ->assertSee('Western Visayas: Investment and Economic Profile')
                    ->assertSee('2024')
                    ->assertDontSee('Coming Soon :)')
                    ->screenshot('year_2024_view');

            // Test Future Year (2026) -> Coming Soon
            $browser->visit('/?year=2026')
                    ->assertSee('Coming Soon :)')
                    ->assertSee('Data for 2026 is currently being collated')
                    ->assertDontSee('GRDP Growth Rates')
                    ->screenshot('year_2026_view');

            // Test navigation back to 2024 via button
            $browser->clickLink('Return to 2024')
                     ->assertQueryStringHas('year', '2024')
                     ->assertSee('Western Visayas: Investment and Economic Profile');
        });
    }
}
