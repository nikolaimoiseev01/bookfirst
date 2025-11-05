<?php


namespace App\Enums;

enum OwnBookInsideStatusEnums: string
{
    case DEVELOPMENT = 'В разработке';
    case PREVIEW = 'На проверке автором';
    case CORRECTIONS = 'Внесение исправлений';
    case READY_FROM_AUTHOR = 'Готов от автора';
    case READY_FOR_PUBLICATION = 'Готов к изданию';
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
