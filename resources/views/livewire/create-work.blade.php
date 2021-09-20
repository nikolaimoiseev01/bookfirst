<div style="width:90%;">
    <form wire:submit.prevent="storeWork(Object.fromEntries(new FormData($event.target)))"
          method="post"
          enctype="multipart/form-data">
        <div class="add-work-form">
            @csrf
            <input wire:model="work_title" type="text" placeholder="Название" name="work_title" id="title">
            <textarea wire:model="work_text" id="poem-input" type="text" placeholder="Текст произведения"
                      name="work_text" id="text"></textarea>
        </div>
        <input wire:model="symbols" style="display: none" type="number" name="symbols" id="symbols">
        <input wire:model="rows" style="display: none" type="number" name="rows" id="rows">
        <input wire:model="pages" style="display: none" type="number" min="1" name="pages" id="pages">
        <button type="submit" class="button">Добавить</button>
    </form>
{{--    <a style="" id="back" href="{{Session('back_after_add')}}" class="fast-load">back_after_add: {{Session('back_after_add')}}</a>--}}
    @section('page-js')
        <script>
            document.addEventListener('livewire:load', function () {
                symbols = 0;
                pages = 1;
                $('#poem-input').bind('input propertychange', function () {
                    var symbol = $(this).val().split('');
                    symbols = $(this).val().length;
                    symbols_to_rows = 0;
                    rows = 1;
                    $.each(symbol, function () {

                        if (this == '\n') {
                            rows++;
                            symbols_to_rows = 0;
                        } else {
                            if (symbols_to_rows > 50) {
                                rows++;
                                symbols_to_rows = 0;
                            } else {
                                symbols_to_rows++
                            }
                        }
                        ;

                        pages = Math.ceil(rows / 33);

                        $('#rows').val(rows);
                    @this.set("rows", rows);
                        $('#symbols').val(symbols);
                    @this.set("symbols", symbols);
                        $('#pages').val(pages);
                    @this.set("pages", pages);
                        $('#symbols_to_rows').val(symbols_to_rows);
                    });
                });
            });
        </script>
    @endsection
</div>
