<?php


namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CalculateOwnBookService
{
    public $pages;
    public $pagesColor;
    public $needTextDesign;
    public $needTextCheck;
    public $coverReady;
    public $needPrint;
    public $booksCnt;
    public $coverType;
    public $promoType;

    private const PRINT_PROFIT_COEF = 2.5;
    private const TEXT_DESIGN_PER_PAGE = 13;
    private const TEXT_CHECK_PER_PAGE = 30;
    private const TEXT_ISBN_PRICE = 300;
    private const TEXT_PRICE_CONST = 500;

    public function __construct($pages, $pagesColor, $needTextDesign, $needTextCheck, $coverReady, $needPrint, $booksCnt, $coverType, $promoType)
    {
        $this->pages = intVal($pages);
        $this->pagesColor = intVal($pagesColor);
        $this->needTextDesign = $needTextDesign;
        $this->needTextCheck = $needTextCheck;
        $this->coverReady = $coverReady;
        $this->needPrint = $needPrint;
        $this->booksCnt = intVal($booksCnt);
        $this->coverType = $coverType;
        $this->promoType = $promoType;
    }

    public function calculate()
    {
        $priceTextDesign = ($this->needTextDesign) ? $this->pages * self::TEXT_DESIGN_PER_PAGE : 0;
        $priceTextCheck = ($this->needTextCheck) ? $this->pages * self::TEXT_CHECK_PER_PAGE : 0;
        $priceTextInside = self::TEXT_ISBN_PRICE + self::TEXT_PRICE_CONST + $priceTextDesign + $priceTextCheck;
        $priceCover = ($this->coverReady) ? 0 : 1500;
        $pricePromo = match ($this->promoType) {
            '1' => 500,
            '2' => 2000,
            default => 0,
        };


        // Считаем стоимость печати
        if ($this->needPrint) {

            // Если менее 4-х, то стоимость за 4
            if ($this->booksCnt <= 4) {
                $this->booksCnt = 4;
            }

            // Скидка за тираж
            $booksCntDiscount = match (true) {
                $this->booksCnt <= 10 => 1,
                $this->booksCnt <= 50 => 0.95,
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
                $this->coverType == 'Твердая' => 2,
                default => 1,
            };

            // Цена одной книги без скидок и накруток
            $pricePrintPre = ($this->pages - $this->pagesColor + ($this->pagesColor * 3)) * 0.7 * $coverStyleCoef * $pagesCoef;

            $pricePrint = ceil($pricePrintPre * $booksCntDiscount * $this->booksCnt * self::PRINT_PROFIT_COEF);
        } else {
            $pricePrint = 0;
        }

        $priceTotal = $priceTextInside + $priceCover + $pricePromo;


        return [
            'priceTextInside' => $priceTextInside,
            'priceTextDesign' => $priceTextDesign,
            'priceTextCheck' => $priceTextCheck,
            'priceCover' => $priceCover,
            'pricePromo' => $pricePromo,
            'priceTotal' => $priceTotal,
            'pricePrint' => $pricePrint,
        ];
    }
}
