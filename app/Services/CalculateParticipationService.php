<?php


namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CalculateParticipationService
{

    public $pages;
    public $needPrint;
    public $booksCnt;
    public $needCheck;
    public $discount;


    public function __construct($pages, $needPrint, $booksCnt, $needCheck, $discount=0)
    {
        $this->pages = intVal($pages);
        $this->needPrint = $needPrint;
        $this->booksCnt = intVal($booksCnt);
        $this->needCheck = $needCheck;
        $this->discount = $discount;
    }


    public function calculate()
    {

        // Считаем стоимость участия
        if ($this->pages === 0) {
            $pricePart = 0;
        } elseif ($this->pages <= 7) {
            $pricePart = 1000;
        } elseif ($this->pages >= 8 && $this->pages <= 14) {
            $pricePart = 1900;
        } elseif ($this->pages >= 15 && $this->pages <= 21) {
            $pricePart = 2850;
        } elseif ($this->pages >= 22 && $this->pages <= 28) {
            $pricePart = 3800;
        } else {
            $pricePart = $this->pages * 150;
        }
        $pricePart = $pricePart * (1 - ($this->discount / 100));

        // Считаем стоимость проверки текста
        if ($this->needCheck) {
            $priceCheck = $pricePart * 0.7;
        } else {
            $priceCheck = 0;
        }


        // Считаем стоимость печати
        if ($this->needPrint) {
            if ($this->booksCnt <= 5) {
                $printsDiscount = 1;
            } else if ($this->booksCnt <= 10) {
                $printsDiscount = 0.95;
            } else if ($this->booksCnt <= 20) {
                $printsDiscount = 0.90;
            } else {
                $printsDiscount = 0.85;
            }
            $pricePrint = ($this->booksCnt * 300) * $printsDiscount;
        } else {
            $pricePrint = 0;
        }


        // Считаем полную стоимость
        $priceTotal = $pricePart + $priceCheck;


        return [
            'pricePart' => $pricePart,
            'priceCheck' => $priceCheck,
            'pricePrint' => $pricePrint,
            'priceTotal' => $priceTotal
        ];
    }
}
