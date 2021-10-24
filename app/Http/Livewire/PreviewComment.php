<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\own_book;
use App\Models\Participation;
use App\Models\preview_comment;
use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class PreviewComment extends Component
{

    public $text;
    public $page;
    public $comments;
    public $collection_id;
    public $own_book_id;
    public $own_book_comment_type;
    public $preview_comment_type;
    protected $listeners = ['delete'];

    public function render()
    {
        return view('livewire.preview-comment', [
            'comments' => $this->comments,
            'comment_type' => $this->own_book_comment_type,
            'preview_comment_type' => $this->preview_comment_type,
        ]);
    }

    public function mount($collection_id, $own_book_id, $own_book_comment_type)
    {
        $this->collection_id = $collection_id;
        $this->own_book_id = $own_book_id;
        $this->own_book_comment_type = $own_book_comment_type;
        if ($this->collection_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['collection_id', $collection_id]])->get();
        }
        if ($this->own_book_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['own_book_id', $own_book_id], ['own_book_comment_type', $own_book_comment_type]])->get();
        }

        if ($this->own_book_id <> 0) {
            $this->preview_comment_type = 'own_book';
        }
        else {
            $this->preview_comment_type = 'collection';
        }

    }

    public function add_preview_comment($formData)
    {
        $validator = Validator::make($formData, [
            'text' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $error[] = [$message, '<br>'];
            }
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors->all()),
            ]);

            return;
        }

        $new_comment = new preview_comment();
        $new_comment->user_id = Auth::user()->id;
        if ($this->collection_id <> 0) {
            $new_comment->collection_id = $this->collection_id;
            $new_comment->participation_id = Participation::where([['user_id', Auth::user()->id], ['collection_id', $this->collection_id]])->value('id');
        } elseif ($this->own_book_id <> 0) {
            $new_comment->own_book_id = $this->own_book_id;
            $new_comment->own_book_comment_type = $this->own_book_comment_type;
        }
        $new_comment->page = $this->page;
        $new_comment->text = $this->text;
        $new_comment->status_done = 0;
        $new_comment->save();

        if ($this->collection_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['collection_id', $this->collection_id]])->get();
        } elseif ($this->own_book_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['own_book_id', $this->own_book_id], ['own_book_comment_type', $this->own_book_comment_type]])->get();
        }
        $this->text = '';
        $this->page = '';
    }

    public function delete_confirm($comment_id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Вы уверены, что хотите удалить исправление (действие нельзя будет отменить)?',
            'id' => $comment_id
        ]);
    }

    public function delete($comment_id)
    {
        preview_comment::where('id', $comment_id)->delete();
//        $this->dispatchBrowserEvent('swal:modal', [
//            'type' => 'success',
//            'title' => 'Исправление успешно удалено!',
//        ]);

        if ($this->collection_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['collection_id', $this->collection_id]])->get();
        } elseif ($this->own_book_id <> 0) {
            $this->comments = preview_comment::where([['user_id', Auth::user()->id], ['own_book_id', $this->own_book_id], ['own_book_comment_type', $this->own_book_comment_type]])->get();
        }

    }

    public function change_inside_status($status_id)
    {

        $errors_array = [];

        $prev_comments_cnt = preview_comment::where('own_book_id', $this->own_book_id)->where('own_book_comment_type', 'inside')->where('status_done', 0)->count();

        if ($status_id <> 4 && $prev_comments_cnt === 0) {
            array_push($errors_array, 'Сначала добавьте исправления (необходимо ввести страницу и текст в форму, затем нажать на "самолетик" справа)');
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

            own_book::where('id', $this->own_book_id)->update([
                'own_book_inside_status_id' => $status_id,
                'inside_deadline' => Carbon::now()->addDays(5)->toDate(),
            ]);

            $cover_status = own_book::where('id', $this->own_book_id)->value('own_book_cover_status_id');
            $print_price = own_book::where('id', $this->own_book_id)->value('print_price');
            $total_status_needed = 3;

            if ($status_id === 4) {
                if ($cover_status === 4 & $print_price > 0) {
                    $total_status_needed = 4;
                    $alert_text = 'Поздравляем! Внутренний блок и обложка были утверждены! Далее, чтобы продолжить, в блоке "Печать" необходимо оплатить финальную стоимость печати.';
                } elseif ($cover_status === 4 & !($print_price > 0)) {
                    $total_status_needed = 9;
                    $alert_text = 'Внутренний блок был утвержден! Так как обложка была тоже утверждена, а печать не требуется, мы поздравляем Вас с окончанием процесса издания!';
                } else {
                    $total_status_needed = 3;
                    $alert_text = 'Внутренний блок был утвержден!';
                }
            }

            own_book::where('id', $this->own_book_id)->update([
                'own_book_status_id' => $total_status_needed,
            ]);


            session()->flash('success', 'success');
            session()->flash('alert_text', 'Статус изменен!');
            if ($status_id === 3) {
                session()->flash('alert_text', 'Отлично! Мы уже начали вносить указанные изменения. Срок исправления - 5 дней. Как только закончим, Вы получите оповещение, и внутренний блок снова можно будет проверить на этой странице.');
            } elseif ($status_id === 4) {
                session()->flash('alert_text', $alert_text);
            }

            // Посылаем Telegram уведомление нам
            Notification::route('telegram', '-506622812')
                ->notify(new TelegramNotification('✍🏼 Автор послал исправления ВБ! ✍🏼',
                    "Книга: " . own_book::where('id', $this->own_book_id)->value('title') .
                    "\nДеадлайн на исправление: " . Carbon::now()->addDays(5)->toDate(),
                    "Его страница издания",
                    route('own_books_page', $this->own_book_id)));


            return redirect()->to(url()->previous());



        }
    }

    public function change_cover_status($status_id)
    {

        $errors_array = [];

        $prev_comments_cnt = preview_comment::where('own_book_id', $this->own_book_id)->where('own_book_comment_type', 'cover')->where('status_done', 0)->count();

        if ($status_id <> 4 && $prev_comments_cnt === 0) {
            array_push($errors_array, 'Сначала добавьте исправления (необходимо ввести страницу и текст в форму, затем нажать на "самолетик" справа)');
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

            own_book::where('id', $this->own_book_id)->update([
                'own_book_cover_status_id' => $status_id,
                'cover_deadline' => Carbon::now()->addDays(5)->toDate(),
            ]);


            $inside_status = own_book::where('id', $this->own_book_id)->value('own_book_inside_status_id');
            $print_price = own_book::where('id', $this->own_book_id)->value('print_price');
            $total_status_needed = 3;

            if ($status_id === 4) {
                if ($inside_status === 4 & $print_price > 0) {
                    $total_status_needed = 4;
                    $alert_text = 'Поздравляем! Внутренний блок и обложка были утверждены! Далее, чтобы продолжить, в блоке "Печать" необходимо оплатить финальную стоимость печати.';
                } elseif ($inside_status === 4 & !($print_price > 0)) {
                    $total_status_needed = 9;
                    $alert_text = 'Обложка была утверждена! Так как внутренний блок был тоже утвержден, а печать не требуется, мы поздравляем Вас с окончанием процесса издания!';
                } else {
                    $total_status_needed = 3;
                    $alert_text = 'Обложка была утверждена!';
                }
            }

            own_book::where('id', $this->own_book_id)->update([
                'own_book_status_id' => $total_status_needed,
            ]);


            session()->flash('success', 'success');
            session()->flash('alert_text', 'Статус изменен!');
            if ($status_id === 3) {
                session()->flash('alert_text', 'Отлично! Мы уже начали вносить указанные изменения. Срок исправления - 5 дней. Как только закончим, Вы получите оповещение, и обложку снова можно будет проверить на этой странице.');
            } elseif ($status_id === 4) {
                session()->flash('alert_text', $alert_text);
            }

            // Посылаем Telegram уведомление нам
            Notification::route('telegram', '-506622812')
                ->notify(new TelegramNotification('✍🏼 Автор послал исправления по обложке! ✍🏼',
                    "Книга: " . own_book::where('id', $this->own_book_id)->value('title') .
                    "\nДеадлайн на исправление: " . Carbon::now()->addDays(5)->toDate(),
                    "Его страница издания",
                    route('own_books_page', $this->own_book_id)));


            return redirect()->to(url()->previous());


        }
    }

}
