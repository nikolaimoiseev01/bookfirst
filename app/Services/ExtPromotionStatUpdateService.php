<?php

namespace App\Services;

use App\Models\ext_promotion_parsed_reader;
use App\Models\ExtPromotion\ExtPromotionParsedReader;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExtPromotionStatUpdateService
{
    public $extPromotion;

    public function __construct($extPromotion)
    {
        $this->extPromotion = $extPromotion;
    }

    public function checkLastUpdate()
    {
        $extPromotionMaxDate = Carbon::parse($this->extPromotion->parsedReaders->max('checked_at') ?? now()->addHour(-3));
        $timeNow = Date::parse(Carbon::now());
        $dateDiff = $extPromotionMaxDate->diff($timeNow);
        $dateDiffSec = ($dateDiff->d * 24 * 60 * 60) + ($dateDiff->h * 60 * 60) + ($dateDiff->m * 60) + $dateDiff->i;

        if ($dateDiffSec > 60 * 60) { /* Если больше часа */
            return True;
        } else {
            return False;
        }
    }

    public function addNewStat(): bool
    {

        if (in_array($this->extPromotion['site'], ['stihi', 'proza'])) {
            try {
                return DB::transaction(function () {

                    // Находим за последние сутки
                    $client = new Client();
                    $response = $client->request('GET', "https://{$this->extPromotion['site']}.ru/avtor/{$this->extPromotion['login']}");
                    $html = $response->getBody()->getContents();
                    $html = mb_convert_encoding($html, 'UTF-8', 'Windows-1251');
                    $html = str_replace("\n", "", $html);
                    $readers = intval(Str::between($html, '>Читателей</a>: <b>', '</b><br></p><h2>'));

                    // Прибавляем к ним за сегодня
                    $client = new Client();
                    $response = $client->request('GET', "https://{$this->extPromotion['site']}.ru/readers.html?{$this->extPromotion['login']}");
                    $html = $response->getBody()->getContents();
                    $html = mb_convert_encoding($html, 'UTF-8', 'Windows-1251');
                    $html = str_replace("\n", "", $html);

                    $readers_today = intval(Str::between($html, '<p>Сегодня <b>', '</b> новых читателей'));

                    $total_readers = max($readers_today, 0) + max($readers, 0);

                    ExtPromotionParsedReader::create([
                        'user_id' => $this->extPromotion['user_id'],
                        'ext_promotion_id' => $this->extPromotion['id'],
                        'checked_at' => Carbon::now('Europe/Moscow')->toDateTime(),
                        'readers_num' => $total_readers
                    ]);
                    return true;
                });
            } catch (\Exception $e) {
                return false;
            }
        }
        return false;
    }
}
