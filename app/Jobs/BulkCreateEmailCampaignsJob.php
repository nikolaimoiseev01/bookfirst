<?php

namespace App\Jobs;

use App\Models\EmailMarketing\EmailCampaign;
use App\Services\EmailMarketing\CreateEmailCampaignService;
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
        $createdBy = $this->data['created_by'] ?? null;
        $campaigns = $this->data['campaigns'];

        foreach ($campaigns as $campaignData) {
            $alreadyCreated = EmailCampaign::query()
                ->where('name', $campaignData['name'])
                ->where('email_recipient_list_id', $campaignData['email_recipient_list_id'])
                ->where('scheduled_at', $campaignData['scheduled_at'])
                ->where('email_template_id', $templateId)
                ->exists();

            if ($alreadyCreated) {
                continue;
            }

            $campaignData = array_merge($campaignData, [
                'email_template_id' => $templateId,
                'subject' => $subject,
                'created_by' => $createdBy,
            ]);

            app(CreateEmailCampaignService::class)->createCampaign($campaignData);
        }
    }
}
