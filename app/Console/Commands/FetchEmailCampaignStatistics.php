<?php

namespace App\Console\Commands;

use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignStatistic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchEmailCampaignStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:fetch-campaign-statistics {--campaign-id= : Specific campaign ID to fetch statistics for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch statistics from Samotpravil API for all email campaigns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.samotpravil.secret_key');

        if (!$apiKey) {
            $this->error('Samotpravil API key not configured');
            return 1;
        }

        $campaignId = $this->option('campaign-id');

        // Get campaigns - either specific one or all with mailganer_id
        $query = EmailCampaign::whereNotNull('mailganer_id');

        if ($campaignId) {
            $query->where('id', $campaignId);
        }

        $campaigns = $query->get();

        if ($campaigns->isEmpty()) {
            $this->info('No campaigns with mailganer_id found');
            return 0;
        }

        $this->info("Found {$campaigns->count()} campaigns to fetch statistics for");

        $successCount = 0;
        $failCount = 0;

        foreach ($campaigns as $campaign) {
            try {
                $this->info("Fetching statistics for campaign #{$campaign->id} (mailganer_id: {$campaign->mailganer_id})");

                $response = Http::get('https://api.samotpravil.ru/api/v1/get_issue_stat', [
                    'id' => 2175212, //$campaign->mailganer_id,
                    'key' => $apiKey,
                ]);

                if (!$response->successful()) {
                    $this->error("Failed to fetch statistics for campaign #{$campaign->id}: {$response->status()}");
                    $failCount++;
                    continue;
                }

                $data = $response->json();

                dd($data);

                if (!isset($data['stat'])) {
                    $this->error("Invalid response format for campaign #{$campaign->id}");
                    $failCount++;
                    continue;
                }

                $stat = $data['stat'][0]['stat'];

                // Create or update statistic record
                EmailCampaignStatistic::create([
                        'email_campaign_id' => $campaign->id,
                        'total' => $stat['total'] ?? 0,
                        'send_ok' => $stat['send_ok'] ?? 0,
                        'send_fail' => $stat['send_fail'] ?? 0,
                        'open_msg' => $stat['open_msg'] ?? 0,
                        'open_msg_uniq' => $stat['open_msg_uniq'] ?? 0,
                        'click_link' => $stat['click_link'] ?? 0,
                        'click_link_uniq' => $stat['click_link_uniq'] ?? 0,
                        'gen_ok' => $stat['gen_ok'] ?? 0,
                        'dup' => $stat['dup'] ?? 0,
                        'bad' => $stat['bad'] ?? 0,
                        'fbl' => $stat['fbl'] ?? 0,
                        'stop' => $stat['stop'] ?? 0,
                        'trap' => $stat['trap'] ?? 0,
                        'bounce' => $stat['bounce'] ?? 0,
                        'spam' => $stat['spam'] ?? 0,
                        'unsubscribe' => $stat['unsubscribe'] ?? 0,
                    ]
                );

                $this->info("Statistics updated for campaign #{$campaign->id}");
                $successCount++;

            } catch (\Exception $e) {
                $this->error("Error fetching statistics for campaign #{$campaign->id}: {$e->getMessage()}");
                Log::error('FetchEmailCampaignStatistics error', [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage(),
                ]);
                $failCount++;
            }
        }

        $this->info("Completed: {$successCount} successful, {$failCount} failed");

        return 0;
    }
}
