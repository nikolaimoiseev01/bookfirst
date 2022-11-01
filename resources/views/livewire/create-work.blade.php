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


        <div style="margin-top: 20px; margin-right: 20px;" class="input-file">
            <label for="work_file" class="custom-file-upload">
                Выбрать файл
            </label>
            <span wire:ignore="" id="label_work_file"><p></p></span>

            <input accept=".png, .jpg" style="display: none;" wire:model="file" name="work_file"
                   class="custom-file-input" id="work_file" type="file">
            <div style="margin: 0 20px; display: none;" wire:loading="" wire:target="file"><p style="font-size: 22px;">
                    Файл загружается</p></div>
        </div>

        <select wire:model="work_type" name="work_type" id="work_type">
            <option value="" disabled selected="selected">Выберите тип</option>
            @foreach($work_types->unique('type') as $work_type)
                <option value="{{$work_type['type']}}">{{$work_type['type']}}</option>
            @endforeach
        </select>

        <select wire:model="work_topic" name="work_topic" id="work_topic">
            <option value="" disabled selected="selected">Выберите тему</option>
            @foreach($work_topics as $work_topic)
                <option value="{{$work_topic['topic']}}">{{$work_topic['topic']}}</option>
            @endforeach
        </select>

        <button type="submit" class="button">Добавить</button>

    </form>
    <img src="{{$file_preview}}" id="work_img_preview" style="max-width: 300px;" alt="">

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
