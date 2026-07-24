<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages;

use App\Filament\Resources\EmailMarketing\EmailCampaigns\EmailCampaignResource;
use App\Jobs\BulkCreateEmailCampaignsJob;
use App\Jobs\CreateOnlyOneEmailCampaignJob;
use App\Services\EmailMarketing\CreateEmailCampaignService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateEmailCampaign extends CreateRecord
{
    protected static string $resource = EmailCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }

    protected function afterCreate(): void
    {
        CreateOnlyOneEmailCampaignJob::dispatch($this->record->id);
    }
}
