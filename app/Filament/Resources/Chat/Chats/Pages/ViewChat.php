<?php

namespace App\Filament\Resources\Chat\Chats\Pages;

use App\Filament\Resources\Chat\Chats\ChatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

class ViewChat extends ViewRecord
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getTitle(): HtmlString
    {
        $author = $this->record->userCreated;
        $authorLink = "<a class='text-primary-500' href='/admin/user/users/{$author['id']}/edit'>{$author->getUserFullName()}</a>";
        return new HtmlString("Чат автора {$authorLink}: {$this->record->title}");
    }
}
