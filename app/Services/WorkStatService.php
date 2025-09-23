<?php

namespace App\Services;

use Illuminate\Http\Request;

class WorkStatService
{
    public function calculate($work_text)
    {
        // Получаем все строчки
        $rows = explode("\n", $work_text);

        $plus_rows = 0;

        foreach ($rows as $line) {
            $rows_in_line = mb_strlen($line);
            $rows_in_line = $rows_in_line / 50;
            if ($rows_in_line > 1) { // Если строчка длинная - получаем кол-во символов по 50
                $plus_rows += $rows_in_line - 1; // И отнимаем 1, чтобы потом прибавить к общему числу
            }
        }

        $symbols = mb_strlen($work_text);
        $rows = count($rows) + $plus_rows ?? 0 - 1;
        $pages = round(ceil($rows / 38));

        return  [
            'symbols' => $symbols,
            'rows' => $rows,
            'pages' => $pages
        ];
    }
}
