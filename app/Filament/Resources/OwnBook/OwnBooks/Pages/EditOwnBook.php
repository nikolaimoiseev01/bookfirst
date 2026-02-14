<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Pages;

use App\Enums\OwnBookStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Jobs\EmailNotificationJob;
use App\Jobs\InnerTaskUpdateJob;
use App\Jobs\PdfCutJob;
use App\Notifications\Collection\ParticipationStatusUpdate;
use App\Notifications\OwnBook\OwnBookStatusUpdateNotification;
use App\Services\InnerTasksService;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EditOwnBook extends EditRecord
{
    protected static string $resource = OwnBookResource::class;

    public $oldStatusGeneral;
    public $oldStatusInside;
    public $oldStatusCover;

    protected function getHeaderActions(): array
    {
        return [
//            ViewAction::make(),
//            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->oldStatusGeneral = $this->record->getOriginal('status_general');
        $this->oldStatusInside = $this->record->getOriginal('status_inside');
        $this->oldStatusCover = $this->record->getOriginal('status_cover');
    }

    protected function sendStatusUpdateNotification(): void
    {
        foreach (['general', 'inside', 'cover'] as $type) {
            $field = "status_{$type}";
            $old = "oldStatus" . ucfirst($type);
            if ($this->record->wasChanged($field)) {
                EmailNotificationJob::dispatch(
                    $this->record->user_id,
                    new OwnBookStatusUpdateNotification(
                        $this->record,
                        $field,
                        $this->$old->value,
                        $this->record[$field]->value
                    )
                );
            }
        }
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('author_name')) {
            dd('author_name changed!');
        }
        $this->sendStatusUpdateNotification();

        if ($this->record->wasChanged('status_general') && $this->record['status_general'] == OwnBookStatusEnums::PRINTING) {
            $this->record->initialPrintOrder?->update([
                'status' => PrintOrderStatusEnums::PRINTING->value,
            ]);
            $this->record->update([
                'deadline_print' => Carbon::now()->addDays(18),
            ]);
        }
        InnerTaskUpdateJob::dispatch();
    }

    public function getTitle(): HtmlString
    {
        $author = $this->record['author'];
        $title = $this->record['title'];
        $user_id = $this->record['user_id'];
        return new HtmlString("<a class='text-primary-500' href='/admin/user/users/{$user_id}/edit'>{$author}</a>: {$title}");
    }
}
