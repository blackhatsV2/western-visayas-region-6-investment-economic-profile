<div x-data="globalLoader()" 
     x-show="isLoading" 
     x-transition:enter="transition ease-out duration-300" 
     x-transition:enter-start="opacity-0 backdrop-blur-none" 
     x-transition:enter-end="opacity-100 backdrop-blur-md" 
     x-transition:leave="transition ease-in duration-300" 
     x-transition:leave-start="opacity-100 backdrop-blur-md" 
     x-transition:leave-end="opacity-0 backdrop-blur-none" 
     class="fixed inset-0 z-[9999] bg-arbitra-black/80 backdrop-blur-md flex items-center justify-center pointer-events-auto" 
     style="display: none;">
    <div class="flex flex-col items-center justify-center space-y-6 bg-arbitra-dark/90 p-8 rounded-3xl border border-white/10 shadow-[0_0_100px_rgba(16,185,129,0.15)] transform transition-transform duration-500 scale-100">
        <!-- Spinner -->
        <svg class="animate-spin h-16 w-16 text-arbitra-emerald drop-shadow-[0_0_15px_rgba(16,185,129,0.8)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span x-text="currentLoadingMessage" class="text-white font-bold text-sm tracking-widest uppercase animate-pulse">Processing...</span>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('globalLoader', () => ({
            isLoading: false,
            loadingMessages: [
                'Processing Request...',
                'Syncing with Database...',
                'Crunching the Numbers...',
                'Preparing your Data...',
                'Almost there...',
                'Just a moment...'
            ],
            currentLoadingMessage: 'Processing Request...',
            loadingInterval: null,
            init() {
                // Listen to page navigation
                window.addEventListener('beforeunload', () => {
                    this.startLoading();
                });

                // Listen to form submissions
                document.addEventListener('submit', (e) => {
                    if (!e.target.target || e.target.target !== '_blank') {
                        this.startLoading();
                    }
                });

                // Manual triggers for SPA-like AJAX requests
                window.addEventListener('show-global-loader', () => {
                    this.startLoading();
                });
                
                window.addEventListener('hide-global-loader', () => {
                    this.stopLoading();
                });
            },
            startLoading() {
                if (this.isLoading) return;
                this.isLoading = true;
                let msgIndex = 0;
                this.currentLoadingMessage = this.loadingMessages[0];
                this.loadingInterval = setInterval(() => {
                    msgIndex = (msgIndex + 1) % this.loadingMessages.length;
                    this.currentLoadingMessage = this.loadingMessages[msgIndex];
                }, 1800);
            },
            stopLoading() {
                this.isLoading = false;
                if (this.loadingInterval) {
                    clearInterval(this.loadingInterval);
                    this.loadingInterval = null;
                }
            }
        }));
    });
</script>
