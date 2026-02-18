<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPREADSHEET - Admin Panel</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'], },
                    colors: {
                        arbitra: {
                            black: '#000000',
                            dark: '#0A0A0A',
                            emerald: '#10b981',
                            gray: '#888888',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { background-color: #000000; color: #FFFFFF; font-size: 13px; }
        [x-cloak] { display: none !important; }
        .grid-input {
            background: transparent;
            border: none;
            width: 100%;
            height: 100%;
            padding: 8px 12px;
            color: white;
            outline: none;
            transition: all 0.2s;
        }
        .grid-input:focus {
            background: rgba(16, 185, 129, 0.1);
            box-shadow: inset 0 0 0 2px #10b981;
        }
        .grid-cell {
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0;
            vertical-align: top;
        }
        .grid-header {
            background: #111;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            color: #888;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
</head>
<body x-data="spreadsheetApp()" class="antialiased font-sans">
    <nav class="fixed top-0 w-full z-40 bg-arbitra-black border-b border-white/5 py-4">
        <div class="max-w-full mx-auto px-8 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="/admin?year={{ $selectedYear }}" class="text-arbitra-gray hover:text-white transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="font-bold text-xs">Back to Dashboard</span>
                </a>
                <div class="h-4 w-px bg-white/10"></div>
                <h1 class="text-sm font-black uppercase tracking-tight italic">Spreadsheet Mode <span class="text-arbitra-emerald ml-2">{{ $selectedYear }}</span></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="/admin/export?year={{ $selectedYear }}" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg text-xs font-bold hover:bg-white/10 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export to Excel
                </a>
                <button @click="saveAll()" :disabled="saving" class="bg-arbitra-emerald text-arbitra-black px-6 py-2 rounded-lg text-xs font-black transition-all flex items-center gap-2 disabled:opacity-50">
                    <template x-if="saving">
                        <svg class="animate-spin h-3 w-3 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </template>
                    <span x-text="saving ? 'SAVING...' : 'SAVE ALL CHANGES'"></span>
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-white/5 border border-white/10 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-white/10 transition-all">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="pt-24 min-h-screen">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="grid-header w-12 text-center">ID</th>
                    <th class="grid-header w-24">Order</th>
                    <th class="grid-header w-64">Section Title</th>
                    <th class="grid-header w-32">Type</th>
                    <th class="grid-header">JSON Content (Click to Edit)</th>
                    <th class="grid-header w-64">Source</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contents as $content)
                    <tr x-data="{ 
                        id: {{ $content->id }},
                        page: '{{ $content->page_number }}',
                        title: '{{ str_replace("'", "\\'", $content->section_title) }}',
                        type: '{{ $content->type }}',
                        json: @js(json_encode($content->content, JSON_PRETTY_PRINT)),
                        source: '{{ str_replace("'", "\\'", $content->source) }}',
                        dirty: false
                    }" @keyup="dirty = true">
                        <td class="grid-cell text-center py-3 text-arbitra-gray font-mono">{{ $content->id }}</td>
                        <td class="grid-cell"><input type="number" x-model="page" @change="handleChange(id, 'page_number', page)" class="grid-input text-center"></td>
                        <td class="grid-cell"><input type="text" x-model="title" @change="handleChange(id, 'section_title', title)" class="grid-input"></td>
                        <td class="grid-cell py-3 px-3 text-[10px] font-black uppercase text-arbitra-emerald/60 bg-white/[0.02]">
                            {{ $content->type }}
                        </td>
                        <td class="grid-cell">
                            <textarea x-model="json" @change="handleJsonChange(id, json)" class="grid-input font-mono text-[10px] leading-relaxed min-h-[100px] py-4" style="resize: vertical;"></textarea>
                        </td>
                        <td class="grid-cell"><input type="text" x-model="source" @change="handleChange(id, 'source', source)" class="grid-input"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($contents->isEmpty())
            <div class="p-20 text-center">
                <p class="text-arbitra-gray text-lg font-medium mb-4">No data available for this year range.</p>
                <a href="/admin?year={{ $selectedYear }}" class="text-arbitra-emerald hover:underline font-bold">Return to dashboard to add content</a>
            </div>
        @endif
    </div>

    <script>
        function spreadsheetApp() {
            return {
                saving: false,
                updates: {},

                handleChange(id, field, value) {
                    if (!this.updates[id]) this.updates[id] = {};
                    this.updates[id][field] = value;
                },

                handleJsonChange(id, value) {
                    try {
                        const parsed = JSON.parse(value);
                        if (!this.updates[id]) this.updates[id] = {};
                        this.updates[id]['content'] = parsed;
                    } catch (e) {
                        alert('Invalid JSON in row #' + id);
                    }
                },

                async saveAll() {
                    const ids = Object.keys(this.updates);
                    if (ids.length === 0) {
                        alert('No changes to save.');
                        return;
                    }

                    this.saving = true;
                    
                    try {
                        for (const id of ids) {
                            const response = await fetch(`/admin/content/${id}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(this.updates[id])
                            });
                            
                            if (!response.ok) throw new Error('Failed to save row #' + id);
                        }
                        
                        this.updates = {};
                        alert('All changes saved successfully!');
                        window.location.reload();
                    } catch (e) {
                        alert('Error: ' + e.message);
                    } finally {
                        this.saving = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
