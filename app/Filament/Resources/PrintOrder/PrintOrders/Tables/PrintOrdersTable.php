<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders\Tables;

use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Services\Cdek\CdekPrintService;
use App\Services\Cdek\CdekTracksUpdateService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PrintOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        PrintOrderStatusEnums::CREATED => 'primary',
                        PrintOrderStatusEnums::PAID, PrintOrderStatusEnums::PRINTING => 'warning',
                        PrintOrderStatusEnums::SEND_NEED => 'danger',
                        PrintOrderStatusEnums::SENT => 'success',
                    }),
                TextColumn::make('type')
                    ->label('Тип')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.title')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('books_cnt')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('inside_color')
                    ->searchable(),
                TextColumn::make('pages_color')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cover_type')
                    ->searchable(),
                TextColumn::make('price_print')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_send')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('receiver_name')
                    ->searchable(),
                TextColumn::make('receiver_telephone')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('address_json')
                    ->getStateUsing(function(Model $record) {
                        return $record['address_json']['string'];
                    })
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('printingCompany.name')
                    ->sortable(),
                TextColumn::make('logisticCompany.name')
                    ->sortable(),
                TextInputColumn::make('track_number')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->multiple()
                    ->default([PrintOrderStatusEnums::CREATED->value, PrintOrderStatusEnums::PAID->value, PrintOrderStatusEnums::PRINTING->value])
                    ->options([
                        collect(PrintOrderStatusEnums::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->value])
                            ->toArray()
                    ])
                    ->multiple()
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                Action::make('importTracks')
                    ->label('Импорт треков CDEK')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        FileUpload::make('file')
                            ->label('XLSX файл')
                            ->disk('local')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ])
                            ->required()
                            ->directory('imports') // storage/app/imports
                            ->preserveFilenames(),
                    ])
                    ->action(function (array $data) {

                            $filePath = storage_path('app/private/' . $data['file']);

                            $updatedRows = app(CdekTracksUpdateService::class)
                                ->import($filePath);

                            Notification::make()
                                ->title('Все ок ✅')
                                ->success()
                                ->body("Успешно обновили $updatedRows строк")
                                ->send();

                    })
                    ->modalHeading('Импорт треков')
                    ->modalSubmitActionLabel('Импортировать'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
