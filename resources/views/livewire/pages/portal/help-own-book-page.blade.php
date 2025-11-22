<main class="flex-1 content mb-32">
    @section('title')
        Инструкции
    @endsection
    <h1 class="mb-8">Инструкции</h1>
    <x-portal.help-template :data="$data"/>
</main>
