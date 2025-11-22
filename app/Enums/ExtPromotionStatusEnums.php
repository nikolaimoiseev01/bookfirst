<?php


namespace App\Enums;

enum ExtPromotionStatusEnums: string
{
    case REVIEW = 'На проверке';
    case PAYMENT_REQUIRED = 'Необходима оплата';
    case START_REQUIRED = 'Оплата подтверждена, ожидание продвижения';
    case IN_PROGRESS = 'Идет продвижение';
    case DONE = 'Продвижение завершено';
    case WAITING_FOR_AUTHOR_IN_CHAT = 'Ожидание автора в чате';
    case NOT_ACTUAL = 'Заявка неактуальна';

    public function order(): int
    {
        return match($this) {
            self::REVIEW => 1,
            self::PAYMENT_REQUIRED => 2,
            self::START_REQUIRED => 3,
            self::IN_PROGRESS => 4,
            self::DONE => 9,
            self::WAITING_FOR_AUTHOR_IN_CHAT => 99,
            self::NOT_ACTUAL => 999,
        };
    }

}
