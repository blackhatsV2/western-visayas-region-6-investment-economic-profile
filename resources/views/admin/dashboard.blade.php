<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN - Western Visayas Economic Profile</title>
    <link rel="icon" type="image/png" href="{{ asset('dti-logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                    },
                    borderRadius: { 'bento': '2rem', }
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
            position: relative;
            overflow: hidden;
        }
        .bento-card:hover { border-color: rgba(16, 185, 129, 0.6); }
        .emerald-text { color: #10b981; }
        .nav-link { font-size: 12px; font-weight: 600; color: #888888; transition: all 0.2s ease; }
        .nav-link:hover, .nav-link.active { color: #FFFFFF; }
        
        /* Admin Specific */
        .admin-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .admin-modal {
            background: #0A0A0A;
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 2rem;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        .admin-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: white;
            width: 100%;
        }
        .admin-input option {
            background: #0A0A0A;
            color: white;
        }
        .admin-label {
            font-size: 10px;
            font-weight: 800;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body x-data="adminApp()" class="antialiased font-sans">
    <nav class="fixed top-0 w-full z-40 bg-arbitra-black/80 backdrop-blur-xl border-b border-white/5 py-4">
        <div class="max-w-[1240px] mx-auto px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="bg-arbitra-emerald text-arbitra-black px-3 py-1 rounded-md font-black text-[10px] uppercase">ADMIN PANEL</span>
                <h1 class="text-sm font-black tracking-tight uppercase">Dashboard Control</h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex bg-white/5 rounded-lg p-1 mr-4">
                    <a href="/admin?year={{ $selectedYear }}" class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all bg-arbitra-emerald text-arbitra-black">Visual Edit</a>
                    <a href="/admin/grid?year={{ $selectedYear }}" class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase transition-all text-arbitra-gray hover:text-white">Spreadsheet</a>
                </div>

                <a href="/admin/export?year={{ $selectedYear }}" class="flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-2 rounded-full text-xs font-bold hover:bg-white/10 transition-all mr-4">
                    <svg class="w-3 h-3 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    EXCEL
                </a>

                @foreach($years as $year)
                    <a href="?year={{ $year }}" 
                       class="px-3 py-1 rounded-full text-[10px] font-bold transition-all {{ $selectedYear == $year ? 'bg-arbitra-emerald text-arbitra-black' : 'text-arbitra-gray hover:text-white' }}">
                        {{ $year }}
                    </a>
                @endforeach
                <button @click="showAddYear = true" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-arbitra-emerald hover:text-arbitra-black transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                </button>
                
                <div class="h-6 w-px bg-white/10 mx-2"></div>
                
                <button @click="confirmDeleteYear('{{ $selectedYear }}')" class="group flex items-center gap-2 text-arbitra-gray hover:text-red-500 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest hidden group-hover:block">Delete Year</span>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <a href="/" class="text-xs font-bold text-arbitra-emerald hover:underline">View Public Site</a>
                <button @click="showProfileEdit = true" class="text-xs font-bold text-arbitra-gray hover:text-white transition-all uppercase tracking-widest text-[10px]">Profile</button>
                <form @submit.prevent="confirmLogout($event)" action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-arbitra-gray hover:text-white transition-all uppercase tracking-widest text-[10px]">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="pt-28 pb-20 px-8">
        <div class="max-w-[1240px] mx-auto space-y-16">
            
            @php $hero = $contents->where('type', 'hero')->first(); @endphp
            <div id="section-hero" class="scroll-mt-32">
                @if($hero)
                    <div x-data="{ 
                        editing: false, 
                        techy: false,
                        editingModal: false,
                        id: {{ $hero->id }},
                        form: JSON.parse($el.dataset.form), 
                        title: $el.dataset.title, 
                        source: $el.dataset.source,
                        modalJson: '',
                        modalTabs: [],
                        activeTab: 0,
                        parseModalDetails() {
                            const details = this.form.modal_details || {};
                            this.modalTabs = Object.entries(details).map(([name, value]) => {
                                let type = 'text';
                                let data = value;
                                
                                if (value && typeof value === 'object') {
                                    if (value.Points && Array.isArray(value.Points)) {
                                        type = 'points';
                                        data = [...value.Points];
                                    } else if (value['Map Points'] && Array.isArray(value['Map Points'])) {
                                        type = 'map';
                                        data = value['Map Points'].map(p => ({...p}));
                                    } else if (Array.isArray(value)) {
                                        if (value.length > 0 && typeof value[0] === 'object' && value[0].lat !== undefined) {
                                            type = 'map';
                                            data = value.map(p => ({...p}));
                                        } else {
                                            type = 'points';
                                            data = [...value];
                                        }
                                    } else {
                                        type = 'table';
                                        data = Object.entries(value).map(([k, v]) => ({key: k, value: v}));
                                    }
                                }
                                return { name, type, data };
                            });
                        },
                        syncModalDetails() {
                            const details = {};
                            this.modalTabs.forEach(tab => {
                                if (tab.type === 'points') {
                                    details[tab.name] = { Points: tab.data };
                                } else if (tab.type === 'table') {
                                    const tableObj = {};
                                    tab.data.forEach(row => { if(row.key) tableObj[row.key] = row.value });
                                    details[tab.name] = tableObj;
                                } else if (tab.type === 'map') {
                                    details[tab.name] = { 'Map Points': tab.data };
                                } else {
                                    details[tab.name] = tab.data;
                                }
                            });
                            this.form.modal_details = details;
                            this.modalJson = JSON.stringify(details, null, 4);
                        },
                        addModalTab() {
                            this.modalTabs.push({ name: 'New Section', type: 'points', data: [] });
                            this.activeTab = this.modalTabs.length - 1;
                        },
                        removeModalTab(index) {
                            if(confirm('Remove this entire popup section?')) {
                                this.modalTabs.splice(index, 1);
                                if(this.activeTab >= this.modalTabs.length) this.activeTab = Math.max(0, this.modalTabs.length - 1);
                            }
                        }
                    }"
                    data-form="{{ json_encode($hero->content) }}"
                    data-title="{{ $hero->section_title }}"
                    data-source="{{ $hero->source }}"
                    x-init="
                        modalJson = JSON.stringify(form.modal_details || null, null, 4);
                        parseModalDetails();
                        $watch('form.modal_details', (val) => {
                            modalJson = JSON.stringify(val, null, 4);
                        });
                        $watch('modalTabs', () => syncModalDetails(), { deep: true });
                        $watch('techy', (val) => { if(!val) parseModalDetails() });
                        
                        // Change Tracking: snapshot initial state, then detect real changes
                        _initialFormSnapshots[id] = { form: JSON.stringify(form), title: title, source: source };
                        $watch('form', () => {
                            if (JSON.stringify(form) !== _initialFormSnapshots[id].form) {
                                Alpine.store('admin').setSectionDirty(id, title);
                            }
                        }, { deep: true });
                        $watch('title', (newTitle) => {
                            if (newTitle !== _initialFormSnapshots[id].title) {
                                Alpine.store('admin').setSectionDirty(id, newTitle);
                            }
                        });
                        $watch('source', () => {
                            if (source !== _initialFormSnapshots[id].source) {
                                Alpine.store('admin').setSectionDirty(id, title);
                            }
                        });
                    ">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative">
                        <!-- Hero Content Preview (Match Public Site) -->
                        <div @click="editing = true" class="lg:col-span-2 bento-card p-12 flex flex-col justify-center bg-gradient-to-br from-arbitra-dark to-arbitra-black cursor-pointer group hover:border-arbitra-emerald/60 transition-all relative overflow-hidden">
                            <div class="absolute inset-0 bg-arbitra-emerald/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center gap-3 mb-10">
                                    <span class="px-5 py-1.5 rounded-full bg-arbitra-emerald/10 text-arbitra-emerald font-black text-[10px] uppercase tracking-[0.2em] border border-arbitra-emerald/20">Investment Motivation</span>
                                    <div class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-white/50 group-hover:text-white group-hover:bg-arbitra-emerald/20 transition-all flex items-center gap-2">
                                        <span>CLICK TO EDIT</span>
                                    </div>
                                </div>
                                <h2 class="text-6xl font-black mb-10 leading-[1] tracking-tighter uppercase italic group-hover:text-white transition-colors" x-text="form.title || 'Why Invest in Western Visayas?'"></h2>
                                <p class="text-lg text-arbitra-gray max-w-xl leading-relaxed font-medium group-hover:text-white/80 transition-colors" x-text="form.description"></p>
                            </div>

                            <div class="pt-8 mt-auto border-t border-white/5 relative z-10">
                                <span class="text-[10px] font-bold text-arbitra-gray uppercase tracking-widest block mb-1">Source</span>
                                <p class="text-xs text-arbitra-emerald font-bold" x-text="source"></p>
                            </div>
                        </div>

                        <!-- Highlight Stats -->
                        <div class="flex flex-col gap-6">
                            <template x-for="(stat, index) in form.highlight_stats" :key="index">
                                <div x-data="{ editingStat: false }" class="flex-1 flex flex-col">
                                    <div @click="editingStat = true" class="bento-card flex-1 p-10 flex flex-col justify-between group relative cursor-pointer hover:border-arbitra-emerald/60">
                                        <span class="text-sm font-bold text-arbitra-gray uppercase tracking-widest" x-text="stat.label"></span>
                                        <div class="mt-4">
                                            <h3 class="text-5xl font-black emerald-text tracking-tighter" x-text="stat.value"></h3>
                                            <span class="text-[10px] font-black text-arbitra-gray uppercase tracking-widest mt-2 block opacity-40" x-text="stat.label"></span>
                                        </div>
                                    </div>

                                    <!-- Stat Modal (Un-nested from bento-card) -->
                                    <div x-show="editingStat" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         x-cloak 
                                         class="admin-overlay"
                                         @click.stop="tryClose(id, () => editingStat = false)">
                                        
                                        <div class="admin-modal max-w-lg" @click.stop
                                             x-transition:enter="transition ease-out duration-300 transform"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-200 transform"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95">
                                            
                                            <h3 class="text-xl font-black uppercase text-arbitra-emerald mb-8">Edit Highlight Stat</h3>
                                            
                                            <div class="space-y-6">
                                                <div>
                                                    <label class="admin-label">Stat Label</label>
                                                    <input type="text" x-model="stat.label" class="admin-input mt-2">
                                                </div>
                                                <div>
                                                    <label class="admin-label">Value (e.g. 7.5%)</label>
                                                    <input type="text" x-model="stat.value" class="admin-input mt-2 text-2xl font-black text-arbitra-emerald">
                                                </div>
                                            </div>

                                            <div class="flex gap-4 mt-10">
                                                <button @click.stop="save({{ $hero->id }}, {section_title: title, content: form, source: source}); editingStat = false" class="flex-1 bg-arbitra-emerald text-arbitra-black py-4 rounded-full font-black text-xs uppercase tracking-widest transition-all hover:scale-105">SAVE STAT</button>
                                                <button @click.stop="tryClose(id, () => editingStat = false)" class="px-8 border border-white/10 rounded-full font-bold text-xs">CANCEL</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                        <!-- Enhanced Modal Edit Overlay (Hero Main) -->
                        <div x-show="editing" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             x-cloak 
                             class="admin-overlay" 
                             @click="tryClose(id, () => editing = false)">
                            
                            <div class="admin-modal" @click.stop
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                                
                                <div class="flex justify-between items-center mb-8">
                                    <div>
                                        <h3 class="text-2xl font-black uppercase italic text-arbitra-emerald">Edit Hero Section</h3>
                                        <p class="text-[10px] text-arbitra-gray font-bold uppercase tracking-widest mt-1">MAIN LANDING HEADER</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button @click="techy = !techy" class="text-[10px] font-black uppercase text-white/40 hover:text-white border border-white/10 px-4 py-2 rounded-full transition-all" x-text="techy ? 'Standard Mode' : 'JSON Mode'"></button>
                                         <button @click="tryClose(id, () => editing = false)" class="text-arbitra-gray hover:text-white p-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div x-show="techy" class="space-y-4">
                                    <label class="admin-label">Raw JSON Data</label>
                                    <textarea x-model="JSON.stringify(form, null, 2)" @change="form = safeParse($event.target.value, form)" class="admin-input h-[400px] font-mono text-xs leading-relaxed bg-black/50"></textarea>
                                </div>

                                <div x-show="!techy" class="space-y-8">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="col-span-2">
                                            <label class="admin-label">Internal Section Title</label>
                                            <input type="text" x-model="title" class="admin-input mt-2">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="admin-label text-arbitra-emerald">Main Hero Title (ITALIC)</label>
                                            <input type="text" x-model="form.title" class="admin-input mt-2 text-xl font-black italic">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="admin-label">Hero Description</label>
                                            <textarea x-model="form.description" class="admin-input h-32 mt-2 leading-relaxed"></textarea>
                                        </div>
                                        <div>
                                            <label class="admin-label">Data Source Reference</label>
                                            <input type="text" x-model="source" class="admin-input mt-2">
                                        </div>
                                    </div>

                                    {{-- Popup Editor for Hero --}}
                                    <div class="mt-12 pt-12 border-t border-white/10">
                                        <div class="flex items-center justify-between mb-8">
                                            <div>
                                                <h4 class="text-sm font-black uppercase tracking-widest text-arbitra-emerald">Interactive Popup Details</h4>
                                                <p class="text-[9px] text-arbitra-gray font-medium mt-1 uppercase">Configure the details for the motivation popup</p>
                                            </div>
                                            <button @click="if(!form.modal_details) form.modal_details = {}; editingModal = !editingModal" class="text-[10px] font-black uppercase px-6 py-2 rounded-full border border-white/10 transition-all" :class="editingModal ? 'bg-arbitra-emerald text-arbitra-black border-arbitra-emerald' : 'bg-white/5 text-white hover:bg-white/10'" x-text="editingModal ? 'Hide Popup Editor' : 'Edit Popup'"></button>
                                        </div>

                                        <div x-show="editingModal" x-transition class="space-y-8 bg-black/40 p-8 rounded-3xl border border-white/5">
                                            <!-- Same Category Tab logic for Hero -->
                                            <div class="flex flex-wrap gap-2 border-b border-white/10 pb-6">
                                                <template x-for="(tab, index) in modalTabs" :key="index">
                                                    <div class="flex items-center gap-1 group">
                                                        <button @click="activeTab = index" 
                                                                class="text-[10px] font-black uppercase px-5 py-2.5 rounded-xl border transition-all"
                                                                :class="activeTab === index ? 'bg-arbitra-emerald/10 border-arbitra-emerald text-arbitra-emerald' : 'bg-white/5 border-white/10 text-arbitra-gray hover:text-white'">
                                                            <span x-text="tab.name"></span>
                                                        </button>
                                                    </div>
                                                </template>
                                                <button @click="addModalTab()" class="text-[10px] font-black uppercase px-5 py-2.5 rounded-xl border border-dashed border-white/20 text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all">+ Add Tab</button>
                                            </div>

                                            <template x-if="modalTabs[activeTab]">
                                                <div class="space-y-8">
                                                    <div class="flex justify-between items-center bg-white/5 -mx-8 -mt-8 mb-8 p-6 rounded-t-3xl border-b border-white/5">
                                                        <div class="flex items-center gap-6">
                                                            <div class="space-y-1">
                                                                <label class="admin-label text-[8px]">Tab Name</label>
                                                                <input type="text" x-model="modalTabs[activeTab].name" class="bg-transparent border-none p-0 text-white font-black uppercase tracking-tight focus:ring-0 w-48">
                                                            </div>
                                                            <div class="h-8 w-px bg-white/10"></div>
                                                            <div class="space-y-1">
                                                                <label class="admin-label text-[8px]">Display Style</label>
                                                                <select x-model="modalTabs[activeTab].type" class="bg-transparent border-none p-0 text-arbitra-emerald font-black uppercase text-[10px] focus:ring-0 cursor-pointer">
                                                                    <option value="points" class="bg-arbitra-black">Bullet Points</option>
                                                                    <option value="table" class="bg-arbitra-black">Data Table</option>
                                                                    <option value="map" class="bg-arbitra-black">Infrastructure Map</option>
                                                                    <option value="text" class="bg-arbitra-black">Plain Text</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button @click="removeModalTab(activeTab)" class="text-red-500/50 hover:text-red-500 transition-all">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>

                                                    <div x-show="modalTabs[activeTab].type === 'points'" class="space-y-4">
                                                        <template x-for="(pt, ptIdx) in modalTabs[activeTab].data" :key="ptIdx">
                                                            <div class="flex gap-4 items-center group">
                                                                <div class="w-1.5 h-1.5 rounded-full bg-arbitra-emerald"></div>
                                                                <input type="text" x-model="modalTabs[activeTab].data[ptIdx]" class="admin-input flex-1 font-medium">
                                                                <button @click="modalTabs[activeTab].data.splice(ptIdx, 1)" class="opacity-0 group-hover:opacity-100 text-red-500 p-2">×</button>
                                                            </div>
                                                        </template>
                                                        <button @click="modalTabs[activeTab].data.push('New detail point...')" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add New Point</button>
                                                    </div>

                                                    <div x-show="modalTabs[activeTab].type === 'table'" class="space-y-3">
                                                        <template x-for="(row, rowIdx) in modalTabs[activeTab].data" :key="rowIdx">
                                                            <div class="flex gap-4 items-center">
                                                                <input type="text" x-model="row.key" placeholder="Metric" class="admin-input flex-1 font-bold">
                                                                <input type="text" x-model="row.value" placeholder="Value" class="admin-input flex-1 text-arbitra-emerald font-black">
                                                                <button @click="modalTabs[activeTab].data.splice(rowIdx, 1)" class="text-red-500 p-1">×</button>
                                                            </div>
                                                        </template>
                                                        <button @click="modalTabs[activeTab].data.push({key: '', value: ''})" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add Row</button>
                                                    </div>
                                                    
                                                    <div x-show="modalTabs[activeTab].type === 'map'" class="space-y-4">
                                                        <template x-for="(pt, ptIdx) in modalTabs[activeTab].data" :key="ptIdx">
                                                            <div class="grid grid-cols-12 gap-3 items-center">
                                                                <div class="col-span-6"><label class="text-[8px] font-black uppercase mb-1 block tracking-wider">Label</label><input type="text" x-model="pt.label" class="admin-input text-xs"></div>
                                                                <div class="col-span-2"><label class="text-[8px] font-black uppercase mb-1 block">LAT</label><input type="number" step="0.0001" x-model.number="pt.lat" class="admin-input text-xs"></div>
                                                                <div class="col-span-2"><label class="text-[8px] font-black uppercase mb-1 block">LNG</label><input type="number" step="0.0001" x-model.number="pt.lng" class="admin-input text-xs"></div>
                                                                <div class="col-span-2 flex justify-end"><button @click="modalTabs[activeTab].data.splice(ptIdx, 1)" class="text-red-500 mt-4">×</button></div>
                                                            </div>
                                                        </template>
                                                        <button @click="modalTabs[activeTab].data.push({label: 'New Infrastructure', lat: 10.7, lng: 122.5})" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add Map Point</button>
                                                    </div>

                                                    <div x-show="modalTabs[activeTab].type === 'text'">
                                                        <textarea x-model="modalTabs[activeTab].data" class="admin-input h-32 leading-relaxed" placeholder="Type plain text info here..."></textarea>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-4 mt-12 pt-8 border-t border-white/10 sticky bottom-0 bg-[#0A0A0A] pb-4">
                                    <button @click.stop="save({{ $hero->id }}, {section_title: title, content: form, source: source}); editing = false" class="bg-arbitra-emerald text-arbitra-black px-10 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-arbitra-emerald/20">SAVE HERO</button>
                                    <div class="flex-1"></div>
                                    <button @click.stop="tryClose(id, () => editing = false)" class="text-white/60 px-8 py-4 rounded-full font-black text-xs uppercase tracking-widest hover:text-white transition-all border border-white/10">CANCEL</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bento-card p-20 border-dashed border-arbitra-emerald/30 flex flex-col items-center justify-center text-center">
                        <h2 class="text-2xl font-black text-white/20 uppercase mb-4">No Hero Section for {{ $selectedYear }}</h2>
                        <button @click="addSection('hero')" class="bg-arbitra-emerald/20 text-arbitra-emerald border border-arbitra-emerald/50 px-8 py-3 rounded-full font-black text-xs hover:bg-arbitra-emerald hover:text-arbitra-black transition-all">CREATE HERO SECTION</button>
                    </div>
                @endif
            </div>

            <!-- Dynamic Sections -->
            @foreach($contents->whereNotIn('type', ['hero'])->sortBy('page_number') as $content)
                <section x-data="{ 
                    editing: false, 
                    techy: false,
                    editingModal: false,
                    id: {{ $content->id }},
                    form: JSON.parse($el.dataset.form), 
                    title: $el.dataset.title, 
                    source: $el.dataset.source,
                    modalJson: '',
                    modalTabs: [],
                    activeTab: 0,
                    mainChartInstance: null,
                    previewChartInstance: null,
                    parseModalDetails() {
                        const details = this.form.modal_details || {};
                        this.modalTabs = Object.entries(details).map(([name, value]) => {
                            let type = 'text';
                            let data = value;
                            
                            if (value && typeof value === 'object') {
                                if (value.Points && Array.isArray(value.Points)) {
                                    type = 'points';
                                    data = [...value.Points];
                                } else if (value['Map Points'] && Array.isArray(value['Map Points'])) {
                                    type = 'map';
                                    data = value['Map Points'].map(p => ({...p}));
                                } else if (Array.isArray(value)) {
                                    if (value.length > 0 && typeof value[0] === 'object' && value[0].lat !== undefined) {
                                        type = 'map';
                                        data = value.map(p => ({...p}));
                                    } else {
                                        type = 'points';
                                        data = [...value];
                                    }
                                } else {
                                    type = 'table';
                                    data = Object.entries(value).map(([k, v]) => ({key: k, value: v}));
                                }
                            }
                            return { name, type, data };
                        });
                    },
                    syncModalDetails() {
                        const details = {};
                        this.modalTabs.forEach(tab => {
                            if (tab.type === 'points') {
                                details[tab.name] = { Points: tab.data };
                            } else if (tab.type === 'table') {
                                const tableObj = {};
                                tab.data.forEach(row => { if(row.key) tableObj[row.key] = row.value });
                                details[tab.name] = tableObj;
                            } else if (tab.type === 'map') {
                                details[tab.name] = { 'Map Points': tab.data };
                            } else {
                                details[tab.name] = tab.data;
                            }
                        });
                        this.form.modal_details = details;
                        this.modalJson = JSON.stringify(details, null, 4);
                    },
                    addModalTab() {
                        this.modalTabs.push({ name: 'New Section', type: 'points', data: [] });
                        this.activeTab = this.modalTabs.length - 1;
                    },
                    removeModalTab(index) {
                        if(confirm('Remove this entire popup section?')) {
                            this.modalTabs.splice(index, 1);
                            if(this.activeTab >= this.modalTabs.length) this.activeTab = Math.max(0, this.modalTabs.length - 1);
                        }
                    }
                }"
                data-form="{{ json_encode($content->content) }}"
                data-title="{{ $content->section_title }}"
                data-source="{{ $content->source }}"
                x-init="
                    modalJson = JSON.stringify(form.modal_details || null, null, 4);
                    parseModalDetails();
                    $watch('form.modal_details', (val) => {
                        modalJson = JSON.stringify(val, null, 4);
                    });
                    $watch('modalTabs', () => syncModalDetails(), { deep: true });
                    $watch('techy', (val) => { if(!val) parseModalDetails() });
                    
                    // Change Tracking: snapshot initial state, then detect real changes
                    _initialFormSnapshots[id] = { form: JSON.stringify(form), title: title, source: source };
                    $watch('form', () => {
                        if (JSON.stringify(form) !== _initialFormSnapshots[id].form) {
                            Alpine.store('admin').setSectionDirty(id, title);
                        }
                    }, { deep: true });
                    $watch('title', (newTitle) => {
                        if (newTitle !== _initialFormSnapshots[id].title) {
                            Alpine.store('admin').setSectionDirty(id, newTitle);
                        }
                    });
                    $watch('source', () => {
                        if (source !== _initialFormSnapshots[id].source) {
                            Alpine.store('admin').setSectionDirty(id, title);
                        }
                    });
                "
                class="scroll-mt-32 pb-20 group relative">
                    
                    @if($content->type === 'stats_grid')
                        <div @click="editing = true" class="cursor-pointer">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="bg-arbitra-emerald/10 text-arbitra-emerald px-3 py-1 rounded font-black text-[10px] uppercase">STATS GRID</span>
                                <h2 class="text-2xl font-black uppercase tracking-tight" x-text="title"></h2>
                                <button @click.stop="deleteSection({{ $content->id }})" class="opacity-0 group-hover:opacity-100 text-arbitra-gray hover:text-red-500 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <span class="text-[10px] font-bold text-arbitra-emerald/50 uppercase ml-auto">Click to edit</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <template x-for="(stat, index) in form.stats">
                                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 relative group/item hover:border-arbitra-emerald/40 transition-all">
                                        <span class="text-[10px] font-bold text-arbitra-gray uppercase block" x-text="stat.label"></span>
                                        <h3 class="text-2xl font-black mt-2" x-text="stat.value"></h3>
                                    </div>
                                </template>
                            </div>
                        </div>
                    @elseif($content->type === 'metadata')
                        <div @click="editing = true" class="cursor-pointer bg-arbitra-emerald/5 p-8 rounded-2xl border border-arbitra-emerald/10 hover:border-arbitra-emerald transition-all">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-2xl bg-arbitra-emerald/20 flex items-center justify-center border border-arbitra-emerald/20">
                                    <svg class="w-8 h-8 text-arbitra-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xl font-extrabold text-white" x-text="form.site_title"></h4>
                                    <p class="text-sm text-arbitra-gray mt-1 uppercase font-black tracking-widest text-[10px]">Site Configuration / CLICK TO EDIT</p>
                                </div>
                            </div>
                        </div>
                    @elseif($content->type === 'grid')
                        <div @click="editing = true" class="cursor-pointer">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="bg-arbitra-emerald/10 text-arbitra-emerald px-3 py-1 rounded font-black text-[10px] uppercase">INFO GRID</span>
                                <h2 class="text-2xl font-black uppercase tracking-tight" x-text="title"></h2>
                                <button @click.stop="deleteSection({{ $content->id }})" class="opacity-0 group-hover:opacity-100 text-arbitra-gray hover:text-red-500 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <template x-for="(item, index) in form.items">
                                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10 relative group/item hover:border-arbitra-emerald/40">
                                        <h4 class="font-black uppercase mb-2" x-text="item.name"></h4>
                                        <p class="text-xs text-arbitra-gray line-clamp-3" x-text="item.details"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    @elseif($content->type === 'marquee')
                        <div @click="editing = true" class="cursor-pointer relative overflow-hidden whitespace-nowrap py-10 border-y border-white/5 hover:bg-white/5 transition-all">
                            <div class="absolute top-2 left-4 px-3 py-1 rounded-full bg-arbitra-emerald/10 text-arbitra-emerald font-black text-[8px] uppercase tracking-widest">PARTNERS MARQUEE / CLICK TO EDIT</div>
                            <div class="flex gap-8 animate-marquee whitespace-nowrap opacity-50">
                                <template x-for="item in form.items">
                                    <span class="text-xl font-black uppercase tracking-widest text-white" x-text="item"></span>
                                </template>
                            </div>
                        </div>
                    @elseif($content->type === 'cta')
                        <div @click="editing = true" class="cursor-pointer bento-card p-12 text-center bg-gradient-to-br from-arbitra-emerald/10 to-transparent border-arbitra-emerald/20 hover:border-arbitra-emerald/60 transition-all">
                            <h3 class="text-4xl font-black uppercase italic mb-6" x-text="form.title"></h3>
                            <p class="text-lg text-arbitra-gray max-w-xl mx-auto mb-8" x-text="form.description"></p>
                            <div class="inline-block bg-arbitra-emerald text-arbitra-black px-8 py-3 rounded-full font-black text-xs uppercase tracking-widest">Connect Feedback Form</div>
                        </div>
                    @elseif($content->type === 'chart')
                        <div @click="editing = true" class="cursor-pointer">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="bg-arbitra-emerald/10 text-arbitra-emerald px-3 py-1 rounded font-black text-[10px] uppercase">DYNAMIC CHART</span>
                                <h2 class="text-2xl font-black uppercase tracking-tight" x-text="title"></h2>
                                <button @click.stop="deleteSection({{ $content->id }})" class="opacity-0 group-hover:opacity-100 text-arbitra-gray hover:text-red-500 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                             <div class="bg-white/5 p-8 rounded-2xl border border-white/10 hover:border-arbitra-emerald/40" 
                                 x-init="
                                    this.mainChartInstance = renderChart($el.querySelector('.main-chart'), form.chart_type, form.series, form.categories);
                                    $watch('form.series', (val) => { if (this.mainChartInstance) this.mainChartInstance.updateSeries(JSON.parse(JSON.stringify(val))) }, { deep: true });
                                    $watch('form.categories', (val) => { if (this.mainChartInstance) this.mainChartInstance.updateOptions({ xaxis: { categories: val } }) });
                                    $watch('form.chart_type', (val) => {
                                        if (this.mainChartInstance) {
                                            this.mainChartInstance.destroy();
                                            this.mainChartInstance = renderChart($el.querySelector('.main-chart'), val, form.series, form.categories);
                                        }
                                    });
                                 ">
                                <div class="main-chart w-full h-[450px]"></div>
                            </div>
                        </div>
                    @elseif($content->type === 'list')
                        <div @click="editing = true" class="cursor-pointer">
                            <div class="flex items-center gap-4 mb-8">
                                <span class="bg-arbitra-emerald/10 text-arbitra-emerald px-3 py-1 rounded font-black text-[10px] uppercase">POINT LIST</span>
                                <h2 class="text-2xl font-black uppercase tracking-tight" x-text="title"></h2>
                                <button @click.stop="deleteSection({{ $content->id }})" class="opacity-0 group-hover:opacity-100 text-arbitra-gray hover:text-red-500 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <template x-for="item in form.items">
                                    <div class="flex items-center gap-4 p-5 bg-white/5 rounded-2xl border border-white/10 hover:border-arbitra-emerald/30">
                                        <div class="w-2 h-2 rounded-full bg-arbitra-emerald shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                        <span class="text-white font-bold" x-text="item"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Modal Edit Overlay -->
                    <div x-show="editing" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         x-cloak 
                         class="admin-overlay" 
                         @click="tryClose(id, () => editing = false)">
                        
                        <div class="admin-modal" @click.stop
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                            
                            <div class="flex justify-between items-center mb-8">
                                <div>
                                    <h3 class="text-2xl font-black uppercase italic text-arbitra-emerald">Edit Section</h3>
                                    <p class="text-[10px] text-arbitra-gray font-bold uppercase tracking-widest mt-1" x-text="'TYPE: ' + @js($content->type)"></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button @click="techy = !techy" class="text-[10px] font-black uppercase text-white/40 hover:text-white border border-white/10 px-4 py-2 rounded-full transition-all" x-text="techy ? 'Standard Mode' : 'JSON Mode'"></button>
                                    <button @click="tryClose(id, () => editing = false)" class="text-arbitra-gray hover:text-white p-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <label class="admin-label">Section Title</label>
                                <input type="text" x-model="title" class="admin-input mt-2 text-lg font-bold">
                            </div>
                            
                            <div x-show="techy" class="space-y-4">
                                <label class="admin-label">Raw JSON Data</label>
                                <textarea x-model="JSON.stringify(form, null, 2)" @change="form = safeParse($event.target.value, form)" class="admin-input h-[400px] font-mono text-xs leading-relaxed bg-black/50"></textarea>
                            </div>

                            <div x-show="!techy" class="space-y-8">
                            @if($content->type === 'stats_grid')
                                <div class="space-y-4">
                                    <template x-for="(stat, index) in form.stats" :key="index">
                                        <div class="flex gap-4 items-end bg-white/5 p-4 rounded-xl">
                                            <div class="flex-1">
                                                <label class="admin-label">Label</label>
                                                <input type="text" x-model="stat.label" class="admin-input">
                                            </div>
                                            <div class="flex-1">
                                                <label class="admin-label">Value</label>
                                                <input type="text" x-model="stat.value" class="admin-input">
                                            </div>
                                            <button @click="form.stats.splice(index, 1)" class="p-2 text-red-500 hover:bg-red-500/10 rounded">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="form.stats.push({label: 'New Label', value: '0'})" class="w-full py-2 border-2 border-dashed border-white/10 rounded-xl text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all font-bold text-xs uppercase">+ Add New Stat</button>
                                    
                                    <div class="mt-6 pt-6 border-t border-white/5">
                                        <label class="admin-label text-arbitra-emerald">Notable Info / Highlight Note</label>
                                        <textarea x-model="form.notable_info" class="admin-input h-24 mt-2 leading-relaxed" placeholder="Enter highlighted note here..."></textarea>
                                    </div>

                                    <template x-if="form.modal_details && typeof form.modal_details['Map Labels'] !== 'undefined'">
                                        <div class="mt-6 pt-6 border-t border-white/5">
                                            <label class="admin-label text-arbitra-emerald">Map Labels (For Static Map)</label>
                                            <textarea x-model="form.modal_details['Map Labels']" class="admin-input h-24 mt-2 leading-relaxed" placeholder="Sibuyan Sea, Visayan Sea..."></textarea>
                                        </div>
                                    </template>
                                </div>
                            @elseif($content->type === 'grid')
                                <div class="space-y-4">
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <div class="bg-white/5 p-4 rounded-xl space-y-4">
                                            <div class="flex gap-4 items-start">
                                                <div class="flex-1 space-y-2">
                                                    <label class="admin-label">Name</label>
                                                    <input type="text" x-model="item.name" class="admin-input">
                                                    <label class="admin-label">Details</label>
                                                    <textarea x-model="item.details" class="admin-input h-20"></textarea>
                                                </div>
                                                <button @click="form.items.splice(index, 1)" class="p-2 text-red-500 hover:bg-red-500/10 rounded">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>

                                            {{-- Simplified Item-level Popup Editor --}}
                                            <div class="pt-4 border-t border-white/5">
                                                <div class="flex items-center justify-between mb-3">
                                                    <span class="text-[8px] font-black uppercase text-arbitra-emerald tracking-widest">Item Pop-up Details</span>
                                                    <button @click="if(!item.modal_details) item.modal_details = {}; item.showEditor = !item.showEditor" 
                                                            class="text-[8px] font-black uppercase bg-white/5 border border-white/10 px-3 py-1 rounded-full hover:bg-arbitra-emerald hover:text-arbitra-black transition-all"
                                                            x-text="item.showEditor ? 'Hide' : 'Edit Pop-up'"></button>
                                                </div>

                                                <div x-show="item.showEditor" class="space-y-4 bg-black/40 p-4 rounded-xl border border-white/5">
                                                    <template x-if="item.modal_details && item.modal_details['Map Points']">
                                                        <div class="space-y-3">
                                                            <template x-for="(pt, ptIdx) in item.modal_details['Map Points']" :key="ptIdx">
                                                                <div class="flex gap-2">
                                                                    <input type="text" x-model="pt.label" placeholder="Label" class="admin-input text-[10px] flex-1">
                                                                    <input type="number" step="0.0001" x-model.number="pt.lat" placeholder="Lat" class="admin-input text-[10px] w-24">
                                                                    <input type="number" step="0.0001" x-model.number="pt.lng" placeholder="Lng" class="admin-input text-[10px] w-24">
                                                                    <button @click="item.modal_details['Map Points'].splice(ptIdx, 1)" class="text-red-500">×</button>
                                                                </div>
                                                            </template>
                                                            <button @click="item.modal_details['Map Points'].push({label: 'New Point', lat: 10.7, lng: 122.5})" class="w-full py-1.5 border border-dashed border-white/10 rounded text-[8px] uppercase font-black text-arbitra-gray hover:text-white">+ Add Map Point</button>
                                                        </div>
                                                    </template>

                                                    <template x-if="!item.modal_details || !item.modal_details['Map Points']">
                                                        <div class="py-2">
                                                            <p class="text-[8px] text-arbitra-gray italic mb-4">No map points found for this item.</p>
                                                            <div class="flex gap-3">
                                                                <button @click="item.modal_details = {'Map Points': [{label: 'Center Point', lat: 10.7, lng: 122.5}]}" class="flex-1 py-2 bg-white/5 rounded-lg text-[8px] font-black hover:bg-arbitra-emerald/20 hover:text-arbitra-emerald transition-all border border-white/5">INIT INFRA MAP</button>
                                                                <button @click="item.showEditor = false; techy = true" class="flex-1 py-2 bg-white/5 rounded-lg text-[8px] font-black hover:bg-white/10 transition-all border border-white/5 uppercase tracking-widest">Use Techy Mode</button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <button @click="form.items.push({name: 'New Item', details: 'Description...', modal_details: {}})" class="w-full py-2 border-2 border-dashed border-white/10 rounded-xl text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all font-bold text-xs uppercase">+ Add New Item</button>
                                </div>
                            @elseif($content->type === 'chart')
                                <div class="space-y-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="admin-label">Chart Type</label>
                                            <select x-model="form.chart_type" class="admin-input">
                                                <option value="bar">Bar Chart</option>
                                                <option value="line">Line Chart</option>
                                                <option value="area">Area Chart</option>
                                            </select>
                                        </div>
                                    </div>
                                                                       <div class="bg-black/40 p-6 rounded-2xl border border-white/5"
                                         x-init="
                                             $watch('editing', (val) => {
                                                 if (val && !this.previewChartInstance) {
                                                      setTimeout(() => {
                                                          this.previewChartInstance = renderPreview($el.querySelector('.preview-chart'), form.chart_type, form.series, form.categories);
                                                      }, 200);
                                                 }
                                             });
                                             $watch('form.chart_type', (val) => {
                                                 if (this.previewChartInstance) {
                                                     this.previewChartInstance.destroy();
                                                     this.previewChartInstance = renderPreview($el.querySelector('.preview-chart'), val, form.series, form.categories);
                                                 }
                                             });
                                             $watch('form.categories', (val) => { if(this.previewChartInstance) this.previewChartInstance.updateOptions({ xaxis: { categories: val } }) });
                                             $watch('form.series', (val) => { if(this.previewChartInstance) this.previewChartInstance.updateSeries(JSON.parse(JSON.stringify(val))) }, { deep: true });
                                         ">
                                         <div class="preview-chart w-full h-[300px]"></div>
                                    </div>
                                    <div class="space-y-4">
                                        <template x-for="(cat, index) in form.categories" :key="index">
                                            <div class="flex gap-4 items-center bg-white/5 p-4 rounded-xl">
                                                <div class="flex-[2]">
                                                    <label class="admin-label">Label</label>
                                                    <input type="text" x-model="form.categories[index]" class="admin-input">
                                                </div>
                                                <div class="flex-1 space-y-4">
                                                    <template x-for="(s, sIndex) in form.series" :key="sIndex">
                                                        <div>
                                                            <label class="admin-label text-[8px]" x-text="s.name"></label>
                                                            <input type="number" step="0.1" x-model.number="s.data[index]" class="admin-input">
                                                        </div>
                                                    </template>
                                                </div>
                                                <button @click="form.categories.splice(index, 1); form.series.forEach(s => s.data.splice(index, 1))" class="p-2 text-red-500 hover:bg-red-500/10 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button @click="form.categories.push('New Label'); form.series.forEach(s => s.data.push(0))" class="w-full py-2 border-2 border-dashed border-white/10 rounded-xl text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all font-bold text-xs uppercase">+ Add Data Point</button>
                                    </div>
                                    
                                    <div class="mt-6 pt-6 border-t border-white/5">
                                        <label class="admin-label text-arbitra-emerald">Notable Info / Highlight Note</label>
                                        <textarea x-model="form.notable_info" class="admin-input h-24 mt-2 leading-relaxed" placeholder="Enter highlighted note here..."></textarea>
                                    </div>
                                    
                                    <div class="mt-6 pt-6 border-t border-white/5">
                                        <label class="admin-label">Data Series Names</label>
                                        <div class="grid grid-cols-2 gap-4 mt-2">
                                            <template x-for="(s, sIndex) in form.series" :key="sIndex">
                                                <div class="flex gap-2 items-center bg-white/5 p-2 rounded">
                                                    <input type="text" x-model="s.name" class="admin-input text-[10px] py-1">
                                                    <button @click="form.series.splice(sIndex, 1)" class="text-red-500 p-1">×</button>
                                                </div>
                                            </template>
                                            <button @click="form.series.push({name: 'New Series', data: new Array(form.categories.length).fill(0)})" class="text-[10px] font-bold text-arbitra-emerald uppercase">+ Add Series</button>
                                        </div>
                                    </div>
                                </div>
                            @elseif($content->type === 'list')
                                <div class="space-y-4">
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <div class="flex gap-4 items-center bg-white/5 p-4 rounded-xl">
                                            <input type="text" x-model="form.items[index]" class="admin-input flex-1">
                                            <button @click="form.items.splice(index, 1)" class="p-2 text-red-500 hover:bg-red-500/10 rounded">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="form.items.push('New Strategy Item')" class="w-full py-2 border-2 border-dashed border-white/10 rounded-xl text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all font-bold text-xs uppercase">+ Add New Item</button>
                                </div>
                            @elseif($content->type === 'marquee')
                                <div class="space-y-4">
                                    <label class="admin-label">Partners / Logos (Marquee)</label>
                                    <template x-for="(item, index) in form.items" :key="index">
                                        <div class="flex gap-4 items-center bg-white/5 p-4 rounded-xl">
                                            <input type="text" x-model="form.items[index]" class="admin-input">
                                            <button @click="form.items.splice(index, 1)" class="p-2 text-red-500 hover:bg-red-500/10 rounded">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button @click="form.items.push('NEW PARTNER')" class="w-full py-2 border-2 border-dashed border-white/10 rounded-xl text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all font-bold text-xs uppercase">+ Add Partner</button>
                                </div>
                            @elseif($content->type === 'cta')
                                <div class="space-y-4">
                                    <label class="admin-label">CTA Title</label>
                                    <input type="text" x-model="form.title" class="admin-input">
                                    <label class="admin-label">CTA Description</label>
                                    <textarea x-model="form.description" class="admin-input h-32"></textarea>
                                </div>
                            @elseif($content->type === 'metadata')
                                <div class="space-y-4">
                                    <label class="admin-label">Browser Tab Title</label>
                                    <input type="text" x-model="form.browser_tab_title" class="admin-input">
                                    
                                    <label class="admin-label">Navbar Title</label>
                                    <input type="text" x-model="form.site_title" class="admin-input">
                                    
                                    <label class="admin-label">Logo Subtitle (e.g. DTI Region 6)</label>
                                    <input type="text" x-model="form.logo_text" class="admin-input">
                                </div>
                            @endif

                            {{-- Dynamic Popup (Modal) Editor --}}
                            <div class="mt-12 pt-12 border-t border-white/10">
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h4 class="text-sm font-black uppercase tracking-widest text-arbitra-emerald">Interactive Popup Details</h4>
                                        <p class="text-[9px] text-arbitra-gray font-medium mt-1 uppercase">Configure the modal that appears when users click this section</p>
                                    </div>
                                    <button @click="if(!form.modal_details) form.modal_details = {}; editingModal = !editingModal" class="text-[10px] font-black uppercase px-6 py-2 rounded-full border border-white/10 transition-all" :class="editingModal ? 'bg-arbitra-emerald text-arbitra-black border-arbitra-emerald' : 'bg-white/5 text-white hover:bg-white/10'" x-text="editingModal ? 'Hide Popup Editor' : 'Edit Popup'"></button>
                                </div>

                                <div x-show="editingModal" x-transition class="space-y-8 bg-black/40 p-8 rounded-3xl border border-white/5">
                                    <!-- Category Tabs -->
                                    <div class="flex flex-wrap gap-2 border-b border-white/10 pb-6">
                                        <template x-for="(tab, index) in modalTabs" :key="index">
                                            <div class="flex items-center gap-1 group">
                                                <button @click="activeTab = index" 
                                                        class="text-[10px] font-black uppercase px-5 py-2.5 rounded-xl border transition-all"
                                                        :class="activeTab === index ? 'bg-arbitra-emerald/10 border-arbitra-emerald text-arbitra-emerald' : 'bg-white/5 border-white/10 text-arbitra-gray hover:text-white'">
                                                    <span x-text="tab.name"></span>
                                                </button>
                                            </div>
                                        </template>
                                        <button @click="addModalTab()" class="text-[10px] font-black uppercase px-5 py-2.5 rounded-xl border border-dashed border-white/20 text-arbitra-gray hover:border-arbitra-emerald hover:text-white transition-all">+ Add Tab</button>
                                    </div>

                                    <!-- Active Tab Editor -->
                                    <template x-if="modalTabs[activeTab]">
                                        <div class="space-y-8">
                                            <div class="flex justify-between items-center bg-white/5 -mx-8 -mt-8 mb-8 p-6 rounded-t-3xl border-b border-white/5">
                                                <div class="flex items-center gap-6">
                                                    <div class="space-y-1">
                                                        <label class="admin-label text-[8px]">Tab Name</label>
                                                        <input type="text" x-model="modalTabs[activeTab].name" class="bg-transparent border-none p-0 text-white font-black uppercase tracking-tight focus:ring-0 w-48">
                                                    </div>
                                                    <div class="h-8 w-px bg-white/10"></div>
                                                    <div class="space-y-1">
                                                        <label class="admin-label text-[8px]">Display Style</label>
                                                        <select x-model="modalTabs[activeTab].type" class="bg-transparent border-none p-0 text-arbitra-emerald font-black uppercase text-[10px] focus:ring-0 cursor-pointer">
                                                            <option value="points" class="bg-arbitra-black">Bullet Points</option>
                                                            <option value="table" class="bg-arbitra-black">Data Table</option>
                                                            <option value="map" class="bg-arbitra-black">Infrastructure Map</option>
                                                            <option value="text" class="bg-arbitra-black">Plain Text</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <button @click="removeModalTab(activeTab)" class="text-red-500/50 hover:text-red-500 transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>

                                            <div x-show="modalTabs[activeTab].type === 'points'" class="space-y-4">
                                                <template x-for="(pt, ptIdx) in modalTabs[activeTab].data" :key="ptIdx">
                                                    <div class="flex gap-4 items-center group">
                                                        <div class="w-1.5 h-1.5 rounded-full bg-arbitra-emerald"></div>
                                                        <input type="text" x-model="modalTabs[activeTab].data[ptIdx]" class="admin-input flex-1">
                                                        <button @click="modalTabs[activeTab].data.splice(ptIdx, 1)" class="opacity-0 group-hover:opacity-100 text-red-500 p-2">×</button>
                                                    </div>
                                                </template>
                                                <button @click="modalTabs[activeTab].data.push('New detail point...')" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add New Point</button>
                                            </div>

                                            <div x-show="modalTabs[activeTab].type === 'table'" class="space-y-3">
                                                <template x-for="(row, rowIdx) in modalTabs[activeTab].data" :key="rowIdx">
                                                    <div class="flex gap-4 items-center">
                                                        <input type="text" x-model="row.key" placeholder="Metric Name" class="admin-input flex-1 font-bold">
                                                        <input type="text" x-model="row.value" placeholder="Value" class="admin-input flex-1 text-arbitra-emerald font-black">
                                                        <button @click="modalTabs[activeTab].data.splice(rowIdx, 1)" class="text-red-500 p-1">×</button>
                                                    </div>
                                                </template>
                                                <button @click="modalTabs[activeTab].data.push({key: '', value: ''})" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add Table Row</button>
                                            </div>

                                            <div x-show="modalTabs[activeTab].type === 'map'" class="space-y-4">
                                                <template x-for="(pt, ptIdx) in modalTabs[activeTab].data" :key="ptIdx">
                                                    <div class="grid grid-cols-12 gap-3 items-center">
                                                        <div class="col-span-4"><label class="text-[8px] font-black uppercase mb-1 block">Location Label</label><input type="text" x-model="pt.label" class="admin-input text-xs"></div>
                                                        <div class="col-span-3"><label class="text-[8px] font-black uppercase mb-1 block">LAT</label><input type="number" step="0.0001" x-model.number="pt.lat" class="admin-input text-xs"></div>
                                                        <div class="col-span-3"><label class="text-[8px] font-black uppercase mb-1 block">LNG</label><input type="number" step="0.0001" x-model.number="pt.lng" class="admin-input text-xs"></div>
                                                        <div class="col-span-2 flex justify-end"><button @click="modalTabs[activeTab].data.splice(ptIdx, 1)" class="text-red-500 mt-4">×</button></div>
                                                    </div>
                                                </template>
                                                <button @click="modalTabs[activeTab].data.push({label: 'New Infrastructure', lat: 10.7, lng: 122.5})" class="w-full py-3 border-2 border-dashed border-white/5 rounded-2xl text-[10px] font-black uppercase text-arbitra-gray hover:border-arbitra-emerald/40 hover:text-white transition-all">+ Add Map Point</button>
                                            </div>
                                            
                                            <div x-show="modalTabs[activeTab].type === 'text'">
                                                <textarea x-model="modalTabs[activeTab].data" class="admin-input h-32 leading-relaxed" placeholder="Type plain text info here..."></textarea>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <label class="admin-label mt-4">Source</label>
                        <input @click.stop type="text" x-model="source" class="admin-input">

                        <div class="flex gap-4 mt-8 pt-6 border-t border-white/5">
                            <button @click.stop="save({{ $content->id }}, {section_title: title, content: form, source: source}); editing = false" class="bg-arbitra-emerald text-arbitra-black px-6 py-2 rounded-full font-black text-xs">SAVE SECTION</button>
                            <div class="flex-1"></div>
                            <button @click.stop="deleteSection({{ $content->id }})" class="text-red-500 px-6 py-2 rounded-full font-black text-xs border border-red-500/20 hover:bg-red-500/10">DELETE SECTION</button>
                            <button @click.stop="tryClose(id, () => editing = false)" class="text-white px-6 py-2 rounded-full font-black text-xs border border-white/20">CANCEL</button>
                        </div>
                    </div>
                </section>
            @endforeach

            <!-- Add Section Placeholders -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <button @click="addSection('stats_grid')" class="bento-card p-10 border-dashed border-white/10 flex flex-col items-center justify-center opacity-40 hover:opacity-100 transition-all">
                    <span class="text-3xl mb-2">+</span>
                    <span class="font-black text-[10px] uppercase">Add Stats Grid</span>
                </button>
                <button @click="addSection('grid')" class="bento-card p-10 border-dashed border-white/10 flex flex-col items-center justify-center opacity-40 hover:opacity-100 transition-all">
                    <span class="text-3xl mb-2">+</span>
                    <span class="font-black text-[10px] uppercase">Add Info Grid</span>
                </button>
                <button @click="addSection('chart')" class="bento-card p-10 border-dashed border-white/10 flex flex-col items-center justify-center opacity-40 hover:opacity-100 transition-all">
                    <span class="text-3xl mb-2">+</span>
                    <span class="font-black text-[10px] uppercase">Add Chart</span>
                </button>
                <button @click="addSection('list')" class="bento-card p-10 border-dashed border-white/10 flex flex-col items-center justify-center opacity-40 hover:opacity-100 transition-all">
                    <span class="text-3xl mb-2">+</span>
                    <span class="font-black text-[10px] uppercase">Add Point List</span>
                </button>
                <button @click="addSection('metadata')" class="bento-card p-10 border-dashed border-white/10 flex flex-col items-center justify-center opacity-40 hover:opacity-100 transition-all">
                    <span class="text-3xl mb-2">+</span>
                    <span class="font-black text-[10px] uppercase">Site Settings</span>
                </button>
            </div>

        </div>

        <!-- Inquiries Section -->
        <section id="section-inquiries" class="max-w-[1240px] mx-auto bento-card p-12 mt-16 bg-gradient-to-br from-arbitra-emerald/5 to-transparent border-arbitra-emerald/10">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-black uppercase tracking-tight italic">Investor Inquiries</h2>
                <span class="bg-arbitra-emerald/10 text-arbitra-emerald px-4 py-1.5 rounded-full text-[10px] font-black uppercase">{{ $inquiries->count() }} RECEIVED</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/5">
                            <th class="pb-4 text-[10px] font-black uppercase text-arbitra-gray tracking-widest">Date</th>
                            <th class="pb-4 text-[10px] font-black uppercase text-arbitra-gray tracking-widest">Investor</th>
                            <th class="pb-4 text-[10px] font-black uppercase text-arbitra-gray tracking-widest">Contact</th>
                            <th class="pb-4 text-[10px] font-black uppercase text-arbitra-gray tracking-widest">Message</th>
                            <th class="pb-4 text-[10px] font-black uppercase text-arbitra-gray tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @if(isset($inquiries) && $inquiries->count() > 0)
                            @foreach($inquiries as $inquiry)
                                <tr class="group hover:bg-white/[0.02] transition-colors">
                                    <td class="py-6 text-xs font-medium text-arbitra-gray">{{ $inquiry->created_at->format('M d, Y') }}</td>
                                    <td class="py-6">
                                        <div class="text-sm font-bold text-white">{{ $inquiry->name }}</div>
                                        <div class="text-[10px] text-arbitra-gray">{{ $inquiry->email }}</div>
                                    </td>
                                    <td class="py-6 text-xs text-arbitra-gray">{{ $inquiry->contact }}</td>
                                    <td class="py-6 text-xs text-white max-w-xs truncate" title="{{ $inquiry->message }}">{{ $inquiry->message }}</td>
                                    <td class="py-6 text-right">
                                        <button @click="deleteInquiry({{ $inquiry->id }})" class="text-arbitra-gray hover:text-red-500 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="py-20 text-center text-arbitra-gray uppercase text-[10px] font-black tracking-[0.2em] italic">No inquiries received yet</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div x-init="
        window.onbeforeunload = function() {
            if (Alpine.store('admin') && Alpine.store('admin').hasUnsavedChanges) {
                return 'You have unsaved changes. Are you sure you want to leave?';
            }
        };
    "></div>

    <div x-show="showProfileEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-xl px-6">
        <div class="bg-arbitra-dark p-12 rounded-[2.5rem] border border-white/10 max-w-md w-full relative">
            <button @click="showProfileEdit = false" class="absolute top-8 right-8 text-arbitra-gray hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-2xl font-black mb-6 italic uppercase tracking-tighter">Profile Settings</h3>
            
            <form action="/admin/profile" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="admin-label">Full Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required class="admin-input mt-2">
                </div>

                <div>
                    <label class="admin-label">Email Address</label>
                    <input type="email" name="email" value="{{ Auth::user()->email }}" required class="admin-input mt-2">
                </div>

                <div class="pt-4 border-t border-white/5">
                    <p class="text-[9px] font-black uppercase text-arbitra-gray tracking-widest mb-4">Change Password (Leave blank to keep current)</p>
                    
                    <div class="space-y-4">
                        <div x-data="{ show: false }">
                            <label class="admin-label">New Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" class="admin-input mt-2 pr-12">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 mt-1 text-arbitra-gray hover:text-white transition-all">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div x-data="{ show: false }">
                            <label class="admin-label">Confirm New Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" class="admin-input mt-2 pr-12">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 mt-1 text-arbitra-gray hover:text-white transition-all">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-arbitra-emerald text-arbitra-black py-3 rounded-full font-black text-xs uppercase tracking-widest shadow-[0_0_30px_rgba(16,185,129,0.2)]">Update Profile</button>
                    <button type="button" @click="showProfileEdit = false" class="flex-1 border border-white/20 py-3 rounded-full font-black text-xs uppercase tracking-widest">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message Toast -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         class="fixed bottom-10 right-10 z-[100] bg-arbitra-emerald text-arbitra-black px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-[0_0_50px_rgba(16,185,129,0.3)] animate-fade-in">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" 
         class="fixed bottom-10 right-10 z-[100] bg-red-500 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-[0_0_50px_rgba(239,68,68,0.3)] animate-fade-in">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div x-show="showAddYear" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-xl px-6">
        <div class="bg-arbitra-dark p-12 rounded-[2.5rem] border border-white/10 max-w-md w-full">
            <h3 class="text-2xl font-black mb-6 italic uppercase tracking-tighter">Add New Year Profile</h3>
            
            <label class="admin-label">Year Range (e.g., 2030-2031)</label>
            <input type="text" x-model="newYear" class="admin-input mt-2 mb-6">
            
            <div class="flex items-center gap-3 mb-8 bg-white/5 p-4 rounded-2xl border border-white/5">
                <input type="checkbox" x-model="duplicateFromCurrent" id="dupCheck" class="w-4 h-4 accent-arbitra-emerald">
                <label for="dupCheck" class="text-xs font-bold text-white cursor-pointer uppercase tracking-wider">Duplicate from {{ $selectedYear }}</label>
            </div>

            <div class="flex gap-4">
                <button @click="createYear()" class="flex-1 bg-arbitra-emerald text-arbitra-black py-3 rounded-full font-black text-xs">CREATE PROFILE</button>
                <button @click="showAddYear = false" class="flex-1 border border-white/20 py-3 rounded-full font-black text-xs">CANCEL</button>
            </div>
        </div>
    </div>

    <!-- Custom Unsaved Changes Warning Modal -->
    <div x-data x-show="Alpine.store('unsavedWarning').visible" x-cloak
         class="fixed inset-0 z-[200] flex items-center justify-center bg-black/90 backdrop-blur-xl px-6"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-arbitra-dark p-10 rounded-[2rem] border border-yellow-500/30 max-w-md w-full shadow-2xl shadow-yellow-500/10"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-yellow-500/20 flex items-center justify-center border border-yellow-500/30 flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black uppercase text-yellow-500">Unsaved Changes</h3>
                    <p class="text-[10px] text-arbitra-gray font-bold uppercase tracking-widest mt-0.5">Your edits will be lost</p>
                </div>
            </div>
            <p class="text-sm text-white/70 leading-relaxed mb-8">You have unsaved changes in this section. Are you sure you want to discard them?</p>
            <div class="flex gap-3">
                <button @click="Alpine.store('unsavedWarning').executeDiscard()" class="flex-1 bg-red-500/20 text-red-400 border border-red-500/30 py-3 rounded-full font-black text-xs uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all">Discard Changes</button>
                <button @click="Alpine.store('unsavedWarning').hide()" class="flex-1 bg-arbitra-emerald text-arbitra-black py-3 rounded-full font-black text-xs uppercase tracking-widest hover:scale-105 transition-all">Go Back & Save</button>
            </div>
        </div>
    </div>

    <script>
        // Global: store initial form snapshots to detect real changes
        const _initialFormSnapshots = {};

        // Global tryClose: shows custom warning if dirty, otherwise closes directly
        function tryClose(id, closeCallback) {
            if (Alpine.store('admin').dirtySections[id]) {
                Alpine.store('unsavedWarning').show(closeCallback);
            } else {
                closeCallback();
            }
        }

        document.addEventListener('alpine:init', () => {
            // Unsaved warning modal store
            Alpine.store('unsavedWarning', {
                visible: false,
                _discardCallback: null,
                show(callback) {
                    this._discardCallback = callback;
                    this.visible = true;
                },
                hide() {
                    this.visible = false;
                    this._discardCallback = null;
                },
                executeDiscard() {
                    if (this._discardCallback) this._discardCallback();
                    this.visible = false;
                    this._discardCallback = null;
                }
            });
            Alpine.store('admin', {
                hasUnsavedChanges: false,
                dirtySections: {}, // ID -> Title
                setUnsaved(val) { 
                    this.hasUnsavedChanges = val;
                    if (!val) this.dirtySections = {};
                },
                setSectionDirty(id, title) {
                    this.hasUnsavedChanges = true;
                    this.dirtySections[id] = title || 'Unnamed Section';
                },
                getUnsavedMessage() {
                    const sections = Object.values(this.dirtySections);
                    if (sections.length === 0) return 'You have unsaved changes.';
                    const uniqueSections = [...new Set(sections)];
                    return 'You have unsaved changes in: \n• ' + uniqueSections.join('\n• ');
                }
            });

            // Browser-level navigation warning
            window.addEventListener('beforeunload', (e) => {
                if (Alpine.store('admin').hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            // Intercept internal navigation
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link && !link.hasAttribute('download') && link.hostname === window.location.hostname) {
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript:')) {
                        if (Alpine.store('admin').hasUnsavedChanges) {
                            if (!confirm(Alpine.store('admin').getUnsavedMessage() + '\n\nAre you sure you want to leave?')) {
                                e.preventDefault();
                            }
                        }
                    }
                }
            }, true);
        });

        function adminApp() {
            return {
                showAddYear: false,
                showProfileEdit: false,
                newYear: '',
                duplicateFromCurrent: true,
                selectedYear: @js($selectedYear),

                confirmLogout(e) {
                    if (Alpine.store('admin').hasUnsavedChanges) {
                        if (!confirm('You have unsaved changes. Are you sure you want to logout?')) {
                            return;
                        }
                    }
                    e.target.submit();
                },

                renderChart(el, type, series, categories) {
                    if (!el) return;
                    const isDistributed = (series || []).length <= 1;
                    const options = {
                        series: JSON.parse(JSON.stringify(series || [])),
                        chart: { 
                            type: type || 'bar', 
                            height: 450, 
                            animations: {enabled: true}, 
                            toolbar: {show: false}, 
                            background: 'transparent',
                            fontFamily: 'Inter, sans-serif'
                        },
                        theme: { mode: 'dark' },
                        plotOptions: {
                            bar: { 
                                borderRadius: 2, 
                                columnWidth: '50%',
                                distributed: isDistributed
                            }
                        },
                        grid: { 
                            show: true,
                            borderColor: 'rgba(255,255,255,0.05)',
                            strokeDashArray: 0,
                            xaxis: { lines: { show: false } },
                            yaxis: { lines: { show: true } }
                        },
                        xaxis: { 
                            categories: JSON.parse(JSON.stringify(categories || [])), 
                            labels: {
                                style: {colors: '#94a3b8', fontWeight: 500, fontSize: '11px'}
                            },
                            axisBorder: { show: true, color: 'rgba(255,255,255,0.1)' },
                            axisTicks: { show: true, color: 'rgba(255,255,255,0.1)' }
                        },
                        yaxis: { 
                            labels: {
                                style: {colors: '#94a3b8', fontWeight: 500, fontSize: '11px'}
                            },
                            axisBorder: { show: true, color: 'rgba(255,255,255,0.1)' },
                            axisTicks: { show: false }
                        },
                        colors: ['#334155', '#065f46', '#475569', '#1e293b', '#64748b'],
                        stroke: { 
                            show: true, 
                            width: (type || 'bar') === 'bar' ? 0 : 2, 
                            curve: 'straight',
                            lineCap: 'square'
                        },
                        fill: {
                            type: 'solid',
                            opacity: (type || 'bar') === 'area' ? 0.2 : 0.9
                        },
                        dataLabels: { enabled: false },
                        tooltip: { theme: 'dark' }
                    };
                    const chart = new ApexCharts(el, options);
                    chart.render();
                    return chart;
                },

                renderPreview(el, type, series, categories) {
                    if (!el) return;
                    const options = {
                        series: JSON.parse(JSON.stringify(series || [])),
                        chart: { type: type || 'bar', height: 300, animations: {enabled: false}, toolbar: {show: false}, background: 'transparent' },
                        theme: { mode: 'dark' },
                        xaxis: { categories: JSON.parse(JSON.stringify(categories || [])), labels: {style: {colors: '#888'}} },
                        colors: ['#10b981'],
                        plotOptions: { bar: { borderRadius: 4 } }
                    };
                    const chart = new ApexCharts(el, options);
                    chart.render();
                    return chart;
                },

                safeParse(val, fallback) {
                    try {
                        return JSON.parse(val);
                    } catch (e) {
                        console.error('JSON Parse Error:', e);
                        return fallback;
                    }
                },
                
                async save(id, data) {
                    try {
                        const response = await fetch(`/admin/content/${id}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(data)
                        });
                        if (response.ok) {
                            Alpine.store('admin').setUnsaved(false);
                            alert('Section updated successfully!');
                            window.location.reload();
                        }
                    } catch (e) {
                        alert('Error saving data');
                    }
                },

                async deleteSection(id) {
                    if (!confirm('Are you sure you want to delete this section? This cannot be undone.')) return;
                    
                    try {
                        const response = await fetch(`/admin/content/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        if (response.ok) {
                            Alpine.store('admin').setUnsaved(false);
                            window.location.reload();
                        }
                    } catch (e) {
                        alert('Error deleting section');
                    }
                },

                async confirmDeleteYear(year) {
                    if (!confirm(`CRITICAL WARNING: This will delete ALL data for the year range ${year}. This action is permanent. Are you sure?`)) return;
                    
                    try {
                        const response = await fetch(`/admin/year/${year}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        if (response.ok) {
                            Alpine.store('admin').setUnsaved(false);
                            window.location.href = '/admin';
                        }
                    } catch (e) {
                        alert('Error deleting year');
                    }
                },

                async createYear() {
                    if (!this.newYear) return;
                    
                    if (this.duplicateFromCurrent) {
                        try {
                            const response = await fetch('/admin/year/duplicate', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    source_year: this.selectedYear,
                                    target_year: this.newYear
                                })
                            });
                            if (response.ok) {
                                Alpine.store('admin').setUnsaved(false);
                                window.location.href = `?year=${this.newYear}`;
                            } else {
                                const data = await response.json();
                                alert(data.message || 'Error duplicating year');
                            }
                        } catch (e) {
                            alert('Error duplicating year');
                        }
                    } else {
                        // To simplify, we just redirect and let the "empty skeleton" logic handle the first section creation
                        Alpine.store('admin').setUnsaved(false);
                        window.location.href = `?year=${this.newYear}`;
                    }
                },

                async addSection(type) {
                    const titles = {
                        hero: 'New Hero Section',
                        stats_grid: 'New Stats Overview',
                        grid: 'New Industry Focus',
                        chart: 'New Performance Chart',
                        list: 'New Strategy List'
                    };
                    
                    const defaultContent = {
                        hero: { description: 'Edit this description', highlight_stats: [{label: 'Stat 1', value: '100%'}] },
                        stats_grid: { stats: [{label: 'Stat 1', value: 'Value 1'}] },
                        grid: { items: [{name: 'Feature Name', details: 'Detailed description goes here'}] },
                        chart: { chart_type: 'bar', series: [{name: 'Data', data: [10, 20, 30]}], categories: ['A', 'B', 'C'] },
                        list: { items: ['Item 1', 'Item 2'] },
                        marquee: { items: ['FIRM 1', 'FIRM 2', 'FIRM 3'] },
                        cta: { title: 'Ready to Lead?', description: 'Join over 85,000 thriving businesses.' },
                        metadata: { site_title: 'Western Visayas: Investment Profile', browser_tab_title: 'WV Region 6 Profile', logo_text: 'DTI Region 6' }
                    };

                    try {
                        const response = await fetch('/admin/content', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                year_range: this.selectedYear,
                                type: type,
                                section_title: titles[type],
                                content: defaultContent[type],
                                page_number: 100 // High number for new sections
                            })
                        });
                        if (response.ok) {
                            Alpine.store('admin').setUnsaved(false);
                            window.location.reload();
                        }
                    } catch (e) {
                        alert('Error creating section');
                    }
                },

                async deleteInquiry(id) {
                    if (!confirm('Delete this inquiry record?')) return;
                    try {
                        const response = await fetch(`/admin/inquiry/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.ok) {
                            Alpine.store('admin').setUnsaved(false);
                            window.location.reload();
                        }
                    } catch (e) { alert('Error deleting inquiry'); }
                }
            }
        }
    </script>
</body>
</html>
