<?php

namespace App\Http\Livewire\Account\OwnBook;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\own_book;
use App\Models\own_book_files;
use App\Models\own_books_works;
use App\Models\Printorder;
use App\Models\Work;
use App\Notifications\new_own_book;
use App\Notifications\TelegramNotification;
use App\Service\OwnBookOutputsService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class CreateOwnBook extends Component
{
    use WithFileUploads;

    public $author_name;
    public $book_title;

    public $inside_status;
    public $pages = "0";
    public $pages_color = '0';
    public $inside_type = 'by_file';
    public $inside_ready = '0';
    public $need_design = true;
    public $need_check = false;
    public $inside_files;
    public $works;

    public $cover_ready = '0';
    public $message_files; // Файлы обложки
    public $cover_comment; // Комментарий по обложке
    public $cover_status;

    public $need_print = false;
    public $prints = 10;
    public $cover_type = 'soft';
    public $inside_color = '0';

    public $send_to_name;
    public $send_to_country;
    public $send_to_city;
    public $send_to_index;
    public $send_to_tel;
    public $send_to_address;

    public $need_promo;
    public $promo_type = "1";

    public $price_inside;
    public $price_design;
    public $price_check;
    public $price_cover;
    public $price_print;
    public $price_promo;
    public $price_total;

    public $error_texts = [];
    public $error_fields = [];

    protected $listeners = ['count_doc_pages', 'syncWorks', 'save_own_book'];

    public function render(OwnBookOutputsService $calc_outs)
    {
        if ($this->inside_ready === '1') {
            $this->need_design = false;
            $this->need_check = false;
        }


        if (!$this->need_promo) {
            $this->promo_type = null;
        } elseif (!$this->promo_type) {
            $this->promo_type = '1';
        }

        if ($this->inside_color == "0") {
            $this->pages_color = 0;
        }

        // Узнаем цены участия
        $result = $calc_outs->calculate(
            $this->pages,
            $this->pages_color,
            $this->need_design,
            $this->need_check,
            $this->cover_ready,
            $this->need_print,
            $this->prints,
            $this->cover_type,
            $this->promo_type
        );
        $this->price_inside = $result['price_inside'];
        $this->price_design = $result['price_design'];
        $this->price_check = $result['price_check'];
        $this->price_cover = $result['price_cover'];
        $this->price_print = $result['price_print'];
        $this->price_promo = $result['price_promo'];
        $this->price_total = $result['price_total'];

        return view('livewire.account.own-book.create-own-book');
    }

    public function mount(Request $request)
    {
        $this->author_name = Auth::user()->name . ' ' . Auth::user()->surname;
        $this->send_to_name = Auth::user()->name . ' ' . Auth::user()->surname;

        // Куда нужно перейти после сохранения работ
        $currenturl = url()->full();
        $back_after_work_adding = [
            'button_text' => 'Сохранить и вернуться к заявке',
            'url' => $currenturl
        ];
        $request->session()->put('back_after_work_adding', $back_after_work_adding);

    }


    public function count_doc_pages()
    {

        $this->pages = 0;

        foreach ($this->inside_files as $doc_path) {


// -----------  Пытался получить страницы по-другому, но не вышло :( ----------- //

//            $objReader = IOFactory::createReader('Word2007');
//            $phpWord = $objReader->load($source);
//            $section = $phpWord->addSection();
//            $footer = $section->addFooter();
//            $footer->addPreserveText('Pagesss {PAGE} of {NUMPAGES}. Section pages: {SECTIONPAGES}');
//
////            $textRun = $footer->addTextRun(array('alignment' => Jc::CENTER));
////            $textRun->addText(' total_pages: ');
////            $textRun->addField('NUMPAGES');
//            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//            $objWriter->save('helloWorld232.docx');
//
//            $source = 'helloWorld232.docx';
//            $app= new COM("Word.Application"); $file = "/worddoc.doc";
//            $app->visible = true; $app->Documents->Open($file);
//            $app->ActiveDocument->PrintOut();
//            $app->ActiveDocument->Close(); $app->Quit();
//
//            dd($app);
// -----------  // Пытался получить страницы по-другому, но не вышло :( ----------- //

            $doc_format = substr($doc_path, strrpos($doc_path, '.') + 1);
            if ($doc_format === 'docx') {
                $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();
                $zip->open($doc_path);//relative path to DOCX file
                $xml = new \DOMDocument();
                if ($zip->getFromName("docProps/app.xml")) {
                    $xml->loadXML($zip->getFromName("docProps/app.xml"));
                    $page = $xml->getElementsByTagName('Pages')->item(0)->nodeValue ?? 1;
                    // Returns the number of pages according to app.xml
                    $this->pages += $page;
                }
            }

        }

    }

    public function syncWorks($works)
    {
        // Из компоненты выбора работ постоянно присылается список выбранных
        $this->works = $works;
        $this->pages = collect($works)->sum('pages');
        $this->dispatchBrowserEvent('update_js');
    }

    public function check_app()
    {

        // --------- Ищем ошибки в заполнении  --------- //
        $this->error_texts = [];
        $this->error_fields = [];


        $is_same_title = own_book::where('user_id', Auth::user()->id)->Where('title', $this->book_title)->value('title');
        $is_same_user = own_book::where('user_id', Auth::user()->id)->Where('title', $this->book_title)->value('user_id');


        if ($this->pages == 0) {
            array_push($this->error_texts, "Не указано количество страниц (не загружен файл или они не определились автоматически)!");
            array_push($this->error_fields, 'pages');
        } elseif ($this->pages < 20) {
            array_push($this->error_texts, 'Минимальное количество страниц в собственной книге - 20.');
            array_push($this->error_fields, 'pages');
        }

        if ($this->book_title === $is_same_title & Auth::user()->id === $is_same_user) {
            array_push($this->error_texts, 'У Вас уже есть книга с точно таким же названием!');
            array_push($this->error_fields, 'title');
        }

        if (!$this->author_name) {
            array_push($this->error_texts, 'Имя не введено!');
            array_push($this->error_fields, 'name');
        }

        if (!$this->book_title) {
            array_push($this->error_texts, 'Название книги не введено!');
            array_push($this->error_fields, 'title');
        }

        if ($this->need_print ?? null) {
            if ($this->send_to_name === null || $this->send_to_name === "") {
                array_push($this->error_texts, 'Введите имя получателя!');
                array_push($this->error_fields, 'send_to_name');
            }
            if ($this->send_to_tel === null || $this->send_to_tel === "") {
                array_push($this->error_texts, 'Введите телефон получателя!');
                array_push($this->error_fields, 'send_to_tel');
            }
            if ($this->send_to_country === null || $this->send_to_country === "") {
                array_push($this->error_texts, 'Введите страну получателя!');
                array_push($this->error_fields, 'send_to_country');
            }
            if ($this->send_to_city === null || $this->send_to_city === "") {
                array_push($this->error_texts, 'Введите город получателя!');
                array_push($this->error_fields, 'send_to_city');
            }
            if ($this->send_to_address === null || $this->send_to_address === "") {
                array_push($this->error_texts, 'Введите адрес получателя!');
                array_push($this->error_fields, 'send_to_address');
            }
            if ($this->send_to_index === null || $this->send_to_index === "") {
                array_push($this->error_texts, 'Введите индекс получателя!');
                array_push($this->error_fields, 'send_to_index');
            }
        }

        if ($this->cover_ready == "0" && ($this->cover_comment === null || $this->cover_comment === "")) {
            array_push($this->error_texts, 'Введите комментарий по обложке!');
            array_push($this->error_fields, 'cover');
        }

        if ($this->cover_ready == "1" && ($this->message_files === null || count($this->message_files) == 0)) {
            array_push($this->error_texts, 'Загрузите файлы обложки!');
            array_push($this->error_fields, 'cover');
        }

        if (!empty($this->error_texts)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $this->error_texts),
            ]);
            return false;
        } else {
            return true;
        }
    }

    public function confirm_save()
    {
        if ($this->check_app()) {

            $uploaded_text = ($this->inside_type == 'by_file') ? 'файлов: ' . count($this->inside_files) : 'работ: ' . count($this->works);
            $design_text = ($this->need_design ? 'необходим дизайн (' . $this->price_design . ' руб.)' : '');
            $check_text = ($this->need_check ? 'необходима проверка (' . $this->price_check . ' руб.)' : '');
            $inside_text = ($this->inside_ready == '0') ? $design_text . (($this->need_design && $this->need_check) ? ', ' : '') . $check_text : 'полностью готов к печати';
            $cover_text = ($this->cover_ready == '0') ? 'необходимо создание (1500 руб.).' : 'полностью готова.';
            $print_text = ($this->need_print) ?
                $this->prints . ' экземпляров'
                . '. Обложка: ' . ($this->cover_type == 'soft' ? 'мягкая' : 'твердая')
                . '. Внутренний блок: ' . ($this->inside_color == '0' ? 'ч/б' : 'цветной (' . $this->pages_color . ' цветных страниц).')
                : 'не нужна.';
            $promo_text = $this->need_promo ? 'нужен ' . $this->promo_type . ' вариант' : 'не нужно.';

            $html = "<div style='display: flex; flex-direction: column; gap: 10px;'>
                <p><b>Книга:</b> {$this->author_name}: '{$this->book_title}'</p>
                <p><b>Загружено {$uploaded_text}. </b>(страниц: {$this->pages})</p>
                <p><b>Внутренний блок:</b> {$inside_text}</p>
                <p><b>Обложка:</b> {$cover_text}</p>
                <p><b>Печать:</b> {$print_text}</p>
                <p><b>Продвижение:</b> {$promo_text}</p>
                </div>";

            $this->dispatchBrowserEvent('swal:confirm', [
                'title' => 'Проверьте, пожалуйста, заявку: ',
                'html' => $html,
                'onconfirm' => 'save_own_book'
            ]);
        }
    }

    public function save_own_book()
    {

//        Storage::makeDirectory($user_folder_inside);
//        Storage::makeDirectory($user_folder_cover);
        DB::transaction(function () { // Чтобы не записать ненужного

            // ---- Записываем основную инфу ---- //
            $new_own_book = new own_book();
            $new_own_book->user_id = Auth::user()->id;
            $new_own_book->author = $this->author_name;
            $new_own_book->title = $this->book_title;
            $new_own_book->own_book_status_id = 1;
            $new_own_book->pages = $this->pages;
            $new_own_book->color_pages = intval($this->pages_color);
            $new_own_book->inside_type = $this->inside_type;
            $new_own_book->own_book_inside_status_id = $this->inside_ready === '1' ? 9 : 1;
            $new_own_book->own_book_cover_status_id = $this->cover_ready === '1' ? 9 : 1;
            $new_own_book->cover_comment = $this->cover_comment;
            $new_own_book->promo_type = $this->promo_type;

            $new_own_book->need_design = $this->need_design;
            $new_own_book->need_check = $this->need_check;

            $new_own_book->text_design_price = $this->price_design;
            $new_own_book->text_check_price = $this->price_check;
            $new_own_book->inside_price = $this->price_inside;
            $new_own_book->cover_price = $this->price_cover;
            $new_own_book->promo_price = $this->price_promo;
            $new_own_book->print_price = $this->price_print;
            $new_own_book->total_price = $this->price_total;

            // Делаем ссылку на постоянный внутренний блок PDF
            $inside_file_path = 'admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ВЕРСТКА' . '/ВБ_Main_' . $this->book_title . '.pdf';
            $new_own_book->inside_file = $inside_file_path;
            // ----------------------------

            $new_own_book->save();


            // ----------------------------------------------

            // ---- Сразу создаем нужные папки под книгу ---- //
            $user_folder_inside = 'admin_files/own_books/user_id_' . Auth::user()->id . '/' . $new_own_book->id . '/ВЕРСТКА/От автора';
            $user_folder_cover = 'admin_files/own_books/user_id_' . Auth::user()->id . '/' . $new_own_book->id . '/ОБЛОЖКА/От автора';

            // Создаем папки для внутреннего блока и обложки
            if (!File::exists($user_folder_inside)) {
                File::makeDirectory($user_folder_inside, 0777, true);
            }
            if (!File::exists($user_folder_cover)) {
                File::makeDirectory($user_folder_cover, 0777, true);
            }
            // ------------------------------------------------------------------

            // ---- Сохраняем работы, если они файлом ---- //
            if ($this->inside_type === 'by_file') {
                foreach ($this->inside_files as $key => $doc_path) {
                    $file_name = substr($doc_path, strrpos($doc_path, '/') + 1);
                    $file_old_path = public_path($doc_path);
                    $file_new_path = $user_folder_inside . '/' . $key . '_' . $file_name;
                    File::move($file_old_path, $file_new_path);
                    $own_book_new_file = new own_book_files();
                    $own_book_new_file->own_book_id = $new_own_book->id;
                    $own_book_new_file->file_type = 'inside';
                    $own_book_new_file->file = substr($file_new_path, strpos($file_new_path, 'public'));
                    $own_book_new_file->save();
                    $old_folder = substr($doc_path, 0, strpos($doc_path, '/', strpos($doc_path, '/') + 1));
                    File::deleteDirectory(public_path($old_folder));
                }
            }

            // ---- Сохраняем работы, если они из системы ---- //
            if ($this->inside_type === 'by_system') {
                foreach ($this->works as $work) {
                    $own_book_new_work = new own_books_works();
                    $own_book_new_work->own_book_id = $new_own_book->id;
                    $own_book_new_work->work_id = $work['id'];
                    $own_book_new_work->save();
                }
            }


            // ---- Создаем файлы обложки и складируем их в own_book_files ---- //
            if ($this->message_files && count($this->message_files) > 0) {
                foreach ($this->message_files as $key => $doc_path) {
                    $file_name = substr($doc_path, strrpos($doc_path, '/') + 1);
                    $file_old_path = public_path($doc_path);
                    $file_new_path = $user_folder_cover . '/' . $key . '_' . $file_name;
                    File::move($file_old_path, $file_new_path);
                    $own_book_new_file = new own_book_files();
                    $own_book_new_file->own_book_id = $new_own_book->id;
                    $own_book_new_file->file_type = 'cover';
                    $own_book_new_file->file = substr($file_new_path, strpos($file_new_path, 'public'));
                    $own_book_new_file->save();
                    $old_folder = substr($doc_path, 0, strpos($doc_path, '/', strpos($doc_path, '/') + 1));
                    File::deleteDirectory(public_path($old_folder));
                }
            }

            // ---- Создаем новый Заказ печатных экземпляров ---- //
            if ($this->need_print) {

                $new_PrintOrder = new PrintOrder();
                $new_PrintOrder->own_book_id = $new_own_book->id;
                $new_PrintOrder->user_id = Auth::user()->id;
                $new_PrintOrder->books_needed = $this->prints;
                $new_PrintOrder->cover_type = $this->cover_type;
                $new_PrintOrder->color_pages = intval($this->pages_color);
                $new_PrintOrder->inside_color = $this->inside_color;

                $new_PrintOrder->send_to_name = $this->send_to_name;
                $new_PrintOrder->send_to_tel = $this->send_to_tel;
                $new_PrintOrder->send_to_country = $this->send_to_country;
                $new_PrintOrder->send_to_city = $this->send_to_city;
                $new_PrintOrder->send_to_address = $this->send_to_address;
                $new_PrintOrder->send_to_index = $this->send_to_index;
                $new_PrintOrder->save();

            }


            // ---- Создаем ЧАТ ---- //
            $new_chat = new Chat();
            $new_chat->user_created = Auth::user()->id;
            $new_chat->user_to = 2;
            $new_chat->flg_admin_chat = 1;
            $new_chat->title = 'Чат: ' . $this->book_title;
            $new_chat->own_book_id = $new_own_book->id;
            $new_chat->chat_status_id = 9;
            $new_chat->save();


            // Оповещение нам в телеграм
            $title = '💥 Новая книга от ' . Auth::user()->name . ' ' . Auth::user()->surname . "!💥 ";
            $cover_text = ($this->cover_ready === '1') ? 'готовая от автора' : 'нужно делать';
            $print_text = ($this->need_print) ?
                $this->price_print . ' руб. ' . $this->prints . ' экз. '
                . ($this->cover_type == 'soft' ? 'Мягкая' : 'Твердая')
                . '. ВБ: ' . ($this->inside_color == '0' ? 'ч/б' : 'цветной (' . $this->pages_color . ' цветных страниц).')
                : 'не нужна.';
            $text = "*Автор:* " . $this->author_name .
                "\n*Название:* " . $this->book_title .
                "\n*Страниц:* " . $this->pages .
                "\n*Редактура:* " . $this->price_inside . ' руб.' .
                "\n*Обложка:* " . $cover_text .
                "\n*Печать:* " . $print_text .
                "\n*Промо:* " . $this->price_promo . ' руб.' .
                "\n\n*Выручка:* " . $this->price_total . ' руб.';
            $button_text = 'В админку';
            $url = 'vk.com';


            // Посылаем Telegram уведомление нам
            Notification::route('telegram', '-506622812')
                ->notify(new TelegramNotification($title, $text, $button_text, $url));

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Книга успешно создана!');
            session()->flash('alert_text', 'На этой странице отображается весь процесс издания Вашей книги: оплата, предварительные материалы, отслеживание книги после печати и т.д.');
            return redirect('/myaccount/mybooks/' . $new_own_book->id . '/book_page');

        });
    }

}
