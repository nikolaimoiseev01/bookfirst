<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\own_book;
use App\Models\own_book_files;
use App\Models\own_books_works;
use App\Models\Printorder;
use App\Models\Work;
use App\Notifications\new_own_book;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class CreateOwnBook extends Component
{
    use WithFileUploads;

    public $work_files;
    public $cover_files;
    public $pre_cover_files;
    public $works;

    public $author_name;
    public $book_title;
    public $inside_type;
    public $pages = 0;
    public $color_pages;
    public $inside_status = 2;

    public $text_design_price;
    public $text_check_price;
    public $cover_price = 0;
    public $promo_price = 0;
    public $print_price = 0;
    public $total_price = 0;

    public $cover_type;
    public $cover_status = 1;
    public $cover_color;
    public $cover_comment;

    public $books_needed;
    public $send_to_name;
    public $send_to_address;
    public $send_to_tel;

    public $promo_type;


    protected $listeners = [
        'reviewSectionRefresh' => '$refresh',
        'save_own_book',
        'count_doc_pages'
    ];


    public function render()
    {
        $user_works = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->get() ?? 0;
        return view('livewire.create-own-book', [
            'user_works' => $user_works,
        ]);
    }

    public function mount() {
        $this->author_name = Auth::user()->name . ' ' . Auth::user()->surname;
        $this->send_to_name = Auth::user()->name . ' ' . Auth::user()->surname;

    }


    public function count_doc_pages($docs)
    {
        $this->pages = 0;

        $this->docs = explode(';', $docs);
        foreach ($this->docs as $doc_path) {


            $source = public_path('filepond_temp/' . $doc_path);

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

            if(substr($source, strrpos($source, '.') + 1) === 'docx') {
                $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();
                $zip->open($source);//relative path to DOCX file
                $xml = new \DOMDocument();
                $xml->loadXML($zip->getFromName("docProps/app.xml"));
                $page = $xml->getElementsByTagName('Pages')->item(0)->nodeValue ?? 1;
                // Returns the number of pages according to app.xml
                $this->pages += $page;
            }
        }


        $this->dispatchBrowserEvent('load_pages_from_doc', [
            'pages' => $this->pages,
        ]);
    }


    public function save_own_book()
    {

        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];


        $is_same_title = own_book::where('user_id', Auth::user()->id)->Where('title', $this->book_title)->value('title');
        $is_same_user = own_book::where('user_id', Auth::user()->id)->Where('title', $this->book_title)->value('user_id');
        if ($this->book_title === $is_same_title & Auth::user()->id === $is_same_user) {
            array_push($errors_array, 'У Вас уже есть книга с точно таким же названием!');
        }

        if (!$this->author_name) {
            array_push($errors_array, 'Имя не введено!');
        }

        if (!$this->book_title) {
            array_push($errors_array, 'Название книги не введено!');
        }

        if ($this->inside_type === 'by_file' & ($this->work_files === 0 || $this->work_files === "")) {
            array_push($errors_array, 'Загрузите файлы внутреннего блока!');
        }

        if ($this->inside_type === 'by_system' & $this->works === "") {
            array_push($errors_array, 'Загрузите работы из системы!');
        }

        if ($this->print_price > 0 & ($this->send_to_name === null || $this->send_to_name === "")) {
            array_push($errors_array, 'Введите имя получателя!');
        }
        if ($this->print_price > 0 & ($this->send_to_tel === null || $this->send_to_tel === "")) {
            array_push($errors_array, 'Введите телефон получателя!');
        }

        if ($this->print_price > 0 & ($this->send_to_address === null || $this->send_to_address === "")) {
            array_push($errors_array, 'Введите адрес получателя!');
        }

        if ($this->cover_price > 0 & ($this->cover_comment === null || $this->cover_comment === "")) {
            array_push($errors_array, 'Введите комментарий по обложке!');
        }

        if ($this->cover_price === 0 & ($this->cover_files === null || $this->cover_files === "")) {
            array_push($errors_array, 'Загрузить файлы обложки!');
        }

        if ($this->pages <= 30) {
            array_push($errors_array, 'Минимальное количество страниц в собственной книге - 30.');
        }




        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //


        if (empty($errors_array)) {

            // ---- Сразу создаем нужные папки под книгу ---- //
            Storage::makeDirectory('admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ВЕРСТКА/От автора');
            Storage::makeDirectory('admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ОБЛОЖКА/От автора');

            $user_folder_inside = public_path('admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ВЕРСТКА/От автора');
            $user_folder_cover = public_path('admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ОБЛОЖКА/От автора');

            // ---- Записываем основную инфу ---- //
            $new_own_book = new own_book();
            $new_own_book->user_id = Auth::user()->id;
            $new_own_book->author = $this->author_name;
            $new_own_book->title = $this->book_title;
            $new_own_book->own_book_status_id = 1;
            $new_own_book->pages = $this->pages;
            $new_own_book->inside_type = $this->inside_type;
            $new_own_book->own_book_inside_status_id = $this->inside_status;
            $new_own_book->own_book_cover_status_id = $this->cover_status;

            $new_own_book->text_design_price = $this->text_design_price;
            $new_own_book->text_check_price = $this->text_check_price;
            $new_own_book->inside_price = $this->text_check_price + $this->text_design_price + 300;
            $new_own_book->cover_price = $this->cover_price;
            $new_own_book->cover_comment = $this->cover_comment;
            $new_own_book->promo_type = $this->promo_type;
            $new_own_book->promo_price = $this->promo_price;
            $new_own_book->print_price = $this->print_price;
            $new_own_book->total_price = $this->total_price;

            // Делаем шаблон внутреннего блока
            $inside_file_path = public_path('admin_files/own_books/user_id_' . Auth::user()->id . '/' . $this->book_title . '/ВЕРСТКА') . '/ВБ_Main_' . $this->book_title . '.pdf';
            $new_own_book->inside_file = substr($inside_file_path, strpos($inside_file_path, 'public') + 7);
            // ----------------------------

            $new_own_book->save();


            // ----------------------------------------------


            // Создаем папки для внутреннего блока и обложки
            if (! File::exists($user_folder_inside)) {
                File::makeDirectory($user_folder_inside, 0777, true);
            }
            if (! File::exists($user_folder_cover)) {
                File::makeDirectory($user_folder_cover, 0777, true);
            }
            // ------------------------------------------------------------------

            // ---- Создаем файлы и складируем их в own_book_files ---- //
            if ($this->inside_type === 'файлами') {
                $this->work_files = explode(';', $this->work_files);
                foreach ($this->work_files as $key => $doc_path) {
                    $file_name = substr($doc_path, strrpos($doc_path, '/' )+1);
                    $file_old_path = public_path('filepond_temp/' . $doc_path);
                    $file_new_path = $user_folder_inside . '/' . $key . '_' . $file_name;
                    File::move($file_old_path, $file_new_path);
                    $own_book_new_file = new own_book_files();
                    $own_book_new_file->own_book_id = $new_own_book->id;
                    $own_book_new_file->file_type = 'inside';
                    $own_book_new_file->file = substr($file_new_path, strpos($file_new_path, 'public') + 7);
                    $own_book_new_file->save();
                    $old_folder = substr($doc_path, 0, strpos($doc_path, '/', strpos($doc_path, '/')+1));
                    File::deleteDirectory(public_path('filepond_temp/' . $old_folder));
                }
            }
            //------------------------------------------------------------------------

            // ---- Сохраняем файлы из системы ---- //
            if ($this->inside_type === 'системой') {
                $this->works = explode(';', $this->works);
                foreach ($this->works as $work) {
                    $own_book_new_work = new own_books_works();
                    $own_book_new_work->own_book_id = $new_own_book->id;
                    $own_book_new_work->work_id = intval($work);
                    $own_book_new_work->save();
                }
            }
            // ------------------------------------------------------------------------


            // ---- Создаем файлы готовой обложки и складируем их в own_book_files ---- //
            if ($this->cover_price === 0) {
                $this->cover_files = explode(';', $this->cover_files);
                foreach ($this->cover_files as $key => $doc_path) {
                    $file_name = substr($doc_path, strrpos($doc_path, '/' )+1);
                    $file_old_path = public_path('filepond_temp/' . $doc_path);
                    $file_new_path = $user_folder_cover . '/' . $key . '_' . $file_name;
                    File::move($file_old_path, $file_new_path);
                    $own_book_new_file = new own_book_files();
                    $own_book_new_file->own_book_id = $new_own_book->id;
                    $own_book_new_file->file_type = 'cover';
                    $own_book_new_file->file = substr($file_new_path, strpos($file_new_path, 'public') + 7);
                    $own_book_new_file->save();
                    $old_folder = substr($doc_path, 0, strpos($doc_path, '/', strpos($doc_path, '/')+1));
                    File::deleteDirectory(public_path('filepond_temp/' . $old_folder));
                }
            }
            //------------------------------------------------------------------------

            // ---- Создаем файлы пре обложек и складируем их в own_book_files ---- //
            if ($this->cover_price > 0 && $this->pre_cover_files <> '') {
                $this->pre_cover_files = explode(';', $this->pre_cover_files);
                foreach ($this->pre_cover_files as $key => $doc_path) {
                    $file_name = substr($doc_path, strrpos($doc_path, '/' )+1);
                    $file_old_path = public_path('filepond_temp/' . $doc_path);
                    $file_new_path = $user_folder_cover . '/' . $key . '_' . $file_name;
                    File::move($file_old_path, $file_new_path);
                    $own_book_new_file = new own_book_files();
                    $own_book_new_file->own_book_id = $new_own_book->id;
                    $own_book_new_file->file_type = 'pre_cover';
                    $own_book_new_file->file = substr($file_new_path, strpos($file_new_path, 'public') + 7);
                    $own_book_new_file->save();
                    $old_folder = substr($doc_path, 0, strpos($doc_path, '/', strpos($doc_path, '/')+1));
                    File::deleteDirectory(public_path('filepond_temp/' . $old_folder));
                }
            }
            //------------------------------------------------------------------------


            if ($this->print_price > 0) {

                // ---- Создаем новый Заказ печатных! ---- //
                $new_PrintOrder = new PrintOrder();
                $new_PrintOrder->own_book_id = $new_own_book->id;
                $new_PrintOrder->user_id = Auth::user()->id;
                $new_PrintOrder->books_needed = $this->books_needed;
                $new_PrintOrder->cover_type = $this->cover_type;
                $new_PrintOrder->cover_color = $this->cover_color;
                $new_PrintOrder->color_pages = $this->color_pages;
                $new_PrintOrder->send_to_name = $this->send_to_name;
                $new_PrintOrder->send_to_tel = $this->send_to_tel;
                $new_PrintOrder->send_to_address = $this->send_to_address;
                $new_PrintOrder->save();

                // ----------------------------------------------------------- //
            }



            // ------------ Создаем ЧАТ ------------

            $new_chat = new Chat();
            $new_chat->user_created = Auth::user()->id;
            $new_chat->user_to = 2;
            $new_chat->title = 'Чат: ' . $this->book_title;
            $new_chat->own_book_id = $new_own_book->id;
            $new_chat->chat_status_id = 9;
            $new_chat->save();
            // ------------------------------------


            Notification::route('telegram', '-506622812')
                ->notify(new new_own_book(Auth::user()->name . ' ' . Auth::user()->surname, $this->author_name, $this->book_title, $this->text_check_price + $this->text_design_price + 300, $this->cover_price, $this->print_price, $this->promo_price));


            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Книга успешно создана!');
            session()->flash('alert_text', 'На этой странице отображается весь процесс издания Вашей книги: оплата, предварительные материалы, отслеживание книги после печати и т.д.');
            return redirect('/myaccount/mybooks/' . $new_own_book->id . '/book_page');

        }
    }

}
