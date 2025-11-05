<?php


namespace App\Enums;

enum OwnBookStatusEnums: string
{
    case REVIEW = 'Рассмотрение заявки';
    case PAYMENT_REQUIRED = 'Необходима оплата (кроме печати)';
    case WORK_IN_PROGRESS = 'Идёт работа с файлами';
    case PRINT_PAYMENT_REQUIRED = 'Необходима оплата печати';
    case PRINT_WAITING = 'Печать оплачена, скоро начнётся';
    case PRINTING = 'Идёт печать книги';
    case DONE = 'Процесс завершён';
    case NOT_ACTUAL = 'Неактуально';


    public function order(): int
    {
        return match($this) {
            self::REVIEW => 1,
            self::PAYMENT_REQUIRED => 2,
            self::WORK_IN_PROGRESS => 3,
            self::PRINT_PAYMENT_REQUIRED => 4,
            self::PRINT_WAITING => 5,
            self::PRINTING => 6,
            self::DONE => 7,
            self::NOT_ACTUAL => 9,
        };
    }

}
