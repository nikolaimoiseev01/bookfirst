<?php

namespace App\Filament\Resources\Collection\Collections\Pages;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Filament\Resources\Collection\Collections\CollectionResource;
use App\Jobs\EmailNotificationJob;
use App\Jobs\PdfCutJob;
use App\Notifications\Collection\CollectionStatusUpdate;
use App\Notifications\Collection\CollectionWinnerNotification;
use App\Services\PdfService;
use Filament\Resources\Pages\EditRecord;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            DeleteAction::make(),
        ];
    }


    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status')) {
            $participations = $this->record->participations()->get();

            if ($this->record['status'] == CollectionStatusEnums::PRINT_PREPARE) {
                $this->record->participations()->where('status', '<>', ParticipationStatusEnums::APPROVED)->update([
                    'status' => ParticipationStatusEnums::NOT_ACTUAL
                ]);
            }
            if ($this->record['status'] == CollectionStatusEnums::PRINTING) {
                $participations = $this->record->approvedParticipations()->get();
                foreach ($participations as $participation) {
                    if($participation->printOrder ?? null) {
                        $participation->printOrder->update([
                            'status' => PrintOrderStatusEnums::PRINTING
                        ]);
                    }
                }

                PdfCutJob::dispatch(
                    $this->record,
                    $this->record->getFirstMediaPath('inside_file'),
                    10,
                    'inside_file_preview'
                );
            }

            foreach ($participations as $participation) {
                $notification = new CollectionStatusUpdate($this->record, $participation['id'], $this->record['status']);
                EmailNotificationJob::dispatch($participation['user_id'], $notification);
            }
        }

        if ($this->record->wasChanged('winner_participations')) {
            foreach ($this->record->winnerParticipations()->get() as $key => $winnerParticipation) {
                $notification = new CollectionWinnerNotification($this->record, $key + 1, $winnerParticipation['id']);
                EmailNotificationJob::dispatch($winnerParticipation['user_id'], $notification);
            }
        }
    }

    public
    function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public
    function getTitle(): string
    {
        return $this->record['title'];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Общее';
    }
}
