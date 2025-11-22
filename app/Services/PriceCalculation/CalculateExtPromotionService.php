<?php


namespace App\Services\PriceCalculation;

class CalculateExtPromotionService
{

    public $site;
    public $days;
    public $discount;


    private const OUR_INTEREST = 0.5; /* Сколько цен заказчика забираем себе */
    private const EXECUTOR_SHARE_FROM_OUR_PRICE = 0.5; /* Сколько от нашей маржи забирает заказчик плюсом к своей стоимости */

    public function __construct($site, $days, $discount)
    {
        $this->site = $site;
        $this->days = intval($days);
        $this->discount = $discount;
    }


    public function calculate()
    {

        // Скидка от заказчика за дни
        if ($this->days < 5) {
            $extDiscount = 1;
        } elseif ($this->days < 10) {
            $extDiscount = 0.9;
        } elseif ($this->days < 15) {
            $extDiscount = 0.8;
        } elseif ($this->days < 30) {
            $extDiscount = 0.7;
        } elseif ($this->days < 60) {
            $extDiscount = 0.6;
        } else {
            $extDiscount = 0.5;
        }

        // Считаем стоимость продвижения
        if ($this->site === 'stihi' or $this->site === 'proza') {
            $basePrice = 50;
        } elseif ($this->site === 'chitalnya' or $this->site === 'poembook') {
            $basePrice = 40;
        } elseif ($this->site === 'neizvestniy-geniy' or $this->site === 'fabulae' or $this->site === 'yapishu') {
            $basePrice = 30;
        } else {
            $basePrice = 50;
        }

        $priceExecutor = ceil($basePrice * $extDiscount * $this->days);
        $priceOur = ceil($priceExecutor * self::OUR_INTEREST);
        $priceTotal = ceil(($priceExecutor + $priceOur) * ((100 - $this->discount) / 100));

        $priceExecutorNew = round($priceExecutor + (($priceOur - $priceExecutor) * self::EXECUTOR_SHARE_FROM_OUR_PRICE));
        $priceOurNew = ceil($priceTotal - $priceExecutorNew);


        return [
            'priceTotal' => $priceTotal,
            'priceExecutor' => $priceExecutorNew,
            'priceOur' => $priceOurNew,
            'extDiscount' => (($extDiscount - 1) * -1) * 100
        ];
    }
}
