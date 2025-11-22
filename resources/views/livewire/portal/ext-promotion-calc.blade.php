<div class="ext_promotion_calc_wrap portal_calc_wrap">
    <div class="calc-inputs">
        <div class="calc-row">
            <label for="pages"><p>Выберите сайт для продвижения</p></label>
            <select wire:model="site" id="site" name="site">
                <option value="stihi">stihi</option>
                <option value="proza">proza</option>
            </select>
        </div>

        <div class="calc-row">
            <p>Количество дней продвижения:</p>
            <x-input.range-slider model="days"/>
        </div>
        <div class="check-block">
            <label for="promo"><p>У меня есть скидка в 20%:</p></label>
            <input wire:model="flg_discount" id="flg_discount" type="checkbox">
        </div>
    </div>

    <div class="calc-outputs">
        <div>
            <div class="price-total">
                <p class="price-desc">Итого:&nbsp;</p>
                <div id="total_price" class="price-number">{{$price_total}}</div>
                <p class="price-desc rub">&nbsp;руб.</p>
            </div>
            @if($ext_discount > 0)
                <p style="color: darkgrey;"><i>За такое кол-во дней есть скидка: {{$ext_discount}}%</i></p>
            @endif
        </div>
    </div>
</div>



