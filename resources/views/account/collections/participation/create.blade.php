@extends('layouts.app')

@section('page-title')
    <div class="account-header">
        <h1>Новая заявка в сборник {{$collection['title']}}</h1>
    </div>
@endsection

@section('page-tab-title')
    Создание заявки
@endsection

@section('content')

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Упс</strong> Что-то пошло не так:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @livewire('account.collection-participation.coll-application',
    ['collection_id' => $collection->id,
    'type'=>'create',
    'part_id' => null
    ])
@endsection

@push('page-js')

    <script>
        function make_session() {
            {{Session(['back_after_add' => \Livewire\str(Request::url())])}}
        }

    </script>

@endpush
