<?php

namespace App\Exports;

use App\Models\ProjectContent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectContentExport implements FromCollection, WithHeadings
{
    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
    }

    public function collection()
    {
        // Only fetch types that contain actual economic/demographic data
        $dataOnlyTypes = ['hero', 'stats_grid', 'chart', 'grid', 'list'];
        $contents = ProjectContent::where('year_range', $this->year)
            ->whereIn('type', $dataOnlyTypes)
            ->orderBy('page_number')
            ->get();
            
        $rows = [];

        foreach ($contents as $content) {
            $baseData = [
                'year_range' => $content->year_range,
                'section_title' => $content->section_title,
                'type' => $content->type,
                'source' => $content->source,
            ];

            $data = $content->content;

            switch ($content->type) {
                case 'hero':
                    // In Hero, we only care about stats (e.g. GRDP Growth, Population)
                    if (isset($data['highlight_stats'])) {
                        foreach ($data['highlight_stats'] as $stat) {
                            $rows[] = array_merge($baseData, [
                                'key' => $stat['label'] ?? '',
                                'value' => $stat['value'] ?? '',
                                'extra' => 'Highlight Stat'
                            ]);
                        }
                    }
                    // Exclude title/subtitle as they are structural/branding
                    break;

                case 'stats_grid':
                    if (isset($data['stats'])) {
                        foreach ($data['stats'] as $stat) {
                            $rows[] = array_merge($baseData, [
                                'key' => $stat['label'] ?? '',
                                'value' => $stat['value'] ?? '',
                                'extra' => 'Stat'
                            ]);
                        }
                    }
                    // Description is usually qualitative/structural, but we can keep it if it contains data
                    // For now, focusing on the specific stats
                    break;

                case 'chart':
                    $categories = $data['categories'] ?? [];
                    $series = $data['series'] ?? [];
                    foreach ($series as $s) {
                        foreach ($categories as $index => $cat) {
                            $rows[] = array_merge($baseData, [
                                'key' => $cat,
                                'value' => $s['data'][$index] ?? '',
                                'extra' => $s['name'] ?? 'Value'
                            ]);
                        }
                    }
                    break;

                case 'grid':
                case 'list':
                    if (isset($data['items'])) {
                        foreach ($data['items'] as $item) {
                            if (is_array($item)) {
                                $rows[] = array_merge($baseData, [
                                    'key' => $item['name'] ?? '',
                                    'value' => $item['details'] ?? '',
                                    'extra' => 'Data Point'
                                ]);
                            } else {
                                $rows[] = array_merge($baseData, [
                                    'key' => 'Item',
                                    'value' => $item,
                                    'extra' => ''
                                ]);
                            }
                        }
                    }
                    break;

                // metadata, marquee, cta types are ignored as they are structural
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Year Range',
            'Section Title',
            'Type',
            'Source',
            'Category / Label / Axis',
            'Value / Description',
            'Series / Sub-Type'
        ];
    }
}
