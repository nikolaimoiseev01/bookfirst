<div {{$attributes}} wire:ignore>
            <span class="cus-dropdown">
                @if($alltext && !$default)
                    <input type="radio"
                           name="{{$model}}"
                           value="0"
                           id="{{$model}}_0"
                           checked="checked"
                    >
                    <label for="{{$model}}_0">{{$alltext}}</label>
                @endif

                @foreach($options as $option)
                    <input type="radio"
                           value="{{$option['id']}}"
                           name="{{$model}}"
                           @if($default == $option['id'])
                           checked="checked"
                           @endif
                           id="{{$model}}_{{$option['id']}}">
                    <label for="{{$model}}_{{$option['id']}}" data-model="{{$model}}">{{$option['name']}}</label>
                @endforeach
            </span>

    @push('page-js')
        <script>
            console.log({{$default}})
            $('.cus-dropdown input').change(function () {

            @this.set($(this).attr('name'), $(this).val());
            })
        </script>
    @endpush
</div>
