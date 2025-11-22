<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Pages;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditPrintOrder extends EditRecord
{
    protected static string $resource = PrintOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getTitle(): HtmlString
    {
        $user = $this->record->user;
        $target = $this->record->model;
        return new HtmlString("Заказ печати от <a class='text-primary-500' href='/admin/user/users/{$user['id']}/edit'>{$user->getUserFullName()}</a> на <a class='text-primary-500' href='{$target->adminEditPage()}'>$target->title</a>");
    }
}
