<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN - Admin Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('dti-logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        body { background-color: #000000; color: #FFFFFF; }
        .glass-card {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2.5rem;
        }
    </style>
</head>
<body class="antialiased font-sans min-h-screen flex items-center justify-center p-6">
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-arbitra-emerald/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-arbitra-emerald/5 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10 scale-in animate-fade-in">
        <div class="text-center mb-12">
            <img src="{{ asset('dti-logo.png') }}" class="h-12 w-auto mx-auto mb-6" alt="Logo">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter">Admin Portal</h1>
            <p class="text-arbitra-gray text-xs font-bold uppercase tracking-widest mt-2">Authentication Required</p>
        </div>

        <div class="glass-card p-10 shadow-2xl border border-white/10">
            <form action="{{ url()->current() }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase text-arbitra-emerald tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:ring-2 focus:ring-arbitra-emerald focus:border-transparent outline-none transition-all text-sm font-medium">
                    @error('email')
                        <p class="text-red-500 text-[10px] font-bold mt-2 uppercase italic">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-[10px] font-black uppercase text-arbitra-emerald tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 focus:ring-2 focus:ring-arbitra-emerald focus:border-transparent outline-none transition-all text-sm font-medium pr-12">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-arbitra-gray hover:text-white transition-all">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-bold mt-2 uppercase italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-arbitra-emerald text-arbitra-black py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                        Login to Dashboard
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center mt-8">
            <a href="/" class="text-arbitra-gray hover:text-white text-[10px] font-black uppercase tracking-widest transition-all">← Back to Site</a>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
