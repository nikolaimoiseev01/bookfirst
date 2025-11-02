<?php

namespace App\Filament\Resources\Collection\Participations\Pages;

use App\Enums\ParticipationStatusEnums;
use App\Enums\TransactionStatusEnums;
use App\Filament\Resources\Collection\Participations\ParticipationResource;
use App\Jobs\EmailNotificationJob;
use App\Models\Chat\Chat;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Notifications\Collection\ParticipationStatusUpdate;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditParticipation extends EditRecord
{
    protected static string $resource = ParticipationResource::class;
    protected $oldStatus = null;
    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')
                ->action(function (Participation $record) {
                    Chat::query()
                        ->where('model_type', 'Collection')
                        ->where('model_id', $record->id)
                        ->delete();
                    ParticipationWork::query()
                        ->where('participation_id', $record->id)
                        ->delete();
                    Participation::query()
                        ->where('id', $record->id)
                        ->delete();
                    return redirect('/admin')->with('success', 'Участие успешно удалено!');
                })
                ->requiresConfirmation()
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $paidAmount = (int) $this->record->transactions()
            ->where('status', TransactionStatusEnums::CONFIRMED)
            ->sum('amount');

        $current = $this->record->price_total
            + ($this->record->printOrder->price_print ?? 0);

        if ($paidAmount == $current) {
            $data['status'] = ParticipationStatusEnums::APPROVED;
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        $this->oldStatus = $this->record->getOriginal('status')->value;
    }
    protected function afterSave(): void
    {
        if ($this->record->wasChanged('author_name')) {
            dd('author_name changed!');
        }

        if ($this->record->wasChanged('status')) {
            $notification = new ParticipationStatusUpdate($this->record, $this->oldStatus, $this->record['status']->value);
            EmailNotificationJob::dispatch($this->record->user_id, $notification);
        }
    }

    public function getTitle(): HtmlString
    {
        $authorLink = "<a class='text-primary-500' href='/admin/user/users/{$this->record['user_id']}/edit'>{$this->record['author_name']}</a>";
        $collectionLink = "<a class='text-primary-500' href='/admin/collection/collections/{$this->record['collection_id']}/edit'>{$this->record->collection['title_short']}</a>";
        return new HtmlString("Участие {$authorLink} в {$collectionLink}");
    }
}
