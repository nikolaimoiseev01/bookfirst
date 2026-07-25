<?php

namespace App\Services\EmailMarketing;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\Collection;
use App\Models\EmailMarketing\EmailTemplate;
class EmailTemplateRenderService
{
    public function renderHTML(int $templateId, ?string $utmCampaign, ?string $promoCode = null): string
    {
        $template = EmailTemplate::findOrFail($templateId);

        $collectionsHtml = Collection::query()
            ->where('status', CollectionStatusEnums::APPS_IN_PROGRESS)
            ->get()
            ->map(fn (Collection $collection) => $this->renderCollectionBlock($collection))
            ->implode('');

        $finalHtml = str_replace('{{ACTUAL_COLLECTIONS}}', $collectionsHtml, $template->html_content);
        $finalHtml = str_replace('{{UTM_CAMPAIGN}}', $utmCampaign ?? '', $finalHtml);
        $finalHtml = str_replace('{{PROMOCODE}}', $promoCode ?? '', $finalHtml);
        return $finalHtml;
    }

    private function renderCollectionBlock(Collection $collection): string
    {
        return view('emails.collection-email-card', [
            'collection' => $collection,
        ])->render();
    }
}
