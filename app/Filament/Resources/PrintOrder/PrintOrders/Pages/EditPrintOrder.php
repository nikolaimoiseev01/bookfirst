<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Jobs\EmailNotificationJob;
use App\Notifications\PurchasePrint\PurchasePrintStatusUpdateNotification;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditPrintOrder extends EditRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected $oldStatus = null;

    protected function beforeSave(): void
    {
        $this->oldStatus = $this->record->getOriginal('status')->value;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status')) {
            $notification = new PurchasePrintStatusUpdateNotification($this->record, $this->oldStatus, $this->record['status']->value);
            EmailNotificationJob::dispatch($this->record->user_id, $notification);
        }
    }

    public function getTitle(): HtmlString
    {
        $user = $this->record->user;
        $target = $this->record->model;
        return new HtmlString("Заказ печати от <a class='text-primary-500' href='/admin/user/users/{$user['id']}/edit'>{$user->getUserFullName()}</a> на <a class='text-primary-500' href='{$target->adminEditPageWithoutLogin()}'>$target->title</a>");
    }
}
