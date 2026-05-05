<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WhyInvestModalTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testWhyInvestModal(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(1000)
                    ->assertSee('Why Invest in')
                    ->click('#hero .lg\\:col-span-2') // Click the hero card
                    ->pause(1000)
                    ->assertSee('Why Invest in Visayas Logistics Cluster?')
                    ->pause(500)
                    ->assertPresent('.grid.grid-cols-1.md\\:grid-cols-2') // Assert grid layout
                    ->assertPresent('.bg-white\\/5.border.border-white\\/10') // Assert card styling
                    ->assertSee('Abundant in Natural Resources')
                    ->assertSee('Sufficient Power Supply')
                    ->screenshot('why_invest_modal_cards_verification');
        });
    }
}
