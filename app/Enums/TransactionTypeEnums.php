<?php

namespace App\Enums;


enum TransactionTypeEnums: string
{
    case COLLECTION_PARTICIPATION = 'Оплата участия в сборнике';
    case COLLECTION_ADDITIONAL_RESERVATION = 'Бронирование дополнительных печатных экземпляров сборника';
    case COLLECTION_SHIPPING = 'Оплата пересылки сборника';
    case OWN_BOOK_WO_PRINT = 'Оплата издания собственной книги (без печати)';
    case OWN_BOOK_SHIPPING = 'Оплата пересылки собственной книги';
    case OWN_BOOK_PRINT = 'Оплата печати собственной книги';
    case OWN_BOOK_EBOOK_PURCHASE = 'Покупка электронного экземпляра собственной книги';
    case COLLECTION_EBOOK_PURCHASE = 'Покупка электронного экземпляра сборника';
    case WALLET_TOP_UP = 'Пополнение кошелька';
    case EXT_PROMOTION_PAYMENT = 'Оплата продвижения';
}
