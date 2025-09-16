<?php

namespace App\Filament\Resources\User\Users\Pages;

use App\Filament\Resources\User\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
                    return redirect()->route('account.collections');
                }
                )
        ];
    }
    public function getTitle(): string
    {
        $name = $this->record['name'] . ' ' . $this->record['surname'];
        $nickname = $this->record['nickname'];
        return "{$name} ({$nickname})";
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
