<?php

namespace App\Jobs;

use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessCsvFileJob implements ShouldQueue
{
    use Batchable, Queueable;

    public function __construct(
        public EmailRecipientList $list,
        public string $filePath
    ) {}

    public function handle(): void
    {
        $fullPath = Storage::disk('public')->path($this->filePath);

        if (!file_exists($fullPath)) {
            return;
        }

        $file = fopen($fullPath, 'r');
        $batchSize = 100;
        $recipients = [];

        while (($row = fgetcsv($file)) !== false) {
            if (isset($row[0]) && filter_var($row[0], FILTER_VALIDATE_EMAIL)) {
                $recipients[] = [
                    'email' => $row[0],
                    'name' => $row[1] ?? null,
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

        fclose($file);

        // Delete the file after processing
        Storage::disk('public')->delete($this->filePath);
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
