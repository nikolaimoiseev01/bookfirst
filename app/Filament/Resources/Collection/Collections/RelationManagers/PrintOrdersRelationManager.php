<?php

namespace App\Filament\Resources\Collection\Collections\RelationManagers;

use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Services\CdekPrintService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PrintOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'printOrders';

    protected static ?string $title = 'Заказы печати';

    protected static ?string $relatedResource = PrintOrderResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('downloadPrint')
                    ->label('Скачать печать')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        TextInput::make('thickness')
                            ->label('Толщина')
                            ->required()
                            ->default(4)
                            ->numeric(),

                        TextInput::make('weight')
                            ->label('Вес')
                            ->default(200)
                            ->required()
                            ->numeric(),
                    ])
                    ->action(function (array $data, $livewire) {

                        /** @var Model $record */
                        $record = $livewire->ownerRecord;
                        // ownerRecord — это родительская модель RelationManager

                        return app(CdekPrintService::class)
                            ->makePrintXlsx(
                                collection: $record,
                                book_thickness: $data['thickness'],
                                book_weight: $data['weight'],
                            );
                    })
                    ->modalHeading('Параметры печати')
                    ->modalSubmitActionLabel('Скачать'),
            ]);
    }

    public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
    {
        return Tab::make('Заказы печати')
            ->badge($ownerRecord->printOrders->count());
    }
}
