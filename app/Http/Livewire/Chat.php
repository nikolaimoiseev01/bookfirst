<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\message_file;
use App\Models\User;
use App\Notifications\UserNotification;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;


class Chat extends Component
{
    public $text;
    public $messages;
    public $chat_id;
    public $message_files;
    public $currentUrl;

    protected $listeners = [
        'new_message',
    ];

    public function render()
    {
//        dd($this->message_file);
        $this->chat = \App\Models\Chat::where('id', $this->chat_id)->first();
        return view('livewire.chat', [
            'messages' => $this->messages,
            'chat' => $this->chat,
        ]);
//        dd($this->chat);
    }

    public function reopenChat($chat_id)
    {
        \App\Models\Chat::where('id', $chat_id)->update(array('chat_status_id' => '2'));
        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Вопрос открыт снова!');
        return redirect($this->currentUrl);
    }

    public function mount($chat_id)
    {
        $this->messages = Message::where('chat_id', $chat_id)->with('message_file')->get();
        $this->chat_id = $chat_id;
        $this->currentUrl = url()->current();
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

            if ($this->message_files <> null) {
                $chat_files_folder = public_path('admin_files/chat_files');
                $this->message_files = explode(';', $this->message_files);
                foreach ($this->message_files as $file_path) {

                    $file_old_path = $file_path;
                    $file_new_path = $chat_files_folder . '/' . substr($file_path, strrpos($file_path, '/') + 1);
                    File::move($file_old_path, $file_new_path);
                    $new_message_file = new message_file();
                    $new_message_file->message_id = $new_message->id;
                    $new_message_file->file = $file_new_path;
                    $new_message_file->save();
                    $old_folder = substr($file_path, 25, strpos($file_path, '/', strpos($file_path, '/')+1));
                    File::deleteDirectory(public_path('filepond_temp/chat_files/' . $old_folder));
                }
            }
            $this->dispatchBrowserEvent('clear_filepond');
            // --------- // Добавляем файлы, если есть  --------- //

            $this->messages = Message::where('chat_id', $this->chat_id)->get();
            $this->text = '';

            if (Auth::user()->hasRole('admin')) {
                if (\App\Models\Chat::where('id', $this->chat_id)->value('chat_status_id') === '1') {
                    \App\Models\Chat::where('id', $this->chat_id)->update(array('chat_status_id' => '2'));
                }
                $user = User::orderby('id')->where('id', $this->user_to)->get();
                Notification::send($user, new UserNotification('У Вас новое сообщение!', '/myaccount/chats/' . $this->chat_id));
            } else {
                \App\Models\Chat::where('id', $this->chat_id)->update(array('chat_status_id' => '1'));
            }
        }


    }

}
