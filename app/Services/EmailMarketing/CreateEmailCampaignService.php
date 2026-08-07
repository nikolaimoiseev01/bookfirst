<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignRecipient;
use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailMarketing\EmailTemplate;
use Illuminate\Support\Facades\DB;

class CreateEmailCampaignService
{
    public function createCampaign(array $data): EmailCampaign
    {
        $templateId = $data['email_template_id'];
        $subject = $data['subject'];
        $recipientListId = $data['email_recipient_list_id'];
        $name = $data['name'];
        $scheduledAt = $data['scheduled_at'] ?? null;
        $createdBy = $data['created_by'] ?? auth()->id();

        $recipientList = EmailRecipientList::find($recipientListId);
        $utmCampaign = $recipientList?->utm_campaign;
        $promoCode = $recipientList?->promocode?->name;

        $renderedHtml = app(EmailTemplateRenderService::class)->renderHTML($templateId, $utmCampaign, $promoCode);

        $campaign = DB::transaction(function () use ($name, $subject, $recipientListId, $templateId, $scheduledAt, $renderedHtml, $createdBy) {
            $campaign = EmailCampaign::create([
                'name' => $name,
                'subject' => $subject,
                'email_recipient_list_id' => $recipientListId,
                'email_template_id' => $templateId,
                'scheduled_at' => $scheduledAt,
                'status' => $scheduledAt ? 'scheduled' : 'draft',
                'html_content' => $renderedHtml,
                'created_by' => $createdBy,
            ]);

            $this->createCampaignRecipients($campaign);

            return $campaign;
        });

        return $campaign;
    }

    public function createCampaignRecipients(EmailCampaign $campaign): void
    {
        DB::transaction(function () use ($campaign) {
            EmailRecipient::where('email_recipient_list_id', $campaign->email_recipient_list_id)
                ->chunkById(500, function ($recipients) use ($campaign) {
                    $now = now();

                    $rows = $recipients->map(fn (EmailRecipient $recipient) => [
                        'email_campaign_id' => $campaign->id,
                        'email_recipient_id' => $recipient->id,
                        'mailganer_status' => 'pending',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all();

                    EmailCampaignRecipient::upsert(
                        $rows,
                        ['email_campaign_id', 'email_recipient_id'],
                        ['updated_at']
                    );
                });
        });
    }
}
