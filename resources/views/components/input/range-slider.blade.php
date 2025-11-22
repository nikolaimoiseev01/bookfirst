<div class="range_slider_wrap">
    <input wire:model="{{$model}}" type="number" id="input_test"
           min="1" max="100"
           data-range-slider="test">
    <input  wire:model="{{$model}}" type="range" id="range_test" data-range-slider="test"
           min="1" max="100" value="0"
           class="slider">

    @push('page-js')
        <script>
            $('input').on('input', function () {
                id = $(this).attr('data-range-slider')
                cur_val = $(this).val()
                $('#input_' + id).val(cur_val)
                $('#range_' + id).val(cur_val)
            })
        </script>
    @endpush
</div>
