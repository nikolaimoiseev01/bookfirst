<?php

namespace App\Filament\Resources\PrintOrder\PrintOrders;

use App\Filament\Resources\PrintOrder\PrintOrders\Pages\CreatePrintOrder;
use App\Filament\Resources\PrintOrder\PrintOrders\Pages\EditPrintOrder;
use App\Filament\Resources\PrintOrder\PrintOrders\Pages\ListPrintOrders;
use App\Filament\Resources\PrintOrder\PrintOrders\Pages\ViewPrintOrder;
use App\Filament\Resources\PrintOrder\PrintOrders\Schemas\PrintOrderForm;
use App\Filament\Resources\PrintOrder\PrintOrders\Schemas\PrintOrderInfolist;
use App\Filament\Resources\PrintOrder\PrintOrders\Tables\PrintOrdersTable;
use App\Models\PrintOrder\PrintOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrintOrderResource extends Resource
{
    protected static ?string $model = PrintOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPrinter;

    protected static ?string $label = 'Заказы печати';
    protected static ?string $navigationLabel = 'Заказы печати';
    protected static ?string $pluralLabel = 'Заказы печати';


    public static function form(Schema $schema): Schema
    {
        return PrintOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PrintOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrintOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrintOrders::route('/'),
            'create' => CreatePrintOrder::route('/create'),
            'edit' => EditPrintOrder::route('/{record}/edit'),
        ];
    }
}
