<?php

namespace App\Enums;


enum TransactionTypeEnums: string
{
    case ADDITIONAL_COLLECTION_RESERVATION = 'Бронирование дополнительных печатных экземпляров сборника';
    case BOOK_PUBLISHING_NO_PRINT = 'Оплата издания собственной книги (без печати)';
    case BOOK_SHIPPING = 'Оплата пересылки собственной книги';
    case COLLECTION_SHIPPING = 'Оплата пересылки сборника';
    case BOOK_PRINT = 'Оплата печати собственной книги';
    case COLLECTION_PARTICIPATION = 'Оплата участия в сборнике';
    case BOOK_EBOOK_PURCHASE = 'Покупка электронного экземпляра собственной книги';
    case COLLECTION_EBOOK_PURCHASE = 'Покупка электронного экземпляра сборника';
    case WALLET_TOP_UP = 'Пополнение кошелька';
    case PROMOTION_PAYMENT = 'Оплата продвижения';
}
