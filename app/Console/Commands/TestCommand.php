<?php

namespace App\Console\Commands;

use App\Models\own_book;
use Exception;
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

            try {
            // Создаем обрезанную версию ВБ

            $inside_file_path = public_path('/' . $own_book['inside_file']);
            $file_exist = file_exists($inside_file_path);

            if ($own_book['inside_file'] and $file_exist) {


                $pdfPath = $own_book['inside_file'];
                $user_folder = 'admin_files/own_books/' . 'user_id_' . $own_book['user_id'] . '/' . $own_book['title'] . '/ВЕРСТКА/';
                $cut_file_path = public_path($user_folder . 'ВБ_Main_' . $own_book['title'] . '_CUT.pdf');

                // Понимаем размер файла
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile($inside_file_path);
                $templateId = $pdf->importPage(1);
                $size = $pdf->getTemplateSize($templateId);

//                 Создайте экземпляр Fpdi
                $pdf = new Fpdi('P', 'mm', array(round($size['height']), round($size['width'])));

                // Добавьте первые 10 страниц в новый PDF-документ
                for ($page = 1; $page <= 10; $page++) {
                    $pdf->AddPage();
                    $pdf->setSourceFile($inside_file_path);
                    $template = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($template);
                    $pdf->useTemplate($template);
                }

                $pdf->output($cut_file_path,'F');


                own_book::where('id', $own_book['id'])->update(array(
                    'inside_file_cut' => $user_folder . 'ВБ_Main_' . $own_book['title'] . '_CUT.pdf',
                ));
            }

            } catch (Exception $e) {
                // обработка ошибки
                echo 'Ошибка: ' . $e->getMessage() . '<br>';
                // или просто продолжить цикл без прерывания
                continue;
            }
        }
    }
}
