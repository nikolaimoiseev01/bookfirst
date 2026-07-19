@php
    $htmlContent = $getState();
@endphp

<div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
    <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2 border-b border-gray-200 dark:border-gray-700">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Предпросмотр письма</span>
    </div>
    <div class="p-4 bg-white dark:bg-gray-900" style="min-height: 400px;">
        <div class="max-w-2xl mx-auto border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <iframe
                srcdoc="{{ $htmlContent }}"
                class="w-full"
                style="min-height: 500px; border: none;"
                sandbox="allow-same-origin"
            ></iframe>
        </div>
    </div>
</div>
