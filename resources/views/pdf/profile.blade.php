<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Western Visayas Regional Economic Profile - {{ $year }}</title>
    <style>
        @page { margin: 0.5in; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1a1a1a; font-size: 10px; line-height: 1.3; }
        
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #10b981; margin: 0; font-size: 18px; text-transform: uppercase; font-weight: 800; }
        .header p { color: #555; font-size: 10px; margin-top: 5px; font-weight: bold; letter-spacing: 0.05em; }
        
        .c-section { margin-bottom: 25px; page-break-inside: avoid; }
        .c-section-title { 
            font-size: 14px; 
            font-weight: bold; 
            color: #fff; 
            background-color: #10b981; 
            padding: 5px 10px; 
            margin-bottom: 10px; 
            text-transform: uppercase; 
            border-radius: 4px;
        }

        .c-desc { 
            background: #f8fafc; 
            padding: 8px; 
            border-radius: 4px; 
            margin-bottom: 10px; 
            border-left: 3px solid #64748b; 
            font-size: 10px; 
            color: #334155; 
            text-align: justify;
        }

        /* Standard Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 9px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #e2e8f0; color: #1e293b; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        tr:nth-child(even) { background-color: #f8fafc; }

        /* KPI/Stats Matrix */
        .kpi-table th { width: 30%; background-color: #f1f5f9; color: #0f172a; }
        .kpi-table td { width: 70%; font-weight: bold; color: #10b981; font-size: 10px; }
        
        /* Dense Data Matrix (For Economic Drivers, etc) */
        .matrix-table th.col-name { width: 20%; }
        .matrix-table th.col-details { width: 45%; }
        .matrix-table th.col-extra { width: 35%; }
        .matrix-name { font-weight: bold; color: #0f172a; }
        .matrix-details { color: #334155; }
        
        /* Chart Table Representation */
        .chart-table th { text-align: center; }
        .chart-table td { text-align: center; }
        .chart-table td:first-child { text-align: left; font-weight: bold; background-color: #f8fafc; }
        
        /* Bar Graph in Table */
        .bar-container { width: 100%; background-color: #e2e8f0; height: 10px; border-radius: 2px; overflow: hidden; display: inline-block; vertical-align: middle; }
        .bar-fill { height: 100%; background-color: #10b981; }
        .bar-negative { background-color: #ef4444; }
        .bar-text { display: inline-block; width: 35px; text-align: right; font-size: 8px; font-weight: bold; vertical-align: middle; margin-left: 5px; }

        .sub-list { margin: 0; padding-left: 15px; list-style-type: square; }
        .sub-list li { margin-bottom: 2px; }

        .source { font-size: 8px; color: #94a3b8; font-style: italic; margin-top: 5px; text-align: right; }
        .footer { position: fixed; bottom: -0.3in; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Western Visayas Regional Economic Profile</h1>
        <p>{{ $year }} EXECUTIVE REPORT</p>
    </div>

    @foreach($contents->sortBy('page_number') as $content)
        @if($content->type === 'metadata' || $content->type === 'marquee' || $content->type === 'cta' || $content->type === 'hero')
            @continue
        @endif

        <div class="c-section">
            <div class="c-section-title">{{ $content->section_title }}</div>

            {{-- Description Block --}}
            @if(isset($content->content['description']) || isset($content->content['notable_info']))
                <div class="c-desc">
                    @if(isset($content->content['description']))
                        <p style="margin: 0 0 5px 0;">{{ $content->content['description'] }}</p>
                    @endif
                    @if(isset($content->content['notable_info']))
                        <p style="margin: 0; color: #059669; font-weight: bold;">NOTE: {{ $content->content['notable_info'] }}</p>
                    @endif
                </div>
            @endif

            {{-- Content Type Logic --}}
            @if($content->type === 'hero' || $content->type === 'stats_grid')
                @php $stats = $content->type === 'hero' ? ($content->content['highlight_stats'] ?? []) : ($content->content['stats'] ?? []); @endphp
                @if(count($stats) > 0)
                    <table class="kpi-table">
                        @foreach(collect($stats)->chunk(2) as $row)
                            <tr>
                                @foreach($row as $stat)
                                    <th>{{ $stat['label'] }}</th>
                                    <td>{{ $stat['value'] }}</td>
                                @endforeach
                                @if($row->count() == 1) <th></th><td></td> @endif
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endif

            @if($content->type === 'grid')
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="col-name">Item / Sector</th>
                            <th class="col-details">Description & Key Stats</th>
                            <th class="col-extra">Additional Details (Locations/Breakdown)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($content->content['items'] ?? [] as $item)
                            <tr>
                                <td class="matrix-name">{{ $item['name'] }}</td>
                                <td class="matrix-details">{{ $item['details'] }}</td>
                                <td>
                                    @if(isset($item['modal_details']))
                                        <ul class="sub-list">
                                        @foreach($item['modal_details'] as $mKey => $mVal)
                                            <li>
                                                @if($mKey === 'Map Points')
                                                    <strong>Locations:</strong> 
                                                    {{ implode(', ', array_map(fn($p) => $p['label'], $mVal)) }}
                                                @elseif(is_array($mVal) && array_keys($mVal) !== range(0, count($mVal) - 1))
                                                    <strong>{{ $mKey }}:</strong>
                                                    <ul style="margin: 2px 0 0 10px; list-style-type: circle;">
                                                        @foreach($mVal as $subK => $subV)
                                                            <li>{{ $subK }}: {{ $subV }}</li>
                                                        @endforeach
                                                    </ul>
                                                @elseif(is_array($mVal))
                                                    <strong>{{ $mKey }}:</strong> {{ implode(', ', $mVal) }}
                                                @else
                                                    <strong>{{ $mKey }}:</strong> {{ $mVal }}
                                                @endif
                                            </li>
                                        @endforeach
                                        </ul>
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($content->type === 'chart')
                @php
                    $series = $content->content['series'] ?? [];
                    $categories = $content->content['categories'] ?? [];
                    $isMultiSeries = count($series) > 1;
                @endphp

                <div style="margin-bottom: 10px; font-weight: bold; font-size: 11px; text-transform: uppercase; color: #334155;">
                    {{ $content->content['title'] ?? 'Data Analysis' }}
                </div>

                @if(!$isMultiSeries && count($series) > 0)
                    {{-- Simple Bar Chart Representation --}}
                    @php 
                        $data = $series[0]['data'] ?? [];
                        $max = !empty($data) ? max(array_map('abs', $data)) : 100;
                        if($max == 0) $max = 1;
                    @endphp
                    <table class="chart-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Category</th>
                                <th style="width: 70%;">Value / Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $cat)
                                @php 
                                    $val = $data[$index] ?? 0;
                                    $width = min(abs($val) / $max * 100, 100);
                                @endphp
                                <tr>
                                    <td>{{ $cat }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div class="bar-container" style="width: 80%;">
                                                <div class="bar-fill {{ $val < 0 ? 'bar-negative' : '' }}" style="width: {{ $width }}%;"></div>
                                            </div>
                                            <div class="bar-text">{{ $val }}%</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    {{-- Multi-Series Table --}}
                    <table class="chart-table">
                        <thead>
                            <tr>
                                <th>Category / Year</th>
                                @foreach($series as $s) <th>{{ $s['name'] }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $cat)
                                <tr>
                                    <td>{{ $cat }}</td>
                                    @foreach($series as $s) 
                                        <td>{{ number_format($s['data'][$index] ?? 0) }}</td> 
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                
                @if(isset($content->content['modal_text']))
                    <div style="font-size: 9px; color: #64748b; font-style: italic; margin-top: 5px;">
                        Analysis: {{ $content->content['modal_text'] }}
                    </div>
                @endif
            @endif

            @if($content->type === 'list')
                <div style="background: #fff; border: 1px solid #e2e8f0; padding: 10px; border-radius: 4px;">
                    <ul class="sub-list">
                        @foreach($content->content['items'] ?? [] as $listItem)
                            <li>{{ $listItem }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- General Modal Details Output if not already handled --}}
            @if(isset($content->content['modal_details']) && $content->type !== 'grid' && $content->type !== 'hero' && $content->type !== 'stats_grid')
                <div style="margin-top: 10px; background: #fff; border: 1px dashed #cbd5e1; padding: 8px;">
                    <strong style="display: block; font-size: 9px; margin-bottom: 5px; color: #475569;">SUPPLEMENTARY DATA:</strong>
                    <ul class="sub-list">
                    @foreach($content->content['modal_details'] as $mKey => $mVal)
                        <li>
                            <strong>{{ $mKey }}:</strong> 
                            @if(is_array($mVal))
                                <ul style="margin: 2px 0 0 10px; list-style-type: circle;">
                                @foreach($mVal as $subK => $subV)
                                    <li>
                                        {{ is_numeric($subK) ? '' : "$subK: " }}
                                        {{ is_array($subV) ? json_encode($subV) : $subV }}
                                    </li>
                                @endforeach
                                </ul>
                            @else
                                {{ $mVal }}
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
            @endif

            @if($content->source)
                <div class="source">Source: {{ $content->source }}</div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        Generated for DTI Western Visayas - &copy; {{ date('Y') }} External Affairs Division
    </div>
</body>
</html>
