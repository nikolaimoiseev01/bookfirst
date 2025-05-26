<div class="h-20">
    <div class="relative">
        <p class="italic text-3xl" id="welcome-running-line"></p>
{{--        <p class="italic text-3xl absolute top-0 left-0 text-green-50 z-0">Хорошо унаследовать библиотеку, а еще лучше собрать свою--}}
{{--            собственную.</p>--}}
    </div>


</div>

@push('page-js')
    <script>
        window.onload = function () {
            const app = document.getElementById('app');

            // Typerwrite text content. Use a pipe to indicate the start of the second line "|".
            var textArray = [
                "Хорошо унаследовать библиотеку, а еще лучше собрать свою собственную.",
                "Что разум человека может постигнуть и во что он может поверить, того он способен достичь.",
                "Книги – это и самолет, и поезд, и дорога. Они и пункт назначения, и путешествие."
            ];

            new Typewriter('#welcome-running-line', {
                strings: textArray,
                autoStart: true,
                cursor: '',
                delay: 40,
                loop: true
            });
        }

    </script>
@endpush
