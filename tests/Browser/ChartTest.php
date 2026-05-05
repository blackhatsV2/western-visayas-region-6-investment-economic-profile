<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChartTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testChartHighlighting(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Western Visayas: Investment and Economic Profile')
                    ->scrollIntoView('#economy')
                    ->pause(2000)
                    ->screenshot('chart_highlight_verification');
        });
    }
}
