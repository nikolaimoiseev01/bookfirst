<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages;

use App\Filament\Resources\EmailMarketing\EmailRecipientLists\EmailRecipientListResource;
use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\EmailRecipientList;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateEmailRecipientList extends CreateRecord
{
    protected static string $resource = EmailRecipientListResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Process CSV file if uploaded
        if ($this->data['csv_file'] ?? null) {
            $this->processCsvFile($record, $this->data['csv_file']);
        }

        // Process manual recipients if provided
        if ($this->data['manual_recipients'] ?? null) {
            $this->processManualRecipients($record, $this->data['manual_recipients']);
        }
    }

    private function processCsvFile(EmailRecipientList $list, $filePath): void
    {
        $fullPath = Storage::disk('public')->path($filePath);
        if (file_exists($fullPath)) {
            $file = fopen($fullPath, 'r');
            while (($row = fgetcsv($file)) !== false) {
                if (isset($row[0]) && filter_var($row[0], FILTER_VALIDATE_EMAIL)) {
                    EmailRecipient::firstOrCreate([
                        'email_recipient_list_id' => $list->id,
                        'email' => $row[0],
                    ], [
                        'name' => $row[1] ?? null,
                    ]);
                }
            }
            fclose($file);
        }
    }

    private function processManualRecipients(EmailRecipientList $list, string $recipients): void
    {
        $lines = explode("\n", $recipients);
        foreach ($lines as $line) {
            $line = trim($line);
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) {
                EmailRecipient::firstOrCreate([
                    'email_recipient_list_id' => $list->id,
                    'email' => $line,
                ], [
                    'name' => null,
                ]);
            }
        }
    }
}
