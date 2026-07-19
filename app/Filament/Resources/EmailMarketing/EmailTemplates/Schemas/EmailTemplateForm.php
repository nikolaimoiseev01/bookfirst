<?php

namespace App\Filament\Resources\EmailMarketing\EmailTemplates\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Основная информация')->schema([
                    TextInput::make('name')
                        ->label('Название шаблона')
                        ->required()
                        ->maxLength(255),
                ]),
                Section::make('Содержание')->schema([
                    CodeEditor::make('html_content')
                        ->label('HTML содержимое')
                        ->required()
                        ->language(Language::Html)
                        ->wrap()
                        ->helperText('Используйте {{ переменная }} для динамического контента'),
                    Textarea::make('text_content')
                        ->label('Текстовая версия (опционально)')
                        ->rows(5)
                        ->nullable(),
                ]),
                Section::make('Переменные')->schema([
                    KeyValue::make('variables')
                        ->label('Переменные шаблона')
                        ->keyLabel('Имя переменной')
                        ->valueLabel('Описание')
                        ->addable(true)
                        ->deletable(true)
                        ->helperText('Определите переменные, которые можно использовать в шаблоне'),
                ]),
            ]);
    }
}
