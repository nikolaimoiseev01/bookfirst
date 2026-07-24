<?php

namespace App\Filament\Resources\EmailMarketing\EmailRecipientLists\Pages;

use App\Filament\Resources\EmailMarketing\EmailRecipientLists\EmailRecipientListResource;
use App\Jobs\ProcessCsvFileJob;
use App\Jobs\ProcessManualRecipientsJob;
use App\Models\EmailMarketing\EmailRecipientList;
use Filament\Resources\Pages\EditRecord;

class EditEmailRecipientList extends EditRecord
{
    protected static string $resource = EmailRecipientListResource::class;

    protected function afterSave(): void
    {
        $record = $this->record;

        // Process CSV file if uploaded
        if ($this->data['csv_file'] ?? null) {
            $csvFilePath = is_array($this->data['csv_file'])
                ? array_values($this->data['csv_file'])[0]
                : $this->data['csv_file'];
            ProcessCsvFileJob::dispatch($record, $csvFilePath);
        }

        // Process manual recipients if provided
        if ($this->data['manual_recipients'] ?? null) {
            ProcessManualRecipientsJob::dispatch($record, $this->data['manual_recipients']);
        }
    }
}
