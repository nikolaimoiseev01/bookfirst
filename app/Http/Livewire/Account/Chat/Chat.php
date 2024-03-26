<?php

namespace App\Http\Livewire\Account\Chat;

use App\Models\ext_promotion;
use App\Models\Message;
use App\Models\message_file;
use App\Models\MessageTemplate;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use App\Notifications\UserNotification;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;


class Chat extends Component
{

    public $text;
    public $messages;
    public $chat_id;
    public $message_files;
    public $currentUrl;
    public $user_to;
    public $chat;
    public $flg_chat_creation;
    public $new_chat_user;
    public $templates;
    public $template_type = null;
    public $editing_message_id;
    public $editing_text;

    public $cur_user_role;

    protected $listeners = [
        'new_message',
        'delete_message',
        'refreshChat' => '$refresh'
    ];

    use WithFileUploads;

    public function render()
    {
        $this->templates = MessageTemplate::orderBy('template_type')
            ->orderBy('title', 'asc')
            ->when($this->template_type, function ($q, $role) {
                return $q->where('template_type', $this->template_type);
            })
            ->get();

        $this->chat = \App\Models\Chat::where('id', $this->chat_id)->first();
        return view('livewire.account.chat.chat');

    }


    public function mount($chat_id, $new_chat_user_id)
    {

        $this->cur_user_role = Auth::user()->getRoleNames()[0];


        if ($chat_id) { // Если работаем с уже существующим чатом

            $this->flg_chat_creation = false;

            $this->chat = \App\Models\Chat::where('id', $this->chat_id)->first();
            Auth::user()->id;
            $this->user_to = User::where('id', $this->chat['user_created'])->first();
            $this->messages = Message::where('chat_id', $chat_id)->with('message_file')->get();
            $this->chat_id = $chat_id;
            $this->currentUrl = url()->current();
            $this->dispatchBrowserEvent('update_hrefs');
            if (in_array($this->cur_user_role, ['admin', 'ext_promotion_admin'])) {
                $this->text = 'Здравствуйте, ' . $this->user_to['name'] . '!';
            };
        } else {
            $this->new_chat_user = User::where('id', $new_chat_user_id)->first();
            $this->flg_chat_creation = true;
        }

    }

    public function reopenChat($chat_id)
    {
        \App\Models\Chat::where('id', $chat_id)->update(array('chat_status_id' => '2'));
        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Вопрос открыт снова!');
        return redirect(route('all_chats') . '?cur_chat_id=' . $chat_id);
    }

    public function choose_template_type($template_type)
    {
        if ($template_type === 'all') {
            $this->template_type = null;
        } else {
            $this->template_type = $template_type;
        }

    }

    public function add_template($id)
    {
        $template_text = MessageTemplate::where('id', $id)->first();
        $this->text = $this->text . $template_text['text'];
    }


    public function new_message()
    {
        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($this->text == null) {
            array_push($errors_array, 'Введите текст сообщения!');
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


            if ($this->flg_chat_creation) { /* Если такого чата не было, создаем его */
                $new_chat = new \App\Models\Chat();
                $new_chat->user_created = Auth::user()->id;
                $new_chat->user_to = $this->new_chat_user['id'];
                $new_chat->title = 'Личная переписка';
                $new_chat->chat_status_id = 1;
                $new_chat->pre_comment_flag = 0;

                $new_chat->save();

                $this->chat_id = $new_chat->id;

                /* Понимаем все поля теперь уже существующего чата */
                $this->flg_chat_creation = false;

                $this->chat = \App\Models\Chat::where('id', $this->chat_id)->first();
                $this->user_to = User::where('id', $this->chat['user_created'])->first();
                $this->messages = Message::where('chat_id', $this->chat_id)->with('message_file')->get();
                $this->currentUrl = url()->current();
                $this->dispatchBrowserEvent('update_hrefs');


            }

            $this->user_from = Auth::user()->id;
            if (\App\Models\Chat::where('id', $this->chat_id)->value('user_created') != $this->user_from) {
                $this->user_to = \App\Models\Chat::where('id', $this->chat_id)->value('user_created');
            } else {
                $this->user_to = \App\Models\Chat::where('id', $this->chat_id)->value('user_to');
            }


            $new_message = new Message();
            $new_message->chat_id = $this->chat_id;
            $new_message->user_from = $this->user_from;
            $new_message->user_to = $this->user_to;
            $new_message->text = $this->text;
            $new_message->save();

            // --------- Добавляем файлы, если есть  --------- //

            if (($this->message_files ?? null) && (count($this->message_files)) > 0) {
                $chat_files_folder = public_path('admin_files/chat_files/messageId_' . $new_message->id);

                //Создаем папку сообщения
                File::makeDirectory('admin_files/chat_files/messageId_' . $new_message->id, 0777, true);
                //-----------------------

                foreach ($this->message_files as $file_path) {
                    $file_old_path = $file_path;
                    $file_new_path = $chat_files_folder . '/' . substr($file_path, strrpos($file_path, '/') + 1);

                    File::move($file_old_path, $file_new_path);
                    $new_message_file = new message_file();
                    $new_message_file->message_id = $new_message->id;
                    $new_message_file->file = 'admin_files/chat_files/messageId_' . $new_message->id . '/' . substr($file_path, strrpos($file_path, '/') + 1);
                    $new_message_file->save();
                    $old_folder = substr($file_path, 25, strpos($file_path, '/', strpos($file_path, '/') + 1));
                    File::deleteDirectory(public_path('filepond_temp/chat_files/' . $old_folder));
                }
            }
            $this->dispatchBrowserEvent('clear_filepond');
            // --------- // Добавляем файлы, если есть  --------- //

            $this->messages = Message::where('chat_id', $this->chat_id)->get();
            $user = User::where('id', $this->user_to)->first();
            $chat = \App\Models\Chat::where('id', $this->chat_id)->first();

            if (in_array($this->cur_user_role, ['admin', 'ext_promotion_admin'])) { // Если пишет АДМИН
                $user_from = Auth::user()->name;
                $tel_message_title = "*{$user_from} -> автору '{$user['name']} {$user['surname']}'*";
                $this->chat->update([
                    'flg_chat_read' => 0 // Чат становится непрочитанным
                ]);

                if ($this->chat['chat_status_id'] === '1') { // Если ждет ответа поддержки
                    $this->chat->update([
                        'chat_status_id' => '2' // Ставим статус "ответ получен"
                    ]);
                } elseif ($this->chat['chat_status_id'] === '9') { // Если был пустой
                    $this->chat->update([
                        'chat_status_id' => '4' // Ставим статус "ответ получен"
                    ]);
                }

                if ($chat->collection_id > 0) { // Если общаются по участию в сборнике
                    $participation_id = Participation::where('user_id', $user['id'])->where('collection_id', $chat->collection_id)->value('id');
                    $text = "Вы получили новое сообщение в чате '" . $chat->title . "'! Прочитать и ответить можно на странице Вашего участия.";
                    $button_text = "Страница участия";
                    $url = '/myaccount/collections/' . $chat->collection_id . '/participation/' . $participation_id;

                } elseif ($chat->own_book_id > 0) { // Если общаются по изданию книги
                    $text = "Вы получили новое сообщение в чате '" . $chat->title . "'! Прочитать и ответить можно на странице Вашего издания.";
                    $button_text = "Страница издания";
                    $url = 'https://pervajakniga.ru/myaccount/mybooks/' . $chat->own_book_id . '/book_page';
                } else {
                    $text = "Вы получили новое сообщение в чате. Прочитать и ответить можно на странице Вашего издания.";
                    $button_text = "Мои чаты";
                    $url = route('all_chats');
                }

                if ($chat->own_book_id === null && $chat->collection_id === null) {
                    $url_back = route('chat', $chat->id);

                    // Посылаем Email уведомление пользователю
                    $user->notify(new EmailNotification(
                        'У вас новое сообщение!',
                        $user['name'],
                        "Вы получили новое сообщение в чате '" . $chat->title . "'!",
                        "Перейти в чат",
                        $url_back));
                    Notification::send($user, new UserNotification('У Вас новое сообщение!', '/myaccount/chats/' . $this->chat_id));
                }

                // Посылаем Email уведомление пользователю
                $user->notify(new EmailNotification('У вас новое сообщение!', $user['name'], $text, $button_text, $url));
                Notification::send($user, new UserNotification('У Вас новое сообщение!', '/myaccount/chats/' . $this->chat_id));

            } else {

                $tel_message_title = '';

                \App\Models\Chat::where('id', $this->chat_id)->update([
                    'chat_status_id' => '1',
                    'flg_chat_read' => 1
                ]);

            }

            $user_from = User::where('id', $this->user_from)->first();
            $is_ext_promotion_chat = str_contains($this->chat['title'], 'Личный чат по продвижению на сайте');
            if ($is_ext_promotion_chat) {
                $ext_promotion = ext_promotion::where('chat_id', $this->chat['id'])->first();
                $telegram_chat = '-4120321987';
            } else {
                $telegram_chat = '-506622812';
            }

            if(!(in_array($this->cur_user_role, ['admin', 'ext_promotion_admin'])) || $is_ext_promotion_chat) {
                // Посылаем Telegram уведомление нам
                Notification::route('telegram', $telegram_chat)
                    ->notify(new TelegramNotification($tel_message_title,
                        '💬' . $user_from['name'] . ' ' . $user_from['surname'] . ': ' . $this->text,
                        null,
                        null));
            }

        }

        $this->dispatchBrowserEvent('update_js');
        $this->dispatchBrowserEvent('scroll_chats');

        if ($this->new_chat_user['id'] ?? null) { // Если создали новый чат, обновим список чатов
            $this->emit('get_cur_chat_id', $this->chat['id']);
        }


        $this->text = '';


    }

    public function edit_message($message_id)
    {
        $this->editing_message_id = $message_id;
        $message = Message::where('id', $message_id)->first();
        $this->editing_text = $message['text'];
    }

    public function delete_confirm($message_id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Уверены, что хотите удалить сообщение?',
            'onconfirm' => 'delete_message',
            'id' => $message_id
        ]);
    }


    public function delete_message($message_id)
    {
        $message = Message::where('id', $message_id)->first();
        $message->delete();
        $this->messages = Message::where('chat_id', $this->chat_id)->with('message_file')->get();
    }

    public function save_message()
    {

        $message = Message::where('id', $this->editing_message_id)->first();

        $message->update([
            'text' => $this->editing_text
        ]);

        $this->editing_message_id = null;

        $this->messages = Message::where('chat_id', $this->chat_id)->with('message_file')->get();

        $this->dispatchBrowserEvent('update_js');
        $this->dispatchBrowserEvent('scroll_chats');
    }

}
