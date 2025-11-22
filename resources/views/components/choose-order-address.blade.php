<div class="choose_delivery_block_wrap" x-data="{ country: @entangle('delivery_country') }">
    <div class="delivery_country_wrap">
        <p>Доставка</p>
        <div class="switch-wrap">
            <input wire:model='delivery_country' checked type="radio" value="rus" id="rus"
                   name="delivery_country">
            <label @click="country = 'rus'" for="rus">В Россиию</label>
            <input wire:model='delivery_country' type="radio" id="foreign" value="foreign"
                   name="delivery_country">
            <label @click="country = 'foreign'" for="foreign">В другие страны</label>
        </div>
        <x-question-mark>
            Посылка будет отправлена наложенным платежом. Он в точности равен фактической стоимости доставки.
        </x-question-mark>
    </div>

    <div x-show="country == 'rus'" class="input-group">
        <p>Введите адрес (выберите из подсказки после ввода)</p>
        <input wire:ignore.self id="address" name="address" value="{{$address}}" type="text"/>
    </div>


    <div x-show="country == 'foreign'">
        <div class="participation-inputs-row inputs_row">
            <div class="input-group">
                <p>Страна*</p>
                <input wire:model="send_to_country" type="text">
            </div>
            <div class="input-group">
                <p>Город*</p>
                <input wire:model="send_to_city" type="text">
            </div>
        </div>
        <div class="participation-inputs-row inputs_row">
            <div style="margin-bottom: 0;" class="input-group">
                <p>Адрес*</p>
                <input wire:model="send_to_address" type="text">
            </div>
            <div style="margin-bottom: 0;" class="input-group">
                <p>Индекс*</p>
                <input wire:model="send_to_index" type="text">
            </div>
        </div>
    </div>
    <a href="{{route('chat_create','Проблемы с заплонением адреса')}}" target="_blank" style="color: #fc9797; font-size: 18px;" class="danger link"><i>
            Нужна помощь с заполнением адреса</i></a>
</div>

@push('page-js')
    <link href="http://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/css/suggestions.min.css" rel="stylesheet"/>
    <script src="http://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/js/jquery.suggestions.min.js"></script>

    <script>
        $("#address").suggestions({
            token: "6f1cc48c848b34b0505b94efef460f04aebdc4cf",
            type: "ADDRESS",
            /* Вызывается, когда пользователь выбирает одну из подсказок */
            onSelect: function (suggestion) {
                suggestion.type = 'DaData RUS'
                    @this.address = suggestion
            }
        });
        $("#address").on('input', function (e) {

            val = $(this).val()
            if (val == "") {
                @this.
                address = null
            }
        });

        window.addEventListener('contentChanged', (e) => {
            alert(123);
        });
    </script>
@endpush

{{--<div class="choose_delivery_block_wrap" x-data="{ rus_type: 'post', country: 'rus'  }">--}}
{{--    <style>--}}

{{--        #choose_post_modal .modal-container {--}}
{{--            width: 90%;--}}
{{--            height: 90%;--}}
{{--            max-width: 1500px;--}}
{{--            max-height: 800px;--}}
{{--            margin: auto;--}}
{{--        }--}}

{{--        #choose_post_modal #ecom-widget {--}}
{{--            height: 100%;--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        #courierIframe {--}}
{{--            height: 360px;--}}
{{--        }--}}

{{--        .delivery_country_wrap {--}}
{{--            display: flex;--}}
{{--            flex-wrap: wrap;--}}
{{--            align-items: center;--}}
{{--            gap: 10px;--}}
{{--        }--}}

{{--        .post_office_chosen_wrap {--}}
{{--            display: flex;--}}
{{--            flex-direction: column;--}}
{{--            gap: 5px;--}}
{{--            align-items: flex-start;--}}

{{--        }--}}

{{--        #chosen_post_text {--}}
{{--            display: none;--}}
{{--        }--}}

{{--        .problem {--}}
{{--            font-size: 20px;--}}
{{--            color: #fc9797;--}}
{{--        }--}}

{{--        .link {--}}
{{--            line-height: inherit;--}}
{{--        }--}}
{{--    </style>--}}


{{--    <div x-show="country == 'rus'">--}}


{{--        <div class="post_office_chosen_wrap">--}}
{{--            <p id="chosen_post_text"></p>--}}
{{--            <a data-modal="choose_post_modal" id="choose_post_link" class="modal-from link">Выбрать отделение</a>--}}
{{--            <a href="{{route('chat_create', 'Не могу выбрать нужный адрес')}}" target="_blank" class="link problem">--}}
{{--                <i>Не могу выбрать нужный адрес</i></a>--}}
{{--        </div>--}}


{{--        <div id="choose_post_modal" class="modal">--}}
{{--            <div class="modal-wrap">--}}
{{--                <div class="modal-container">--}}
{{--                    <div id="ecom-widget"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


{{--    <script src="https://widget.pochta.ru/map/widget/widget.js"></script>--}}


{{--    <script>--}}
{{--        document.addEventListener('livewire:load', function () {--}}

{{--            function getPostAddressCalback(data) {--}}
{{--                document.body.click() // Закрываем модалку--}}
{{--                address = data['cityTo'] + ', ' + data['addressTo'] + ', ' + data['indexTo']--}}
{{--                $('#chosen_post_text').text('Выбранное отделение: ' + address)--}}
{{--                $('#chosen_post_text').show()--}}
{{--                $('#choose_post_link').text('Изменить')--}}
{{--                    @this.post_office_address = data--}}
{{--            }--}}

{{--            ecomStartWidget({--}}
{{--                id: 52061,--}}
{{--                callbackFunction: getPostAddressCalback,--}}
{{--                containerId: 'ecom-widget'--}}
{{--            });--}}


{{--        })--}}

{{--    </script>--}}


{{--</div>--}}
