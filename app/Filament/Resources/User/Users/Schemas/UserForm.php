<?php

namespace App\Filament\Resources\User\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('surname')
                    ->required(),
                TextInput::make('nickname'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                DateTimePicker::make('last_seen'),
                TextInput::make('reg_utm_source'),
                TextInput::make('reg_utm_medium'),
                Textarea::make('comment')
                    ->columnSpanFull(),
                TextInput::make('reg_type'),
            ]);
    }
}
