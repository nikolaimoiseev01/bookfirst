<?php


namespace App\Enums;

use App\Models\Award\AwardType;

enum AddressTypeEnums: string
{
    case OLD_V1 = 'OLD v1';
    case OLD_V2 = 'OLD v2';
    case DADATA_RUS = 'DaData RUS';
    case FOREIGN = 'foreign';
    case CDEK = 'СДЭК';

}
