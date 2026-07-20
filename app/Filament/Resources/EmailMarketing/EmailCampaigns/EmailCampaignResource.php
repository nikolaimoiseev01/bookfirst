<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns;

use App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages\CreateEmailCampaign;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages\EditEmailCampaign;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages\ListEmailCampaigns;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages\ViewEmailCampaign;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\RelationManagers\CampaignRecipientsRelationManager;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\Schemas\EmailCampaignForm;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\Tables\EmailCampaignsTable;
use App\Models\EmailMarketing\EmailCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class EmailCampaignResource extends Resource
{
    protected static ?string $model = EmailCampaign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Email кампания';
    protected static ?string $navigationLabel = 'Email кампании';
    protected static ?string $pluralLabel = 'Email кампании';


    public static function form(Schema $schema): Schema
    {
        return EmailCampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailCampaignsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
//            CampaignRecipientsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEmailCampaigns::route('/'),
            'create' => CreateEmailCampaign::route('/create'),
            'view' => ViewEmailCampaign::route('/{record}'),
            'edit' => EditEmailCampaign::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'subject'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Статус' => $record->status,
            'Запланировано' => $record->scheduled_at?->format('Y-m-d H:i') ?? '-',
        ];
    }
}
