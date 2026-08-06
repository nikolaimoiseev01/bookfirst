<?php

namespace App\Services\EmailMarketing;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignRecipient;
use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailMarketing\EmailTemplate;

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
    }

    public function createCampaignRecipients(EmailCampaign $campaign): void
    {
        EmailRecipient::where('email_recipient_list_id', $campaign->email_recipient_list_id)
            ->chunk(500, function ($recipients) use ($campaign) {
                foreach ($recipients as $recipient) {
                    EmailCampaignRecipient::create([
                        'email_campaign_id' => $campaign->id,
                        'email_recipient_id' => $recipient->id,
                        'mailganer_status' => 'pending',
                    ]);
                }
            });
    }
}
