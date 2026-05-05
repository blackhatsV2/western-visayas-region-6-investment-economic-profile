<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HistoricalDataRangesTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testYearRanges(): void
    {
        $this->browse(function (Browser $browser) {
            // Test Default (2024-2025)
            $browser->visit('/')
                    ->assertSee('Western Visayas: Investment and Economic Profile')
                    ->assertSee('2024-2025')
                    ->assertSee('2026-2027') // Verify selector options are present
                    ->assertDontSee('Coming Soon :)')
                    ->screenshot('range_2024_2025_view');

            // Test Future Range (2026-2027) -> Coming Soon
            $browser->clickLink('2026-2027')
                    ->assertQueryStringHas('year', '2026-2027')
                    ->assertSee('Coming Soon :)')
                    ->assertSee('Data for 2026-2027 is currently being collated')
                    ->screenshot('range_2026_2027_view');

            // Test navigation back to default
            $browser->clickLink('Return to 2024-2025')
                     ->assertQueryStringHas('year', '2024-2025')
                     ->assertSee('Western Visayas: Investment and Economic Profile');
        });
    }
}
