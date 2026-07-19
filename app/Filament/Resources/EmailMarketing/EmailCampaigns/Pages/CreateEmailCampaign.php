<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Pages;

use App\Filament\Resources\EmailMarketing\EmailCampaigns\EmailCampaignResource;
use App\Models\EmailMarketing\CampaignRecipient;
use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailCampaignRecipient;
use App\Models\EmailMarketing\EmailRecipient;
use App\Models\EmailMarketing\Recipient;
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
        $campaign = $this->record;

        // Create campaign recipients from the recipient list
        $recipients = EmailRecipient::where('email_recipient_list_id', $campaign->email_recipient_list_id)->get();


        foreach ($recipients as $recipient) {
            EmailCampaignRecipient::create([
                'email_campaign_id' => $campaign->id,
                'email_recipient_id' => $recipient->id,
                'mailganer_status' => 'pending',
            ]);
        }
    }
}
