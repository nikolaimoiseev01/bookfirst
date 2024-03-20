<?php

namespace App\Service;

use App\Models\ext_promotion_parsed_reader;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExtPromotionStatUpdateService
{


    public function check_max($ext_promotion) {
        $ext_promotion_max_time = Carbon::parse($ext_promotion->ext_promotion_parsed_reader->max('checked_at') ?? now()->addHour(-3));
        $now_time = Date::parse(Carbon::now())->addHour(3);
        $date_diff = $ext_promotion_max_time->diff($now_time);
        $date_diff_sec = ($date_diff->h * 60 * 60) + ($date_diff->m * 60) + $date_diff->i;
        if($date_diff_sec > 60 * 10) {
            return True;
        } else {
            return False;
        }
    }
    public function add_new_time($ext_promotion)
    {

        if(in_array($ext_promotion['site'], ['stihi', 'proza'])) {
            $client = new Client();
            $response = $client->request('GET', "https://{$ext_promotion['site']}.ru/avtor/{$ext_promotion['login']}");
            $html = $response->getBody()->getContents();
            $html = mb_convert_encoding($html, 'UTF-8', 'Windows-1251');
            $html = str_replace("\n", "", $html);
            $readers = intval(Str::between($html, '>Читателей</a>: <b>', '</b><br></p><h2>'));

            if ($readers > 0) { // Если смогли обнаружить одно число
                DB::transaction(function () use ($ext_promotion, $readers) {
                    ext_promotion_parsed_reader::create([
                        'user_id' => $ext_promotion['user_id'],
                        'ext_promotion_id' => $ext_promotion['id'],
                        'checked_at' => Carbon::now('Europe/Moscow')->toDateTime(),
                        'readers_num' => $readers
                    ]);
                });
            }
        }





    }
}
