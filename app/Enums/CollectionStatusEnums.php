<?php


namespace App\Enums;

enum CollectionStatusEnums: string
{
    case APPS_IN_PROGRESS = 'Идет прием заявок';
    case PREVIEW = 'Предварительная проверка';
    case PRINT_PREPARE = 'Подготовка к печати';
    case PRINTING = 'Идет печать';
    case DONE = 'Сборник издан';

    public function order(): int
    {
        return match($this) {
            self::APPS_IN_PROGRESS => 1,
            self::PREVIEW => 2,
            self::PRINT_PREPARE => 3,
            self::PRINTING => 4,
            self::DONE => 9,
        };
    }

}
