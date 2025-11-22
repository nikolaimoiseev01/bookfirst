<?php

namespace App\Filament\Resources\User\Users\Pages;

use App\Filament\Resources\User\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('loginAs')
                ->label('Войти в аккаунт')
                ->action(function (Model $record) {
                       Auth::loginUsingId($record->id);
                    return redirect()->route('account.participations');
                }
                )
        ];
    }
    public function getTitle(): HtmlString
    {
        $name = $this->record['name'] . ' ' . $this->record['surname'];
        $nickname = $this->record['nickname'];
        $avatar = getUserAvatar($this->record);
        $avatar = "<img class='w-8 rounded-full' src='{$avatar}'/>";
        return new HtmlString("<div class='flex gap-2'>$avatar {$name} ({$nickname})</div>");
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Общее';
    }
}
