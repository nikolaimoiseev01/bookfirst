<?php

namespace App\Livewire\Components\Account;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chats\Pages\ViewChat;
use App\Jobs\EmailNotificationJob;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Message;
use App\Notifications\ChatMessageEmailNotification;
use App\Notifications\OwnBook\OwnBookCreatedNotification;
use App\Notifications\TelegramDefaultNotification;
use App\Traits\WithCustomValidation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\LivewireFilepond\WithFilePond;

class Chat extends Component
{
    use WithFileUploads;
    use WithFilePond;
    use WithCustomValidation;

    public $chat;
    public $text;
    public $editedText;

    public $files = [];

    public $isSending = false;


    protected $listeners = ['refreshChat' => '$refresh'];

    public function render()
    {
        return view('livewire.components.account.chat');
    }

    public function editMessage($id)
    {
        $this->editedText = collect($this->chat['messages'])->where('id', $id)->first()['text'];
    }

    public function saveEditedMessage($id)
    {
        Message::where('id', $id)->update([
            'text' => $this->editedText
        ]);
    }

    public function deleteMessage($id)
    {
        Message::where('id', $id)->update([
            'text' => $this->editedText
        ]);
    }

    public function mount($chat)
    {
        $this->chat = $chat->load(['messages.user', 'chatStatus']);
        if (Auth::user()->hasRole(['admin', 'ext_promotion_admin', 'secondary_admin'])) {
            $author = $this->chat['user_created'] == 2 ?
                $this->chat->userTo->getUserFullName() :
                $this->chat->userCreated->getUserFullName();
            $this->text = "Здравствуйте, {$author}!";
        }
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
            'files.*' => 'max:3000',
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст сообщения обязателен для заполнения'
        ];
    }

    public function notifyNewMessage()
    {
        if (Auth::user()->hasRole('user') && $this->chat['flg_admin_chat']) {
            if ($this->chat['model_type'] == 'ExtPromotion') {
                $chatToSend = 'extPromotion';
                $url = null;
            } else {
                $chatToSend = 'main';
                $preUrl = match ($this->chat['model_type']) {
                    'Collection', 'OwnBook', 'Participation' =>  $this->chat->model->getAdminEditPage,
                    default => ViewChat::getUrl(['record' => $this->chat])
                };
                $url = route('login_as_admin', ['url_redirect' => $preUrl]);
            }
            $userName = Auth::user()->getUserFullName();
            $notificationText = "💬 {$userName}: {$this->text}";
            $notification = new TelegramDefaultNotification(null, $notificationText, $url, $chatToSend);
            TelegramNotificationJob::dispatch($notification);
        } else {
            $userIdToNotify = $this->chat['user_created'] == 2 ? $this->chat['user_to'] : $this->chat['user_created'];
            $notification = new ChatMessageEmailNotification($this->chat);
            EmailNotificationJob::dispatch($userIdToNotify, $notification);
        }
    }

    public function notifyNewMessageEmail() {

    }

    public function updateChatStatus()
    {
        if ($this->chat['flg_admin_chat']) {
            if (Auth::user()->hasRole('user')) {
                $status = ChatStatusEnums::WAIT_FOR_ADMIN;
            } else {
                $status = ChatStatusEnums::WAIT_FOR_USER;
            }
        } else {
            $status = ChatStatusEnums::PERSONAL_CHAT;
        }
        $this->chat->update([
            'status' => $status
        ]);
    }


    public function sendMessage()
    {
        if ($this->customValidate()) {
            DB::transaction(function () {
                $message = Message::create([
                    'chat_id' => $this->chat['id'],
                    'user_id' => Auth::user()->id,
                    'text' => $this->text
                ]);
                if ($this->files) {
                    foreach ($this->files as $file) {
                        $message
                            ->addMedia($file->getRealPath()) // 👈 важно
                            ->usingFileName($file->getClientOriginalName()) // если хочешь сохранить оригинальное имя
                            ->toMediaCollection('files');
                    }
                }

                $this->updateChatStatus();
                $this->notifyNewMessage();

                $this->dispatch('scrollChatToEnd');
                $this->reset('files');
                $this->text = '';
                $this->dispatch('filepond-reset-files');
            });
        }

        $this->isSending = false;
    }
}
