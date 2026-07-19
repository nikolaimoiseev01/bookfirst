<?php


namespace App\Enums;

enum EmailCampaignStatusEnums: string
{
    case DRAFT = 'Черновик';
    case SCHEDULED = 'Запланирована';
    case SENDING = 'Отправляется';
    case SENT = 'Отправлена';
    case FAILED = 'Ошибка отправки';

    public function order(): int
    {
        return match($this) {
            self::DRAFT => 1,
            self::SCHEDULED => 2,
            self::SENDING => 3,
            self::SENT => 4,
            self::FAILED => 9,
        };
    }

}
