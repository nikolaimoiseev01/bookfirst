<?php

namespace App\Services;

use App\Models\Collection\Collection;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use setasign\Fpdi\Fpdi;

class WordService
{
    public $fileSettings;
    public $pageSettings;

    private function makeAuthorTitle($phpWord, $name, $headerTitle = null)
    {
// Создаем новый раздел для автора
        $section = $phpWord->addSection($this->pageSettings);

        $phpWord->setDefaultParagraphStyle(
            array(
                'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0),
                'spacing' => 120,
                'lineHeight' => 1,
            )
        );

        // Пишем имя автора
        $section->addText(
            $name,
            $this->fileSettings['author_name_style'],
            ['align' => 'center']
        );

        // Делаем отступ от автора
        $section->addText(' ',
            array('name' => 'Calibri', 'size' => 5, 'color' => '000000', 'bold' => false)
        );

        // Пишем имя автора в колонтитул
        $footer = $section->addFooter();
        $footer->addText(
            $name,
            $this->fileSettings['author_name_footer_style']
        );

        // Делаем изображение в хедер
        if (str_contains($headerTitle, 'Дух')) {
            $header = $section->addHeader();
            $header->firstPage();
            $header->addText("");

            $header_sub = $section->addHeader();
            $header_sub->addImage('fixed/duh_header_img.png',
                array('width' => 200,
                    'height' => 27.27,
                    'alignment' => 'center'
                )
            );
        }
        return $section;
    }

    private function insertWorkText($section, $work)
    {
        // Пишем название
        $section->addText($work['title'],
            $this->fileSettings['work_title_style'],
            $this->fileSettings['work_title_align']
        );

        $work_text = str_replace("\n", '<w:br/>', htmlspecialchars($work['text']));

        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);

        // Пишем текст работы
        $section->addText(
            $work_text,
            $this->fileSettings['work_text_style']
        );
    }

    private function fileSettings($workType)
    {
        return match ($workType) {
            'Поэзия' => [
                'page_size' => "A5",
                'author_name_style' => array('name' => 'a_BentTitulNr', 'size' => 16, 'color' => 'F79646', 'bold' => true),
                'author_name_footer_style' => array('name' => 'Bad Script', 'size' => 14, 'color' => '000000', 'bold' => true),
                'work_title_style' => array('name' => 'Bad Script', 'size' => 16, 'color' => 'FF0000', 'bold' => true),
                'work_title_align' => array('align' => 'left'),
                'work_text_style' => array('name' => 'Ayuthaya', 'size' => 10, 'color' => '000000', 'bold' => false)
            ],
            'Проза' => [
                'page_size' => "A4",
                'author_name_style' => array('name' => 'Days', 'size' => 16, 'color' => 'F79646', 'bold' => true),
                'author_name_footer_style' => array('name' => 'Accuratist', 'size' => 14, 'color' => '000000', 'bold' => false),
                'work_title_style' => array('name' => 'Ayuthaya', 'size' => 14, 'color' => 'FF0000', 'bold' => false, 'italic' => true),
                'work_title_align' => array('align' => 'center'),
                'work_text_style' => array('name' => 'Calibri Light', 'size' => 14, 'color' => '000000', 'bold' => false)
            ]
        };
    }

    private function pageSettings()
    {
        return [
            'marginTop' => 1000,
            'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.35),
            'marginBottom' => 1100,
            "paperSize" => $this->fileSettings['page_size'],
            'headerHeight' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(.28)
        ];
    }


    /**
     * Вырезает первые $pages страниц и возвращает бинарную строку PDF.
     */
    public function makeCollection($collection): string
    {
        $participations = $collection->approvedParticipations()->get();

        // Creating the new document...
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);

        // Делаем стили для разных сборников
        $workType = match (true) {
            str_contains($collection['title'], 'Дух') => 'Поэзия',
            str_contains($collection['title'], 'Мысли') => 'Проза',
        };

        $this->fileSettings = $this->fileSettings($workType);
        $this->pageSettings = $this->pageSettings();


        foreach ($participations as $participation) {

            $section = $this->makeAuthorTitle($phpWord, $participation['author_name'], $collection['title']);

            $participationWorks = $participation->participationWorks;

            foreach ($participationWorks as $participationWork) {

                $work = $participationWork->work;

                $this->insertWorkText($section, $work);
            }

        }

        // Создаем контактную информацию авторов

        $section = $phpWord->addSection($this->pageSettings);
        $table = $section->addTable();

        foreach ($participations as $participation) {
            $table->addRow();
            $table->addCell(1750)->addText($participation['author_name']);
            $table->addCell(1750)->addText($participation->user['email']);
        }

        \PhpOffice\PhpWord\Settings::setCompatibility(false);
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $safeTitle = Str::slug($collection['title'], '_');
        $tempDir = storage_path('temp');
        $filePath = $tempDir . DIRECTORY_SEPARATOR . $safeTitle . '.docx';
        $objWriter->save($filePath);
        return $filePath;
    }


    public function makeOwnBook($ownBook, $workType): string
    {

        // Creating the new document...
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);

        $this->fileSettings = $this->fileSettings($workType);
        $this->pageSettings = $this->pageSettings();


        $section = $this->makeAuthorTitle($phpWord, $ownBook['author']);

        foreach ($ownBook->works as $ownBookWork) {

            $work = $ownBookWork->work;

            $this->insertWorkText($section, $work);
        }

        \PhpOffice\PhpWord\Settings::setCompatibility(false);
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(false);
        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $safeTitle = Str::slug($ownBook['title'], '_');
        $tempDir = storage_path('temp');
        $filePath = $tempDir . DIRECTORY_SEPARATOR . $safeTitle . '.docx';
        $objWriter->save($filePath);
        return $filePath;
    }


}
