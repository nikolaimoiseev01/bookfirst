<?php


namespace App\Enums;

use App\Models\PrintOrder\PrintOrderStatus;

enum PrintOrderTypeEnums: string
{
    case COLLECTION_PARTICIPATION = 'Сборники при участии';
    case COLLECTION_ONLY = 'Сборники отдельно';
    case OWN_BOOK_PUBLISH = 'Книги при издании';
    case OWN_BOOK_ONLY = 'Собственные книги отдельно';


}
