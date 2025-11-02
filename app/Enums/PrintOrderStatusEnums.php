<?php


namespace App\Enums;

use App\Models\PrintOrder\PrintOrderStatus;

enum PrintOrderStatusEnums: string
{
    case CREATED = 'Создан';
    case PAID = 'Оплачен, ждет начала печати';
    case PRINTING = 'Идет печать';
    case SEND_NEED = 'Напечатан, ждет отправки';
    case SENT = 'Отправлен';


}
