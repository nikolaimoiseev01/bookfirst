<div class="flex w-full">
    <textarea
        wire:model="{{$model}}"
        class="rounded-l-2xl w-full min-h-28 outline-none focus:outline-none ring-0 focus:ring-0 border border-r-0 border-green-500 focus:border-green-500 resize-none text-dark-600 dark:text-white bg-white dark:bg-dark_bg dark:border-gray-300 p-4"></textarea>
    <div
        class="flex flex-col justify-between rounded-r-2xl border border-l-0 border-green-500 dark:bg-dark_bg  dark:border-gray-300 p-4">
        <x-clarity-attachment-line id="attach-button"
                                   class="rotate-[-30deg] w-5 h-5 cursor-pointer fill-green-500 hover:fill-green-600 transition"/>
        <div
{{--            wire:loading.remove--}}
            id="send-button"
        >
            <x-bi-send wire:click="send()"
                       class="mt-auto w-5 h-5 cursor-pointer fill-green-500 hover:fill-green-600 transition"/>
        </div>

        <div
{{--            wire:loading--}}
    class="hidden"
            id="send-button__spinner"
        >
            <x-ui.spinner class="w-5 h-5 mt-auto"/>
        </div>
    </div>
</div>
<script>
    window.onload = function () {
        $('#attach-button').click(function () {
            $('#filepond-button').click();
        })
        $('#send-button').click(function () {
            $('#send-button__spinner').show()
            $('#send-button').hide()
        })
    }
</script>
