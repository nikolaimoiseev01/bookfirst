<div class="choose_delivery_block_wrap" x-data="{ rus_type: 'post', country: 'rus'  }">
    <style>

        #choose_post_modal .modal-container {
            width: 90%;
            height: 90%;
            max-width: 1500px;
            max-height: 800px;
            margin: auto;
        }

        #choose_post_modal #ecom-widget {
            height: 100%;
            width: 100%;
        }

        #courierIframe {
            height: 360px;
        }

        .delivery_country_wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .post_office_chosen_wrap {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: flex-start;

        }

        #chosen_post_text {
            display: none;
        }

        #cdek-map {
            height: 100%;
        }

        .problem {
            font-size: 20px;
            color: #fc9797;
        }

        .link {
            line-height: inherit;
        }
    </style>

    <div x-show="country == 'rus'">
        <div class="post_office_chosen_wrap">
            <p id="chosen_post_text"></p>
            <a data-modal="choose_post_modal" id="choose_post_link" class="modal-from link">Выбрать отделение</a>
            <a href="{{route('chat_create', 'Не могу выбрать нужный адрес')}}" target="_blank" class="link problem">
                <i>Не могу выбрать нужный адрес</i></a>
        </div>

        <div id="choose_post_modal" class="modal">
            <div class="modal-wrap">
                <div class="modal-container">
                    <div id="cdek-map"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@cdek-it/widget@3" charset="utf-8"></script>
    <script type="text/javascript">
        back_address = []
        document.addEventListener('DOMContentLoaded', () =>
            new window.CDEKWidget({
                from: 'Новосибирск',
                root: 'cdek-map',
                apiKey: 'f4e034c2-8c37-4168-8b97-99b6b3b268d7',
                servicePath: 'https://pervajakniga.ru/service.php',
                defaultLocation: 'Новосибирск',
                hideDeliveryOptions: {
                    office: false,
                    door: true,
                },
                onChoose(type, tariff, address) {
                    console.log('chosen', type, tariff, address);
                    back_address.type = 'CDEK RUS'
                    back_address.data = address
                    back_address.unrestricted_value = address['address']
                    @this.address = back_address
                },
            }));
    </script>
@endpush
