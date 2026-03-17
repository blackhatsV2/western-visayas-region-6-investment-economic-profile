<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ProjectContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test PDF download success for a valid year.
     */
    public function test_pdf_download_success()
    {
        // Seed some data for the year
        $year = 'As of 2024';
        ProjectContent::create([
            'year_range' => $year,
            'section_title' => 'Test Section',
            'type' => 'grid',
            'content' => ['items' => []],
            'page_number' => 1
        ]);

        $response = $this->get('/download-profile/' . rawurlencode($year));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test PDF download 404 for an invalid year.
     */
    public function test_pdf_download_not_found()
    {
        $response = $this->get('/download-profile/' . rawurlencode('Invalid Year'));

        $response->assertStatus(404);
    }
}
