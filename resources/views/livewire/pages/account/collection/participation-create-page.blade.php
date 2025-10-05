<div>
    @section('title')
        Новая заявка в сборник {{$collection['title']}}
    @endsection
    <livewire:components.account.collection.participation-form :collection="$collection" form-type="create"/>
</div>
