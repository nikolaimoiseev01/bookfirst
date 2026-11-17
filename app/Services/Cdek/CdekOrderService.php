<?php

namespace App\Services\Cdek;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CdekOrderService
{
    private function token(): string
    {
        return Cache::remember('cdek_api_token', now()->addMinutes(50), function (): string {
            $response = Http::asForm()->timeout(20)->post(rtrim(config('services.cdek.base_uri'), '/') . '/v2/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.cdek.client_id'),
                'client_secret' => config('services.cdek.client_secret'),
            ]);

            if (!$response->successful() || !($response->json('access_token'))) {
                throw new RuntimeException('Не удалось авторизоваться в API СДЭК: ' . $response->body());
            }

            return $response->json('access_token');
        });
    }

    public function create(array $payload): array
    {
        $response = Http::withToken($this->token())
            ->acceptJson()
            ->asJson()
            ->timeout(40)
            ->post(rtrim(config('services.cdek.base_uri'), '/') . '/v2/orders', $payload);

        if (!$response->successful()) {
            throw new RuntimeException('СДЭК отклонил заказ: ' . $response->body());
        }

        return $response->json() ?? [];
    }

    public function calculateTariff(array $payload): array
    {
        $response = Http::withToken($this->token())
            ->acceptJson()
            ->asJson()
            ->timeout(40)
            ->post(rtrim(config('services.cdek.base_uri'), '/') . '/v2/calculator/tariff', $payload);

        if (!$response->successful()) {
            throw new RuntimeException('Не удалось рассчитать стоимость доставки СДЭК: ' . $response->body());
        }

        $result = $response->json() ?? [];
        if (!array_key_exists('total_sum', $result) || !empty($result['errors'])) {
            throw new RuntimeException('СДЭК не вернул стоимость доставки: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return $result;
    }

    public function createIntake(array $payload): array
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $response = Http::withToken($this->token())
                ->acceptJson()
                ->asJson()
                ->timeout(40)
                ->post(rtrim(config('services.cdek.base_uri'), '/') . '/v2/intakes', $payload);

            $result = $response->json() ?? [];
            $entityNotFound = str_contains($response->body(), 'v2_entity_not_found');

            if ($entityNotFound && $attempt < $maxAttempts) {
                usleep($attempt * 2 * 1_000_000);
                continue;
            }

            if (!$response->successful()) {
                throw new RuntimeException('Не удалось вызвать курьера СДЭК: ' . $response->body());
            }

            return $result;
        }

        throw new RuntimeException('Не удалось вызвать курьера СДЭК.');
    }

    public function getOrder(string $uuid): array
    {
        $response = Http::withToken($this->token())->acceptJson()->timeout(30)
            ->get(rtrim(config('services.cdek.base_uri'), '/') . '/v2/orders/' . $uuid);

        if (!$response->successful()) {
            throw new RuntimeException('Не удалось получить статус заказа СДЭК: ' . $response->body());
        }

        return $response->json() ?? [];
    }
}
