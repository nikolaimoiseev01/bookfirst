<?php


namespace App\Enums;

enum ParticipationStatusEnums: string
{
    case APPROVE_NEEDED = 'Ожидается подтверждение заявки';
    case PAYMENT_NEEDED = 'Заявка подтверждена, ожидается оплата';
    case APPROVED = 'Участие подтверждено';
    case NOT_ACTUAL = 'Заявка неактуальна';

    public function order(): int
    {
        return match($this) {
            self::APPROVE_NEEDED => 1,
            self::PAYMENT_NEEDED => 2,
            self::APPROVED => 3,
            self::NOT_ACTUAL => 9,
        };
    }

}
