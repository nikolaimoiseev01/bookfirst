<?php

namespace App\Http\Livewire\Account\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\new_chat;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ChatCreate extends Component
{
    public $chat_title;
    public $text;
    public $collection_id;
    public $own_book_id;
    public $user_to;

    protected $listeners = ['storeChat'];

    public function render()
    {
        return view('livewire.account.chat.chat-create', [
            'chat_title' => $this->chat_title,
        ]);
    }

    public function mount($chat_title, $collection_id, $own_book_id, $user_to)
    {
        $this->chat_title = $chat_title;
        $this->collection_id = $collection_id;
        $this->own_book_id = $own_book_id;
        $this->user_to = $user_to;
    }

    public function storeChat()
    {

        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($this->text == null) {
            array_push($errors_array, 'Введите текст вопроса!');
        }

        if ($this->chat_title == null) {
            array_push($errors_array, 'Введите тему вопроса!');
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

            $new_chat = new Chat();
            $new_chat->user_created = Auth::user()->id;
            $new_chat->user_to = $this->user_to;
            $new_chat->title = $this->chat_title;
            if (Auth::user()->id == 2) {
                $new_chat->chat_status_id = 4;
            } else {
                $new_chat->chat_status_id = 1;
            }
            $new_chat->flg_admin_chat = 1;
            $new_chat->save();

            $new_message = new Message();
            $new_message->chat_id = $new_chat->id;
            $new_message->user_from = Auth::user()->id;
            if (Auth::user()->id != $this->user_to) {
                $new_message->user_to = 2;
            }
            $new_message->text = $this->text;
            $new_message->save();

            if ($this->collection_id > 0) {
                Participation::where('collection_id', $this->collection_id)->where('user_id', Auth::user()->id)
                    ->update(array('chat_id' => $new_chat->id));
            }

            if (Auth::user()->hasRole('admin')) {
                $user = User::where('id', $this->user_to)->first();
                $url_back = route('chat', $new_chat->id);
                // Посылаем Email уведомление пользователю
                $user->notify(new EmailNotification(
                    'Открыто новое обсуждение!',
                    $user['name'],
                    "С Вами был открыт новый чат на тему '" . $new_chat->title . "'! Для того, чтобы ответить, пожалуйста, перейдите на страницу чата:",
                    "Перейти в чат",
                    $url_back));
                Notification::send($user, new UserNotification('У Вас новое сообщение!', '/myaccount/chats/' . $new_chat->id));

                session()->flash('success', 'change_printorder');
                session()->flash('alert_type', 'success');
                session()->flash('alert_title', 'Обсужение успешно создано!');
                session()->flash('alert_text', 'Мы даже послали Email об этом автору!)');
                return redirect(request()->header('Referer'));

            }

            if (Auth::user()->id <> 2) {
                Notification::route('telegram', '-506622812')
                    ->notify(new new_chat(Auth::user()->name, Auth::user()->surname, $this->chat_title, $this->text));
            }

            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Вопрос успешно создан!');
            session()->flash('alert_text', 'Как только мы на него ответим, вы получите оповещение по почте и на сайте. Обычно мы отвечаем за 2-3 дня.');
            return redirect('/myaccount/chats');
        }

    }
}
