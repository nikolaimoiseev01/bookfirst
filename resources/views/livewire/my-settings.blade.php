<div>
    <style>
        .input-group {
            flex-direction: row;
            align-items: center;
        }
    </style>

    <div class="input-group">
        <p>Имя:&nbsp</p>
        <p
            style="
            @if ($show_input === 1)
                display:none;
            @else
                display:inline;
            @endif
                "
            class="settings-text">{{$name}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="name" type="text"
               name="name" value="{{$name}}"
               id="name">
    </div>

    <div class="input-group">
        <p>Фамилия:&nbsp</p>
        <p             style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

            class="settings-text">{{$surname}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="surname" type="text"
               name="surname"
               id="surname">
    </div>

    <div class="input-group">
        <p>Псевдоним:&nbsp</p>
        <p             style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

            class="settings-text">{{$nickname}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            "class="inputs" wire:model="nickname" type="text"
               name="nickname"
               id="nickname">
    </div>

    <div class="input-group">
        <p>Email:&nbsp</p>
        <p             style="
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            "

            class="settings-text">{{$email}}</p>
        <input style="
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " class="inputs" wire:model="email" type="text"
               name="email"
               id="email">
    </div>


    <div class="setting-button">
        <a style="box-shadow: none;
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " wire:click.prevent="save()" class="header-button-wrap  button inputs">Сохранить</a>

        <a style="box-shadow: none;
        @if ($show_input === 1)
            display:inline;
        @else
            display:none;
        @endif
            " wire:click.prevent="show_0()" class="header-button-wrap  button inputs">Отменить</a>



        <a style="box-shadow: none;
        @if ($show_input === 1)
            display:none;
        @else
            display:inline;
        @endif
            " wire:click.prevent="show_1()" class="header-button-wrap  button password-reset">Редактировать</a>
        <a style="box-shadow: none; margin-left: 10px;" href="{{ route('password.request') }}" class="header-button-wrap  button password-reset">Восстановить пароль</a>

        <a style="box-shadow: none; margin-left: 10px;" class="button" href="{{ route('logout') }}"
           onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
            {{ __('Выйти') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </div>

    @section('page-js')
        {{--        <script>--}}
        {{--            $('#edit').click(function () {--}}
        {{--                $('.inputs').toggle();--}}
        {{--                $('.settings-text').toggle();--}}

        {{--                if($('.inputs').is(":visible")) {--}}
        {{--                    $('#edit').html('<i class="mr-2 fa fa-times"></i> Отменить');--}}
        {{--                }--}}
        {{--                else {--}}
        {{--                    $('#edit').html('Редактировать');--}}
        {{--                }--}}
        {{--            })--}}
        {{--        </script>--}}
    @endsection
</div>
