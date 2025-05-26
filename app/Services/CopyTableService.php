<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyTableService
{


    public function copy(
        string $sourceTable,
        string $targetTable = null,
        string $modelClass = null,
        array  $columnsToExclude = [],
        array  $columnsToRename = [],
        array  $columnsMedia = [],
        int    $fromId = null,
    ): void
    {

        $allColumns = Schema::connection('old_mysql')->getColumnListing($sourceTable);

        // Если есть переименования, добавим select с alias
        if (!empty($columnsToRename)) {
            foreach ($columnsToRename as $old => $new) {
                if (($key = array_search($old, $allColumns)) !== false) {
                    unset($allColumns[$key]);
                }
                $allColumns[] = DB::raw("`$old` as `$new`");
            }
        }

        $rows = DB::connection('old_mysql')
            ->table($sourceTable)
            ->select($allColumns)
            ->when(isset($fromId), fn($query) => $query->where('id', '>', $fromId))
            ->get();

        if ($rows->isEmpty()) {
            return;
        }


        if ($targetTable ?? null) {
            $data = $rows
                ->map(fn($row) => array_diff_key((array)$row, array_flip($columnsToExclude)))
                ->toArray();
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table($targetTable)->insert($chunk);
            }

        } else {
            foreach ($rows as $row) {
                $rowArray = (array)$row;
                $filteredRows = array_diff_key($rowArray, array_flip($columnsToExclude));
                // Сохраняем данные без media колонок
                $mediaUrls = [];
                foreach ($columnsMedia as $old => $collection) {
                    if (isset($rowArray[$old])) {
                        if (str_contains($rowArray[$old], '/img/')) {
                            $url = 'https://pervajakniga.ru' . $rowArray[$old];
                        } else {
                            $url = $rowArray[$old];
                        }
                        $mediaUrls[] = [
                            'url' => $url,
                            'collection' => $collection,
                        ];
                        unset($rowArray[$old]); // не вставляем URL напрямую
                    }
                }

                // Создаём запись
                /** @var \Illuminate\Database\Eloquent\Model $model */
                $model = $modelClass::create($filteredRows);

                // Добавляем медиа, если есть
                foreach ($mediaUrls as $media) {
                    try {
                        $model->addMediaFromUrl($media['url'])
                            ->toMediaCollection($media['collection']);
                    } catch (\Exception $e) {
                        continue;
                    }

                }
            }
        }

    }
}
