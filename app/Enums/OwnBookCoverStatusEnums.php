<?php


namespace App\Enums;

enum OwnBookCoverStatusEnums: string
{
    case DEVELOPMENT = 'В разработке';
    case PREVIEW = 'На проверке автором';
    case CORRECTIONS = 'Внесение исправлений';
    case READY_FROM_AUTHOR = 'Готова от автора';
    case READY_FOR_PUBLICATION = 'Готова к изданию';
    case WAITING_FOR_AUTHOR_IN_CHAT = 'Ожидание автора в чате';



    public function order(): int
    {
        return match($this) {
            self::DEVELOPMENT => 1,
            self::PREVIEW => 2,
            self::CORRECTIONS => 3,
            self::READY_FOR_PUBLICATION => 4,
            self::READY_FROM_AUTHOR => 9,
            self::WAITING_FOR_AUTHOR_IN_CHAT => 99
        };
    }

}
