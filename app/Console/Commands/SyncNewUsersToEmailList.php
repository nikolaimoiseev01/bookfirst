<?php

namespace App\Console\Commands;

use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailSubscription;
use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncNewUsersToEmailList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sync-new-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users created in the last day and email subscriptions to email recipient list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $listId = config('app.email_campaign_main_base');

        $recipientList = EmailRecipientList::find($listId);

        if (!$recipientList) {
            $this->error("Email recipient list with ID {$listId} not found.");
            return 1;
        }

        $this->info("Syncing to email recipient list: {$recipientList->name} (ID: {$listId})");

        $addedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        DB::beginTransaction();

        try {
            // Get users created in the last day
            $users = User::where('created_at', '>=', now()->subDays(500))->get();
            $this->info("Found {$users->count()} users created in the last day");

            foreach ($users as $user) {
                $this->addRecipient($user->email, $user->name, $listId, $addedCount, $skippedCount);
            }

            // Get email subscriptions created in the last day
            $subscriptions = EmailSubscription::where('created_at', '>=', now()->subDay())
                ->whereNull('user_id') // Only subscriptions not linked to users
                ->get();
            $this->info("Found {$subscriptions->count()} email subscriptions created in the last day");

            foreach ($subscriptions as $subscription) {
                $this->addRecipient($subscription->email, null, $listId, $addedCount, $skippedCount);
            }

            DB::commit();

            $this->info("Completed: {$addedCount} added, {$skippedCount} skipped (already exists), {$errorCount} errors");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: {$e->getMessage()}");
            Log::error('SyncNewUsersToEmailList error', [
                'list_id' => $listId,
                'error' => $e->getMessage(),
            ]);
            return 1;
        }
    }

    private function addRecipient(string $email, ?string $name, int $listId, int &$addedCount, int &$skippedCount): void
    {
        if (empty($email)) {
            return;
        }

        // Check if recipient already exists in this list
        $exists = EmailRecipient::where('email_recipient_list_id', $listId)
            ->where('email', $email)
            ->exists();

        if ($exists) {
            $this->line("Skipped: {$email} (already in list)");
            $skippedCount++;
            return;
        }

        try {
            EmailRecipient::create([
                'email_recipient_list_id' => $listId,
                'email' => $email,
                'name' => $name,
                'metadata' => [
                    'source' => 'auto_sync',
                    'synced_at' => now()->toIso8601String(),
                ],
            ]);

            $this->line("Added: {$email}");
            $addedCount++;

        } catch (\Exception $e) {
            $this->error("Failed to add {$email}: {$e->getMessage()}");
        }
    }
}
