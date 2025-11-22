<?php

namespace App\Service;

use Illuminate\Http\Request;

class ParticipationOutputsService
{
    public function calculate($pages, $print_need, $prints, $need_check, $promo_discount)
    {

        $pages = intval($pages);
        $prints = intval($prints);

        // Считаем стоимость участия
        if($pages === 0) {
            $price_part = 0;
        } elseif ($pages <= 7) {
            $price_part = 1000;
        } elseif ($pages >= 8 && $pages <= 14) {
            $price_part = 1900;
        } elseif ($pages >= 15 && $pages <= 21) {
            $price_part = 2850;
        } elseif ($pages >= 22 && $pages <= 28) {
            $price_part = 3800;
        } else {
            $price_part = $pages * 150;
        }
        $price_part = $price_part * (1 - ($promo_discount / 100));

        // Считаем стоимость проверки текста
        if ($need_check) {
            $price_check = $price_part * 0.7;
        } else {
            $price_check = 0;
        }


        // Считаем стоимость печати
        if($print_need) {
            if ($prints <= 5) {
                $prints_discount = 1;
            } else if ($prints > 5 && $prints <= 10) {
                $prints_discount = 0.95;
            } else if ($prints > 10 && $prints <= 20) {
                $prints_discount = 0.90;
            } else if ($prints > 20) {
                $prints_discount = 0.85;
            }
            $price_print = ($prints * 300) * $prints_discount;
        } else {
            $price_print = 0;
        }


        // Считаем полную стоимость
        $price_total = $price_part + $price_check + $price_print;


        return [
            'price_part' => $price_part,
            'price_check' => $price_check,
            'price_print' => $price_print,
            'price_total' => $price_total
        ];
    }
}
