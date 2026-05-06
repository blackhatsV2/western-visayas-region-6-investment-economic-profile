<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JSON EDITOR - Western Visayas Economic Profile</title>
    <link rel="icon" type="image/png" href="{{ asset('dti-logo.png') }}">
    
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
        body { background-color: #000000; color: #FFFFFF; font-size: 14px; }
        [x-cloak] { display: none !important; }
        .bento-card {
            background: rgba(28, 28, 30, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .json-textarea {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 2rem;
            color: #10b981;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', monospace;
            font-size: 13px;
            line-height: 1.6;
            width: 100%;
            height: 70vh;
            outline: none;
            transition: border-color 0.3s;
        }
        .json-textarea:focus {
            border-color: #10b981;
        }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.3); }
    </style>
</head>
<body x-data="jsonApp()" class="antialiased font-sans">
    <nav class="fixed top-0 w-full z-40 bg-arbitra-black/80 backdrop-blur-xl border-b border-white/5 py-4">
        <div class="max-w-[1240px] mx-auto px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/admin?year={{ $selectedYear }}" class="bg-white/5 text-white px-3 py-1 rounded-md font-black text-[10px] uppercase hover:bg-white/10 transition-all flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    BACK
                </a>
                <span class="bg-arbitra-emerald text-arbitra-black px-3 py-1 rounded-md font-black text-[10px] uppercase">JSON MASTER EDIT</span>
                <h1 class="text-sm font-black tracking-tight uppercase italic">{{ $selectedYear }}</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex bg-white/5 rounded-lg p-1 mr-4">
                    <a href="/admin?year={{ $selectedYear }}" class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all text-arbitra-gray hover:text-white">Visual Edit</a>
                    <a href="/admin/grid?year={{ $selectedYear }}" class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all text-arbitra-gray hover:text-white">Spreadsheet</a>
                    <a href="/admin/json?year={{ $selectedYear }}" class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all bg-arbitra-emerald text-arbitra-black">JSON Master</a>
                </div>

                <button @click="formatJson()" class="text-[10px] font-black uppercase text-arbitra-gray hover:text-white border border-white/10 px-4 py-2 rounded-full transition-all">Format JSON</button>
                <button @click="copyToClipboard()" class="text-[10px] font-black uppercase text-arbitra-gray hover:text-white border border-white/10 px-4 py-2 rounded-full transition-all">Copy to AI</button>
                <div class="h-6 w-px bg-white/10 mx-2"></div>
                <button @click="saveChanges()" :disabled="saving" class="bg-arbitra-emerald text-arbitra-black px-8 py-2 rounded-full text-xs font-black uppercase tracking-widest hover:scale-105 transition-all disabled:opacity-50 shadow-lg shadow-arbitra-emerald/20">
                    <span x-text="saving ? 'SAVING...' : 'SAVE CHANGES'"></span>
                </button>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-20 px-8">
        <div class="max-w-[1240px] mx-auto">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black uppercase italic tracking-tighter">Full Data Integration</h2>
                    <p class="text-arbitra-gray text-sm mt-2">Edit the entire database for this year range as a single JSON object. Perfect for AI tools and migrations.</p>
                </div>
                <div class="flex bg-white/5 rounded-lg p-1">
                    @foreach($years as $year)
                        <a href="?year={{ $year }}" 
                           class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all {{ $selectedYear == $year ? 'bg-arbitra-emerald text-arbitra-black' : 'text-arbitra-gray hover:text-white' }}">
                            {{ $year }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bento-card p-6">
                <textarea 
                    x-model="rawJson" 
                    class="json-textarea" 
                    spellcheck="false"
                    placeholder="Paste your JSON here..."
                    @keydown.tab.prevent="insertTab($event)"
                ></textarea>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bento-card p-8 border-arbitra-emerald/10 bg-arbitra-emerald/[0.02]">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-arbitra-emerald/20 flex items-center justify-center text-arbitra-emerald font-black text-xs">AI</span>
                        <h4 class="text-xs font-black uppercase text-arbitra-emerald">Pro Tip: AI Integration</h4>
                    </div>
                    <p class="text-xs text-arbitra-gray leading-relaxed">Copy this entire JSON and paste it into ChatGPT, Claude, or Gemini. Ask it to <strong>"update the data based on [your source]"</strong> or <strong>"generate new sections for [topic]"</strong>. Paste the result back here and save. This is the fastest way to populate your site with complex data.</p>
                </div>
                <div class="bento-card p-8 border-white/5 bg-white/[0.02]">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white font-black text-xs">{}</span>
                        <h4 class="text-xs font-black uppercase text-white">Data Structure</h4>
                    </div>
                    <p class="text-xs text-arbitra-gray leading-relaxed">This is an array of content objects. Keep the <code>id</code> for updates, or remove it to create new entries. The <code>type</code> field determines how the public site renders the section (e.g. <code>hero</code>, <code>grid</code>, <code>chart</code>, <code>stats_grid</code>).</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function jsonApp() {
            return {
                rawJson: @js(json_encode($jsonData, JSON_PRETTY_PRINT)),
                saving: false,
                selectedYear: '{{ $selectedYear }}',

                formatJson() {
                    try {
                        const obj = JSON.parse(this.rawJson);
                        this.rawJson = JSON.stringify(obj, null, 4);
                    } catch (e) {
                        alert('Invalid JSON: ' + e.message);
                    }
                },

                copyToClipboard() {
                    navigator.clipboard.writeText(this.rawJson).then(() => {
                        alert('JSON copied to clipboard! You can now paste it into your favorite AI tool.');
                    });
                },

                insertTab(e) {
                    const start = e.target.selectionStart;
                    const end = e.target.selectionEnd;
                    this.rawJson = this.rawJson.substring(0, start) + "    " + this.rawJson.substring(end);
                    this.$nextTick(() => {
                        e.target.selectionStart = e.target.selectionEnd = start + 4;
                    });
                },

                async saveChanges() {
                    try {
                        // Validate before sending
                        JSON.parse(this.rawJson);
                    } catch (e) {
                        alert('Cannot save invalid JSON: ' + e.message);
                        return;
                    }

                    if (!confirm('This will update all data for ' + this.selectedYear + '. Are you sure?')) return;

                    this.saving = true;
                    try {
                        const response = await fetch('/admin/json', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                json_data: this.rawJson,
                                year: this.selectedYear
                            })
                        });

                        const result = await response.json();
                        if (result.success) {
                            alert('Data updated successfully!');
                            window.location.reload();
                        } else {
                            alert('Error: ' + (result.message || 'Failed to update data.'));
                        }
                    } catch (e) {
                        alert('System Error: ' + e.message);
                    } finally {
                        this.saving = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
