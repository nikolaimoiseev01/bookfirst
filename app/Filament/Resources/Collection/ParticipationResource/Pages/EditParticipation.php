<?php

namespace App\Filament\Resources\Collection\ParticipationResource\Pages;

use App\Filament\Resources\Collection\ParticipationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class EditParticipation extends EditRecord
{
    protected static string $resource = ParticipationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle():  Htmlable
    {
        $user = $this->record->user;
        $user_full_name = "{$user['name']} {$user['surname']}";
        if ($user_full_name == $this->record['author_name']) {
            $name = $user_full_name;
        } else {
            $name = "{$this->record['author_name']} ({$user_full_name})";
        }
        $collection = $this->record->collection;
        return new HtmlString("Участие автора <a class='text-primary-600' href=''>$name</a><br>в сборнике <a class='text-primary-600' href='/admin/collection/collections/{$collection['id']}/edit'>{$collection['name_short']}</a>") ;
    }
}
