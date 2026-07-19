<div
    x-data
    x-init="$refs.preview.srcdoc = @js($html)"
    class="w-full"
>
    <iframe
        x-ref="preview"
        class="w-full h-[75vh] rounded-xl border border-gray-200 bg-white"
    ></iframe>
</div>
