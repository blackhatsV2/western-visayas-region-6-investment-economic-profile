<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ProjectContent;
use App\Models\Inquiry;
use App\Services\RecaptchaService;
use Barryvdh\DomPDF\Facade\Pdf;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $years = ProjectContent::distinct()->pluck('year_range')->toArray();
        if (empty($years)) {
            $years = ['2024-2025'];
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
        $contents = ProjectContent::where('year_range', $year)->get();
        if ($contents->isEmpty()) {
            abort(404, "No profile data found for this year.");
        }

        $pdf = Pdf::loadView('pdf.profile', [
            'contents' => $contents,
            'year' => $year
        ]);

        return $pdf->download("Western_Visayas_Investment_Profile_{$year}.pdf");
    }
}
