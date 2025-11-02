<?php


namespace App\Enums;


enum TransactionStatusEnums: string
{
    case CREATED = 'CREATED';
    case FAILED = 'FAILED';
    case CONFIRMED = 'CONFIRMED';
}
