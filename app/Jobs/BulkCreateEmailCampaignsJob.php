<?php

namespace App\Jobs;

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
        $campaigns = $this->data['campaigns'];

        foreach ($campaigns as $campaignData) {
            $campaignData = array_merge($campaignData, [
                'email_template_id' => $templateId,
                'subject' => $subject,
                'created_by' => auth()->id(),
            ]);

            app(CreateEmailCampaignService::class)->createCampaign($campaignData);
        }
    }
}
