<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProjectContent;
use App\Models\Inquiry;
use App\Services\RecaptchaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $years = ProjectContent::distinct()->pluck('year_range')->toArray();
        if (empty($years)) {
            $years = ['As of 2024'];
        }
        rsort($years);

        $selectedYear = $request->query('year', $years[0]);
        $contents = ProjectContent::where('year_range', $selectedYear)->orderBy('page_number')->get();
        $noContent = $contents->isEmpty();

        return view('welcome', compact('contents', 'selectedYear', 'years', 'noContent'));
    }

    public function submitContactForm(Request $request, RecaptchaService $recaptcha)
    {
        if (!$recaptcha->verify($request->input('captcha_token'))) {
            return response()->json(['success' => false, 'message' => 'reCAPTCHA verification failed. Please try again.'], 422);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'contact' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        try {
            $inquiry = Inquiry::create($validated);
            
            $subject = "Inquiry: " . $validated['name'];
            $body = "Name: " . $validated['name'] . "\r\n" .
                    "Email: " . $validated['email'] . "\r\n" .
                    "Contact: " . $validated['contact'] . "\r\n\r\n" .
                    "Inquiry:\r\n" . $validated['message'];
            
            $mailto = "mailto:r06@dti.gov.ph?subject=" . rawurlencode($subject) . "&body=" . rawurlencode($body);

            return response()->json(['success' => true, 'mailto' => $mailto]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to save inquiry.'], 500);
        }
    }

    public function downloadPdf($year)
    {
        $year = trim($year);
        $totalRecords = ProjectContent::count();
        Log::info("Starting PDF download for year: [{$year}]. Total records in DB: {$totalRecords}");
        
        // Increase limits for PDF generation
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        try {
            $contents = ProjectContent::where('year_range', $year)->orderBy('page_number')->get();
            if ($contents->isEmpty()) {
                Log::warning("No profile data found for year: [{$year}]");
                return response()->json([
                    'error' => 'No profile data found for this year.',
                    'year' => $year
                ], 404);
            }

            Log::info("Found " . $contents->count() . " records for year: [{$year}]. Starting rendering...");

            $pdf = Pdf::loadView('pdf.profile', [
                'contents' => $contents,
                'year' => $year
            ]);

            // Optional: set paper and orientation
            $pdf->setPaper('a4', 'portrait');

            Log::info("PDF rendered in-memory. Attempting download...");
            return $pdf->download("Western_Visayas_Investment_Profile_{$year}.pdf");
        } catch (\Throwable $e) {
            Log::error("FATAL ERROR during PDF generation for year: [{$year}]", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Failed to generate PDF. This might be due to a memory limit or complex content.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function debugDb()
    {
        $count = ProjectContent::count();
        $years = ProjectContent::distinct()->pluck('year_range')->toArray();
        return response()->json([
            'total_count' => $count,
            'distinct_years' => $years,
            'first_row' => ProjectContent::first()
        ]);
    }

}
