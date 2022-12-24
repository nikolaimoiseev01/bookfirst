<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\message_file;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ChatsBlock extends Component
{
    public $chosen_chat_id = 2;
    public $cur_chat = [];

    public $query;
    public $user_chats;

    public $text;
    public $messages;
    public $chat_id;
    public $message_files;
    public $currentUrl;
    public $user_to;
    public $chat;
    public $new_chat_user_id;
    public $new_chat_user_id_check;

    public $show_admin_chats = false;


    protected $listeners = [
        'new_message',
    ];

    function __construct()
    {
        $this->query = '
        SELECT c.*
        ,u_cr.id as u_cr_id, u_cr.avatar as u_cr_avatar ,ifnull(u_cr.nickname, concat(u_cr.name, " ",u_cr.surname)) as u_cr_name
        ,u_to.id as u_to_id, u_to.avatar as u_to_avatar ,ifnull(u_to.nickname, concat(u_to.name, " ",u_to.surname)) as u_to_name
        ,m.last_mes_text, m.last_mes_created
        ,m.last_mes_id
        ,m.last_mes_to
        ,m.flag_mes_read
        FROM chats as c
        JOIN users as u_cr on u_cr.id = c.user_created
        JOIN users as u_to on u_to.id = c.user_to
        LEFT JOIN  (
            SELECT
            m.chat_id,
            m.id as last_mes_id,
            m.text as last_mes_text,
            m.user_to as last_mes_to,
            m.flag_mes_read,
            m.created_at as last_mes_created
            FROM messages m
            JOIN (
                SELECT chat_id, MAX(m.updated_at) AS max_mes_upd
                FROM messages m
                group by chat_id
            ) b ON m.chat_id = b.chat_id and m.updated_at = b.max_mes_upd
        ) m on m.chat_id = c.id
		WHERE (c.user_created = ' . Auth::user()->id . ' or c.user_to = ' . Auth::user()->id . ')
		and c.chat_status_id <> 3
        ORDER BY last_mes_created desc
        ';
    }


    public function render()
    {


        $this->user_chats = DB::select(DB::raw($this->query));


        $this->cur_chat = DB::table('chats as c')
            ->Join('users as u_cr', 'u_cr.id', '=', 'c.user_created')
            ->Join('users as u_to', 'u_to.id', '=', 'c.user_to')
            ->select('c.*'
                , 'u_cr.id as u_cr_id', 'u_cr.avatar as u_cr_avatar', DB::raw('ifnull(u_cr.nickname, concat(u_cr.name, " ",u_cr.surname)) as u_cr_name')
                , 'u_to.id as u_to_id', 'u_to.avatar as u_to_avatar', DB::raw('ifnull(u_to.nickname, concat(u_to.name, " ",u_to.surname)) as u_to_name'))
            ->where('c.id', $this->chosen_chat_id)
            ->get();

        if($this->new_chat_user_id_check) {
            $cur_chat_publ_page = null;
        }

        elseif ($this->cur_chat[0]->collection_id) {
            $participation = Participation::where('collection_id', $this->cur_chat[0]->collection_id)->where('user_id', Auth::user()->id)->first();
            $cur_chat_publ_page = route('participation_index', ['participation_id'=>$participation['id'],'collection_id'=>$participation['collection_id']]);
        }

        elseif ($this->cur_chat[0]->own_book_id) {
            $cur_chat_publ_page = route('book_page', $this->cur_chat[0]->own_book_id);
        }

        $this->messages = Message::where('chat_id', $this->chosen_chat_id)->with('message_file')->get();
        return view('livewire.chats-block', [
            'user_chats' => $this->user_chats,
            'cur_chat' => $this->cur_chat,
            'chosen_chat_id' => $this->chosen_chat_id,
            'messages' => $this->messages,
            'new_chat_user_id' => $this->new_chat_user_id,
            'new_chat_user_id_check' => $this->new_chat_user_id_check,
            'cur_chat_publ_page' => $cur_chat_publ_page ?? null
        ]);

    }


    public function mount($new_chat_user_id)
    {

        if ($new_chat_user_id) {
            $this->new_chat_user_id = User::where('id', $new_chat_user_id)->first();
            $this->new_chat_user_id_check = true;
        }

        $this->user_chats = DB::select(DB::raw($this->query));

        if ($new_chat_user_id) { // Если есть, кому писать, проверяем, есть ли такой чат уже

            $if_chat_exists = \App\Models\Chat::
            where(function ($query) {
                $query->where('user_created', $this->new_chat_user_id['id'])
                    ->Where('user_to', Auth::user()->id);
            })
                ->orwhere(function ($query) {
                    $query->where('user_created', Auth::user()->id)
                        ->Where('user_to', $this->new_chat_user_id['id']);
                })
                ->first();

            if ($if_chat_exists) { // если такой чат есть, скрываем поля для нового чата
                $this->chosen_chat_id = $if_chat_exists['id']; // видим id уже имеющегося чата
                $this->new_chat_user_id_check = false;
                $this->new_chat_user_id = null;
            }


        } else {
            $this->chosen_chat_id = $this->user_chats[0]->id; // видим id последнего чата
        }


    }


    public function choose_chat($clicked_chat_id)
    {
        $this->new_chat_user_id_check = false;
        $this->chosen_chat_id = $clicked_chat_id;

        $this->dispatchBrowserEvent('show_admin_chats_true');

    }


    public function choose_new_chat()
    {
        $this->new_chat_user_id_check = true;

    }

    public function reopenChat($chat_id)
    {
        \App\Models\Chat::where('id', $chat_id)->update(array('chat_status_id' => '2'));
        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Вопрос открыт снова!');
        return redirect($this->currentUrl);
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


            $this->user_from = Auth::user()->id;




            if ($this->new_chat_user_id_check === true) {
                $new_chat = new \App\Models\Chat();
                $new_chat->user_created = Auth::user()->id;
                $new_chat->user_to = $this->new_chat_user_id['id'];
                $new_chat->title = 'Личная переписка';
                $new_chat->chat_status_id = 1;
                $new_chat->pre_comment_flag = 0;

                $new_chat->save();
                $this->chosen_chat_id = $new_chat->id;
                $this->new_chat_user_id_check = false;
//                $this->dispatchBrowserEvent('new_chat_hide');
                $this->new_chat_user_id = null;

            }

            if (\App\Models\Chat::where('id', $this->chosen_chat_id)->value('user_created') != Auth::user()->id) {
                $this->user_to = \App\Models\Chat::where('id', $this->chosen_chat_id)->value('user_created');
            } else {
                $this->user_to = \App\Models\Chat::where('id', $this->chosen_chat_id)->value('user_to');
            }


            $new_message = new Message();
            $new_message->chat_id = $this->chosen_chat_id;
            $new_message->user_from = $this->user_from;
            $new_message->user_to = $this->user_to;
            $new_message->text = $this->text;
            $new_message->save();


            // --------- Добавляем файлы, если есть  --------- //

            if ($this->message_files <> null) {
                $chat_files_folder = public_path('admin_files/chat_files/messageId_' . $new_message->id);
                $this->message_files = explode(';', $this->message_files);

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


            $this->messages = Message::where('chat_id', $this->chosen_chat_id)->get();
            $user = User::where('id', $this->user_to)->first();
            $chat = \App\Models\Chat::where('id', $this->chosen_chat_id)->first();
//
            if (Auth::user()->hasRole('admin')) { // Если сообщение посылает админ, то шлем Email пользователю

                if (\App\Models\Chat::where('id', $this->chosen_chat_id)->value('chat_status_id') === '1') {
                    \App\Models\Chat::where('id', $this->chosen_chat_id)->update(array('chat_status_id' => '2'));
                }


                if ($chat->collection_id > 0) {
                    $participation_id = Participation::where('user_id', $user['id'])->where('collection_id', $chat->collection_id)->value('id');
                    $url_back = '/myaccount/collections/' . $chat->collection_id . '/participation/' . $participation_id;
                    $email_text = "Вы получили новое сообщение в чате '" . $chat->title . "'! Прочитать и ответить можно на странице Вашего участия:";
                }

                if ($chat->own_book_id > 0) {
                    $url_back = 'https://pervajakniga.ru/myaccount/mybooks/' . $chat->own_book_id . '/book_page';
                    $email_text = "Вы получили новое сообщение в чате '" . $chat->title . "'! Прочитать и ответить можно на странице Вашего издания:";
                }

                if ($chat->own_book_id === null && $chat->collection_id === null) {
                    $url_back = route('chat', $chat->id);
                    $email_text = "Вы получили новое сообщение в чате '" . $chat->title . "'!";
                }

                // Посылаем Email уведомление пользователю
                $user->notify(new EmailNotification(
                    'У вас новое сообщение!',
                    $user['name'],
                    "Вы получили новое сообщение в чате '" . $chat->title . "'! Прочитать и ответить можно на странице Вашего участия:",
                    "Страница участия",
                    $url_back));
                Notification::send($user, new UserNotification('У Вас новое сообщение!', '/myaccount/chats/' . $this->chosen_chat_id));


            } elseif ($this->user_to === 2) { // Если сообщение админу, то посылаем сообщение нам в телегу

                $user_from = User::where('id', $this->user_from)->first();

                \App\Models\Chat::where('id', $this->chosen_chat_id)->update(array('chat_status_id' => '1', 'flag_hide_question' => 0));

                // Посылаем Telegram уведомление нам
                Notification::route('telegram', '-506622812')
                    ->notify(new TelegramNotification('',
                        '💬' . $user_from['name'] . ' ' . $user_from['surname'] . ': ' . $this->text,
                        "К чатам",
                        "https://pervajakniga.ru/"));
            }
        }

        $this->dispatchBrowserEvent('scroll_down');
        $this->dispatchBrowserEvent('show_send_button');
        $this->dispatchBrowserEvent('update_hrefs');
        $this->dispatchBrowserEvent('show_cur_chat_block_after_send');

        $this->text = '';


    }


}
