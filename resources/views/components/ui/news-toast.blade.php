{{-- resources/views/components/ui/referral-toast.blade.php --}}
@props([
    // ключ в sessionStorage
    'storageKey' => 'referral_toast_closed_v1',
    // задержка перед показом (мс)
    'delay' => 2500,
    'buttonText' => 'Подробнее'
])

<div
    x-data="referralToast({
        key: @js($storageKey),
        delay: @js($delay),
    })"
    x-init="init()"
    x-show="open"
    x-transition:enter="transition ease-[cubic-bezier(.16,1,.3,1)] duration-50"
    x-transition:enter-start="opacity-0 translate-y-10 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-6 scale-95"
    class="fixed bottom-4 left-4 right-4 max-w-3xl z-[9999] lg:!mx-auto"
    style="display: none;"
    role="status"
    aria-live="polite"
>


    <div
        class="
    relative overflow-hidden rounded-2xl
    bg-white/95 backdrop-blur
    border border-black/10
    p-5
    animate-shadow-pulse
"
    >

        <!-- close -->
        {{--        <button--}}
        {{--            type="button"--}}
        {{--            @click="close()"--}}
        {{--            class="absolute top-3 right-3 inline-flex h-9 w-9 items-center justify-center rounded-full--}}
        {{--                   text-gray-500 hover:text-gray-800 hover:bg-black/5 transition--}}
        {{--                   focus:outline-none focus:ring-2 focus:ring-green-500/40"--}}
        {{--            aria-label="Закрыть"--}}
        {{--        >--}}
        {{--            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2">--}}
        {{--                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />--}}
        {{--            </svg>--}}
        {{--        </button>--}}

        <div class="relative flex flex-col gap-4">
            <div>
                {{$slot}}
            </div>
            {{-- опционально: кнопка/ссылка --}}
            <div class="flex items-center gap-3 md:flex-col">
                <x-ui.link href="{{ $attributes->get('href', '#') }}" data-check-logged
                           class="!text-lg !py-0">{{$buttonText}}</x-ui.link>
                <button
                    type="button"
                    @click="close()"
                    class="text-base text-gray-600 hover:text-gray-900 transition"
                >
                    Закрыть и больше не показывать
                </button>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('referralToast', ({key, delay}) => ({
                open: false,
                timer: null,

                init() {
                    try {
                        if (sessionStorage.getItem(key) === '1') return;
                    } catch (e) {
                    }

                    this.timer = setTimeout(() => {
                        // повторная проверка на всякий случай
                        try {
                            if (sessionStorage.getItem(key) === '1') return;
                        } catch (e) {
                        }
                        this.open = true;
                    }, Number(delay) || 5000);
                },

                close() {
                    this.open = false;
                    if (this.timer) clearTimeout(this.timer);

                    try {
                        sessionStorage.setItem(key, '1');
                    } catch (e) {
                    }
                },
            }));
        });
    </script>
</div>

<style>
    @keyframes shadowPulse {
        0%, 100% {
            box-shadow: 0 0 8px rgba(71, 175, 152, 0.35),
            0 0 0 rgba(71, 175, 152, 0);
        }
        50% {
            box-shadow: 0 0 25px rgba(71, 175, 152, 0.55),
            0 0 15px rgba(71, 175, 152, 0.45);
        }
    }

    .animate-shadow-pulse {
        animation: shadowPulse 3s ease-in-out infinite;
    }
</style>
