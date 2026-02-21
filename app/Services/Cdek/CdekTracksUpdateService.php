<?php

namespace App\Services\Cdek;


use App\Models\PrintOrder\PrintOrder;
use App\Models\PrintOrder\PrintOrderStatus;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CdekTracksUpdateService
{
    public function import(string $filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, true, true);

        // 1️⃣ Получаем заголовки
        $headerRow = array_shift($rows);

        $headers = $this->normalizeHeaders($headerRow);

        $updatedRows = 0;
        DB::transaction(function () use ($rows, $headers, &$updatedRows) {

            foreach ($rows as $row) {

                $rowData = $this->mapRow($row, $headers);

                $parts = explode('/', $rowData['order_number']);
                $printOrderId = trim(end($parts));

                if (empty($rowData['track_number'])) {
                    continue;
                }

                PrintOrder::where('id', $printOrderId)->update(['track_number' => $rowData['track_number']]);

                $updatedRows += 1;
            }
        });

        return $updatedRows;
    }

    private function normalizeHeaders(array $headerRow): array
    {
        $normalized = [];

        foreach ($headerRow as $column => $value) {
            if (!$value) continue;

            $normalized[$column] = Str::of($value)
                ->trim()
                ->lower()
                ->replace([' ', '.', ','], '_')
                ->value();
        }

        return $normalized;
    }

    private function mapRow(array $row, array $headers): array
    {
        $data = [];

        foreach ($headers as $column => $headerName) {

            $value = $row[$column] ?? null;

            switch ($headerName) {

                case 'номер_заказа':
                    $data['track_number'] = $value;
                    break;

                case 'номер_отправления_им':
                    $data['order_number'] = $value;
                    break;
            }
        }

        return $data;
    }

}
