<?php

namespace App\Service;

use Illuminate\Http\Request;

class ExtPromotionOutputsService
{
    public function calculate($site, $days, $discount)
    {

        $days = intval($days);

        $our_interest = 2;

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

        $price_executor =  ceil($base_price * $ext_discount * $days);

        $price_our = ceil($price_executor * ($our_interest * ((100 - $discount) / 100)) - $price_executor);

        $price_total = $price_executor + $price_our;

        return [
            'price_total' => $price_total,
            'price_executor' => $price_executor,
            'price_our' => $price_our,
            'ext_discount' => (($ext_discount - 1) * -1) * 100
        ];
    }
}
