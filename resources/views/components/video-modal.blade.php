<x-ui.modal name="videoModal">
    <div class="flex flex-col gap-4 p-4">
        <video  x-init="$el.volume = 0.3" id="modalVideo" class="w-full aspect-video" controls>
            <source src="/fixed/welcome_video.mp4" type="video/mp4">
            <source src="movie.ogg" type="video/ogg">
            Your browser does not support the video tag.
        </video>
    </div>
</x-ui.modal>
