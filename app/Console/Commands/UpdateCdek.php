<?php

namespace App\Console\Commands;

use App\Models\Cdek\CdekOffice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateCdek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan cdek:update-cities
     */
    protected $signature = 'cdek:update';
    private $token;
    private $urlPrefix = 'https://api.cdek.ru/v2/';

    public function auth()
    {
        $response = Http::asForm()->post('https://api.cdek.ru/v2/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.cdek.client_id'),
            'client_secret' => config('services.cdek.client_secret'),
        ]);
        $this->token = $response->json()['access_token'];
    }

    public function getOffices()
    {
        DB::table('cdek_offices')->truncate();
        $page = 0;
        $size = 1000;   // максимум 1000 у CDEK

        while (true) {
            $url = $this->urlPrefix . "deliverypoints?page={$page}&size={$size}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])
                ->timeout(120)
                ->connectTimeout(30)
                ->retry(5, 1000) // 5 попыток, шаг 1 секунда
                ->withOptions(['curl' => [CURLOPT_FORBID_REUSE => true]])
                ->get($url);

            if (!$response->successful()) {
                throw new \RuntimeException("Request failed: " . $response->body());
            }

            $data = $response->json();

            if (empty($data)) {
                break; // когда больше нет офисов
            }

            // пачкой вставляем в таблицу
            foreach ($data as $row) {
                CdekOffice::create([
                    'code' => $row['code'] ?? null,
                    'name' => $row['name'] ?? null,
                    'country_code' => $row['location']['country_code'] ?? null,
                    'region_code' => $row['location']['region_code'] ?? null,
                    'longitude' => $row['location']['longitude'] ?? null,
                    'latitude' => $row['location']['latitude'] ?? null,
                    'full_data' => $row
                ]);
            }

            unset($rows, $data, $response);
            gc_collect_cycles();

            echo "Обработали страницу {$page}\n";

            $page++;
        }

        dd('GOT'); // общее количество городов
    }


    public function getCities()
    {

        DB::table('cdek_cities')->truncate();
        $page = 0;
        $size = 1000;   // максимум 1000 у CDEK

        while (true) {
            $url = $this->urlPrefix . "location/cities?country_codes=RU&page={$page}&size={$size}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->get($url);

            if (!$response->successful()) {
                throw new \RuntimeException("Request failed: " . $response->body());
            }

            $data = $response->json();

            if (empty($data)) {
                break; // когда больше нет городов
            }

            // оставляем только нужные поля
            $rows = array_map(function ($item) {
                return [
                    'code' => $item['code'] ?? null,
                    'city' => $item['city'] ?? null,
                    'country_code' => $item['country_code'] ?? null,
                    'country' => $item['country'] ?? null,
                    'region' => $item['region'] ?? null,
                    'region_code' => $item['region_code'] ?? null,
                    'sub_region' => $item['sub_region'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data);

            // пачкой вставляем в таблицу
            foreach ($rows as $row) {
                DB::table('cdek_cities')->insert($row);
            }

            unset($rows, $data, $response);
            gc_collect_cycles();

            echo "Обработали страницу {$page}\n";

            $page++;
        }

        dd('GOT'); // общее количество городов
    }

    public function getRegions()
    {

        DB::table('cdek_regions')->truncate();
        $page = 0;
        $size = 1000;   // максимум 1000 у CDEK

        while (true) {
            $url = $this->urlPrefix . "location/regions?country_codes=RU&page={$page}&size={$size}";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
            ])->get($url);

            if (!$response->successful()) {
                throw new \RuntimeException("Request failed: " . $response->body());
            }

            $data = $response->json();

            if (empty($data)) {
                break; // когда больше нет городов
            }

            // оставляем только нужные поля
            $rows = array_map(function ($item) {
                return [
                    "country_code" => $item['country_code'] ?? null,
                    "country" => $item['country'] ?? null,
                    "region" => $item['region'] ?? null,
                    "region_code" => $item['region_code'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data);

            // пачкой вставляем в таблицу
            foreach ($rows as $row) {
                DB::table('cdek_regions')->insert($row);
            }

            unset($rows, $data, $response);
            gc_collect_cycles();

            echo "Обработали страницу {$page}\n";

            $page++;
        }

        dd('GOT'); // общее количество городов
    }

    public function handle()
    {
        $this->auth();
        $this->getCities();
    }
}
