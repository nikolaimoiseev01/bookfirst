<?php

namespace App\Console\Commands;

use App\Models\EmailMarketing\EmailCampaign;
use App\Services\EmailMarketing\SendEmailService;
use Illuminate\Console\Command;

class SendScheduledEmailCampaigns extends Command
{
    protected $signature = 'app:send-scheduled-email-campaigns';

    protected $description = 'Send scheduled email campaigns';

    public function handle()
    {
        $this->info('Checking for scheduled email campaigns...');

        $campaigns = EmailCampaign::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No scheduled campaigns to send.');
            return 0;
        }

        $this->info("Found {$campaigns->count()} scheduled campaign(s) to send.");

        foreach ($campaigns as $campaign) {
            $this->info("Sending campaign: {$campaign->name}");

            try {
                app(SendEmailService::class, ['emailCampaign' => $campaign])->sendSingle();
                $this->info("✓ Campaign '{$campaign->name}' sent successfully.");
            } catch (\Exception $e) {
                $this->error("✗ Failed to send campaign '{$campaign->name}': {$e->getMessage()}");
            }
        }

        $this->info('Scheduled campaigns processing completed.');
        return 0;
    }
}
