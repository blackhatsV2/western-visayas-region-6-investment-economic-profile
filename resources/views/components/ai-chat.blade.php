<div x-data="aiChat()" class="fixed bottom-6 right-6 z-[999]">
    <!-- Chat Button -->
    <button @click="toggleChat"
            class="flex items-center justify-center w-14 h-14 bg-emerald-500 hover:bg-emerald-400 text-black rounded-full shadow-xl transition-transform hover:scale-105"
            :class="isOpen ? 'scale-0 opacity-0' : 'scale-100 opacity-100'">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300 transform origin-bottom-right"
         x-transition:enter-start="opacity-0 scale-50 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform origin-bottom-right"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-50 translate-y-4"
         class="absolute bottom-0 right-0 w-[90vw] sm:w-[380px] bg-[#0f0f11] border border-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col h-[500px] max-h-[85vh] z-[1000]"
         style="display: none; transform-origin: calc(100% - 28px) calc(100% - 28px);">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#141416] to-[#1c1c1f] p-4 flex justify-between items-center border-b border-gray-800 relative">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-500/10 flex items-center justify-center border border-emerald-500/30 text-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wide">WV Invest AI</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        <p class="text-[11px] font-medium text-emerald-500 uppercase tracking-widest">Online</p>
                    </div>
                </div>
            </div>
            <button @click="toggleChat" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-5" id="ai-chat-messages">
            <!-- Intro message -->
            <div class="flex justify-start">
                <div class="bg-[#1e1e22] text-gray-300 border border-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%] text-sm shadow-sm leading-relaxed">
                    Hello! I'm the Region 6 AI Assistant. I can answer questions about the investment and economic profile using only the data found in this project.<br><br>What would you like to know?
                </div>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.role === 'user' ? 'bg-emerald-600 text-white rounded-2xl rounded-tr-sm' : 'bg-[#1e1e22] text-gray-200 border border-gray-800 rounded-2xl rounded-tl-sm'"
                         class="px-4 py-3 max-w-[85%] text-sm shadow-sm whitespace-pre-wrap leading-relaxed" x-text="msg.content">
                    </div>
                </div>
            </template>
            
            <div x-show="isLoading" class="flex justify-start">
                <div class="bg-[#1e1e22] border border-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 min-w-[60px] flex items-center justify-center gap-1.5 h-[46px]">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/60 animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/60 animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/60 animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-[#111113] border-t border-gray-800 relative z-10 w-full">
            <form @submit.prevent="sendMessage" class="relative flex items-center">
                <input type="text" x-model="question" placeholder="Ask about Region 6..." 
                       class="w-full bg-[#18181b] text-white border border-gray-800/80 rounded-xl py-3.5 pl-4 pr-12 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30 text-[13px] placeholder-gray-600 transition-all font-medium"
                       :disabled="isLoading"
                       autocomplete="off">
                <button type="submit" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-[34px] h-[34px] flex items-center justify-center rounded-lg bg-emerald-500 hover:bg-emerald-400 text-[#000000] transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_0_10px_rgba(16,185,129,0.3)]"
                        :disabled="isLoading || !question.trim()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px] transform -rotate-45 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
            <div class="w-full flex justify-center mt-2 pb-0.5">
                <div class="px-2 py-0.5 bg-yellow-500/10 rounded border border-yellow-500/20 inline-flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div>
                    <span class="text-[9.5px] font-bold text-yellow-500/90 uppercase tracking-widest">Strictly limited to site content</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiChat', () => ({
            isOpen: false,
            question: '',
            isLoading: false,
            messages: [],
            
            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    setTimeout(() => { this.scrollToBottom(); }, 50);
                    // Prevent body scroll when chat is open on mobile
                    if (window.innerWidth < 640) {
                        document.body.classList.toggle('overflow-hidden', true);
                    }
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            },
            
            scrollToBottom() {
                const container = document.getElementById('ai-chat-messages');
                if (container) {
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            },
            
            async sendMessage() {
                if (!this.question.trim() || this.isLoading) return;
                
                const q = this.question.trim();
                this.messages.push({ role: 'user', content: q });
                this.question = '';
                this.isLoading = true;
                
                this.$nextTick(() => this.scrollToBottom());
                
                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ question: q })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        this.messages.push({ role: 'ai', content: data.answer });
                    } else {
                        // Handle rate limit error 429
                        if (response.status === 429) {
                            this.messages.push({ role: 'ai', content: "🛑 I'm receiving too many questions right now to keep the free API quota safe. Please try again in exactly one minute!" });
                        } else {
                            this.messages.push({ role: 'ai', content: data.error || "An error occurred while connecting to the AI service." });
                        }
                    }
                } catch (e) {
                    this.messages.push({ role: 'ai', content: "Failed to connect to the server. Please check your network." });
                } finally {
                    this.isLoading = false;
                    this.$nextTick(() => this.scrollToBottom());
                }
            }
        }));
    });
</script>
