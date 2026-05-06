<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @php $meta = $contents->where('type', 'metadata')->first(); @endphp
    <title>{{ isset($meta) && !empty(data_get($meta->content, 'browser_tab_title')) ? data_get($meta->content, 'browser_tab_title') : 'Western Visayas: Investment and Economic Profile' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('dti-logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="WV Invest">
    <link rel="apple-touch-icon" href="{{ asset('dti-logo.png') }}">
    
    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        arbitra: {
                            black: '#000000',
                            dark: '#0A0A0A',
                            emerald: '#10b981',
                            gray: '#888888',
                        }
                    },
                    borderRadius: {
                        'bento': '2rem',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            background-color: #000000; 
            color: #FFFFFF; 
            font-size: 14px;
        }
        
        [x-cloak] { display: none !important; }
        
        .bento-card {
            background: rgba(28, 28, 30, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .bento-card { border-radius: 2rem; }
        }
        
        .bento-card:hover {
            border-color: rgba(16, 185, 129, 0.6);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -20px rgba(0, 0, 0, 0.5);
        }
        
        /* Mobile touch - no hover transform */
        @media (hover: none) {
            .bento-card:hover { transform: none; }
        }

        .pop-indicator {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .bento-card:hover .pop-indicator {
            background: #10b981;
            color: #000000;
            transform: rotate(90deg);
        }

        .text-sm { font-size: 14px; }
        .text-xs { font-size: 14px; } /* Override xs to 14px */
        
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B0B0B; }
        ::-webkit-scrollbar-thumb { background: #1C1C1C; border-radius: 3px; border: 1px solid #2C2C2C; }
        
        .emerald-text { color: #10b981; }
        
        .grid-compact {
            display: grid;
            gap: 1.5rem; /* 24px gap */
        }

        .section-header {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 900;
            letter-spacing: -0.05em;
            text-transform: uppercase;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: #888888;
            transition: all 0.2s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #FFFFFF;
        }
        
        /* Dark Mode Map Tiles */
        .map-tiles {
            filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
        }
        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background-color: #0A0A0A;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        /* ===== MOBILE APP EXPERIENCE ===== */
        @media (max-width: 767px) {
            html { scroll-behavior: smooth; }
            body { padding-top: env(safe-area-inset-top); }

            /* Hide old mobile nav triggers */
            .mobile-top-hide { display: none !important; }

            /* Bottom Navigation Bar */
            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0; left: 0; right: 0;
                z-index: 50;
                background: rgba(10, 10, 10, 0.97);
                backdrop-filter: blur(30px);
                -webkit-backdrop-filter: blur(30px);
                border-top: 1px solid rgba(255, 255, 255, 0.06);
                padding: 0.35rem 0.25rem calc(0.35rem + env(safe-area-inset-bottom));
                justify-content: space-around;
                align-items: center;
            }
            .mobile-bottom-nav a,
            .mobile-bottom-nav button {
                display: flex; flex-direction: column; align-items: center;
                gap: 2px; color: rgba(255,255,255,0.35);
                font-size: 9px; font-weight: 800; text-transform: uppercase;
                letter-spacing: 0.06em; background: none; border: none;
                cursor: pointer; padding: 0.4rem 0.5rem;
                border-radius: 0.75rem; position: relative;
                -webkit-tap-highlight-color: transparent;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .mobile-bottom-nav a.active,
            .mobile-bottom-nav button.active { color: #10b981; }
            .mobile-bottom-nav a.active::before {
                content: ''; position: absolute;
                top: -0.35rem; left: 50%; transform: translateX(-50%);
                width: 18px; height: 3px; background: #10b981;
                border-radius: 3px; box-shadow: 0 0 8px rgba(16,185,129,0.5);
            }
            .mobile-bottom-nav svg { width: 22px; height: 22px; stroke-width: 1.8; }

            /* Full-screen hero */
            .mobile-hero-fullscreen {
                min-height: calc(100svh - 4rem);
                display: flex; flex-direction: column; justify-content: center;
            }
            .mobile-hero-fullscreen > .grid { flex: 1; align-content: center; }

            /* Hero stats horizontal row */
            .mobile-hero-stats {
                flex-direction: row !important;
                overflow-x: auto; scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch; gap: 0.75rem !important;
            }
            .mobile-hero-stats::-webkit-scrollbar { display: none; }
            .mobile-hero-stats { scrollbar-width: none; }
            .mobile-hero-stats > div {
                scroll-snap-align: start; min-width: 44vw; flex-shrink: 0;
            }

            /* Scroll indicator */
            .mobile-scroll-hint {
                display: flex; flex-direction: column; align-items: center;
                gap: 0.5rem; padding: 1rem 0 0;
                color: rgba(255,255,255,0.25);
            }
            @keyframes mobile-bounce {
                0%, 100% { transform: translateY(0); opacity: 0.3; }
                50% { transform: translateY(6px); opacity: 0.8; }
            }
            .mobile-scroll-hint svg { animation: mobile-bounce 2s ease-in-out infinite; }

            /* Horizontal scroll cards (stats) */
            .mobile-scroll-x {
                display: flex !important; overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                gap: 0.75rem !important; padding-bottom: 0.75rem;
                margin: 0 -1rem; padding-left: 1rem; padding-right: 1rem;
            }
            .mobile-scroll-x::-webkit-scrollbar { display: none; }
            .mobile-scroll-x { scrollbar-width: none; }
            .mobile-scroll-x > div {
                scroll-snap-align: start; min-width: 72vw; flex-shrink: 0;
            }

            /* Compact cards */
            .bento-card { border-radius: 1.25rem !important; }

            /* Section spacing */
            .section-header { margin-bottom: 1.25rem; }
            .section-header h2 { font-size: 1.25rem !important; }

            /* Footer + main padding for bottom nav */
            footer { padding-bottom: 8rem !important; }
            main { padding-bottom: 8rem !important; }

            /* Year strip compact */
            .mobile-year-strip { gap: 0.25rem !important; padding: 0.15rem !important; }
            .mobile-year-strip a { padding: 0.2rem 0.5rem !important; font-size: 9px !important; }

            /* Scroll-in animations */
            .mobile-animate {
                opacity: 0; transform: translateY(24px);
                transition: opacity 0.6s cubic-bezier(0.23, 1, 0.32, 1),
                            transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            }
            .mobile-animate.is-visible { opacity: 1; transform: translateY(0); }

            /* Marquee compact */
            .animate-marquee span {
                font-size: 1rem !important;
                margin-left: 1.5rem !important; margin-right: 1.5rem !important;
            }
        }

        /* Desktop: hide mobile-only elements */
        @media (min-width: 768px) {
            .mobile-bottom-nav { display: none !important; }
            .mobile-scroll-hint { display: none !important; }
        }
        /* Floating Sidebar Styles */
        .sidebar-container {
            position: fixed;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%) translateX(-120%);
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
            padding: 0.5rem;
            background: rgba(10, 10, 10, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease;
            opacity: 0;
        }

        .sidebar-container.is-visible {
            transform: translateY(-50%) translateX(0);
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-sidebar-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            background: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            z-index: 300;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-sidebar-drawer.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 299;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .sidebar-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
        
        @media (min-width: 1024px) {
            .sidebar-container { left: 1.5rem; }
        }
        
        .sidebar-btn {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            position: relative;
        }
        
        .sidebar-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 50%;
            background: transparent;
            color: rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-btn:hover .sidebar-icon,
        .sidebar-btn.active .sidebar-icon {
            background: #10b981;
            color: #000000;
            border-color: #10b981;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        }
        
        .sidebar-label {
            padding: 0.5rem 1rem 0.5rem 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            display: none; /* Mobile */
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        @media (min-width: 1024px) {
            .sidebar-label {
                display: block; /* Desktop */
                opacity: 1;
                transform: translateX(0);
            }
            .sidebar-btn:hover .sidebar-icon {
                background: #10b981;
                color: #000000;
                border-color: #10b981;
                transform: scale(1.1);
            }
        }
        
        /* Mobile Tooltip */
        .mobile-tooltip {
            position: absolute;
            left: 4rem;
            background: #10b981;
            color: #000000;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            font-weight: 800;
            font-size: 0.7rem;
            text-transform: uppercase;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 101;
        }
        
        @media (max-width: 1023px) {
            .sidebar-container { left: 1rem; }
            .sidebar-icon { width: 2.5rem; height: 2.5rem; }
        }

        /* Desktop Sidebar Toggle (Integrated) */
        .desktop-sidebar-toggle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .desktop-sidebar-toggle:hover {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .desktop-sidebar-toggle.sidebar-hidden {
            background: #10b981;
            border-color: #10b981;
            color: #000000;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
        }
        /* Power Search Styles */
        .search-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .search-container {
            width: 100%;
            max-width: 600px;
            background: rgba(15, 15, 15, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: 80vh;
        }

        .search-input-wrapper {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-input {
            background: transparent;
            border: none;
            color: #FFFFFF;
            width: 100%;
            font-size: 1.125rem;
            outline: none;
            font-weight: 500;
        }

        .search-results {
            overflow-y: auto;
            padding: 0.5rem;
        }

        .search-result-item {
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            color: rgba(255, 255, 255, 0.6);
        }

        .search-result-item:hover, .search-result-item.selected {
            background: rgba(16, 185, 129, 0.1);
            color: #FFFFFF;
        }

        .search-result-item.selected {
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .search-category {
            padding: 0.75rem 1rem 0.25rem;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(16, 185, 129, 0.8);
        }

        .search-shortcut-hint {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.7rem;
            font-weight: 600;
        }

        .kbd {
            padding: 0.1rem 0.4rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
            font-family: sans-serif;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-block;
            animation: marquee 30s linear infinite;
        }
    </style>

</head>
<body x-data="app()" class="antialiased font-sans">
    @php
        // Dynamic Navigation Builder
        $navSections = $contents->whereNotIn('type', ['metadata', 'marquee', 'cta'])
            ->filter(fn($item) => !in_array(strtolower(trim($item->section_title)), ['title page', 'cover page', 'intro', 'introduction', 'welcome']))
            ->sortBy('page_number')
            ->map(function($item) {
                $slug = Str::slug($item->section_title);
                if (empty($slug)) $slug = "section-" . $item->id;
                
                // Dynamic Icon Mapping
                $icon = 'M4 6h16M4 12h16M4 18h7'; // Default
                $title = strtolower($item->section_title);
                
                if ($item->type === 'hero') $icon = 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6';
                elseif (str_contains($title, 'economy') || str_contains($title, 'gdp')) $icon = 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z';
                elseif (str_contains($title, 'driver') || str_contains($title, 'priority')) $icon = 'M13 10V3L4 14h7v7l9-11h-7z';
                elseif (str_contains($title, 'infra') || str_contains($title, 'airport') || str_contains($title, 'port')) $icon = 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4';
                elseif (str_contains($title, 'logistic') || str_contains($title, 'transport')) $icon = 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9';
                elseif (str_contains($title, 'industry')) $icon = 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.628.282a2 2 0 01-1.808 0l-.628-.282a6 6 0 00-3.86-.517l-2.387.477a2 2 0 00-1.022.547l-1.159 1.159a2 2 0 00-.547 1.022l-.477 2.387a2 2 0 00.547 1.022l1.159 1.159a2 2 0 001.022.547l2.159-.432-.628.282a2 2 0 011.808 0l.628.282a6 6 0 003.86.517z';
                
                return [
                    'id' => $slug,
                    'name' => $item->section_title,
                    'icon' => $icon
                ];
            })->filter(fn($i) => !empty($i['name']));

        $sidebarNav = $navSections->values()->toArray();
        $sidebarNav[] = ['id' => 'action', 'name' => 'Connect', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'];
    @endphp
    <script>
        // Global Map Renderer for Blocks
        window.renderModalMap = function(containerId, data) {
            const mapContainer = document.getElementById(containerId);
            const points = Array.isArray(data) ? data : (data.items || []);
            if (!mapContainer || points.length === 0) return;

            // Optional: Store map instance globally to destroy it on close
            if (window.currentModalMap) { window.currentModalMap.remove(); }
            
            window.currentModalMap = L.map(containerId).setView([points[0].lat, points[0].lng], 8);
            
            setTimeout(() => { window.currentModalMap.invalidateSize(); }, 200);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                className: 'map-tiles'
            }).addTo(window.currentModalMap);

            const emeraldIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color: #10b981; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px #10b981;'></div>",
                iconSize: [12, 12],
                iconAnchor: [6, 6]
            });

            const bounds = [];
            points.forEach(point => {
                L.marker([point.lat, point.lng], {icon: emeraldIcon})
                    .addTo(window.currentModalMap)
                    .bindPopup(`<b style="color:#FFFFFF; font-size: 13px; text-transform: uppercase;">${point.label}</b>`);
                
                bounds.push([point.lat, point.lng]);
            });
            
            if (bounds.length > 0) {
                window.currentModalMap.fitBounds(bounds, { padding: [50, 50] });
            }
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                mobileSidebarOpen: false,
                desktopSidebarOpen: true,
                modalOpen: false,
                contactOpen: false,
                selectedYear: '{{ $selectedYear }}',
                policyOpen: false,
                termsOpen: false,
                modalTitle: '', 
                modalContent: {},
                contactForm: { name: '', email: '', contact: '', message: '' },
                contactLoading: false,
                contactSuccess: false,
                searchOpen: false,
                searchQuery: localStorage.getItem('power_search_query') || '',
                searchResults: [],
                searchIndex: [],
                selectedIndex: 0,
                highlightMarker: null,
                map: null,
                init() {
                    this.buildSearchIndex();
                    
                    if (this.searchQuery) {
                        this.performSearch();
                    }

                    window.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.searchOpen = !this.searchOpen;
                        }
                        if (this.searchOpen) {
                            if (e.key === 'Escape') this.searchOpen = false;
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                this.selectedIndex = (this.selectedIndex + 1) % this.searchResults.length;
                            }
                            if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                this.selectedIndex = (this.selectedIndex - 1 + this.searchResults.length) % this.searchResults.length;
                            }
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                if (this.searchResults[this.selectedIndex]) {
                                    this.navigateToResult(this.searchResults[this.selectedIndex]);
                                }
                            }
                        }
                    });

                    this.$watch('searchOpen', value => {
                        this.updateScrollLock();
                        if (value) {
                            setTimeout(() => {
                                this.$refs.searchInput?.focus();
                                this.$refs.searchInput?.select();
                            }, 50);
                        }
                    });

                    this.$watch('searchQuery', value => {
                        localStorage.setItem('power_search_query', value);
                        this.performSearch();
                    });

                    this.$watch('modalOpen', value => {
                        this.updateScrollLock();
                        if (value) {
                            setTimeout(() => { this.initMap(); }, 100); 
                        } else if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }
                    });
                    this.$watch('contactOpen', () => this.updateScrollLock());
                    this.$watch('policyOpen', () => this.updateScrollLock());
                    this.$watch('termsOpen', () => this.updateScrollLock());
                },
                updateScrollLock() {
                    if (this.modalOpen || this.contactOpen || this.policyOpen || this.termsOpen || this.searchOpen) {
                        document.body.classList.add('overflow-hidden');
                    } else {
                        document.body.classList.remove('overflow-hidden');
                    }
                },
                openModal(title, content, highlight = null) {
                    this.modalTitle = title;
                    this.modalContent = content;
                    this.highlightMarker = highlight;
                    this.modalOpen = true;
                },
                openFromEl(el, highlight = null) {
                    if (!el.dataset.content) return;
                    try {
                        const content = JSON.parse(el.dataset.content);
                        const title = el.dataset.title || 'Details';
                        this.openModal(title, content, highlight);
                    } catch (e) {
                        console.error('Modal Error:', e, el.dataset.content);
                    }
                },
                initMap() {
                    // Legacy Support for dictionaries (Map Points property)
                    if (!Array.isArray(this.modalContent) && this.modalContent && this.modalContent['Map Points']) {
                        this.renderMapInstance('leaflet-map', this.modalContent['Map Points']);
                    } else if (Array.isArray(this.modalContent)) {
                       // Find the first map block and render it for global backward compatibility
                       const mapBlock = this.modalContent.find(b => b.type === 'map');
                       if (mapBlock && mapBlock.data) {
                           // The frontend will call renderModalMap via x-init, but we can do a fallback here
                           setTimeout(() => {
                               // Usually handled by x-init="$nextTick(() => window.renderModalMap(block.data, index))",
                               // but we keep this here just in case.
                           }, 100);
                       }
                    }
                },
                renderMapInstance(containerId, points) {
                    const mapContainer = document.getElementById(containerId);
                    if (!mapContainer || !points || points.length === 0) return;

                    if (this.map) { this.map.remove(); }
                    this.map = L.map(containerId).setView([points[0].lat, points[0].lng], 8);
                    
                    setTimeout(() => { this.map.invalidateSize(); }, 200);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap',
                        className: 'map-tiles'
                    }).addTo(this.map);

                    const emeraldIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: "<div style='background-color: #10b981; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px #10b981;'></div>",
                        iconSize: [12, 12],
                        iconAnchor: [6, 6]
                    });

                    const bounds = [];
                    points.forEach(point => {
                        const m = L.marker([point.lat, point.lng], {icon: emeraldIcon})
                            .addTo(this.map)
                            .bindPopup(`<b style="color:#FFFFFF; font-size: 13px; text-transform: uppercase;">${point.label}</b>`);
                        
                        // Auto-open popup if this is the highlighted marker
                        if (this.highlightMarker && point.label.toLowerCase().includes(this.highlightMarker.toLowerCase())) {
                            setTimeout(() => {
                                if (this.map) {
                                    m.openPopup();
                                    this.map.setView([point.lat, point.lng], 12);
                                }
                            }, 800);
                        }
                        
                        bounds.push([point.lat, point.lng]);
                    });
                    
                    if (bounds.length > 0) {
                        this.map.fitBounds(bounds, { padding: [50, 50] });
                    }
                },
                async submitInquiry() {
                    this.contactLoading = true;
                    try {
                        const token = await new Promise((resolve, reject) => {
                            grecaptcha.ready(() => {
                                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact'}).then(resolve).catch(reject);
                            });
                        });

                        const response = await fetch('/contact', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ...this.contactForm, captcha_token: token })
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.contactSuccess = true;
                            this.contactForm = { name: '', email: '', contact: '', message: '' };
                            if (data.mailto) {
                                window.location.href = data.mailto;
                            }
                        } else {
                            alert(data.message || 'Error sending inquiry');
                        }
                    } catch (e) {
                        alert('Connect Error: Failed to send inquiry');
                    } finally {
                        this.contactLoading = false;
                    }
                },
                buildSearchIndex() {
                    this.searchIndex = [];
                    // Index Sections
                    const sections = document.querySelectorAll('section[id], div[id="hero"]');
                    sections.forEach(s => {
                        const title = s.querySelector('h2')?.innerText || s.id;
                        this.searchIndex.push({
                            type: 'Section',
                            title: title,
                            id: s.id,
                            icon: 'M4 6h16M4 12h16M4 18h7',
                            searchableText: (s.innerText || '').toLowerCase()
                        });
                        
                        // Index plain paragraphs inside the section
                        const paragraphs = s.querySelectorAll('p, span:not(.bento-card span)');
                        paragraphs.forEach(p => {
                            const text = p.innerText?.trim();
                            if (text && text.length > 5) {
                                this.searchIndex.push({
                                    type: 'Text',
                                    title: text.substring(0, 50) + (text.length > 50 ? '...' : ''),
                                    subtitle: `in ${title}`,
                                    id: s.id,
                                    el: p,
                                    searchableText: text.toLowerCase(),
                                    icon: 'M4 6h16M4 12h16M4 18h7'
                                });
                            }
                        });
                    });

                    // Index Stats and Cards
                    const cards = document.querySelectorAll('.bento-card');
                    cards.forEach((card, idx) => {
                        const title = card.querySelector('h3')?.innerText || card.querySelector('span')?.innerText;
                        const subtitle = card.querySelector('p')?.innerText;
                        const cardText = card.innerText || '';
                        
                        if (cardText && cardText.length > 2) {
                            this.searchIndex.push({
                                type: 'Card/Stat',
                                title: title || 'Card Info',
                                subtitle: subtitle,
                                id: card.closest('section')?.id || 'hero',
                                el: card,
                                searchableText: cardText.toLowerCase(),
                                icon: 'M13 10V3L4 14h7v7l9-11h-7z'
                            });
                        }

                        // Recursively index modal content if present
                        if (card.dataset.content) {
                            try {
                                const content = JSON.parse(card.dataset.content);
                                this.indexRecursive(content, card, title || 'Details');
                            } catch (e) {
                                console.error('Index Error:', e);
                            }
                        }
                    });
                },
                indexRecursive(obj, el, parentTitle) {
                    if (!obj) return;
                    
                    if (Array.isArray(obj)) {
                        obj.forEach(item => {
                            if (typeof item === 'string') {
                                this.searchIndex.push({
                                    type: 'Detail',
                                    title: item.substring(0, 50) + (item.length > 50 ? '...' : ''),
                                    subtitle: `in ${parentTitle}`,
                                    el: el,
                                    searchableText: item.toLowerCase(),
                                    isModalItem: true,
                                    icon: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                                });
                            } else {
                                this.indexRecursive(item, el, parentTitle);
                            }
                        });
                    } else if (typeof obj === 'object') {
                        // Special Handling for Map Points
                        if (obj['Map Points'] && Array.isArray(obj['Map Points'])) {
                            obj['Map Points'].forEach(point => {
                                if (point.label) {
                                    this.searchIndex.push({
                                        type: 'Location',
                                        title: point.label,
                                        subtitle: `Map Point in ${parentTitle}`,
                                        el: el,
                                        searchableText: point.label.toLowerCase(),
                                        isModalItem: true,
                                        markerLabel: point.label,
                                        icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'
                                    });
                                }
                            });
                        }

                        for (const [key, value] of Object.entries(obj)) {
                            if (key === 'Map Points') continue;

                            if (typeof value === 'string') {
                                // Index both key and value if it's a stats-like object
                                const title = isNaN(parseInt(key)) ? `${key}: ${value}` : value;
                                this.searchIndex.push({
                                    type: 'Detail',
                                    title: title.substring(0, 50) + (title.length > 50 ? '...' : ''),
                                    subtitle: `in ${parentTitle}`,
                                    el: el,
                                    searchableText: `${key} ${value}`.toLowerCase(),
                                    isModalItem: true,
                                    icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                });
                            } else {
                                this.indexRecursive(value, el, parentTitle);
                            }
                        }
                    }
                },
                performSearch() {
                    if (!this.searchQuery.trim()) {
                        this.searchResults = [];
                        return;
                    }
                    
                    const queryWords = this.searchQuery.toLowerCase().split(/\s+/).filter(w => w.length > 0);
                    
                    this.searchResults = this.searchIndex.filter(item => {
                        const searchText = item.searchableText || '';
                        const titleText = item.title ? item.title.toLowerCase() : '';
                        const subtitleText = item.subtitle ? item.subtitle.toLowerCase() : '';
                        
                        // Match ALL words in the query against the searchable text, title or subtitle
                        return queryWords.every(word => 
                            searchText.includes(word) || 
                            titleText.includes(word) || 
                            subtitleText.includes(word)
                        );
                    }).slice(0, 50); // limit to top 50 matches for performance
                    
                    this.selectedIndex = 0;
                },
                navigateToResult(result) {
                    this.searchOpen = false;
                    if (result.el) {
                        result.el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        if (result.isModalItem) {
                            // Delay slightly for smooth scroll to finish, then open modal
                            setTimeout(() => {
                                this.openFromEl(result.el, result.markerLabel);
                            }, 500);
                        } else {
                            result.el.classList.add('ring-4', 'ring-arbitra-emerald/50');
                            setTimeout(() => result.el.classList.remove('ring-4', 'ring-arbitra-emerald/50'), 2000);
                        }
                    } else if (result.id) {
                        const el = document.getElementById(result.id);
                        if (el) el.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }));
        });
    </script>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[400] bg-arbitra-black/80 backdrop-blur-xl border-b border-white/5 py-3 md:py-4">
        <div class="max-w-[1240px] mx-auto px-4 md:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3 md:gap-4">
                <!-- Desktop Sidebar Toggle -->
                <button class="desktop-sidebar-toggle hidden md:flex" 
                        :class="{ 'sidebar-hidden': !desktopSidebarOpen }"
                        @click="desktopSidebarOpen = !desktopSidebarOpen"
                        title="Toggle Navigation">
                    <svg x-show="desktopSidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <svg x-show="!desktopSidebarOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Sidebar Burger (Mobile Only) -->
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="flex md:hidden flex-col items-center justify-center w-9 h-9 rounded-full bg-white/5 border border-white/10 backdrop-blur-xl transition-all active:scale-95">
                    <svg x-show="!mobileSidebarOpen" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileSidebarOpen" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <img src="{{ asset('dti-logo.png') }}" class="h-7 md:h-8 w-auto" alt="DTI Logo">
                <div class="h-6 w-px bg-white/10 hidden md:block"></div>
                <h1 class="text-[9px] md:text-sm font-black tracking-tight uppercase block max-w-[120px] md:max-w-none leading-tight">{{ isset($meta) && !empty(data_get($meta->content, 'site_title')) ? data_get($meta->content, 'site_title') : 'Western Visayas: Investment and Economic Profile' }}</h1>
            </div>
            
            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-6 bg-white/5 px-6 py-2 rounded-full border border-white/5 overflow-x-auto max-w-[400px] no-scrollbar">
                @foreach(collect($sidebarNav)->take(5) as $nav)
                    <a href="#{{ $nav['id'] }}" class="nav-link text-[10px] font-black uppercase tracking-widest whitespace-nowrap">{{ strtoupper($nav['name']) }}</a>
                @endforeach
            </div>
            
            <!-- Right Side Actions -->
            <div class="flex items-center gap-2 md:gap-3">
                @php 
                    $yearsList = collect($years);
                    $shownYears = $yearsList->take(2); 
                    $otherYears = $yearsList->slice(2);
                    
                    // On mobile we show 1, so the 2nd year onwards should be in the dropdown
                    $mobileDropYears = $yearsList->slice(1);
                @endphp
                <div class="flex items-center gap-1.5 md:gap-2 bg-white/5 px-1.5 md:px-2 py-1 md:py-1.5 rounded-full border border-white/5" x-data="{ moreOpen: false }">
                    <!-- Search Trigger -->
                    <button @click="searchOpen = true" 
                            class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 rounded-full bg-arbitra-emerald text-arbitra-black border border-arbitra-emerald/50 shadow-[0_0_15px_rgba(16,185,129,0.4)] hover:scale-110 transition-all mr-2"
                            title="Power Search (Ctrl+K)">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    
                    @foreach($shownYears as $index => $year)
                        <a href="?year={{ $year }}" 
                           class="px-2 md:px-3 py-1 rounded-full text-[9px] md:text-[10px] font-bold transition-all {{ $selectedYear == $year ? 'bg-arbitra-emerald text-arbitra-black shadow-[0_0_10px_rgba(16,185,129,0.3)]' : 'text-arbitra-gray hover:text-white' }} {{ $index >= 1 ? 'hidden md:block' : '' }}">
                            {{ $year }}
                        </a>
                    @endforeach
                    
                    @if($mobileDropYears->count() > 0)
                    <div class="relative">
                        <button @click="moreOpen = !moreOpen" 
                                class="px-1.5 md:px-2 py-1 text-arbitra-gray hover:text-white transition-all text-xs font-bold flex items-center gap-1 group"
                                title="More periods available">
                            <span class="text-[10px]" :class="moreOpen ? 'rotate-90' : ''">›</span>
                        </button>
                        <div x-show="moreOpen" @click.away="moreOpen = false" x-cloak
                             class="absolute top-10 right-0 bg-arbitra-dark border border-white/10 rounded-xl p-2 min-w-[100px] z-50 shadow-2xl">
                            {{-- Desktop Dropdown (from 4th year onwards) --}}
                            <div class="hidden md:block">
                                @foreach($otherYears as $year)
                                    <a href="?year={{ $year }}" 
                                       class="block px-4 py-2 rounded-lg text-[10px] font-bold transition-all {{ $selectedYear == $year ? 'bg-arbitra-emerald text-arbitra-black' : 'text-arbitra-gray hover:text-white hover:bg-white/5' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
                            {{-- Mobile Dropdown (from 2nd year onwards) --}}
                            <div class="md:hidden">
                                @foreach($mobileDropYears as $year)
                                    <a href="?year={{ $year }}" 
                                       class="block px-4 py-2 rounded-lg text-[10px] font-bold transition-all {{ $selectedYear == $year ? 'bg-arbitra-emerald text-arbitra-black' : 'text-arbitra-gray hover:text-white hover:bg-white/5' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Mobile Actions (PDF/Connect) -->
                <div class="flex md:hidden items-center gap-2">
                    <a href="/download-profile/{{ rawurlencode($selectedYear) }}" 
                       class="flex items-center justify-center w-8 h-8 rounded-full bg-white/5 border border-white/10 text-arbitra-gray"
                       title="Download PDF">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </a>
                    <button @click="contactOpen = true; contactSuccess = false" class="bg-arbitra-emerald text-arbitra-black p-2 rounded-full hover:scale-105 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </button>
                </div>

                <!-- Desktop Tools -->
                <div class="hidden md:flex items-center gap-3">
                    <div class="relative group">
                        <a href="/download-profile/{{ rawurlencode($selectedYear) }}" 
                           class="flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10 text-arbitra-gray hover:text-arbitra-emerald hover:border-arbitra-emerald/50 transition-all"
                           title="Download Profile PDF">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                        <div class="absolute top-12 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-arbitra-dark border border-white/10 rounded-lg text-[10px] font-bold text-white whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50">
                            DOWNLOAD PDF
                        </div>
                    </div>

                    <button @click="contactOpen = true; contactSuccess = false" class="bg-arbitra-emerald text-arbitra-black px-4 md:px-6 py-2 md:py-2.5 rounded-full font-black text-[10px] md:text-xs uppercase tracking-widest hover:brightness-110 transition shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                        Connect
                    </button>
                </div>

            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 h-[2px] bg-arbitra-emerald transition-all duration-300" id="scroll-progress" style="width: 0%"></div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 md:pt-28 pb-32 md:pb-20 px-4 md:px-8 lg:pl-64 transition-all duration-500">
        @if(isset($noContent) && $noContent)
            <div class="min-h-[60vh] flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mb-8 animate-pulse">
                    <svg class="w-10 h-10 text-arbitra-emerald opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4 tracking-tight">Coming Soon :)</h2>
                <p class="text-arbitra-gray max-w-md mx-auto text-lg">Data for <span class="text-arbitra-emerald font-bold">{{ $selectedYear }}</span> is currently being collated. Please check back later.</p>
                <a href="?year=As of 2024" class="mt-8 px-8 py-3 rounded-full bg-white/5 border border-white/10 text-white font-bold text-sm hover:bg-white/10 transition">Return to As of 2024</a>
            </div>
        @else
        <div class="max-w-[1240px] mx-auto space-y-16">
            
            @foreach($contents->whereNotIn('type', ['metadata'])->sortBy('page_number') as $content)
                @php
                    $slug = Str::slug($content->section_title);
                    if (empty($slug)) $slug = "section-" . $content->id;
                    $sectionId = $slug;
                @endphp

                @if($content->type === 'hero')
                    <div id="{{ $sectionId }}" class="scroll-mt-32 mobile-hero-fullscreen">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div @if(!empty(data_get($content->content, 'modal_details'))) 
                                    data-content="{{ json_encode(data_get($content->content, 'modal_details.Why Invest in Visayas Logistics Cluster?', data_get($content->content, 'modal_details'))) }}"
                                    data-title="{{ data_get($content->content, 'modal_details.Why Invest in Visayas Logistics Cluster?.title', 'Why Invest in Visayas Logistics Cluster?') }}"
                                    @click="openFromEl($el)" 
                                    class="lg:col-span-2 bento-card p-6 md:p-12 flex flex-col justify-center bg-gradient-to-br from-arbitra-dark to-arbitra-black cursor-pointer group hover:border-arbitra-emerald/60 transition-all relative overflow-hidden"
                                 @else
                                    class="lg:col-span-2 bento-card p-6 md:p-12 flex flex-col justify-center bg-gradient-to-br from-arbitra-dark to-arbitra-black"
                                 @endif>
                                 
                                <div class="absolute inset-0 bg-arbitra-emerald/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-10">
                                        <span class="px-5 py-1.5 rounded-full bg-arbitra-emerald/10 text-arbitra-emerald font-black text-[10px] uppercase tracking-[0.2em] border border-arbitra-emerald/20">Investment Motivation</span>
                                        @if(!empty(data_get($content->content, 'modal_details')))
                                        <div class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-white/50 group-hover:text-white group-hover:bg-arbitra-emerald/20 transition-all flex items-center gap-2">
                                            <span>CLICK TO VIEW DETAILS</span>
                                        </div>
                                        @endif
                                    </div>
                                    <h2 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 md:mb-10 leading-[1] tracking-tighter uppercase italic group-hover:text-white transition-colors">
                                        {!! nl2br(e(data_get($content->content, 'title', 'Why Invest in Western Visayas?'))) !!}
                                    </h2>
                                    <p class="text-lg text-arbitra-gray max-w-xl leading-relaxed font-medium group-hover:text-white/80 transition-colors">
                                        {{ data_get($content->content, 'description', '') }}
                                    </p>
                                </div>
                                <div class="pt-8 mt-auto border-t border-white/5">
                                    <span class="text-[10px] font-bold text-arbitra-gray uppercase tracking-widest block mb-1">Source</span>
                                    <p class="text-xs text-arbitra-emerald font-bold">{{ $content->source ?? 'DTI Region 6' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-row md:flex-col gap-3 md:gap-6 overflow-x-auto mobile-hero-stats">
                                @foreach((data_get($content->content, 'highlight_stats', [])) as $index => $stat)
                                <div class="bento-card flex-1 p-6 md:p-10 flex flex-col justify-between">
                                    <span class="text-sm font-bold text-arbitra-gray uppercase tracking-widest">{{ data_get($stat, 'label') }}</span>
                                    <div class="mt-4">
                                        <h3 class="text-3xl md:text-5xl font-black emerald-text tracking-tighter">{{ data_get($stat, 'value') }}</h3>
                                        <span class="text-[10px] font-black text-arbitra-gray uppercase tracking-widest mt-2 block opacity-40">{{ data_get($stat, 'label') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mobile-scroll-hint">
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em]">Scroll to explore</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                    </div>

                @elseif($content->type === 'marquee')
                    <div x-data="{ hovered: false }" 
                         @mouseenter="hovered = true" 
                         @mouseleave="hovered = false"
                         class="relative overflow-hidden whitespace-nowrap py-10 border-y border-white/5 transition duration-500">
                        <div class="inline-block animate-marquee transition-all duration-500" :class="hovered ? 'scale-[1.1] text-arbitra-emerald' : ''">
                            @php $firms = data_get($content->content, 'items', []); @endphp
                            @foreach(array_merge($firms, $firms, $firms) as $firm)
                                <span class="text-2xl font-black mx-12 tracking-widest cursor-default inline-block transition-colors duration-500" :class="hovered ? 'text-arbitra-emerald' : 'text-white'">
                                    {{ $firm }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                @elseif($content->type === 'stats_grid')
                    <section id="{{ $sectionId }}" class="scroll-mt-32 border-b border-white/5 pb-20 mb-20">
                        <div class="section-header border-b border-white/5 pb-4 mb-10 justify-between items-end">
                            <h2 class="font-black uppercase tracking-tight">{{ $content->section_title }}</h2>
                            <div class="hidden md:block text-right">
                                <span class="text-[10px] font-bold text-arbitra-gray uppercase tracking-widest block">Source</span>
                                <p class="text-xs text-arbitra-emerald font-bold">{{ $content->source ?? 'DTI Region 6' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mobile-scroll-x">
                            @foreach((data_get($content->content, 'stats', [])) as $stat)
                                @php
                                    $hasExtra = !empty(data_get($stat, 'modal_details')) || !empty(data_get($content->content, 'modal_details')) || !empty(data_get($stat, 'detail'));
                                    $extraData = data_get($stat, 'modal_details') ?? data_get($content->content, 'modal_details') ?? (!empty(data_get($stat, 'detail')) ? ['Details' => data_get($stat, 'detail')] : null);
                                    $label = strtolower(data_get($stat, 'label'));
                                    $iconPath = 'M13 10V3L4 14h7v7l9-11h-7z'; 
                                    if(str_contains($label, 'population') || str_contains($label, 'graduate')) $iconPath = 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z';
                                    if(str_contains($label, 'area') || str_contains($label, 'land')) $iconPath = 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                @endphp
                                <div @if($hasExtra) data-content="{{ json_encode($extraData) }}" data-title="{{ data_get($stat, 'label') }}" @click="openFromEl($el)" @endif
                                     class="bento-card p-5 md:p-8 flex flex-col justify-between {{ $hasExtra ? 'poppable cursor-pointer group' : '' }}">
                                    @if($hasExtra)
                                    <div class="px-2 py-0.5 rounded-full bg-white/5 border border-white/10 text-[9px] font-bold text-white/30 group-hover:text-white group-hover:bg-arbitra-emerald/20 transition-all flex items-center gap-1.5 absolute top-6 right-6">
                                        <span>DETAILS</span>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="w-10 h-10 rounded-xl bg-arbitra-emerald/10 flex items-center justify-center mb-8 border border-arbitra-emerald/10">
                                            <svg class="h-5 w-5 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path></svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-arbitra-gray uppercase tracking-widest mb-3">{{ data_get($stat, 'label') }}</h4>
                                        <h3 class="text-3xl font-extrabold text-white tracking-tight leading-none">{{ data_get($stat, 'value') }}</h3>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif($content->type === 'chart')
                    <section id="{{ $sectionId }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-20 border-b border-white/5 pb-20 scroll-mt-32">
                        <div class="lg:col-span-2 bento-card p-6 md:p-12">
                            <div class="flex items-center justify-between mb-12">
                                <div>
                                    <h2 class="text-2xl font-extrabold text-white">{{ $content->section_title }}</h2>
                                    <p class="text-sm text-arbitra-gray mt-2">{{ data_get($content->content, 'modal_text', '') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-arbitra-emerald animate-pulse"></div>
                                    <span class="text-sm font-bold text-arbitra-gray uppercase">Trending</span>
                                </div>
                            </div>
                            <div id="chart-{{ $content->id }}" class="w-full"></div>
                        </div>
                        <div class="bento-card p-6 md:p-10 flex flex-col justify-between bg-gradient-to-br from-arbitra-dark to-[#151515]">
                            <div>
                                <h3 class="text-sm font-bold text-arbitra-gray uppercase tracking-[0.2em] mb-8">INSIGHTS</h3>
                                <p class="text-base text-white/80 leading-relaxed font-medium">
                                    {{ data_get($content->content, 'modal_text', 'Detailed statistical analysis of the regional performance.') }}
                                </p>
                            </div>
                            <div class="pt-8 border-t border-white/5">
                                <span class="text-[10px] font-bold text-arbitra-gray block mb-2 uppercase tracking-widest">Source</span>
                                <p class="text-xs text-arbitra-emerald font-bold italic">{{ $content->source ?? 'DTI Region 6' }}</p>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var options = {
                                    series: @json(data_get($content->content, 'series', [])),
                                    chart: { type: '{{ data_get($content->content, 'chart_type', 'bar') }}', height: 400, fontFamily: 'Inter, sans-serif', toolbar: { show: false }, background: 'transparent' },
                                    theme: { mode: 'dark' },
                                    plotOptions: { bar: { horizontal: {{ !empty(data_get($content->content, 'horizontal')) ? 'true' : 'false' }}, borderRadius: 2, columnWidth: '50%', distributed: {{ count((array)data_get($content->content, 'series', [])) <= 1 ? 'true' : 'false' }} } },
                                    grid: { borderColor: 'rgba(255,255,255,0.05)' },
                                    xaxis: { categories: @json(data_get($content->content, 'categories', [])), labels: { style: { colors: '#94a3b8', fontSize: '11px' } } },
                                    yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '11px' } } },
                                    colors: ['#10b981', '#FFFFFF', '#334155'],
                                    dataLabels: { enabled: false },
                                    stroke: { width: 2, curve: 'smooth' },
                                    fill: { opacity: 0.9 },
                                    tooltip: { theme: 'dark' }
                                };
                                var chart = new ApexCharts(document.querySelector("#chart-{{ $content->id }}"), options);
                                chart.render();
                            });
                        </script>
                    </section>

                @elseif($content->type === 'grid')
                    <section id="{{ $sectionId }}" class="scroll-mt-32 border-b border-white/5 pb-20 mb-20">
                        <div class="section-header border-b border-white/5 pb-4 mb-10 justify-between items-end">
                            <h2 class="font-black uppercase tracking-tight">{{ $content->section_title }}</h2>
                            <div class="hidden md:block text-right">
                                <span class="text-[10px] font-bold text-arbitra-gray uppercase tracking-widest block">Source</span>
                                <p class="text-xs text-arbitra-emerald font-bold">{{ $content->source ?? 'DTI Region 6' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach((data_get($content->content, 'items', [])) as $item)
                                @php $hasModal = !empty(data_get($item, 'modal_details')); @endphp
                                <div @if($hasModal) data-content="{{ json_encode(data_get($item, 'modal_details')) }}" data-title="{{ data_get($item, 'name') }}" @click="openFromEl($el)" @endif
                                     class="bento-card p-6 md:p-10 flex flex-col justify-between group {{ $hasModal ? 'cursor-pointer' : '' }}">
                                    <div class="flex justify-between items-start mb-6">
                                        <div class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:border-arbitra-emerald/50 group-hover:bg-arbitra-emerald/10 transition-all">
                                            <svg class="w-6 h-6 text-white group-hover:text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        @if($hasModal)
                                        <span class="text-[9px] font-black uppercase text-arbitra-emerald/40 group-hover:text-arbitra-emerald">Details</span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-black text-white uppercase tracking-tight mb-4 group-hover:text-arbitra-emerald transition-colors">{{ data_get($item, 'name') }}</h3>
                                    <p class="text-sm text-arbitra-gray leading-relaxed">{{ data_get($item, 'details', '') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif($content->type === 'list')
                    <section id="{{ $sectionId }}" class="scroll-mt-32 pb-20 mb-20 border-b border-white/5">
                        <div class="section-header border-b border-white/5 pb-4 mb-10">
                            <h2 class="font-black uppercase tracking-tight">{{ $content->section_title }}</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach((data_get($content->content, 'items', [])) as $item)
                                <div class="bento-card p-6 flex items-center gap-4 group">
                                    <div class="w-2 h-2 rounded-full bg-arbitra-emerald group-hover:scale-150 transition-transform"></div>
                                    <span class="text-sm font-bold text-white/90 uppercase tracking-wider">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif($content->type === 'cta')
                    <section id="{{ $sectionId }}" class="scroll-mt-32 mb-20">
                        <div class="bento-card p-12 text-center bg-gradient-to-br from-arbitra-emerald/10 to-transparent border-arbitra-emerald/20">
                            <h3 class="text-4xl font-black uppercase italic mb-6">{{ data_get($content->content, 'title', 'Ready to Invest?') }}</h3>
                            <p class="text-lg text-arbitra-gray max-w-xl mx-auto mb-8">{{ data_get($content->content, 'description', '') }}</p>
                            <button @click="contactOpen = true" class="bg-arbitra-emerald text-arbitra-black px-10 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 transition-all">Get in Touch</button>
                        </div>
                    </section>
                @endif
            @endforeach

            <!-- STAGE 5: ACTION (CLOSING CTA) -->
            @php $cta = $contents->where('type', 'cta')->first(); @endphp
            @if($cta)
            <section id="action" class="py-20">
                <div class="bento-card p-8 md:p-20 text-center bg-gradient-to-br from-arbitra-emerald/10 to-transparent border-arbitra-emerald/20">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-6 md:mb-8 tracking-tighter uppercase italic">
                        {!! nl2br(e(data_get($cta->content, 'title'))) !!}
                    </h2>
                    <p class="text-base md:text-xl text-arbitra-gray max-w-2xl mx-auto mb-8 md:mb-12 font-medium">
                        {{ data_get($cta->content, 'description') }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6">
                        <button @click="contactOpen = true; contactSuccess = false" class="w-full sm:w-auto bg-arbitra-emerald text-arbitra-black px-8 md:px-12 py-4 md:py-5 rounded-full font-black text-sm md:text-lg uppercase tracking-widest hover:scale-105 transition shadow-[0_0_50px_rgba(16,185,129,0.3)]">
                            Contact DTI Region 6
                        </button>
                        <a href="/download-profile/{{ rawurlencode($selectedYear) }}" class="w-full sm:w-auto text-center bg-white/5 text-white border border-white/10 px-8 md:px-12 py-4 md:py-5 rounded-full font-black text-sm md:text-lg uppercase tracking-widest hover:bg-white/10 transition inline-block">
                            Download Profile PDF
                        </a>
                    </div>
                </div>
            </section>
            @endif

        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-arbitra-black border-t border-white/5 pt-12 md:pt-20 pb-10 px-4 md:px-8">
        <div class="max-w-[1240px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('dti-logo.png') }}" class="h-10 w-auto" alt="DTI Logo">
                        <div class="h-8 w-px bg-white/10"></div>
                        <span class="text-arbitra-emerald font-black tracking-tighter text-xl italic uppercase">Region 6</span>
                    </div>
                    <p class="text-arbitra-gray text-sm leading-relaxed max-w-xs">
                        Empowering Western Visayas through strategic investments, economic innovation, and global collaboration.
                    </p>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="text-white font-black text-xs uppercase tracking-[0.2em] mb-8">Navigation</h4>
                    <ul class="space-y-4">
                        @foreach(collect($sidebarNav)->take(5) as $nav)
                            <li><a href="#{{ $nav['id'] }}" class="text-arbitra-gray hover:text-arbitra-emerald transition-colors text-sm font-medium uppercase tracking-widest">{{ $nav['name'] }}</a></li>
                        @endforeach
                        <li><a href="{{ route('download.pdf', ['year' => $selectedYear]) }}" class="text-arbitra-gray hover:text-arbitra-emerald transition-colors text-sm font-medium uppercase tracking-widest">Download PDF</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-black text-xs uppercase tracking-[0.2em] mb-8">Contact Us</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-arbitra-emerald mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-arbitra-gray text-sm leading-snug">DTI Building, corner J.M. Basa-Gen. M. Peralta Streets, Iloilo City, 5000 Iloilo</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-arbitra-emerald shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:r06@dti.gov.ph" class="text-arbitra-gray hover:text-white transition-colors text-sm font-medium">r06@dti.gov.ph</a>
                        </li>
                    </ul>
                </div>

                <!-- Connect -->
                <div id="action">
                    <h4 class="text-white font-black text-xs uppercase tracking-[0.2em] mb-8">Ready to Invest?</h4>
                    <p class="text-arbitra-gray text-sm mb-6">Connect with our team to explore opportunities in Western Visayas.</p>
                    <button @click="contactOpen = true; contactSuccess = false" class="inline-flex items-center gap-2 bg-arbitra-emerald/10 text-arbitra-emerald border border-arbitra-emerald/20 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:bg-arbitra-emerald hover:text-arbitra-black transition-all">
                        START INQUIRY
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-arbitra-gray text-[10px] font-bold uppercase tracking-widest">
                    &copy; {{ date('Y') }} DTI REGION 6. ALL RIGHTS RESERVED.
                </p>
                <div class="flex gap-8">
                    <button @click="policyOpen = true" class="text-arbitra-gray hover:text-white text-[10px] font-bold uppercase tracking-widest transition-colors">Privacy Policy</button>
                    <button @click="termsOpen = true" class="text-arbitra-gray hover:text-white text-[10px] font-bold uppercase tracking-widest transition-colors">Terms of Use</button>
                </div>
            </div>
        </div>
    </footer>

    <!-- Power Search Modal -->
    <div x-show="searchOpen" 
         x-cloak
         class="search-modal-backdrop"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0" @click="searchOpen = false"></div>
        
        <div class="search-container relative"
             x-transition:enter="transition ease-out duration-300 transform scale-95"
             x-transition:enter-end="scale-100"
             x-transition:leave="transition ease-in duration-200 transform scale-95">
            
            <div class="search-input-wrapper">
                <svg class="w-6 h-6 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" 
                       x-model="searchQuery" 
                       x-ref="searchInput"
                       class="search-input" 
                       placeholder="Search sections, stats, cards..."
                       @keydown.escape="searchOpen = false">
                <button @click="searchQuery = ''" x-show="searchQuery" class="text-arbitra-gray hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="search-results custom-scrollbar">
                <template x-if="searchResults.length === 0 && searchQuery.length > 0">
                    <div class="p-8 text-center text-arbitra-gray">
                        No results found for "<span class="text-white" x-text="searchQuery"></span>"
                    </div>
                </template>

                <template x-if="searchQuery.length === 0">
                    <div class="p-8 text-center text-arbitra-gray">
                        Type something to start searching...
                    </div>
                </template>

                <template x-for="(result, index) in searchResults" :key="index">
                    <div>
                        <!-- Category Header (Show if first of its type) -->
                        <template x-if="index === 0 || searchResults[index-1].type !== result.type">
                            <div class="search-category" x-text="result.type"></div>
                        </template>

                        <div class="search-result-item" 
                             :class="{ 'selected': selectedIndex === index }"
                             @click="navigateToResult(result)"
                             @mouseenter="selectedIndex = index">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="result.icon"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold truncate" x-text="result.title"></div>
                                <template x-if="result.subtitle">
                                    <div class="text-xs opacity-50 truncate" x-text="result.subtitle"></div>
                                </template>
                            </div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-30">Jump to</div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="search-shortcut-hint">
                <div class="flex items-center gap-1.5">
                    <span class="kbd">↑↓</span> to navigate
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="kbd">Enter</span> to select
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="kbd">Esc</span> to close
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Modal -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-arbitra-black/98 backdrop-blur-3xl" @click="modalOpen = false"></div>
        
        <div class="relative bg-arbitra-dark max-w-2xl w-full rounded-t-[2rem] md:rounded-[2.5rem] border border-white/10 shadow-[0_30px_100px_rgba(0,0,0,0.8)] overflow-hidden max-h-[85vh] flex flex-col"
             x-transition:enter="transition ease-out duration-500 transform scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-300 transform scale-95 opacity-0">
            
            <!-- Fixed Header -->
            <div class="shrink-0 p-6 pb-0 md:p-16 md:pb-0">
                <button @click="modalOpen = false" class="absolute top-5 right-5 md:top-10 md:right-10 text-arbitra-gray hover:text-white transition-all transform hover:rotate-90 z-20">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h3 class="text-xl md:text-3xl font-extrabold text-white tracking-tighter mb-6 md:mb-12 uppercase italic pr-8" x-text="modalTitle"></h3>
            </div>
            
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto px-6 md:px-16 custom-scrollbar">
                <div class="space-y-10 pb-4">
                    <template x-if="Array.isArray(modalContent)">
                        <template x-for="(block, index) in modalContent" :key="index">
                            <div class="space-y-4">
                                <!-- Map Block rendering -->
                                <template x-if="block.type === 'map'">
                                    <div class="w-full h-96 rounded-2xl overflow-hidden border border-white/10 relative z-0 mb-8" x-init="$nextTick(() => { if(window.renderModalMap) window.renderModalMap('leaflet-map-' + index, block.data) })">
                                        <div :id="'leaflet-map-' + index" class="w-full h-full bg-arbitra-dark"></div>
                                    </div>
                                </template>

                                <!-- Border Card Block rendering -->
                                <template x-if="block.type === 'border_card'">
                                    <div class="p-8 rounded-3xl bg-white/5 border border-white/10 hover:border-arbitra-emerald/30 transition-all group">
                                        <!-- Title -->
                                        <template x-if="block.data.title">
                                            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-arbitra-emerald mb-6 pb-4 border-b border-white/5" x-text="block.data.title"></h4>
                                        </template>
                                        
                                        <!-- Bullet Points -->
                                        <template x-if="block.data.items && block.data.items.length > 0">
                                            <div class="space-y-4">
                                                <template x-for="(item, itemIdx) in block.data.items" :key="itemIdx">
                                                    <div class="flex items-start gap-4">
                                                        <div class="mt-2 w-1.5 h-1.5 rounded-full bg-arbitra-emerald/50 shrink-0 group-hover:bg-arbitra-emerald transition-colors"></div>
                                                        <p class="text-base font-medium text-white/80 leading-relaxed" x-text="item"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                <!-- Text Card Block rendering -->
                                <template x-if="block.type === 'text_card'">
                                    <div class="prose prose-invert prose-emerald max-w-none">
                                        <p class="text-lg text-white/80 font-medium leading-relaxed whitespace-pre-line" x-html="block.data.text"></p>
                                    </div>
                                </template>

                                <!-- Fallback for legacy blocks that weren't migrated properly (optional, safety net) -->
                                <template x-if="!block.type && typeof block === 'object'">
                                    <div class="bg-red-500/10 border border-red-500/20 p-4 rounded-xl">
                                        <span class="text-xs font-bold text-red-500 uppercase tracking-widest block mb-2">Notice:</span>
                                        <p class="text-sm text-red-500/80">This section is using a legacy data structure. Please edit and re-save it in the dashboard to migrate it to the new block format.</p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>

                    <!-- Legacy Fallback for dictionaries -->
                    <template x-if="!Array.isArray(modalContent)">
                         <div class="space-y-12">
                            <div class="bg-arbitra-emerald/10 border border-arbitra-emerald/20 p-6 rounded-2xl">
                                 <h4 class="text-sm font-black text-arbitra-emerald uppercase tracking-widest mb-2">Legacy Content Mode</h4>
                                 <p class="text-sm text-white/70">This popup is still using the legacy format. Edit it in the dashboard to convert it to the new block system.</p>
                            </div>
                            
                            <!-- Leaflet Map Container (Fixed Position) -->
                            <template x-if="modalContent['Map Points']">
                                <div class="w-full h-96 rounded-2xl overflow-hidden border border-white/10 relative z-0 mb-8">
                                    <div id="leaflet-map" class="w-full h-full bg-arbitra-dark"></div>
                                </div>
                            </template>

                            <template x-for="(value, key) in modalContent" :key="key">
                                <div class="space-y-6">
                                    <!-- Hide Map Points Key in Loop -->
                                    <template x-if="key !== 'Map Points'">
                                        <h4 class="text-sm font-bold uppercase tracking-[0.3em] text-arbitra-emerald sticky top-0 bg-arbitra-dark z-10 py-2" x-text="key"></h4>
                                    </template>
                                    
                                    <!-- Grid for 'Points' (Why Invest) -->
                                    <template x-if="key === 'Points' && Array.isArray(value)">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <template x-for="item in value">
                                                <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:border-arbitra-emerald/50 hover:bg-white/10 transition-all group flex items-center justify-center text-center">
                                                    <span class="text-lg font-bold text-white group-hover:text-arbitra-emerald transition-colors" x-text="item"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Standard Key-Value List (Stats) -->
                                    <template x-if="key !== 'Points' && key !== 'Map Points' && typeof value === 'object'">
                                        <div class="grid grid-cols-1 gap-4">
                                            <template x-for="(v, k) in value" :key="k">
                                                <div class="flex items-center justify-between p-6 rounded-2xl bg-black/40 border border-white/5">
                                                    <span class="text-sm font-bold text-arbitra-gray uppercase tracking-widest" x-text="k"></span>
                                                    <span class="text-lg font-bold text-white" x-text="v"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <!-- Fallback Text -->
                                    <template x-if="typeof value !== 'object' && key !== 'Map Points'">
                                        <p class="text-lg text-white/80 font-medium leading-relaxed" x-text="value"></p>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Fixed Footer -->
            <div class="shrink-0 p-6 md:px-16 md:py-8 border-t border-white/5 flex justify-end bg-arbitra-dark">
                <button @click="modalOpen = false" class="bg-arbitra-emerald text-arbitra-black font-extrabold px-10 py-3 rounded-full hover:brightness-110 transition uppercase text-sm tracking-widest">
                    GO BACK
                </button>
            </div>
        </div>
    </div>

    <script>
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("scroll-progress").style.width = scrolled + "%";
        };
    </script>

    <!-- Connect with Us Modal -->
    <div x-show="contactOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-arbitra-black/98 backdrop-blur-3xl" @click="contactOpen = false"></div>
        
        <div class="relative bg-arbitra-dark max-w-lg w-full p-8 md:p-12 rounded-[2.5rem] border border-white/10 shadow-[0_30px_100px_rgba(0,0,0,0.8)] max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-500 transform scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-300 transform scale-95 opacity-0">
            
            <button @click="contactOpen = false" class="absolute top-8 right-8 text-arbitra-gray hover:text-white transition-all transform hover:rotate-90">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <template x-if="!contactSuccess">
                <div>
                    <h3 class="text-3xl font-extrabold text-white tracking-tighter mb-4 uppercase italic">Connect with Us</h3>
                    <p class="text-arbitra-gray mb-8 text-sm">Fill out the form below and the DTI Region 6 team will get back to you shortly.</p>
                    
                    <form @submit.prevent="submitInquiry" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-arbitra-emerald uppercase tracking-[0.2em] mb-2">Full Name</label>
                            <input type="text" x-model="contactForm.name" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arbitra-emerald outline-none transition uppercase text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-arbitra-emerald uppercase tracking-[0.2em] mb-2">Email Address</label>
                            <input type="email" x-model="contactForm.email" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arbitra-emerald outline-none transition text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-arbitra-emerald uppercase tracking-[0.2em] mb-2">Contact Number</label>
                            <input type="text" x-model="contactForm.contact" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arbitra-emerald outline-none transition text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-arbitra-emerald uppercase tracking-[0.2em] mb-2">Message</label>
                            <textarea x-model="contactForm.message" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arbitra-emerald outline-none transition text-xs font-bold" placeholder="Please send as a message"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" :disabled="contactLoading" class="w-full bg-arbitra-emerald text-arbitra-black font-black py-4 rounded-xl hover:brightness-110 transition disabled:opacity-50 uppercase tracking-widest text-xs">
                                <span x-show="!contactLoading">SEND INQUIRY</span>
                                <span x-show="contactLoading">SENDING...</span>
                            </button>
                        </div>
                        
                        <p class="text-[10px] text-arbitra-gray text-center leading-relaxed">
                            <span class="font-bold text-white/40">PRIVACY POLICY:</span> By submitting this form, you agree to the collection and processing of your personal information by DTI Region 6 for the purpose of addressing your investment inquiry. Your data will be handled in accordance with the Data Privacy Act of 2012.
                        </p>
                    </form>
                </div>
            </template>

            <template x-if="contactSuccess">
                <div class="text-center py-10">
                    <div class="w-20 h-20 bg-arbitra-emerald/20 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="w-10 h-10 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 uppercase italic">Thank You!</h3>
                    <p class="text-arbitra-gray mb-8">Your inquiry has been sent to DTI Region 6. We will contact you soon via the details provided.</p>
                    <button @click="contactOpen = false" class="bg-white/10 text-white px-8 py-3 rounded-full font-bold hover:bg-white/20 transition uppercase text-xs tracking-widest">Close</button>
                </div>
            </template>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div x-show="policyOpen" 
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 md:p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-arbitra-black/98 backdrop-blur-3xl" @click="policyOpen = false"></div>
        
        <div class="relative bg-arbitra-dark max-w-3xl w-full p-8 md:p-12 lg:p-16 rounded-[2.5rem] border border-white/10 shadow-[0_30px_100px_rgba(0,0,0,0.8)] max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-500 transform scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-300 transform scale-95 opacity-0">
            
            <button @click="policyOpen = false" class="absolute top-10 right-10 text-arbitra-gray hover:text-white transition-all transform hover:rotate-90">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-3xl font-extrabold text-white tracking-tighter mb-8 uppercase italic">Privacy Policy</h3>
            
            <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-6 custom-scrollbar text-arbitra-gray text-sm leading-relaxed">
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">1. Data Collection</h4>
                    <p>We collect personal information such as name, email address, and contact number when you voluntarily submit an inquiry through our platform. This information is essential for us to respond to your requests and provide investment assistance.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">2. Purpose of Processing</h4>
                    <p>Your data is processed exclusively for addressing investment inquiries, providing economic data updates, and facilitating communication between potential investors and DTI Region 6. We do not use your data for unrelated marketing purposes.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">3. Data Security</h4>
                    <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction. Your data is stored on secure servers with restricted access.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">4. Disclosure to Third Parties</h4>
                    <p>We do not sell, trade, or otherwise transfer your personal information to outside parties. This does not include trusted third parties who assist us in operating our website or conducting our business, so long as those parties agree to keep this information confidential.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">5. Compliance with Data Privacy Act</h4>
                    <p>Our data collection and processing activities are in strict compliance with the Data Privacy Act of 2012 (Republic Act No. 10173) of the Philippines. You have the right to access, correct, or request the deletion of your personal data at any time.</p>
                </section>
            </div>
            
            <div class="mt-10 pt-8 border-t border-white/5 flex justify-end">
                <button @click="policyOpen = false" class="bg-arbitra-emerald text-arbitra-black font-extrabold px-10 py-3 rounded-full hover:brightness-110 transition uppercase text-xs tracking-widest">
                    CLOSE
                </button>
            </div>
        </div>
    </div>

    <!-- Terms of Use Modal -->
    <div x-show="termsOpen" 
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 md:p-8"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="absolute inset-0 bg-arbitra-black/98 backdrop-blur-3xl" @click="termsOpen = false"></div>
        
        <div class="relative bg-arbitra-dark max-w-3xl w-full p-8 md:p-12 lg:p-16 rounded-[2.5rem] border border-white/10 shadow-[0_30px_100px_rgba(0,0,0,0.8)] max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-500 transform scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-300 transform scale-95 opacity-0">
            
            <button @click="termsOpen = false" class="absolute top-10 right-10 text-arbitra-gray hover:text-white transition-all transform hover:rotate-90">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-3xl font-extrabold text-white tracking-tighter mb-8 uppercase italic">Terms of Use</h3>
            
            <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-6 custom-scrollbar text-arbitra-gray text-sm leading-relaxed">
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">1. Acceptance of Terms</h4>
                    <p>By accessing and using this Western Visayas Investment Profile portal, you acknowledge that you have read, understood, and agree to be bound by these terms and conditions.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">2. Use of Information</h4>
                    <p>The information provided on this portal is for general informational and investment promotion purposes. While we strive for accuracy, DTI Region 6 does not warrant the completeness or reliability of the data presented.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">3. Intellectual Property</h4>
                    <p>All content, including text, graphics, logos, and data visualizations, is the property of DTI Region 6 or its content suppliers and is protected by intellectual property laws. Unauthorized reproduction or distribution is prohibited.</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">4. User Conduct</h4>
                    <p>Users agree to use the portal only for lawful purposes. You are prohibited from attempting to breach the security of the portal, submitting false information, or using the contact forms for unsolicited commercial messages (spam).</p>
                </section>
                <section>
                    <h4 class="text-white font-bold uppercase tracking-widest mb-2">5. Limitation of Liability</h4>
                    <p>DTI Region 6 shall not be liable for any direct, indirect, incidental, or consequential damages arising out of the use or inability to use the portal or any information provided herein.</p>
                </section>
            </div>
            
            <div class="mt-10 pt-8 border-t border-white/5 flex justify-end">
                <button @click="termsOpen = false" class="bg-arbitra-emerald text-arbitra-black font-extrabold px-10 py-3 rounded-full hover:brightness-110 transition uppercase text-xs tracking-widest">
                    CLOSE
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav" x-data="{ activeTab: 'hero' }"
         @scroll.window.throttle.100ms="
            const sections = ['hero', 'economy', 'drivers', 'action'];
            let active = 'hero';
            for (const id of sections) {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top <= 200) active = id;
            }
            activeTab = active;
         ">
        <a href="#hero" :class="{ 'active': activeTab === 'hero' }">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Home</span>
        </a>
        <a href="#economy" :class="{ 'active': activeTab === 'economy' }">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span>Stats</span>
        </a>
        <a href="#drivers" :class="{ 'active': activeTab === 'drivers' }">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span>Drivers</span>
        </a>
        <a href="/download-profile/{{ rawurlencode($selectedYear) }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>PDF</span>
        </a>
        <button @click="contactOpen = true; contactSuccess = false">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>Connect</span>
        </button>
    </div>

    <!-- Desktop Sidebar Navigation -->
    <div class="sidebar-container hidden md:flex" :class="{ 'is-visible': desktopSidebarOpen }"
         x-data="{ activeTooltip: null, activeSection: 'hero' }"
         @scroll.window.throttle.100ms="
            const sections = ['hero', 'profile', 'economy', 'drivers', 'infrastructure', 'logistics', 'industries', 'action'];
            for (const id of sections) {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top <= 200) activeSection = id;
            }
         ">
        @foreach($sidebarNav as $nav)
            <a href="#{{ $nav['id'] }}" 
               class="sidebar-btn group" 
               :class="{ 'active': activeSection === '{{ $nav['id'] }}' }">
                <div class="sidebar-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"></path>
                    </svg>
                </div>
                <span class="sidebar-label">{{ $nav['name'] }}</span>
            </a>
        @endforeach
    </div>

    <!-- Mobile Retractable Sidebar -->
    <div class="sidebar-overlay lg:hidden" :class="{ 'open': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>
    <div class="mobile-sidebar-drawer lg:hidden p-8 flex flex-col gap-6" :class="{ 'open': mobileSidebarOpen }">
        <h3 class="text-2xl font-black text-white uppercase italic mb-4 tracking-tighter">Navigate</h3>
        <div class="flex flex-col gap-2">
            @foreach($sidebarNav as $nav)
                <a href="#{{ $nav['id'] }}" 
                   @click="mobileSidebarOpen = false"
                   class="flex items-center gap-4 p-4 rounded-2xl border border-white/5 transition-all text-arbitra-gray hover:text-white hover:bg-white/5 hover:border-arbitra-emerald/30 group">
                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-arbitra-emerald group-hover:text-arbitra-black transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $nav['icon'] }}"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest">{{ $nav['name'] }}</span>
                </a>
            @endforeach
            <a href="/download-profile/{{ rawurlencode($selectedYear) }}" 
               class="flex items-center gap-4 p-4 rounded-2xl border border-white/5 bg-arbitra-emerald/5 text-arbitra-emerald hover:bg-arbitra-emerald hover:text-arbitra-black transition-all mt-4 group">
                <div class="w-10 h-10 rounded-full bg-arbitra-emerald/10 flex items-center justify-center group-hover:bg-white/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-xs font-black uppercase tracking-widest">Download Full PDF</span>
            </a>
        </div>
    </div>

    <script>
        if (window.innerWidth < 768) {
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.bento-card').forEach(function(card) {
                    card.classList.add('mobile-animate');
                });
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -10% 0px' });
                document.querySelectorAll('.mobile-animate').forEach(function(el) {
                    observer.observe(el);
                });
            });
        }
    </script>

    @include('components.ai-chat')
</body>
</html>
