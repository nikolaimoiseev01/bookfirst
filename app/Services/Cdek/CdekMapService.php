<?php

namespace App\Services\Cdek;

class CdekMapService
{
    private string $login;
    private string $secret;
    private string $baseUrl;
    private ?string $authToken = null;
    private array $requestData = [];
    private array $metrics = [];

    public function __construct($login, $secret, $baseUrl = 'https://api.cdek.ru/v2')
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->baseUrl = $baseUrl;
    }

    public function process($requestData, $body)
    {
        $start = $this->startMetrics();

        $this->requestData = array_merge($requestData, json_decode($body ?? '', true) ?: []);

        if (!isset($this->requestData['action'])) {
            return $this->validationError('Action is required');
        }

        $this->getAuthToken();

        switch ($this->requestData['action']) {
            case 'offices':
                return $this->sendResponse($this->getOffices(), $start);
            case 'calculate':
                return $this->sendResponse($this->calculate(), $start);
            default:
                return $this->validationError('Unknown action');
        }
    }

    private function validationError($message)
    {
        return response()->json(['message' => $message], 400)
            ->header('X-Service-Version', '3.11.1');
    }

    private function getAuthToken()
    {
        $start = $this->startMetrics();

        $token = $this->httpRequest('oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->login,
            'client_secret' => $this->secret,
        ], true);

        $this->endMetrics('auth', 'Server Auth Time', $start);

        $result = json_decode($token['result'], true);

        if (!isset($result['access_token'])) {
            throw new \RuntimeException('Server not authorized to CDEK API');
        }

        $this->authToken = $result['access_token'];
    }

    private function startMetrics()
    {
        return function_exists('hrtime') ? hrtime(true) : microtime(true);
    }

    private function httpRequest($method, $data, $useFormData = false, $useJson = false)
    {
        $ch = curl_init("{$this->baseUrl}/{$method}");

        $headers = [
            'Accept: application/json',
            'X-App-Name: widget_pvz',
            'X-App-Version: 3.11.1'
        ];

        if ($this->authToken) {
            $headers[] = "Authorization: Bearer {$this->authToken}";
        }

        if ($useFormData) {
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            ]);
        } elseif ($useJson) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
            ]);
        } else {
            curl_setopt($ch, CURLOPT_URL, "{$this->baseUrl}/{$method}?" . http_build_query($data));
        }

        curl_setopt_array($ch, [
            CURLOPT_USERAGENT => 'widget/3.11.1',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
        ]);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $result = substr($response, $headerSize);

        return [
            'result' => $result,
            'addedHeaders' => $this->extractXHeaders($headers),
        ];
    }

    private function extractXHeaders($headers)
    {
        return array_filter(explode("\r\n", $headers), function ($line) {
            return stripos($line, 'X-') === 0;
        });
    }

    private function sendResponse($data, $start)
    {
        $this->endMetrics('total', 'Total Time', $start);

        $response = response($data['result'], 200)
            ->header('Content-Type', 'application/json')
            ->header('X-Service-Version', '3.11.1');

        foreach ($data['addedHeaders'] as $header) {
            [$name, $value] = explode(':', $header, 2);
            $response->header($name, trim($value));
        }

        if (!empty($this->metrics)) {
            $serverTiming = array_reduce($this->metrics, function ($c, $i) {
                return $c . "{$i['name']};desc=\"{$i['description']}\";dur={$i['time']},";
            }, '');

            $response->header('Server-Timing', rtrim($serverTiming, ','));
        }

        return $response;
    }

    private function endMetrics($name, $desc, $start)
    {
        $duration = function_exists('hrtime')
            ? (hrtime(true) - $start) / 1e+6
            : (microtime(true) - $start) * 1000;

        $this->metrics[] = [
            'name' => $name,
            'description' => $desc,
            'time' => round($duration, 2),
        ];
    }

    private function getOffices()
    {
        $start = $this->startMetrics();
        $result = $this->httpRequest('deliverypoints', $this->requestData);

        $this->endMetrics('office', 'Offices Request', $start);
        return $result;
    }

    private function calculate()
    {
        $start = $this->startMetrics();
        $result = $this->httpRequest('calculator/tarifflist', $this->requestData, false, true);

        $this->endMetrics('calc', 'Calculate Request', $start);
        return $result;
    }
}
