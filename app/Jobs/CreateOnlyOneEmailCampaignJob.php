<?php

namespace App\Jobs;

use App\Models\EmailMarketing\EmailCampaign;
use App\Services\EmailMarketing\CreateEmailCampaignService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateOnlyOneEmailCampaignJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $emailCampaignId
    ) {}

    public function handle(): void
    {
        $campaign = EmailCampaign::find($this->emailCampaignId);
        app(CreateEmailCampaignService::class)->createCampaignRecipients($campaign);
    }
}
