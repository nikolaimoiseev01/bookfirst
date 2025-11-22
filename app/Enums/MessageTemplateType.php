<?php


namespace App\Enums;

enum MessageTemplateType: string
{
    case COLLECTION = 'Работа со сборником';
    case OWN_BOOK_GENERAL = 'Работа со книгой';
    case OWN_BOOK_INSIDE = 'Работа со книгой (ВБ)';
    case OWN_BOOK_COVER = 'Работа со книгой (Обложка)';
}
