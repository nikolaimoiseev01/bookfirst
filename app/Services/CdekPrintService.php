<?php

namespace App\Services;

use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\PrintOrder\PrintOrder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CdekPrintService
{

    public $collection;
    public $book_thickness;
    public $book_weight;

    public function __construct($collection, $book_thickness, $book_weight)
    {
        $this->collection = $collection;
        $this->book_thickness = $book_thickness;
        $this->book_weight = $book_weight;
    }

    public function makeColumnsRus()
    {
        return [
            'A1' => 'Номер отправления',
            'B1' => 'Город получателя',
            'C1' => 'Индекс города получателя',
            'D1' => 'Получатель',
            'E1' => 'ФИО получателя',
            'F1' => 'Адрес получателя',
            'G1' => 'Код ПВЗ',
            'H1' => 'Телефон получателя',
            'I1' => 'Доп сбор за доставку с получателя в т.ч. НДС',
            'J1' => 'Ставка НДС с доп.сбора за доставку',
            'K1' => 'Сумма НДС с доп.сбора за доставку',
            'L1' => 'Истинный продавец',
            'M1' => 'Комментарий',
            'N1' => 'Порядковый номер места',
            'O1' => 'Вес места, кг',
            'P1' => 'Длина места, см',
            'Q1' => 'Ширина места, см',
            'R1' => 'Высота места, см',
            'S1' => 'Описание места',
            'T1' => 'Код маркировки',
            'U1' => 'Код товара/артикул',
            'V1' => 'Наименование товара',
            'W1' => 'Стоимость единицы товара',
            'X1' => 'Оплата с получателя за ед товара в т.ч. НДС',
            'Y1' => 'Вес товара, кг',
            'Z1' => 'Количество, шт',
            'AA1' => 'Ставка НДС, %',
            'AB1' => 'Сумма НДС за ед.',
            'AC1' => 'Наименование компании',
            'AD1' => 'Страна продавца',
            'AE1' => 'Форма собственности',
            'AF1' => 'ИНН истинного продавца',
            'AG1' => 'Телефон истинного продавца',
        ];
    }

    public function fillValuesRus($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness)
    {
        $parsedAddressData = $printOrder['address_json']['parsed_data'];

        $sheet->setCellValue('A' . $key + 2, $cdek_desc); // Номер отправления
        $sheet->setCellValue('B' . $key + 2, $parsedAddressData['city'] ?? ''); // Город получателя
        $sheet->setCellValue('C' . $key + 2, $parsedAddressData['postal_code'] ?? ''); // Индекс города получателя
        $sheet->setCellValue('D' . $key + 2, $printOrder['receiver_name']); // Получатель
        $sheet->setCellValue('E' . $key + 2, $printOrder['receiver_name']); // ФИО Получателя
        $sheet->setCellValue('F' . $key + 2, $printOrder['address_json']['string']); // Адрес получателя
        $sheet->setCellValue('G' . $key + 2, $parsedAddressData['code'] ?? ''); // КОД ПВЗ
        $sheet->setCellValue('H' . $key + 2, $printOrder['receiver_telephone']); // Телефон получателя
        $sheet->setCellValue('I' . $key + 2, 1); // Доп сбор за доставку с получателя в т.ч. НДС
        $sheet->setCellValue('J' . $key + 2, 0); // Ставка НДС с доп.сбора за доставку
        $sheet->setCellValue('K' . $key + 2, 0); // Сумма НДС с доп.сбора за доставку
        $sheet->setCellValue('L' . $key + 2, ''); // Истинный продавец
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


    public function makeColumnsForeign()
    {
        return [
            'A1' => 'Номер отправления',
            'B1' => 'Форма собственности получателя',
            'C1' => 'Получатель',
            'D1' => 'Идентификационный номер налогоплательщика',
            'E1' => 'ФИО получателя',
            'F1' => 'ИИН, ИНН, ПИН',
            'G1' => 'Страна получателя',
            'H1' => 'Город получателя',
            'I1' => 'Индекс города получателя',
            'J1' => 'Адрес получателя',
            'K1' => 'Код ПВЗ',
            'L1' => 'Телефон получателя',
            'M1' => 'Доп сбор за доставку с получателя в т.ч. НДС',
            'N1' => 'Ставка НДС с доп.сбора за доставку',
            'O1' => 'Сумма НДС с доп.сбора за доставку',
            'P1' => 'Истинный продавец',
            'Q1' => 'Адрес истинного продавца',
            'R1' => 'Грузоотправитель',
            'S1' => 'Адрес грузоотправителя',
            'T1' => 'Номер грузоместа',
            'U1' => 'Вес грузоместа, кг',
            'V1' => 'Длина грузоместа, см',
            'W1' => 'Ширина грузоместа, см',
            'X1' => 'Высота грузоместа, см',
            'Y1' => 'Код маркировки',
            'Z1' => 'Код товара/артикул',
            'AA1' => 'Наименование товара на русском',
            'AB1' => 'Стоимость за ед. товара в валюте договора',
            'AC1' => 'Вес ед. товара нетто, кг',
            'AD1' => 'Вес ед. товара брутто(с упаковкой), кг',
            'AE1' => 'Количество единиц товара',
            'AF1' => 'Оплата с получателя за ед товара в т.ч. НДС',
            'AG1' => 'Ставка НДС, %',
            'AH1' => 'Сумма НДС за ед.',
        ];

    }

    public function fillValuesForeign($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness)
    {
        $parsedAddressData = $printOrder['address_json']['parsed_data'];

        $sheet->setCellValue('A' . $key + 2, $cdek_desc); // Номер отправления
        $sheet->setCellValue('B' . $key + 2, ''); // Форма собственности получателя
        $sheet->setCellValue('C' . $key + 2, $printOrder['receiver_name']); // Получатель
        $sheet->setCellValue('D' . $key + 2, ''); // Идентификационный номер налогоплательщика
        $sheet->setCellValue('E' . $key + 2, $printOrder['receiver_name']); // ФИО получателя
        $sheet->setCellValue('F' . $key + 2, ''); // ИИН, ИНН, ПИН
        $sheet->setCellValue('G' . $key + 2, $printOrder['country']); // Страна получателя
        $sheet->setCellValue('H' . $key + 2, $parsedAddressData['city'] ?? ''); // Город получателя
        $sheet->setCellValue('I' . $key + 2, $parsedAddressData['index'] ?? ''); // Индекс города получателя
        $sheet->setCellValue('J' . $key + 2, $printOrder['address_json']['string']); // Адрес получателя
        $sheet->setCellValue('K' . $key + 2, $parsedAddressData['code'] ?? ''); // Код ПВЗ
        $sheet->setCellValue('L' . $key + 2, $printOrder['receiver_telephone']); // Телефон получателя
        $sheet->setCellValue('M' . $key + 2, ''); // Доп сбор за доставку с получателя в т.ч. НДС
        $sheet->setCellValue('N' . $key + 2, 0); // Ставка НДС с доп.сбора за доставку
        $sheet->setCellValue('O' . $key + 2, 0); // Сумма НДС с доп.сбора за доставку
        $sheet->setCellValue('P' . $key + 2, ''); // Истинный продавец
        $sheet->setCellValue('Q' . $key + 2, ''); // Адрес истинного продавца
        $sheet->setCellValue('R' . $key + 2, 'CDEK GLOBAL'); // Грузоотправитель
        $sheet->setCellValue('S' . $key + 2, 'CDEK GLOBAL'); // Адрес грузоотправителя
        $sheet->setCellValue('T' . $key + 2, 1); // Номер грузоместа
        $sheet->setCellValue('U' . $key + 2, $sending_weight); // Вес грузоместа, кг
        $sheet->setCellValue('V' . $key + 2, '23'); // Длина грузоместа, см
        $sheet->setCellValue('W' . $key + 2, '17'); // Ширина грузоместа, см
        $sheet->setCellValue('X' . $key + 2, $sending_thickness); // Высота грузоместа, см
        $sheet->setCellValue('Y' . $key + 2, ''); // Код маркировки
        $sheet->setCellValue('Z' . $key + 2, $cdek_desc); // Код товара/артикул
        $sheet->setCellValue('AA' . $key + 2, 'Книги (сборники современных поэтов)'); // Наименование товара на русском
        $sheet->setCellValue('AB' . $key + 2, 1); // Стоимость за ед. товара в валюте договора
        $sheet->setCellValue('AC' . $key + 2, $sending_weight); // Вес ед. товара нетто, кг
        $sheet->setCellValue('AD' . $key + 2, $sending_weight); // Вес ед. товара брутто(с упаковкой), кг
        $sheet->setCellValue('AE' . $key + 2, 1); // Количество единиц товара
        $sheet->setCellValue('AF' . $key + 2, ''); // Оплата с получателя за ед товара в т.ч. НДС
        $sheet->setCellValue('AG' . $key + 2, 0); // Ставка НДС, %
        $sheet->setCellValue('AH' . $key + 2, 0); // Сумма НДС за ед.
    }

//    public function makePrintXlsx($countryType)
//    {
//        $query = PrintOrder::query()
//            ->where('type', PrintOrderTypeEnums::COLLECTION_PARTICIPATION)
//            ->where('status', PrintOrderStatusEnums::PRINTING)
//            ->where('model_id', $this->collection['id'])
//            ->orderBy('books_cnt');
//
//        $spreadsheet = new Spreadsheet();
//        $sheet = $spreadsheet->getActiveSheet();
//
//        if ($countryType === 'Rus') {
//            $query->where('country', 'Россия');
//            $columns = $this->makeColumnsRus();
//        } else {
//            $query->where('country', '<>', 'Россия');
//            $columns = $this->makeColumnsForeign();
//        }
//
//        foreach ($columns as $cell => $value) {
//            $sheet->setCellValue($cell, $value);
//        }
//
//
//        $printOrders = $query->get();
//
//        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);
//
//        foreach ($printOrders as $key => $printOrder) {
//            $participation = Participation::query()
//                ->where('print_order_id', $printOrder['id'])
//                ->first();
//            $cdek_desc = $this->collection['title_short'] . '. ' . $printOrder['books_cnt'] . ' шт. ' . $participation['id'] . ' / ' . $printOrder['id'];
//            $comment = $this->collection['title_short'] . ', ' . $printOrder['books_cnt'] . ' шт.';
//            $sending_weight = ($this->book_weight * $printOrder['books_cnt'] + 20) / 1000;
//            $sending_thickness = $this->book_thickness * $printOrder['books_cnt'] + 1;
//
//            if ($countryType === 'Rus') {
//                $this->fillValuesRus($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness);
//            } else {
//                $this->fillValuesForeign($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness);
//            }
//
//        }
//
//        foreach (range('A', 'D') as $columnID) {
//            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
//                ->setAutoSize(true);
//        }
//
//        $writer = new Xlsx($spreadsheet);
//        $fileName = 'Печать ' . $this->collection['title_short'] . ($countryType == 'Rus' ? '' : ' (Иностранные)') . '.xlsx';
//        return response()->streamDownload(function () use ($writer) {
//            $writer->save('php://output');
//        }, $fileName, [
//            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//        ]);
//    }

    public function makePrintXlsx(string $countryType): Spreadsheet
    {
        $query = PrintOrder::query()
            ->where('type', PrintOrderTypeEnums::COLLECTION_PARTICIPATION)
            ->where('status', PrintOrderStatusEnums::PRINTING)
            ->where('model_id', $this->collection['id'])
            ->orderBy('books_cnt');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($countryType === 'Rus') {
            $query->where('country', 'Россия');
            $columns = $this->makeColumnsRus();
        } else {
            $query->where('country', '<>', 'Россия');
            $columns = $this->makeColumnsForeign();
        }

        foreach ($columns as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $printOrders = $query->get();

        $spreadsheet->getActiveSheet()->getStyle("A1:D1")->getFont()->setBold(true);

        foreach ($printOrders as $key => $printOrder) {
            $participation = Participation::where('print_order_id', $printOrder['id'])->first();

            $cdek_desc = $this->collection['title_short'] . '. ' . $printOrder['books_cnt'] . ' шт. ' . $participation['id'] . ' / ' . $printOrder['id'];
            $comment = $this->collection['title_short'] . ', ' . $printOrder['books_cnt'] . ' шт.';
            $sending_weight = ($this->book_weight * $printOrder['books_cnt'] + 20) / 1000;
            $sending_thickness = $this->book_thickness * $printOrder['books_cnt'] + 1;

            if ($countryType === 'Rus') {
                $this->fillValuesRus($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness);
            } else {
                $this->fillValuesForeign($sheet, $key, $cdek_desc, $printOrder, $comment, $sending_weight, $sending_thickness);
            }
        }

        foreach (range('A', 'D') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function downloadZipWithBoth(): BinaryFileResponse
    {
        $zipFileName = 'Печать ' . $this->collection['title_short'] . '.zip';
        $tempZipPath = storage_path('app/' . uniqid() . '.zip');

        $zip = new \ZipArchive();
        $zip->open($tempZipPath, \ZipArchive::CREATE);

        // --- Русские ---
        $rusSpreadsheet = $this->makePrintXlsx('Rus');
        $rusPath = storage_path('app/rus.xlsx');
        (new Xlsx($rusSpreadsheet))->save($rusPath);
        $zip->addFile($rusPath, "Печать {$this->collection['title_short']} Россия.xlsx");

        // --- Иностранные ---
        $foreignSpreadsheet = $this->makePrintXlsx('Foreign');
        $foreignPath = storage_path('app/foreign.xlsx');
        (new Xlsx($foreignSpreadsheet))->save($foreignPath);
        $zip->addFile($foreignPath, "Печать {$this->collection['title_short']} Иностранные.xlsx");

        $zip->close();

        return response()->download($tempZipPath, $zipFileName)->deleteFileAfterSend(true);
    }

}
