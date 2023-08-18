<div class="create_work_page_wrap">

    @section('page-style')
        <link rel="stylesheet" href="/css/cropjs.css" crossorigin="anonymous"/>
    @endsection


    <div id="crop_preview" class="modal">
        <div class="modal-wrap">
            <div class="modal-container">
                <h1 class="header">Выберите миниатюру</h1>
                <div class="images_wrap">
                    <img src="{{$filepreview}}" id="work_img_preview" alt="">
                    <div class="cropped_preview"></div>
                </div>
                <div class="buttons_wrap">
                    <a id="save_crop" class="button show_preloader_on_click">Сохранить</a>
                    <a id="cancel_crop" class="button grey">Отменить</a>
                </div>
            </div>
        </div>
    </div>

    <div class="form_wrap">
        <input wire:model="work_title"
               type="text"
               placeholder="Название">

        <textarea wire:model="work_text" type="text"
                  placeholder="Текст произведения"></textarea>

        <div class="options_wrap">

            <div class="selects_wrap">
                <x-input.dropdown class="custom_dropdown_wrap"
                                  model="work_type"
                                  :options="$work_types"
                                  alltext="Выберите тип"
                                  :default="$work_type"
                ></x-input.dropdown>


                <x-input.dropdown class="custom_dropdown_wrap"
                                  model="work_topic"
                                  :options="$work_topics"
                                  alltext="Выберите тему"
                                  :default="$work_topic"
                ></x-input.dropdown>
            </div>


            <x-input.image-upload-crop :cropped="$filepreview"/>

        </div>

        <a wire:click.prevent="storeWork" class="button save_work show_preloader_on_click">
            @if($form_type == 'create')
                {{$back_after_work_adding['button_text'] ?? 'Сохранить'}}
            @else
                Сохранить изменения
            @endif
        </a>
    </div>

    @push('page-js')
        <script src="/js/cropjs.js" crossorigin="anonymous"></script>
    @endpush

</div>



