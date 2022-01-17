<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\chat_status;
use App\Models\own_book;
use App\Models\own_book_cover_status;
use App\Models\own_book_files;
use App\Models\own_book_inside_status;
use App\Models\own_book_status;
use App\Models\own_books_works;
use App\Models\preview_comment;
use App\Models\Printorder;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OwnBookController extends Controller
{
    public $own_book;

    public function index()
    {
        $own_books = own_book::orderBy('id', 'desc')->where('own_book_status_id', '<>', 9)->paginate(10);
        return view('admin.own_books.index', [
            'own_books' => $own_books,
        ]);
    }

    public function closed_own_books()
    {
        $own_books = own_book::orderBy('created_at', 'desc')->where('own_book_status_id', 9)->paginate(5);
        return view('admin.own_books.closed', [
            'own_books' => $own_books,
        ]);
    }


    public function own_books_page(Request $request)
    {
        $this->own_book = own_book::where('id', $request->own_book_id)->with('printorder')->with('own_books_works')->with('own_book_files')->first();
        $inside_files = own_book_files::where('file_type', 'inside')->where('own_book_id', $request->own_book_id)->get();
        $cover_files = own_book_files::where(function ($q) {
            $q->where('file_type', 'cover')
                ->orwhere('file_type', 'pre_cover');
        })
            ->where('own_book_id', $request->own_book_id)->get();
        $prev_comments_inside = preview_comment::where('own_book_id', $request->own_book_id)->where('own_book_comment_type', 'inside')->orderBy('status_done', 'asc')->orderBy('created_at', 'desc')->get();
        $prev_comments_cover = preview_comment::where('own_book_id', $request->own_book_id)->where('own_book_comment_type', 'cover')->orderBy('status_done', 'asc')->orderBy('created_at', 'desc')->get();
        $own_book_statuses = own_book_status::orderby('id')->get();
        $own_book_inside_statuses = own_book_inside_status::orderby('id')->get();
        $own_book_cover_statuses = own_book_cover_status::orderby('id')->get();
        $chat = Chat::where('own_book_id', $this->own_book['id'])->first();
        $chat_statuses = chat_status::orderBy('id')->get();
        $transactions = Transaction::where('own_book_id', $request->own_book_id)->get();

        return view('admin.own_books.own_book_page', [
            'own_book' => $this->own_book,
            'own_book_statuses' => $own_book_statuses,
            'inside_files' => $inside_files,
            'cover_files' => $cover_files,
            'own_book_inside_statuses' => $own_book_inside_statuses,
            'own_book_cover_statuses' => $own_book_cover_statuses,
            'prev_comments_inside' => $prev_comments_inside,
            'prev_comments_cover' => $prev_comments_cover,
            'chat' => $chat,
            'chat_statuses' => $chat_statuses,
            'transactions' => $transactions,
        ]);
    }

    public function change_amazon_link(Request $request)
    {
        session()->flash('success', 'change_printorder');

        $this->own_book = own_book::where('id', $request->own_book_id)->first();
        own_book::where('id', $request->own_book_id)->update(array(
            'amazon_link' => $request->amazon_link,
        ));
        if ($this->own_book['amazon_link'] == null and $request->amazon_link <> null and own_book['user_id'] <> null) {
            $user = User::where('id', $request->user_id)->first();


            $user->notify(new EmailNotification(
                'Книга появилась на Amazon.com!',
                $user['name'],
                "Спешим сообщить, что Ваша книга успешно появилась на Amazon.com!" .
                "Все ссылки можно посомтреть на странице наших изданных книг:",
                "Наши книги",
                route('homePortal') . '/own_books'
            ));

            \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                'Книга появилась на Amazon.com!',
                route('homePortal') . '/own_books',
            ));

        }


        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Ссылка успешно добавлена!');

        return redirect()->back();
    }


    public function change_book_status(Request $request)
    {

        session()->flash('success', 'change_printorder');

        $this->own_book = own_book::where('id', $request->own_book_id)->first();
        $printorder = Printorder::where('own_book_id', $request->own_book_id)->first();


        if ((($printorder['track_number'] ?? null === null || $printorder['send_price'] ?? null  === null) && $this->own_book['own_book_status_id'] === 6 && intval($request->own_book_status_id) === 9)) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Статус не заменен!');
            session()->flash('alert_text', 'Не могу завершить печать, когда трек номер или цена отправления пустые');
        } else {
            own_book::where('id', $request->own_book_id)->update(array(
                'own_book_status_id' => $request->own_book_status_id,
            ));


            $user = User::where('id', $request->user_id)->first();

            if ((int)$request->own_book_status_id == 2) {
                $user->notify(new EmailNotification(
                        'Процесс издания книги',
                        $user['name'],
                        "Проверили Ваши файлы и готовы сказать, что мы с радостью можем начать процесс издания Вашей книги '" . own_book::where('id', $request->own_book_id)->value('title') . "'!" .
                        "\nНа текущий момент издание имеет общий статус: '" . own_book_status::where('id', $request->own_book_status_id)->value('status_title') . "." .
                        "\nТак сразу после оплаты стоимости издания мы начнём работу с макетами и пришлём первые варианты в течение 11-ти календарных дней. " .
                        "Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
                        "Страница издания",
                        route('book_page', $this->own_book['id']))
                );

                \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Смена статуса издания книги!',
                        route('book_page', $this->own_book['id']))
                );
            }


            if ((int)$request->own_book_status_id === 9) {
                $user->notify(new EmailNotification(
                        'Процесс издания завершен!',
                        $user['name'],
                        "С радостью сообщаем, что процесс издания Вашей книги '" . own_book::where('id', $request->own_book_id)->value('title') . "' завершен! " .
                        "Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги. Сотрудничать с Вами было одно удовольствие, и мы с радостью ждем Вас для издания следующих книг, а также для участия в наших сборниках! Мы будем очень признательны, если Вы оставите отзыв о нашей деятельности в нашей группе ВК: vk.com/topic-122176261_35858257",
                        "Страница издания",
                        route('book_page', $this->own_book['id']))
                );

                \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Смена статуса издания книги!',
                        route('book_page', $this->own_book['id']))
                );
            }

            if ((int)$request->own_book_status_id <> 9 && (int)$request->own_book_status_id <> 2) {
                $user->notify(new EmailNotification(
                        'Процесс издания книги',
                        $user['name'],
                        "Спешим сообщить, что произошла смена статуса издания Вашей книги: '" . own_book::where('id', $request->own_book_id)->value('title') . "'." .
                        "\nНа текущий момент издание имеет общий статус: '" . own_book_status::where('id', $request->own_book_status_id)->value('status_title') . "'. Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
                        "Страница издания",
                        route('book_page', $this->own_book['id']))
                );

                \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                        'Смена статуса издания книги!',
                        route('book_page', $this->own_book['id']))
                );
            }


            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Статус успешно изменен!');
        }

        return redirect()->back();

    }

    public
    function change_book_inside_status(Request $request)
    {
        session()->flash('success', 'change_printorder');
        $own_book = own_book::where('id', $request->own_book_id)->first();

        if (file_exists($own_book['inside_file'])) {

            own_book::where('id', $request->own_book_id)->update(array(
                'own_book_inside_status_id' => $request->own_book_inside_status_id,
            ));

            $user = User::where('id', $request->user_id)->first();


            $user->notify(new EmailNotification(
                    'Процесс издания книги',
                    $user['name'],
                    "Спешим сообщить, что произошла смена статуса работы по внутреннему блоку Вашей книги: '" . own_book::where('id', $request->own_book_id)->value('title') . "." .
                    "\nНа текущий момент внутренний блок имеет статус: '" . own_book_inside_status::where('id', $request->own_book_inside_status_id)->value('status_title') . "'. Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
                    "Страница издания",
                    route('book_page', $own_book['id']))
            );

            \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                    'Смена статуса внутреннего блока!',
                    route('book_page', $own_book['id']))
            );

            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Статус успешно изменен!');
        } else {
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Статус не заменен!');
            session()->flash('alert_text', 'Не смог найти файл внутреннего блока в папке!');
        }

        return redirect()->back();


    }

    public
    function change_book_promo_type(Request $request)
    {
        $promo_var = null;

        if ($request->promo_type > 0) {
            if ($request->promo_type == 500) {
                $promo_var = 1;} else {$promo_var = 2;}
        }


        own_book::where('id', $request->own_book_id)->update(array(
            'promo_price' => $request->promo_type,
            'promo_type' => $promo_var,
        ));

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Вид продвижения изменен!');
        return redirect()->back();
    }

    public function update_own_book_desc(Request $request)
    {

        own_book::where('id', $request->own_book_id)->update(array(
            'own_book_desc' => $request->desc,
        ));

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Аннотация успешно изменена!');
        return redirect()->back();
    }



    public
    function change_book_cover_status(Request $request)
    {
        $own_book = own_book::where('id', $request->own_book_id)->first();
        session()->flash('success', 'change_printorder');


        if ($own_book['cover_2d'] & $own_book['cover_3d']) {

            own_book::where('id', $request->own_book_id)->update(array(
                'own_book_cover_status_id' => $request->own_book_cover_status_id,
            ));

            $user = User::where('id', $request->user_id)->first();


            $user->notify(new EmailNotification(
                    'Процесс издания книги',
                    $user['name'],
                    "Спешим сообщить, что произошла смена статуса работы с обложкой по книге: '" . own_book::where('id', $request->own_book_id)->value('title') . "." .
                    "\nНа текущий момент обложка имеет статус: '" . own_book_cover_status::where('id', $request->own_book_cover_status_id)->value('status_title') . "'. Всю подробную информацию об издании Вы всегда можете отслеживать на специальной странице издания книги.",
                    "Страница издания",
                    route('book_page', $own_book['id']))
            );

            \Illuminate\Support\Facades\Notification::send($user, new UserNotification(
                    'Смена статуса работы с обложкой!',
                    route('book_page', $own_book['id']))
            );


            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Статус успешно изменен!');
        } else {
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Статус не заменен!');
            session()->flash('alert_text', 'С файлами обложек что-то не так!');
        }

        return redirect()->back();

    }

    public
    function change_book_pages(Request $request)
    {
        $own_book = own_book::where('id', $request->own_book_id)->first();

        own_book::where('id', $request->own_book_id)->update(array(
            'pages' => $request->own_book_pages,
            'color_pages' => $request->own_book_color_pages,
        ));

        if ($own_book['print_price'] > 0) {
            $pages = $request->own_book_pages;
            $old_price = $own_book['print_price'];

            if ($own_book['color_pages'] > 0) {
                $color_pages = $request->own_book_color_pages;
            } else {
                $color_pages = 0;
            }


            if ($own_book->printorder['books_needed'] < 10) {
                $tirag_coef = 1;
            } else if ($own_book->printorder['books_needed'] < 50) {
                $tirag_coef = 0.95;
            } else {
                $tirag_coef = 0.9;
            }


            if ($own_book->printorder['cover_color'] === 1) {
                $cover_color_coef = 1;
            } elseif ($own_book->printorder['cover_color'] === 0) {
                $cover_color_coef = 0.7;
            }


            if ($own_book->printorder['cover_type'] === 'hard') {
                $cover_style_coef = 2.1;
            } else {
                $cover_style_coef = 1;
            }

            if ($own_book->printorder['books_needed'] < 100) {
                $pages_coef = 1.8;
            } else {
                $pages_coef = 1;
            }

            $print_needed = $own_book->printorder['books_needed'];


            $total_price = round(($pages - $color_pages + ($color_pages * 3)) * 0.7 * $tirag_coef * $cover_color_coef * $cover_style_coef * $pages_coef * $print_needed * 2.2);

            own_book::where('id', $request->own_book_id)->update(array(
                'print_price' => $total_price,
            ));

            printorder::where('own_book_id', $request->own_book_id)->update(array(
                'color_pages' => $color_pages,
            ));

            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Успешно!');
            session()->flash('alert_text', 'Кроме страниц поменяли еще печать! Старая цена: ' . $old_price . ' ---> Новая цена: ' . $total_price);
        }


        return redirect()->back();
    }


    public function update_own_book_track_number(Request $request)
    {

        session()->flash('success', 'change_printorder');
        $own_book = own_book::where('id', $request->own_book_id)->first();

        Printorder::where('own_book_id', $request->own_book_id)->update(array(
            'track_number' => $request->track_number,
        ));

        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Трак номер установлен!');

        return redirect()->back();
    }

    public function update_own_book_send_price(Request $request)
    {

        session()->flash('success', 'change_printorder');
        $own_book = own_book::where('id', $request->own_book_id)->first();

        Printorder::where('own_book_id', $request->own_book_id)->update(array(
            'send_price' => $request->send_price,
        ));

        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Стоимость пересылки успешно установлена!');

        return redirect()->back();
    }

    public
    function change_preview_comment_status(Request $request)
    {
        $cur_status = preview_comment::where('id', $request->preview_comment_id)->value('status_done');
        $next_status = abs($cur_status - 1);
        preview_comment::where('id', $request->preview_comment_id)->update(array(
            'status_done' => $next_status,
        ));

        return redirect()->back();

    }

    public
    function change_all_preview_comment_status(Request $request)
    {
        $prev_comments = preview_comment::where('own_book_id', $request->own_book_id)
            ->where('own_book_comment_type', $request->comment_type)->update(['status_done' => 1]);

        return redirect(url()->previous() . '#' . $request->comment_type);
    }


    public
    function update_own_book_inside(Request $request)
    {

        $own_book = own_book::where('id', $request->own_book_id)->first();
        $user_folder = 'admin_files/own_books/' . 'user_id_' . $own_book['user_id'] . '/' . $own_book['title'] . '/ВЕРСТКА/';
        $file_new_path = $user_folder . 'ВБ_Main_' . $own_book['title'] . '.' . $request->file('inside_file')->getClientOriginalExtension();

        if (!is_null($request->file('inside_file'))) {
            File::delete($own_book->inside_file);
        }
        own_book::where('id', $request->own_book_id)->update(array(
            'inside_file' => $file_new_path,
        ));
        $request->file('inside_file')->move($user_folder, 'ВБ_Main_' . $own_book['title'] . '.' . $request->file('inside_file')->getClientOriginalExtension());

        session()->flash('success', 'change_printorder');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Файл внутреннего блока обновлен!');

        return redirect()->back();

    }

    public
    function update_own_book_cover(Request $request)
    {


        $own_book = own_book::where('id', $request->own_book_id)->first();
        $user_folder = $cover_2d = 'admin_files/own_books/' . 'user_id_' . $own_book['user_id'] . '/' . $own_book['title'] . '/ОБЛОЖКА/';

        if (!is_null($request->file('cover_2d'))) {
            $file_new_path = $user_folder . 'Cover_2d' . '.' . $request->file('cover_2d')->getClientOriginalExtension();
            own_book::where('id', $request->own_book_id)->update(array(
                'cover_2d' => $file_new_path,
            ));
            File::delete($own_book->cover_2d);
            $request->file('cover_2d')->move($user_folder, 'Cover_2d' . '.' . $request->file('cover_2d')->getClientOriginalExtension());
        }

        if (!is_null($request->file('cover_3d'))) {

            $file_new_path = $user_folder . 'Cover_3d' . '.' . $request->file('cover_3d')->getClientOriginalExtension();
            own_book::where('id', $request->own_book_id)->update(array(
                'cover_3d' => $file_new_path,
            ));
            File::delete($own_book->cover_3d);
            $request->file('cover_3d')->move($user_folder, 'Cover_3d' . '.' . $request->file('cover_3d')->getClientOriginalExtension());
        }

        return redirect()->back();

    }


    public
    function update_own_book_prices(Request $request)
    {
        session()->flash('success', 'change_printorder');
        $own_book = own_book::where('id', $request->own_book_id)->first();

        if ($request->text_check_price === null || $request->text_design_price === null || $request->cover_price === null || $request->print_price === null || $request->promo_price === null) {
            session()->flash('alert_type', 'error');
            session()->flash('alert_title', 'Ошибка!');
            session()->flash('alert_text', 'Не все цены заполнены!');
        }
        else {

            own_book::where('id', $request->own_book_id)->update(array(
                'text_check_price' => intval($request->text_check_price),
                'text_design_price' => intval($request->text_design_price),
                'inside_price' => intval($request->text_check_price) + intval($request->text_design_price),
                'cover_price' => intval($request->cover_price),
                'print_price' => intval($request->print_price),
                'promo_price' => intval($request->promo_price),
                'total_price' => intval($request->text_check_price) + intval($request->text_design_price) + intval($request->cover_price) + intval($request->print_price) + intval($request->promo_price),
            ));
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Все цены были успешно изменены!');
        }





        return redirect()->back();

    }


}
