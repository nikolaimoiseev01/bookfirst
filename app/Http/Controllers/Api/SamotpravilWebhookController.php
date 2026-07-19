<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignRecipient;
use App\Models\EmailMarketing\EmailRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SamotpravilWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!isset($data['xml_messages']) || !is_array($data['xml_messages'])) {
            Log::error('Invalid webhook data', ['data' => $data]);
            return response()->json(['status' => 'error'], 400);
        }

        foreach ($data['xml_messages'] as $message) {
            $packId = $message['pack_id'] ?? null;
            $email = $message['email'] ?? null;
            $status = $message['status'] ?? null;
            $reason = $message['reason'] ?? null;
            $clickLink = $message['click_link'] ?? null;
            $createdAt = $message['created_at'] ?? null;

            if (!$packId || !$email || !$status) {
                Log::warning('Missing required fields in webhook message', ['message' => $message]);
                continue;
            }

            // Find campaign by mailganer_id (pack_id)
            $campaign = EmailCampaign::where('mailganer_id', $packId)->first();

            if (!$campaign) {
                Log::warning('Campaign not found for pack_id', ['pack_id' => $packId]);
                continue;
            }

            // Find recipient by email
            $recipient = EmailRecipient::where('email', $email)->first();

            if (!$recipient) {
                Log::warning('Recipient not found for email', ['email' => $email]);
                continue;
            }

            // Find campaign recipient
            $campaignRecipient = EmailCampaignRecipient::where('email_campaign_id', $campaign->id)
                ->where('email_recipient_id', $recipient->id)
                ->first();

            if (!$campaignRecipient) {
                Log::warning('Campaign recipient not found', [
                    'campaign_id' => $campaign->id,
                    'recipient_id' => $recipient->id
                ]);
                continue;
            }

            // Update campaign recipient
            $updateData = [
                'mailganer_status' => $status,
            ];

            if ($reason) {
                $updateData['mailganer_reason'] = $reason;
            }

            if ($clickLink) {
                $updateData['mailganer_click_link'] = $clickLink;
            }

            $campaignRecipient->update($updateData);

            Log::info('Campaign recipient updated', [
                'campaign_id' => $campaign->id,
                'recipient_id' => $recipient->id,
                'mailganer_status' => $status,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
