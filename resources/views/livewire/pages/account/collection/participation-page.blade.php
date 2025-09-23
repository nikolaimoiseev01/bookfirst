<div class="mb-16 max-w-5xl">
    @section('title')
        Мое участие в сборнике {{$participation->collection['title']}}
    @endsection
    <livewire:components.account.collection.survey-participation :participation="$participation"/>
</div>
