<?php

namespace App\Jobs;

use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessManualRecipientsJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
        public EmailRecipientList $list,
        public string $recipients
    ) {}

    public function handle(): void
    {
        $lines = explode("\n", $this->recipients);
        $batchSize = 100;
        $recipients = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = [
                    'email' => $line,
                    'name' => null,
                ];

                if (count($recipients) >= $batchSize) {
                    $this->processBatch($recipients);
                    $recipients = [];
                }
            }
        }

        // Process remaining recipients
        if (!empty($recipients)) {
            $this->processBatch($recipients);
        }
    }

    private function processBatch(array $recipients): void
    {
        foreach ($recipients as $recipientData) {
            // Check if email already exists in any recipient list globally
            $existingRecipient = EmailRecipient::where('email', $recipientData['email'])->first();

            if (!$existingRecipient) {
                EmailRecipient::create([
                    'email_recipient_list_id' => $this->list->id,
                    'email' => $recipientData['email'],
                    'name' => $recipientData['name'],
                ]);
            }
        }
    }
}
