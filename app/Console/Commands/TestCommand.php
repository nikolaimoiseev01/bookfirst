<?php

namespace App\Console\Commands;

use App\Models\own_book;
use Illuminate\Console\Command;
use setasign\Fpdi\Fpdi;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TestCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $own_books = own_book::all();

        foreach ($own_books as $own_book) {

            // Создаем обрезанную версию ВБ

            if ($own_book['inside_file'] and file_exists($own_book['inside_file'])) {
                $pdfPath = $own_book['inside_file'];
                $user_folder = 'admin_files/own_books/' . 'user_id_' . $own_book['user_id'] . '/' . $own_book['title'] . '/ВЕРСТКА/';
                $cut_file_path = $user_folder . 'ВБ_Main_' . $own_book['title'] . '_CUT.pdf';

                // Понимаем размер файла
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile($pdfPath);
                $templateId = $pdf->importPage(1);
                $size = $pdf->getTemplateSize($templateId);

                // Создайте экземпляр Fpdi
                $pdf = new Fpdi('P', 'mm', array(round($size['height']), round($size['width'])));

                // Добавьте первые 10 страниц в новый PDF-документ
                for ($page = 1; $page <= 10; $page++) {
                    $pdf->AddPage();
                    $pdf->setSourceFile($pdfPath);
                    $template = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($template);
                    $pdf->useTemplate($template);
                }

                $pdf->output($cut_file_path, 'F');

                own_book::where('id', $own_book['id'])->update(array(
                    'inside_file_cut' => $cut_file_path,
                ));
            }
        }
    }
}
