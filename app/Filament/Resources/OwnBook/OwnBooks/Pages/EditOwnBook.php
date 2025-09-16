<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Pages;

use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

class EditOwnBook extends EditRecord
{
    protected static string $resource = OwnBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            ViewAction::make(),
//            DeleteAction::make(),
        ];
    }

    public function getTitle(): HtmlString
    {
        $author = $this->record['author'];
        $title = $this->record['title'];
        $user_id = $this->record['user_id'];
        return new HtmlString("<a class='text-primary-500' href='/admin/user/users/{$user_id}/edit'>{$author}</a>: {$title}");
    }
}
