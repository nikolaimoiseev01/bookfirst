<div x-data="{ open: false }" style="max-width: fit-content">
    <table id="participants_table" class="table table-bordered table-hover">
        <thead>
        <tr style="background: white">
            <th scope="col" style="text-align: center;">Ждут перевода исполнителю</th>
            <th scope="col" style="text-align: center;">Уже переведено исполнителю</th>
        </tr>
        </thead>
        <tbody>
        {{App::setLocale('ru')}}
        <tr style="">
            <td data-label="Ждут перевода исполнителю" class="p-1" style="text-align: center;">
                <h4>{{$ext_promotions_to_pay_sum}} руб.</h4>
            </td>
            <td scope="row" data-label="Уже переведено исполнителю" class="p-1" style="text-align: center;">
                <h4>{{$payments->sum('amount')}} руб.</h4>
            </td>
        </tr>
        </tbody>
    </table>
    <div style="gap: 20px;" class="d-flex align-items-center">

        <a wire:click="make_payment" class="ml-3 btn btn-outline-info">
            Сделать перевод
        </a>
    </div>

    <div style="display: none;" id="payments_table">
        <table id="participants_table" class="table mt-3 table-bordered table-hover">
            <thead>
            <tr style="background: white">
                <th scope="col" style="text-align: center;">Когда</th>
                <th scope="col" style="text-align: center;">За какие ID?</th>
                <th scope="col" style="text-align: center;">Сумма</th>
            </tr>
            </thead>
            <tbody>
            {{App::setLocale('ru')}}
            @foreach($payments as $payment)
                <tr style="">
                    <td data-label="Когда" class="p-1" style="text-align: center;">
                        <p>{{ Date::parse($payment['created_at'])->addHours(3)->format('j F H:i') }}</p>
                    </td>
                    <td scope="row" data-label="За какие ID?" class="p-1" style="text-align: center;">
                        <p>{{$payment['paid_for']}}</p>
                    </td>
                    <td scope="row" data-label="Сумма" class="p-1" style="text-align: center;">
                        <p>{{$payment['amount']}} руб.</p>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    @push('page-js')

    @endpush

</div>
