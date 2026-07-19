<?php

namespace App\Services\EmailMarketing;

use App\Enums\EmailCampaignStatusEnums;
use App\Models\EmailMarketing\EmailCampaign;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class  SendEmailService
{
    public EmailCampaign $emailCampaign;

    public function __construct($emailCampaign)
    {
        $this->emailCampaign = $emailCampaign;
    }

    public function sendSingle(): void
    {
        $apiKey = config('services.samotpravil.secret_key');

        $this->emailCampaign->recipientList->recipients;
        $users = $this->emailCampaign
            ->recipientList
            ->recipients
            ->map(fn ($recipient) => [
                'emailto' => $recipient->email,
            ])
            ->values()
            ->toArray();


        $url = 'https://api.samotpravil.ru/api/v1/add_json_package?key=' . $apiKey;

        $headers = [
            'Authorization' => $apiKey,
            'Content-Type' => 'application/json',
        ];

        $data = [
            'email_from' => config('services.samotpravil.email_from'),
            'name_from' => config('services.samotpravil.name_from'),
            'subject' => $this->emailCampaign->subject,
            'message_text' =>  $this->emailCampaign->html_content,
            'users' => $users
        ];

        $response = Http::withHeaders($headers)
            ->post($url, $data);

        $data = $response->object();

        $this->emailCampaign->update([
            'mailganer_id' => $data->message->pack_id,
            'sent_at' => now(),
            'status' => EmailCampaignStatusEnums::SENT,
        ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Samotpravil request failed: ' . $response->body()
            );
        }

        if (!in_array(strtolower($data->status ?? ''), ['ok'], true)) {
            throw new RuntimeException(
                $data->message ?? 'Samotpravil returned error'
            );
        }

    }
}
