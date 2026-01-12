<?php

namespace App\Enums;

use App\Models\AlmostCompleteAction;
use App\Models\Collection\Collection;

enum AlmostCompleteActionTypeEnums: string
{
    case PARTICIPATION = 'Participation';

    public function payload(AlmostCompleteAction $aca): array
    {
        return match ($this) {
            self::PARTICIPATION => $this->participationPayload($aca),
        };
    }


    public function label(): string
    {
        return match ($this) {
            self::PARTICIPATION => 'Участие в сборнике',
        };
    }

    private function participationPayload(AlmostCompleteAction $aca): array
    {
        $collection = Collection::find($aca->data['collection_id']);

        return [
            'email_text' => sprintf(
                "Мы заметили, что вы начали заполнять заявку на участие в сборнике «%s», но не закончили. \nНам понравились ваши произведения, и мы хотим предоставить вам промокод на скидку в 30%% на участие в этом сборнике: ALMOST_30",
                $collection?->title ?? ''
            ),
            'unsubscribe_text' => sprintf(
                "Вы отписались от уведомлений о заявке в сборник «%s»",
                $collection?->title ?? ''
            ),
            'url' => route('account.participation.create', [
                'collection_id' => $collection->id,
            ]),
        ];
    }
}
