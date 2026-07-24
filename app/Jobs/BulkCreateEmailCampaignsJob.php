<?php

namespace App\Jobs;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignRecipient;
use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailMarketing\EmailTemplate;
use App\Services\EmailMarketing\EmailTemplateRenderService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BulkCreateEmailCampaignsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $data
    ) {}

    public function handle(): void
    {
        $templateId = $this->data['email_template_id'];
        $subject = $this->data['subject'];
        $campaigns = $this->data['campaigns'];

        foreach ($campaigns as $campaignData) {
            $recipientListId = $campaignData['email_recipient_list_id'];

            $recipientList = EmailRecipientList::find($recipientListId);
            $utmCampaign = $recipientList?->utm_campaign;
            $promoCode = $recipientList?->promocode?->name;

            $renderedHtml = app(EmailTemplateRenderService::class)->renderHTML($templateId, $utmCampaign, $promoCode);

            $campaign = EmailCampaign::create([
                'name' => $campaignData['name'],
                'subject' => $subject,
                'email_recipient_list_id' => $recipientListId,
                'email_template_id' => $templateId,
                'scheduled_at' => $campaignData['scheduled_at'],
                'status' => 'scheduled',
                'html_content' => $renderedHtml,
                'created_by' => auth()->id(),
            ]);

            // Create campaign recipients from the recipient list in batches
            EmailRecipient::where('email_recipient_list_id', $campaign->email_recipient_list_id)
                ->chunk(100, function ($recipients) use ($campaign) {
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
}
