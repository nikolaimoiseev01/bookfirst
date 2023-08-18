<?php

namespace App\Http\Livewire\Account\Chat;

use App\Models\Message;
use App\Models\message_file;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\EmailNotification;
use App\Notifications\TelegramNotification;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ChatsBlock extends Component
{
    public $show_type;

    public $cur_chat_id;
    public $cur_chat;

    public $user_chats_query;
    public $flg_admin_chat = 1;
    public $chats_type_to_show = 'admin';
    public $user_chats;
    public $user_chats_all_check;

    public $messages;

    public $new_chat_user_id_check;
    public $cur_chat_publ_page;

    public $new_chat_user;

    protected $listeners = ['refreshChatsBlock' => '$refresh', 'get_cur_chat_id'];
    protected $queryString = ['cur_chat_id'];


    public function __construct()
    {
        $this->user_chats_query = '
        SELECT c.*
        ,case when u_cr.id <> ' . Auth::user()->id . ' then u_cr.id else u_to.id end as u_id
        ,case when u_cr.id <> ' . Auth::user()->id . ' then u_cr.avatar else u_to.avatar end as u_avatar
        ,case when u_cr.id <> ' . Auth::user()->id . '
            then ifnull(u_cr.nickname, concat(u_cr.name, " ",u_cr.surname))
            else ifnull(u_to.nickname, concat(u_to.name, " ",u_to.surname))
            end as u_name
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
            m.flag_mes_read ,
            m.created_at as last_mes_created
            FROM messages m
            JOIN (
                SELECT chat_id, MAX(m.updated_at) AS max_mes_upd
                FROM messages m
                group by chat_id
            ) b ON m.chat_id = b.chat_id and m.updated_at = b.max_mes_upd
        ) m on m.chat_id = c.id
		WHERE (c.user_created = ' . Auth::user()->id . ' or c.user_to = ' . Auth::user()->id . ')
        and flg_admin_chat = {flg_admin_chat}
            and c.chat_status_id <> 3
        ORDER BY last_mes_created desc';
    }

    public function render()
    {

        // Проверяем, есть ли вообще чаты у пользователя?
        $this->user_chats_all_check = \App\Models\Chat::where('user_created',  Auth::user()->id)->orWhere('user_to',  Auth::user()->id)->get();


        // Создаем текущий чат
        $this->cur_chat = collect(DB::table('chats as c')
            ->Join('users as u_cr', 'u_cr.id', '=', 'c.user_created')
            ->Join('users as u_to', 'u_to.id', '=', 'c.user_to')
            ->select('c.*'
                , DB::raw('case when u_cr.id <> ' . Auth::user()->id . ' then u_cr.id else u_to.id end as u_id')
                , DB::raw('case when u_cr.id <> ' . Auth::user()->id . ' then u_cr.avatar else u_to.avatar end as u_avatar')
                , DB::raw('case when u_cr.id <> ' . Auth::user()->id . ' then ifnull(u_cr.nickname, concat(u_cr.name, " ",u_cr.surname)) else ifnull(u_to.nickname, concat(u_to.name, " ",u_to.surname)) end as u_name')
            )
            ->where('c.id', $this->cur_chat_id)
            ->first());

        if (count($this->cur_chat) > 0) {


            // Создаем ссылку сверху чата
            if ($this->new_chat_user_id_check) {
                $this->cur_chat_publ_page = null;
            } elseif ($this->cur_chat['collection_id'] ?? null) {
                $participation = Participation::where('collection_id', $this->cur_chat['collection_id'])->where('user_id', Auth::user()->id)->first();
                if ($participation) {
                    $this->cur_chat_publ_page = route('participation_index', ['participation_id' => $participation['id'], 'collection_id' => $participation['collection_id']]);
                }
            } elseif ($this->cur_chat['own_book_id'] ?? null) {
                $this->cur_chat_publ_page = route('book_page', $this->cur_chat['own_book_id']);
            }

            // Все сообщения чата
            $this->messages = Message::where('chat_id', $this->cur_chat['id'])->with('message_file')->get();

            // Понимаем тип текущего чата
            if ($this->cur_chat) {
                if ($this->cur_chat['flg_admin_chat'] === 1) {
                    $this->chats_type_to_show = 'admin';
                    $this->flg_admin_chat = 1;
                } else {
                    $this->chats_type_to_show = 'personal';
                    $this->flg_admin_chat = 0;
                }
            }
        }

        // Понимаем все чаты
        $query = str_replace('{flg_admin_chat}', $this->flg_admin_chat, $this->user_chats_query);
        $this->user_chats = \App\Models\Chat::hydrate(DB::select($query));


        return view('livewire.account.chat.chats-block');

    }


    public function mount($new_chat_user_id)
    {
        $query = str_replace('{flg_admin_chat}', $this->flg_admin_chat, $this->user_chats_query);
        $this->user_chats = \App\Models\Chat::hydrate(DB::select($query));


        if ($new_chat_user_id) { // Если перешли по "написать чевловеку", проверяем, есть ли такой чат уже

            $this->show_type = 'chat';
            $this->flg_admin_chat = 0;
            $this->chats_type_to_show = 'personal';

            $this->new_chat_user = User::where('id', $new_chat_user_id)->first();

            $found_chat = \App\Models\Chat::
            where(function ($query) {
                $query->where('user_created', $this->new_chat_user['id'])
                    ->Where('user_to', Auth::user()->id);
            })
                ->orwhere(function ($query) {
                    $query->where('user_created', Auth::user()->id)
                        ->Where('user_to', $this->new_chat_user['id']);
                })
                ->first();

            if ($found_chat) { // Если такой чат есть, берем его ID
//                dd($found_chat['id']);
                $this->cur_chat_id = $found_chat['id'];
            } else { // Если нет такого чата, то выбранный чат == null
                $this->cur_chat_id = null; // Текущего чата нет
                $this->new_chat_user_id_check = true; // Но есть флаг на создание нового
            }


        } elseif (count($this->user_chats) > 0 ?? null) { // Если есть какой-нибудь чат
            $this->show_type = 'list';
            if (!$this->cur_chat_id) { // Если никакой чат не выбран
                $this->cur_chat_id = $this->user_chats[0]->id; // видим id последнего чата (по умолчанию личные)
            }
        }

    }


    public function choose_chat($clicked_chat_id)
    {
        $this->cur_chat_id = $clicked_chat_id;
        $this->show_type = 'chat';
        $this->dispatchBrowserEvent('filepond_trigger');
    }


    public function choose_new_chat()
    {
        $this->cur_chat_id = null;
        $this->cur_chat = null;
        $this->show_type = 'chat';
        $this->dispatchBrowserEvent('filepond_trigger');

    }


    public function back_to_chats()
    {
        $this->show_type = 'list';

    }


    public function choose_chats_type($type)
    {

        $this->chats_type_to_show = $type;
        if ($type === 'admin') {
            $this->flg_admin_chat = 1;
        } else {
            $this->flg_admin_chat = 0;
        }

        // Находим последний чат в выбранной категории
        $this->cur_chat_id = \App\Models\Chat::where('flg_admin_chat', $this->flg_admin_chat)
            ->where(function ($query) {
                $query->where('user_to', '=', Auth::user()->id)
                    ->orWhere('user_created', '=', Auth::user()->id);
            })
            ->orderBy('updated_at', 'desc')
            ->first()['id'] ?? null;

        // При обновлении чата тригерим загрузку файла на всякий случай
        $this->dispatchBrowserEvent('filepond_trigger');
    }


    public function get_cur_chat_id($chat_id)
    {
        $this->cur_chat_id = $chat_id;
        $this->flg_admin_chat = 0;
        $this->chats_type_to_show = 'personal';
        $this->new_chat_user_id_check = false;
        // При обновлении чата тригерим загрузку файла на всякий случай
        $this->dispatchBrowserEvent('filepond_trigger');
    }


}
