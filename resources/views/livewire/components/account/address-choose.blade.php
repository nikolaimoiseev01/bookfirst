<div x-ref="deliveryComponent" x-data="{
    showCitySearch: false,
    foreignAddress: '',
    citySearch: @entangle('citySearch'),
    country: @entangle('country').live,
    addressJson: @entangle('addressJson').live,
    addressType: @entangle('addressType').live,
    updateAddressJson() {
        if (this.addressType === 'Заграницу') {
            this.addressJson = {
                string: this.foreignAddress,
                parsed_data: null
            }
        } else {
            this.addressType = 'СДЭК'
        }
    }
}"
     x-init="$watch('addressType', function(value) {
        if (value === 'Заграницу') {
            country = null
            addressJson = null
        } else {
            country = 'Россия'
            addressJson = null
            foreignAddress = null
        }
     })"
     class="flex flex-col">
    <div class="flex gap-2 ">
        <p>Доставка: </p>
        <x-ui.input.toggle model="addressType" :options="['СДЭК' => 'В Россию', 'Заграницу' => 'Заграницу']"/>
    </div>
    <div x-show="addressType == 'СДЭК'" x-cloak x-collapse.duration.400ms>
        <div class="pt-4 flex flex-col gap-4">
            <div class="flex flex-col relative">
                <input type="text" x-model="citySearch" @input="showCitySearch = true"
                       wire:model.live.debounce.1000ms="citySearch" placeholder="Поиск города">
                <div x-show="showCitySearch"
                     class="z-40 flex items-center justify-center bg-white border border-dark-300 rounded rounded-t-none border-b-none  absolute top-full left-0 p-2">
                    <div wire:loading.remove
                         class="flex flex-col max-h-80 min-w-2xs overflow-auto">
                        @forelse($cityResults ?? [] as $city)
                            <span @click="showCitySearch = false"
                                  onclick="updateCdekWidget({{$city['code']}}, '{{$city['city']}}')"
                                  class=" text-lg hover:bg-gray-200 cursor-pointer p-1 rounded">{{$city['city']}}, {{$city['region']}}</span>
                        @empty
                            <span class="text-lg hover:bg-gray-200 cursor-pointer p-1 rounded">Ничего не найдено</span>
                        @endforelse
                    </div>
                    <x-ui.spinner wire:loading class="w-8 h-auto"/>
                </div>

            </div>
            <style>
                #cdek-map > div > div + div {
                    height: 400px !important;
                }
            </style>

            <div wire:ignore id="cdek-map" class="w-full min-h-[400px] h-[400px] hidden"></div>

            <p x-show="addressType == 'СДЭК' && addressJson?.parsed_data" class="flex gap-2">
                <span class="font-medium text-nowrap">Выбранный адресс: </span>
                <span x-text="addressJson?.string"></span>
            </p>
        </div>
    </div>

    <div x-show="addressType == 'Заграницу'" x-cloak x-collapse.duration.400ms>
        <div class="pt-4 grid grid-cols-3 gap-4">
            <x-ui.input.text name="Страна" wire:model.live.debounce.500ms="country" class="col-span-1" label="Страна на латинице"/>
            <x-ui.input.text name="Адрес" x-model="foreignAddress" @input.debounce="updateAddressJson()" class="col-span-2"
                             label="Полный адрес на латинице, начиная с города"/>
        </div>
    </div>
</div>
@push('scripts')
    <script type="module">
            // храним глобально
            window.cdekWidget = null;

            function createCdek(city_code, city) {
                window.cdekWidget = new window.CDEKWidget({
                    from: {
                        country_code: 'RU',
                        city: 'Москва',
                    },
                    hideDeliveryOptions: {
                        door: true,
                    },
                    root: 'cdek-map',
                    apiKey: '{{config('services.yandex-maps-key')}}',
                    servicePath: "/cdek/service?city_code=" + city_code,
                    defaultLocation: city,
                    onChoose(type, tariff, address) {
                        console.log('chosen', type, tariff, address);
                        let alpine = Alpine.$data(document.querySelector('[x-ref=deliveryComponent]'));
                        alpine.country = 'Россия'
                        alpine.addressJson = {
                            string: address.city + ' (' + address.region + '), ' + address.address + ' (' + address.code + ')',
                            parsed_data: address
                        };

                    },
                });
            }

            window.updateCdekWidget = function updateCdekWidget(city_code, city) {
                $('#cdek-map').show()
                if (!window.cdekWidget) {
                    createCdek(city_code, city)
                } else {
                    window.cdekWidget.clearSelection(); //Метод отменят выбранный офис
                    window.cdekWidget.params.servicePath = '/cdek/service?city_code=' + city_code; // Меняем код города в параметрах, но это здесь это не обязательно
                    window.cdekWidget.cdekApi.servicePath = '/cdek/service?city_code=' + city_code; // в service.php код города передается из параметра объекта cdekApi
                    window.cdekWidget.params.defaultLocation = city; // Это Название города, нужно менять чтобы виджет знал куда сместить карту
                    window.cdekWidget.init(); // Повторно инициализируем виджет, который сделает новый запрос на получение списка офисов уже с новым городом
                    window.cdekWidget.updateLocation(); //метод меняет положение карты и восстанавливает зум
                }
            }
    </script>
@endpush
