<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Western Visayas Regional Economic Profile - {{ $year }}</title>
    <style>
        @page { margin: 0.6in; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; font-size: 10px; line-height: 1.4; }
        
        .header { text-align: left; border-bottom: 3px solid #334155; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #334155; margin: 0; font-size: 22px; text-transform: uppercase; font-weight: 900; letter-spacing: -0.02em; }
        .header p { color: #64748b; font-size: 10px; margin-top: 4px; font-weight: bold; letter-spacing: 0.1em; }
        
        .c-section { margin-bottom: 30px; page-break-inside: avoid; }
        .c-section-title { 
            font-size: 12px; 
            font-weight: 900; 
            color: #1e293b; 
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 12px; 
            text-transform: uppercase; 
            letter-spacing: 0.05em;
        }

        .c-desc { 
            background: #f8fafc; 
            padding: 10px 12px; 
            border-radius: 6px; 
            margin-bottom: 15px; 
            border-left: 4px solid #334155; 
            font-size: 10px; 
            color: #334155; 
            line-height: 1.5;
        }

        /* Standard Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 9px; }
        th, td { border: 1px solid #f1f5f9; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f8fafc; color: #475569; font-weight: 800; text-transform: uppercase; font-size: 8px; letter-spacing: 0.02em; }
        tr:nth-child(even) { background-color: #fbfcff; }

        /* KPI/Stats Matrix */
        .kpi-table th { width: 30%; background-color: #ffffff; color: #64748b; border: none; border-bottom: 1px solid #f1f5f9; }
        .kpi-table td { width: 70%; font-weight: 900; color: #065f46; font-size: 12px; border: none; border-bottom: 1px solid #f1f5f9; }
        
        /* Dense Data Matrix */
        .matrix-table th.col-name { width: 22%; }
        .matrix-table th.col-details { width: 43%; }
        .matrix-table th.col-extra { width: 35%; }
        .matrix-name { font-weight: 800; color: #1e293b; text-transform: uppercase; font-size: 8px; }
        .matrix-details { color: #334155; }
        
        /* Chart Table Representation */
        .chart-table th { text-align: left; background-color: transparent; border-bottom: 2px solid #f1f5f9; }
        .chart-table td { border: none; border-bottom: 1px solid #f8fafc; padding: 10px 8px; }
        .chart-table td:first-child { font-weight: 800; color: #475569; text-transform: uppercase; font-size: 8px; }
        
        /* Bar Graph in Table */
        .bar-container { 
            width: 100%; 
            background-color: #f1f5f9; 
            height: 12px; 
            border-radius: 2px; 
            overflow: hidden; 
            display: inline-block; 
            vertical-align: middle; 
        }
        .bar-fill { 
            height: 100%; 
            background-color: #334155; 
            border-radius: 0;
        }
        .bar-negative { background-color: #ef4444; }
        .bar-text { 
            display: inline-block; 
            min-width: 40px; 
            text-align: right; 
            font-size: 9px; 
            font-weight: 900; 
            vertical-align: middle; 
            margin-left: 8px;
            color: #0f172a;
        }

        .sub-list { margin: 0; padding-left: 12px; list-style-type: none; }
        .sub-list li { margin-bottom: 4px; position: relative; padding-left: 12px; }
        .sub-list li:before { content: "•"; color: #10b981; position: absolute; left: 0; font-weight: bold; }

        .source { font-size: 8px; color: #94a3b8; font-weight: bold; text-transform: uppercase; margin-top: 10px; text-align: right; }
        .footer { position: fixed; bottom: -0.4in; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 8px; }
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
            <div class="c-section-title">{{ optional($content)->section_title ?? 'Economic Data' }}</div>

            {{-- Description Block --}}
            @if(isset($content) && (isset($content->content['description']) || isset($content->content['notable_info'])))
                <div class="c-desc">
                    @if(isset($content->content['description']))
                        <p style="margin: 0 0 5px 0;">{{ is_array($content->content['description']) ? json_encode($content->content['description']) : $content->content['description'] }}</p>
                    @endif
                    @if(isset($content->content['notable_info']))
                        <p style="margin: 0; color: #059669; font-weight: bold;">NOTE: {{ is_array($content->content['notable_info']) ? json_encode($content->content['notable_info']) : $content->content['notable_info'] }}</p>
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
                                <td class="matrix-details">{{ is_array($item['details']) ? json_encode($item['details']) : $item['details'] }}</td>
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
                                                            <li>{{ $subK }}: {{ is_array($subV) ? json_encode($subV) : $subV }}</li>
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
                    <div style="font-size: 10px; color: #0f172a; font-weight: 800; margin-top: 12px; padding: 10px; background: #f8fafc; border-radius: 6px; border: 1px solid #f1f5f9;">
                        EXECUTIVE SUMMARY: <span style="font-weight: 500; color: #475569;">{{ is_array($content->content['modal_text']) ? json_encode($content->content['modal_text']) : $content->content['modal_text'] }}</span>
                    </div>
                @endif
            @endif

            @if($content->type === 'list')
                <div style="background: #fff; border: 1px solid #e2e8f0; padding: 10px; border-radius: 4px;">
                    <ul class="sub-list">
                        @foreach($content->content['items'] ?? [] as $listItem)
                            <li>{{ is_array($listItem) ? json_encode($listItem) : $listItem }}</li>
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

            @if(isset($content) && $content->source)
                <div class="source">Source: {{ $content->source }}</div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        Generated for DTI Western Visayas - &copy; {{ date('Y') }} External Affairs Division
    </div>
</body>
</html>
