<x-ui.modal name="participationAddressVideoModal" maxWidth="lg">
    <div class="relative flex flex-col gap-4 p-4">
        <button type="button"
                class="absolute top-2 right-3 text-3xl text-dark-350 hover:text-dark-600"
                @click="$dispatch('close-modal', 'participationAddressVideoModal')"
                aria-label="Закрыть">&times;</button>

        <h3 class="pr-8 text-2xl font-medium">Как заполнять адрес</h3>

        <video id="modalVideo" class="w-full aspect-video" controls preload="metadata">
            <source src="{{asset('fixed/help_media/participation-video.mp4')}}" type="video/mp4">
            Ваш браузер не поддерживает воспроизведение видео.
        </video>
    </div>
</x-ui.modal>
