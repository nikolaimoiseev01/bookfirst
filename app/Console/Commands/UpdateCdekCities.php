<?php

namespace App\Console\Commands;

use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCdekCities extends Command
{
    private $authToken;
    /**
     * @var array Data From Request
     */


    protected $signature = 'app:update-cdek-cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    private function httpRequest($method, $data, $useFormData = false, $useJson = false)
    {
        $ch = curl_init("$this->baseUrl/$method?city_code=270");

        $headers = array(
            'Accept: application/json',
            'X-App-Name: widget_pvz',
            'X-App-Version: 3.11.1'
        );

        if ($this->authToken) {
            $headers[] = "Authorization: Bearer $this->authToken";
        }

        if ($useFormData) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            ));
        } elseif ($useJson) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
            ));
        } else {
            curl_setopt($ch, CURLOPT_URL, "$this->baseUrl/$method?" . http_build_query($data));
        }

        curl_setopt_array($ch, array(
            CURLOPT_USERAGENT => 'widget/3.11.1',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
        ));

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $result = substr($response, $headerSize);

        $addedHeaders = $this->getHeaderValue($headers);

        if ($result === false) {
            throw new RuntimeException(curl_error($ch), curl_errno($ch));
        }

        return array('result' => $result, 'addedHeaders' => $addedHeaders);
    }

    private function getHeaderValue($headers)
    {
        $headerLines = explode("\r\n", $headers);
        return array_filter($headerLines, static function ($line) {
            return !empty($line) && stripos($line, 'X-') !== false;
        });
    }

    private function getAuthToken()
    {

        $token = $this->httpRequest('oauth/token', array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->login,
            'client_secret' => $this->secret,
        ), true);

        $result = json_decode($token['result'], true);

        if (!isset($result['access_token'])) {
            throw new RuntimeException('Server not authorized to CDEK API');
        }

        $this->authToken = $result['access_token'];
    }

    public function handle()
    {
        $this->login = 'lWinZD6KbTnHFVEZH9fMZSvotqH7CUpM';
        $this->secret = 'Lpw2Qc1dSMppKgxujhHDg52YhqUHyFFE';
        $this->baseUrl = 'https://api.cdek.ru/v2';

        $this->getAuthToken(); // 👈 сначала авторизация

        $all = [];
        $page = 0;
        $size = 1000;

        DB::table('cdek_cities')->truncate();

        while (true) {

            $all = [];


            $params = [
                'page' => $page,
                'size' => $size,
                'country_codes' => 'RU',
            ];

            $result = $this->httpRequest('location/cities', $params);
            $decoded = json_decode($result['result'], true);

            if (empty($decoded)) {
                break;
            }

            $all = array_merge($all, $decoded);

            // если вернулось меньше 1000 — значит последняя страница
            if (count($decoded) < $size) {
                break;
            }

            $prepared = array_map(function ($all) {
                return [
                    'code'         => $all['code'],                 // обязательный, уникальный
                    'city'         => $all['city'] ?? null,
                    'region'       => $all['region'] ?? null,
                    'country_code' => $all['country_code'] ?? null,
                ];
            }, $decoded);

            DB::table('cdek_cities')->insert($prepared);


            $page++;
            $cnt = count($all);
            echo ("Обработали $page страницу\n Уже $cnt городов\n");
        }
    }
}
