<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use setasign\Fpdi\Fpdi;

class PdfService
{
    /**
     * Вырезает первые $pages страниц и возвращает бинарную строку PDF.
     */
    public function cutToString(string $pdfPath, int $pages): string
    {
//        if (!is_file($pdfPath)) {
//            throw new FileNotFoundException("PDF not found: {$pdfPath}");
//        }
//        if ($pages < 1) {
//            throw new \InvalidArgumentException('Pages must be >= 1');
//        }

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($pdfPath);
        $limit = min($pages, $pageCount);

        for ($page = 1; $page <= $limit; $page++) {
            $tplId = $pdf->importPage($page);
            $size  = $pdf->getTemplateSize($tplId);
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);
        }

        // 'S' — вернуть как строку (не писать на диск)
        return $pdf->Output('S');
    }

    /**
     * Вырезает и сохраняет во временный файл. Возвращает путь.
     * Нужен только если внешний код действительно требует путь.
     */
    public function cutToTempFile(string $pdfPath, int $pages): string
    {
        $binary = $this->cutToString($pdfPath, $pages);

        // более безопасное имя + системный tmp
        $tmpPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . 'cut_' . bin2hex(random_bytes(8)) . '.pdf';

        file_put_contents($tmpPath, $binary);

        return $tmpPath;
    }

    /**
     * Вырезает и сразу кладёт в Spatie Media Library без временных файлов.
     */
    public function cutAndAttach(
        object $model,
        string $pdfPath,
        int $pages,
        string $collection = 'pdfs',
        ?string $fileName = null
    ) {
        $binary = $this->cutToString($pdfPath, $pages);

        return $model
            ->addMediaFromString($binary)
            ->usingFileName($fileName ?: ('cut_' . pathinfo($pdfPath, PATHINFO_FILENAME) . '.pdf'))
            ->toMediaCollection($collection);
    }
}
