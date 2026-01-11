<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PreviewCommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'previewComments';

    protected static ?string $title = 'Исправления';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('text')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('page'),
                TextEntry::make('text'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('model.author_name')
                    ->searchable(),
                ToggleColumn::make('flg_done'),
                TextColumn::make('page')
                    ->searchable(),
                TextColumn::make('text')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading(fn (Model $record) =>
                    "Исправление автора {$record->model?->author_name}"
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Исправления')
            ->badge($ownerRecord->previewComments->count());
    }
}
