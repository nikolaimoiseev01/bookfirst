<?php


namespace App\Enums;

use App\Models\Award\AwardType;

enum AwardTypeEnums: string
{
    case COLLECTION_PRIZE_FIRST = '1 место в сборнике';
    case COLLECTION_PRIZE_SECOND = '2 место в сборнике';
    case COLLECTION_PRIZE_THIRD = '3 место в сборнике';
    case COLLECTION_PARTICIPANT = 'Участие в сборнике';
    case OWN_BOOK_PUBLISHING = 'Издание книги';

    public function id(): int
    {
        static $cache = [];

        return $cache[$this->value] ??= AwardType::query()
            ->where('name', $this->value)
            ->value('id');
    }

}
