<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectContent;
use App\Models\Inquiry;
use App\Exports\ProjectContentExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
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

        $inquiries = Inquiry::latest()->get();

        return view('admin.dashboard', compact('contents', 'selectedYear', 'years', 'inquiries'));
    }

    public function update(Request $request, ProjectContent $content)
    {
        $validated = $request->validate([
            'section_title' => 'required|string',
            'content' => 'required|array',
            'source' => 'nullable|string',
        ]);

        $content->update($validated);

        return response()->json(['success' => true, 'content' => $content]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_range' => 'required|string',
            'type' => 'required|string',
            'section_title' => 'required|string',
            'content' => 'required|array',
            'page_number' => 'required|integer',
        ]);

        $content = ProjectContent::create($validated);

        return response()->json(['success' => true, 'content' => $content]);
    }

    public function export(Request $request)
    {
        $year = $request->query('year', 'As of 2024');
        return Excel::download(new ProjectContentExport($year), "economic-profile-{$year}.xlsx");
    }

    public function gridView(Request $request)
    {
        $years = ProjectContent::distinct()->pluck('year_range')->toArray();
        if (empty($years)) {
            $years = ['As of 2024'];
        }
        rsort($years);

        $selectedYear = $request->query('year', $years[0]);
        $contents = ProjectContent::where('year_range', $selectedYear)->orderBy('page_number')->get();

        return view('admin.grid', compact('contents', 'selectedYear', 'years'));
    }

    public function destroy(ProjectContent $content)
    {
        $content->delete();
        return response()->json(['success' => true]);
    }

    public function destroyYear($year)
    {
        ProjectContent::where('year_range', $year)->delete();
        return response()->json(['success' => true]);
    }

    public function destroyInquiry(Inquiry $inquiry)
    {
        $inquiry->delete();
        return response()->json(['success' => true]);
    }

    public function duplicateYear(Request $request)
    {
        $validated = $request->validate([
            'source_year' => 'required|string',
            'target_year' => 'required|string',
        ]);

        $sourceContents = ProjectContent::where('year_range', $validated['source_year'])->get();
        
        if ($sourceContents->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Source year has no content.'], 400);
        }

        foreach ($sourceContents as $content) {
            $newContent = $content->replicate();
            $newContent->year_range = $validated['target_year'];
            $newContent->save();
        }

        return response()->json(['success' => true]);
    }

    public function jsonView(Request $request)
    {
        $years = ProjectContent::distinct()->pluck('year_range')->toArray();
        if (empty($years)) {
            $years = ['As of 2024'];
        }
        rsort($years);

        $selectedYear = $request->query('year', $years[0]);
        $contents = ProjectContent::where('year_range', $selectedYear)->orderBy('page_number')->get();

        // Map to a clean structure for editing
        $jsonData = $contents->map(function($item) {
            return [
                'id' => $item->id,
                'page_number' => $item->page_number,
                'section_title' => $item->section_title,
                'type' => $item->type,
                'content' => $item->content,
                'source' => $item->source,
            ];
        });

        return view('admin.json_editor', compact('jsonData', 'selectedYear', 'years'));
    }

    public function updateFromJson(Request $request)
    {
        $validated = $request->validate([
            'json_data' => 'required|string',
            'year' => 'required|string',
        ]);

        $data = json_decode($validated['json_data'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['success' => false, 'message' => 'Invalid JSON format: ' . json_last_error_msg()], 400);
        }

        if (!is_array($data)) {
            return response()->json(['success' => false, 'message' => 'JSON must be an array of objects.'], 400);
        }

        \DB::transaction(function() use ($data, $validated) {
            $existingIds = [];
            
            foreach ($data as $item) {
                $contentData = [
                    'page_number' => $item['page_number'] ?? 0,
                    'section_title' => $item['section_title'] ?? 'Untitled Section',
                    'type' => $item['type'] ?? 'grid',
                    'content' => $item['content'] ?? [],
                    'source' => $item['source'] ?? '',
                    'year_range' => $validated['year'],
                ];

                if (isset($item['id']) && $item['id']) {
                    $content = ProjectContent::find($item['id']);
                    if ($content) {
                        $content->update($contentData);
                        $existingIds[] = $content->id;
                    } else {
                        $newContent = ProjectContent::create($contentData);
                        $existingIds[] = $newContent->id;
                    }
                } else {
                    $newContent = ProjectContent::create($contentData);
                    $existingIds[] = $newContent->id;
                }
            }

            // Purely JSON edit: Delete items not in the JSON
            ProjectContent::where('year_range', $validated['year'])->whereNotIn('id', $existingIds)->delete();
        });

        return response()->json(['success' => true]);
    }
}
