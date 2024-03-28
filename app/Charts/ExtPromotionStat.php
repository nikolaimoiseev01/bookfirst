<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Jenssegers\Date\Date;

class ExtPromotionStat extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    protected $chart;
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        parent::__construct();
    }

    public function build($external_promotion) {


        $parsed_readers = $external_promotion->ext_promotion_parsed_reader;

        $data = $parsed_readers->pluck('readers_num')->toArray();

        $x_axis = [];
        foreach ($parsed_readers as $parsed_reader) {
            $date = Date::parse($parsed_reader['checked_at'])->format('j F H:i');
            $x_axis[] = $date;
        }

        if($external_promotion['ext_promotion_status_id'] < 9) {
            $color = '#578bcd';
        } else {
            $color = '#47AF98';
        }

        return $this->chart->areaChart()
            ->addData('Читателей', $data)
            ->setXAxis($x_axis)
            ->setColors([$color])
            ->setFontFamily('Futura PT Light", serif')
            ->setDataLabels();
    }
}
