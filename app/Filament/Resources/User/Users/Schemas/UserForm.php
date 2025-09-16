<?php

namespace App\Filament\Resources\User\Users\Schemas;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required(),
                    DateTimePicker::make('email_verified_at'),
                    DateTimePicker::make('last_seen'),
                    TextInput::make('reg_utm_source'),
                    TextInput::make('reg_utm_medium'),
                    TextInput::make('reg_type'),
                    Textarea::make('comment')->columnSpanFull(),
                ])->columns(3)->columnSpanFull()
            ]);
    }
}
