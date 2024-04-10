<?php

namespace App\Service;

use Illuminate\Http\Request;

class ExtPromotionOutputsService
{
    public function calculate($site, $days, $discount)
    {

        $days = intval($days);

        $our_interest = 1; /* Сколько цен заказчика забираем себе */
        $executor_share_from_our_price = 0.5; /* Сколько от нашей маржи забирает заказчик плюсом к своей стоимости */

        // Скидка от заказчика за дни
        if ($days < 5) {
            $ext_discount = 1;
        } elseif ($days < 10) {
            $ext_discount = 0.9;
        } elseif ($days < 15) {
            $ext_discount = 0.8;
        } elseif ($days < 30) {
            $ext_discount = 0.7;
        } elseif ($days < 60) {
            $ext_discount = 0.6;
        } else {
            $ext_discount = 0.5;
        }

        // Считаем стоимость продвижения
        if ($site === 'stihi' or $site === 'proza') {
            $base_price = 50;
        } elseif ($site === 'chitalnya' or $site === 'poembook') {
            $base_price = 40;
        } elseif ($site === 'neizvestniy-geniy' or $site === 'fabulae' or $site === 'yapishu') {
            $base_price = 30;
        } else {
            $base_price = 50;
        }

        $price_executor = ceil($base_price * $ext_discount * $days);
        $price_our = ceil($price_executor * $our_interest);
        $price_total = ceil(($price_executor + $price_our) * ((100 - $discount) / 100));

        $price_executor_new = round($price_executor + (($price_total - $price_executor) * $executor_share_from_our_price));
        $price_our_new = ceil($price_total - $price_executor_new);

//        dd('Days: ' . $days . "\n" .
//            'Our Discount: ' . $discount . "\n" .
//            'Price executor: ' . $price_executor . "\n" .
//            'Price our: ' . $price_our . "\n" .
//            'Price executor NEW: ' . $price_executor_new . "\n" .
//            'Price our NEW: ' . $price_our_new . "\n" .
//            'Price total: ' . $price_total . "\n");

        return [
            'price_total' => $price_total,
            'price_executor' => $price_executor_new,
            'price_our' => $price_our_new,
            'ext_discount' => (($ext_discount - 1) * -1) * 100
        ];
    }
}
