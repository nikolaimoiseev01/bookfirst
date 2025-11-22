<?php

namespace App\Service;

use Illuminate\Http\Request;

class OwnBookOutputsService
{
    public function calculate($pages, $pages_color, $need_design, $need_check, $cover_ready, $need_print, $prints, $cover_type, $promo_type)
    {
        $pages = intval($pages);
        $pages_color = intval($pages_color);
        $prints = intval($prints);
        $print_profit_coef = 2.5;


        // Считаем стоимость работы со внутренним блоком
        ($need_design) ? $price_design = $pages * 13 : $price_design = 0;
        ($need_check) ? $price_check = $pages * 30 : $price_check = 0;
        $price_inside = 300 + $price_design + $price_check;

        // Считаем стоимость работы с обложкой
        ($cover_ready == '1') ? $price_cover = 0 : $price_cover = 1500;

        // Считаем стоимость
        if($promo_type) {
            if ($promo_type == '1') {
                $price_promo = 500;
            } elseif ($promo_type == '2') {
                $price_promo = 2000;
            }
        } else {
            $price_promo = 0;
        }



        // Считаем стоимость печати
        if ($need_print) {
            if($prints <= 4) {
                $prints = 4;
            }

            // Скидка за тираж
            if ($prints <= 10) {
                $prints_discount = 1;
            } else if ($prints <= 50) {
                $prints_discount = 0.95;
            } else {
                $prints_discount = 0.90;
            }

            // Накрутка за маленькое кол-во страниц
            if ($pages <= 50) {
                $pages_coef = 2.15;
            } elseif($pages <= 75) {
                $pages_coef = 2.1;
            } elseif($pages <= 100) {
                $pages_coef = 1.5;
            } else {
                $pages_coef = 1;
            }

            if ($cover_type == 'hard') {
                $cover_style_coef = 2;
            } else {
                $cover_style_coef = 1;
            }

            // Цена одной книги без скидок и накруток
            $price_print_pre = ($pages - $pages_color + ($pages_color * 3)) * 0.7 * $cover_style_coef * $pages_coef;

            $price_print = ceil($price_print_pre * $prints_discount * $prints * $print_profit_coef);
        } else {
            $price_print = 0;
        }

        $price_total = $price_inside + $price_cover + $price_promo + $price_print;


        return [
            'price_inside' => $price_inside,
            'price_design' => $price_design,
            'price_check' => $price_check,
            'price_cover' => $price_cover,
            'price_print' => $price_print,
            'price_promo' => $price_promo,
            'price_total' => $price_total
        ];
    }
}
