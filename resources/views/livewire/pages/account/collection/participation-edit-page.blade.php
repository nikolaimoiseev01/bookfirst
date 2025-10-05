<div>
    @section('title')
        Редактирование заявки в сборник {{$participation->collection['title']}}
    @endsection
    <livewire:components.account.collection.participation-form :participation="$participation" form-type="edit"/>
</div>
