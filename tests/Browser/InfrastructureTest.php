<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InfrastructureTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testInfrastructureSection(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('9 AIRPORTS')
                    ->assertSee('152 PORTS')
                    ->assertSee('6 CAAP-operated')
                    // Check order: Infrastructure should be visible before scrolling to GRDP
                    ->assertVisible('#infrastructure') 
                    ->screenshot('infrastructure_section');
        });
    }
}
