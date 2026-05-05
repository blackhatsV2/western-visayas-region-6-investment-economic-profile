<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LeafletMapTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testLeafletMap(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->pause(1000)
                    ->scrollIntoView('#infrastructure')
                    ->pause(500)
                    ->click('#infrastructure .bento-card:first-child') // Click Airports card
                    ->pause(1000)
                    ->assertPresent('#leaflet-map')
                    ->assertPresent('.leaflet-container')
                    ->assertPresent('.leaflet-marker-icon')
                    ->screenshot('leaflet_map_airports_verification')
                    ->click('.absolute.top-10.right-10') // Close modal
                    ->pause(500)
                    ->click('#infrastructure .bento-card:last-child') // Click Ports card
                    ->pause(1000)
                    ->assertPresent('#leaflet-map')
                    ->assertPresent('.leaflet-marker-icon')
                    ->screenshot('leaflet_map_ports_verification');
        });
    }
}
