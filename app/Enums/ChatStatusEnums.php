<?php

namespace App\Enums;

enum ChatStatusEnums: string
{
    case WAIT_FOR_ADMIN = 'Ждет ответа поддержки';
    case ANSWERED = 'Ответ получен';
    case CLOSED = 'Чат закрыт';
    case WAIT_FOR_USER = 'Ждет ответа автора';
    case PERSONAL_CHAT = 'Личная переписка';
    case EMPTY = 'Пустой';

}






