<div x-data="welcomeTyper()" x-init="init()" class="h-20 md:h-32">
    <div class="relative">
        <p class="italic text-3xl sm:text-2xl" id="welcome-running-line"></p>
    </div>
</div>

@push('scripts')
    <script>
        function welcomeTyper() {
            return {
                textArray: [
                    "Хорошо унаследовать библиотеку, а еще лучше собрать свою собственную.",
                    "Что разум человека может постигнуть и во что он может поверить, того он способен достичь.",
                    "Книги – это и самолет, и поезд, и дорога. Они и пункт назначения, и путешествие."
                ],

                async init() {
                    await this.loadTypewriter();
                    this.startTypewriter();
                },

                loadTypewriter() {
                    return new Promise((resolve) => {
                        if (window.Typewriter) {
                            resolve();
                            return;
                        }

                        const script = document.createElement("script");
                        script.src = "/plugins/typewriter.js";
                        script.onload = () => resolve();
                        document.head.appendChild(script);
                    });
                },

                startTypewriter() {
                    new Typewriter('#welcome-running-line', {
                        strings: this.textArray,
                        autoStart: true,
                        cursor: '',
                        delay: 40,
                        loop: true,
                    });
                }
            }
        }
    </script>
@endpush
