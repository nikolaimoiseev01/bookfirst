<?php

namespace App\Services;

use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Models\Collection\Collection;
use App\Models\PrintOrder\PrintOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CdekPrintService
{

    public function __construct()
    {
    }

    public function makePrintXlsx($collection, $book_thickness, $book_weight)
    {
        $printOrders = PrintOrder::query()
            ->where('type', PrintOrderTypeEnums::COLLECTION_PARTICIPATION)
            ->where('status', PrintOrderStatusEnums::PRINTING)
            ->where('model_id', $collection['id'])
            ->get();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Номер отправления');
        $sheet->setCellValue('B1', 'Город получателя');
        $sheet->setCellValue('C1', 'Индекс города получателя');
        $sheet->setCellValue('D1', 'Получатель');
        $sheet->setCellValue('E1', 'ФИО получателя');
        $sheet->setCellValue('F1', 'Адрес получателя');
        $sheet->setCellValue('G1', 'Код ПВЗ');
        $sheet->setCellValue('H1', 'Телефон получателя');
        $sheet->setCellValue('I1', 'Доп сбор за доставку с получателя в т.ч. НДС');
        $sheet->setCellValue('J1', 'Ставка НДС с доп.сбора за доставку');
        $sheet->setCellValue('K1', 'Сумма НДС с доп.сбора за доставку');
        $sheet->setCellValue('L1', 'Истинный продавец');
        $sheet->setCellValue('M1', 'Комментарий');
        $sheet->setCellValue('N1', 'Порядковый номер места');
        $sheet->setCellValue('O1', 'Вес места, кг');
        $sheet->setCellValue('P1', 'Длина места, см');
        $sheet->setCellValue('Q1', 'Ширина места, см');
        $sheet->setCellValue('R1', 'Высота места, см');
        $sheet->setCellValue('S1', 'Описание места');
        $sheet->setCellValue('T1', 'Код маркировки');
        $sheet->setCellValue('U1', 'Код товара/артикул');
        $sheet->setCellValue('V1', 'Наименование товара');
        $sheet->setCellValue('W1', 'Стоимость единицы товара');
        $sheet->setCellValue('X1', 'Оплата с получателя за ед товара в т.ч. НДС');
        $sheet->setCellValue('Y1', 'Вес товара, кг');
        $sheet->setCellValue('Z1', 'Количество, шт');
        $sheet->setCellValue('AA1', 'Ставка НДС, %');
        $sheet->setCellValue('AB1', 'Сумма НДС за ед.');
        $sheet->setCellValue('AC1', 'Наименование компании');
        $sheet->setCellValue('AD1', 'Страна продавца');
        $sheet->setCellValue('AE1', 'Форма собственности');
        $sheet->setCellValue('AF1', 'ИНН истинного продавца');
        $sheet->setCellValue('AG1', 'Телефон истинного продавца');

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

        foreach ($printOrders as $key => $printOrder) {

            $comment = $collection['title_short'] . ', ' . $printOrder['books_cnt'] . ' шт.';
            $sending_weight = ($book_weight * $printOrder['books_cnt'] + 20) / 1000;
            $sending_thickness = $book_thickness * $printOrder['books_cnt'] + 1;

            $cdek_desc = $collection['title_short'] . '. ' . $printOrder['books_cnt'] . ' шт. ' . 'part_id=' . $printOrder->model['id'] . '. ' . 'print_id=' . $printOrder['id'];

            $parsedAddressData = $printOrder['address_json']['parsed_data'];

            $sheet->setCellValue('A' . $key + 2, $cdek_desc); // Номер отправления
            $sheet->setCellValue('B' . $key + 2, $parsedAddressData['city']); // Город получателя
            $sheet->setCellValue('C' . $key + 2, $parsedAddressData['postal_code']); // Индекс города получателя
            $sheet->setCellValue('D' . $key + 2, $printOrder['receiver_name']); // Получатель
            $sheet->setCellValue('E' . $key + 2, $printOrder['receiver_name']); // ФИО Получателя
            $sheet->setCellValue('F' . $key + 2, $printOrder['string']); // Адрес получателя
            $sheet->setCellValue('G' . $key + 2, $parsedAddressData['code']); // КОД ПВЗ
            $sheet->setCellValue('H' . $key + 2, $printOrder['receiver_telephone']); // Телефон получателя
            $sheet->setCellValue('I' . $key + 2, 1); // Доп сбор за доставку с получателя в т.ч. НДС
            $sheet->setCellValue('J' . $key + 2, 0); // Ставка НДС с доп.сбора за доставку
            $sheet->setCellValue('K' . $key + 2, 0); // Сумма НДС с доп.сбора за доставку
            $sheet->setCellValue('L' . $key + 2, '');
            $sheet->setCellValue('M' . $key + 2, $comment); // Комментарий
            $sheet->setCellValue('N' . $key + 2, 1); // Порядковый номер места
            $sheet->setCellValue('O' . $key + 2, $sending_weight); // Вес места, кг
            $sheet->setCellValue('P' . $key + 2, '22,9'); // Длина места, см
            $sheet->setCellValue('Q' . $key + 2, '16,5'); // Ширина места, см
            $sheet->setCellValue('R' . $key + 2, $sending_thickness); // Высота места, см
            $sheet->setCellValue('S' . $key + 2, "Книги ({$printOrder['books_cnt']} шт.)"); // Описание места
            $sheet->setCellValue('T' . $key + 2, '');// Код маркировки
            $sheet->setCellValue('U' . $key + 2, $cdek_desc); // Код товара/артикул
            $sheet->setCellValue('V' . $key + 2, 'Книги'); // Наименование товара
            $sheet->setCellValue('W' . $key + 2, 0); // Стоимость единицы товара
            $sheet->setCellValue('X' . $key + 2, 0); // Оплата с получателя за ед товара в т.ч. НДС
            $sheet->setCellValue('Y' . $key + 2, $sending_weight); // Вес товара, кг
            $sheet->setCellValue('Z' . $key + 2, 1); // Количество, шт
            $sheet->setCellValue('AA' . $key + 2, 0); // Ставка НДС, %
            $sheet->setCellValue('AB' . $key + 2, 0); // Сумма НДС за ед.
            $sheet->setCellValue('AC' . $key + 2, ''); // Наименование компании
            $sheet->setCellValue('AD' . $key + 2, ''); // Страна продавца
            $sheet->setCellValue('AE' . $key + 2, ''); // Форма собственности
            $sheet->setCellValue('AF' . $key + 2, ''); // ИНН истинного продавца
            $sheet->setCellValue('AG' . $key + 2, ''); // Телефон истинного продавца
        }

        foreach (range('A', 'D') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }


        $writer = new Xlsx($spreadsheet);
        $fileName= 'Печать ' . $collection['title_short'] . '.xlsx';
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);



    }

}
