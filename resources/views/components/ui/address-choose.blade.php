<div>
    <div id="cdek-map" style="width:800px;height:600px"></div>
</div>
@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@cdek-it/widget@3" charset="utf-8"></script>
    <script type="text/javascript">
        back_address = []
        let cityCode = 270;
        let defaultLocation = "Новосибирск";
        document.addEventListener('DOMContentLoaded', () =>
            new window.CDEKWidget({
                from: 'Новосибирск',
                root: 'cdek-map',
                apiKey: 'f4e034c2-8c37-4168-8b97-99b6b3b268d7',
                servicePath: 'http://localhost:8000/service.php?city_code=' + cityCode,
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

        fetch('/service.php?action=cities&country_codes=RU')
            .then(res => res.json())
            .then(data => {
                console.log('Всего городов:', data.length);
                console.log(data.slice(0, 10)); // первые 10 для примера
            });
    </script>
@endpush
