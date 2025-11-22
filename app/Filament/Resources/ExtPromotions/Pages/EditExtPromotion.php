<?php

namespace App\Filament\Resources\ExtPromotions\Pages;

use App\Filament\Resources\ExtPromotions\ExtPromotionResource;
use App\Jobs\EmailNotificationJob;
use App\Notifications\Collection\ParticipationStatusUpdate;
use App\Notifications\ExtPromotion\ExtPromotionStatusUpdateNotification;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditExtPromotion extends EditRecord
{
    protected static string $resource = ExtPromotionResource::class;

    protected $oldStatus = null;

    protected function beforeSave(): void
    {
        $this->oldStatus = $this->record->getOriginal('status')->value;
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status')) {
            $notification = new ExtPromotionStatusUpdateNotification($this->record, $this->oldStatus, $this->record['status']->value);
            EmailNotificationJob::dispatch($this->record->user_id, $notification);
        }
    }

    public function getTitle(): HtmlString
    {
        $authorLink = "<a class='text-primary-500' href='/admin/user/users/{$this->record['user_id']}/edit'>{$this->record->user->getUserFullName()}</a>";
        return new HtmlString("Продвижение от автора {$authorLink} на сайте {$this->record['site']}");
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
