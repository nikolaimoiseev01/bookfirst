<?php


namespace App\Services\PriceCalculation;

class CalculateOwnBookService
{
    public $pages;

    private const PRINT_PROFIT_COEF = 2.5;
    private const TEXT_DESIGN_PER_PAGE = 13;
    private const TEXT_CHECK_PER_PAGE = 30;
    private const TEXT_ISBN_PRICE = 300;
    private const TEXT_PRICE_CONST = 500;

    public function __construct($pages)
    {
        $this->pages = intVal($pages);
    }

    public function calculatePrintPrice($pagesColor, $booksCnt, $coverType) {
        // Если менее 4-х, то стоимость за 4
        if ($booksCnt <= 4) {
            $booksCnt = 4;
        }

        // Скидка за тираж
        $booksCntDiscount = match (true) {
            $booksCnt <= 10 => 1,
            $booksCnt <= 50 => 0.95,
            default => 0.90,
        };

        // Накрутка за маленькое кол-во страниц
        $pagesCoef = match (true) {
            $this->pages <= 50 => 2.15,
            $this->pages <= 75 => 2.1,
            $this->pages <= 100 => 1.5,
            default => 1,
        };

        $coverStyleCoef = match (true) {
            $coverType == 'Твердая' => 2,
            default => 1,
        };

        // Цена одной книги без скидок и накруток
        $pricePrintPre = ($this->pages - $pagesColor + ($pagesColor * 3)) * 0.7 * $coverStyleCoef * $pagesCoef;

        return ceil($pricePrintPre * $booksCntDiscount * $booksCnt * self::PRINT_PROFIT_COEF);
    }

    public function calculateAllPrices($needTextDesign, $needTextCheck, $coverReady, $promoType, $needPrint, $pagesColor, $booksCnt, $coverType)
    {
        $priceTextDesign = ($needTextDesign) ? $this->pages * self::TEXT_DESIGN_PER_PAGE : 0;
        $priceTextCheck = ($needTextCheck) ? $this->pages * self::TEXT_CHECK_PER_PAGE : 0;
        $priceInside = self::TEXT_ISBN_PRICE + self::TEXT_PRICE_CONST + $priceTextDesign + $priceTextCheck;
        $priceCover = ($coverReady) ? 0 : 1500;
        $pricePromo = match ($promoType) {
            '1' => 500,
            '2' => 2000,
            default => 0,
        };


        // Считаем стоимость печати
        if ($needPrint) {
            $pricePrint = $this->calculatePrintPrice($pagesColor, $booksCnt, $coverType);
        } else {
            $pricePrint = 0;
        }

        $priceTotal = $priceInside + $priceCover + $pricePromo;


        return [
            'priceInside' => $priceInside,
            'priceTextDesign' => $priceTextDesign,
            'priceTextCheck' => $priceTextCheck,
            'priceCover' => $priceCover,
            'pricePromo' => $pricePromo,
            'priceTotal' => $priceTotal,
            'pricePrint' => $pricePrint,
        ];
    }
}
