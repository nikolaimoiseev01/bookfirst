<main class="flex-1 mb-16">
    @section('title')
        Произведения
    @endsection
    <div class="content">
        <div class="flex flex-col max-w-5xl">
            <div class="flex justify-between items-center">
                <h2 class="text-5xl font-medium mb-4">Лента произведений</h2>
                <span class="italic text-2xl text-dark-350 font-light">{{$totalWorksCount}} шт.</span>
            </div>
            <livewire:components.social.work-feed/>
        </div>
    </div>
</main>
