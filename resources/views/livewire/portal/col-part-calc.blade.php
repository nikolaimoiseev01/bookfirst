<div class="portal_calc_wrap">
    <div class="calc-inputs">
        <div class="calc-row">
            <label for="pages"><p>Выберите количетсво страниц (максимум 28)</p></label>
            <select wire:model="pages" id="pages" name="pages">
                <option value="7">1-7</option>
                <option value="14">8-14</option>
                <option value="21">15-21</option>
                <option value="28">22-28</option>
            </select>
        </div>

        <div class="calc-row">
            <p>Мне также необходимо экземпляров:</p><input wire:model="prints" min="0" value="1" id="print_needed_input"
                                                           class="number-input" type="number">
        </div>

        <div class="calc-row no-wrap">
            <div class="check-block">
                <label for="text-check">
                    <p>
                       <span
                           class="question-mark tooltip"
                           title="Включает в себя проверку орфографии и пунктуации текста.">
                            <svg id="question-circle" data-name="Capa 1"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 40.12 40.12">
                                <path
                                    d="M19.94,12.14c1.85,0,3,1,3,2.66,0,3-5.41,3.87-5.41,7.55a2,2,0,0,0,2,2.07c2.05,0,1.8-1.51,2.54-2.6,1-1.45,5.6-3,5.6-7,0-4.36-3.89-6.19-7.86-6.19-3.77,0-7.24,2.69-7.24,5.73a1.85,1.85,0,0,0,2,1.88C17.52,16.23,16,12.14,19.94,12.14Z"/>
                                <path
                                    d="M22.14,29a2.54,2.54,0,1,0-2.54,2.54A2.55,2.55,0,0,0,22.14,29Z"/>
                                <path
                                    d="M40.12,20.06A20.06,20.06,0,1,0,20.06,40.12,20.08,20.08,0,0,0,40.12,20.06ZM2,20.06A18.06,18.06,0,1,1,20.06,38.12,18.08,18.08,0,0,1,2,20.06Z"/>
                            </svg>
                        </span>
                        Мне нужна проверка:
                    </p>
                </label>
                <x-question-mark>
                    Услуга проверки пунктуации и орфографии
                </x-question-mark>
                <input wire:model="need_check" id="text-check" type="checkbox">
            </div>
            <div class="check-block">
                <label for="promo"><p>У меня есть скидка в 20%:</p></label>
                <input  wire:model="flg_promo" id="promo" type="checkbox">
            </div>
        </div>
    </div>

    <div class="calc-outputs">
        <div>
            <div class="prices-seperate">
                <div class="participation-price">
                    <div id="part_price" class="price-number">{{$price_part}}</div>
                    <p class="price-desc">Участие</p>
                </div>
                <div class="participation-price">
                    <p class="participation-price-plus price-desc">+</p>
                </div>
                <div class="participation-price">
                    <div id="print_price" class="price-number">{{$price_print}}</div>
                    <p class="price-desc">Печать (<span id="print_needed">{{$prints}}</span> экз.)</p>
                </div>

                <div id="text_check_plus" class="@if($price_check) active @else disable @endif participation-price">
                    <p class="participation-price-plus price-desc">+</p>
                </div>

                <div id="text_check_wrap" class="@if($price_check) active @else disable @endif participation-price">
                    <div id="text_check" class="price-number">{{$price_check}}</div>
                    <p class="price-desc">Проверка </p>
                </div>


            </div>
            <div class="price-total">
                <p class="price-desc">Итого:&nbsp;</p>
                <div id="total_price" class="price-number">{{$price_total}}</div>
                <p class="price-desc rub">&nbsp;руб.</p>
            </div>
        </div>
    </div>
</div>
