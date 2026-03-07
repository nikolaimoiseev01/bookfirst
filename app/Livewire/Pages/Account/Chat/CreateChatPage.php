<?php

namespace App\Livewire\Pages\Account\Chat;

use App\Enums\ChatStatusEnums;
use App\Filament\Resources\Chat\Chats\Pages\ViewChat;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\User\User;
use App\Notifications\TelegramDefaultNotification;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

class CreateChatPage extends Component
{
    use WithFilePond;
    use WithCustomValidation;

    #[Url]
    public $title;
    public $text;
    public $files = [];
    public $isSending;
    #[Url]
    public $userToId = 2;
    public $userTo;

    public function render()
    {
        return view('livewire.pages.account.chat.create-chat-page')->layout('layouts.account');
    }

    public function mount() {
        if ($this->userToId != 2) {
            $this->userTo = User::where('id', $this->userToId)->first();
        }
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
            'title' => \Illuminate\Validation\Rule::requiredIf(fn() => $this->userToId == 2), // ~10MB
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст сообщения обязателен для заполнения',
            'title.required' => 'Заголовок обязателен для заполнения'
        ];
    }

    public function createChat()
    {
        if ($this->customValidate()) {
            DB::transaction(function () {
                $chat = Chat::create([
                    'user_created' => Auth::user()->id,
                    'user_to' => $this->userToId,
                    'title' => $this->userToId == 2 ? $this->title : 'Личная переписка c автором ' . $this->userTo->getUserFullName(),
                    'status' => $this->userToId == 2 ? ChatStatusEnums::WAIT_FOR_ADMIN : ChatStatusEnums::PERSONAL_CHAT,
                    'flg_admin_chat' => $this->userToId == 2
                ]);
                $message = Message::create([
                    'chat_id' => $chat['id'],
                    'user_id' => Auth::user()->id,
                    'text' => $this->text,
                ]);

                if (count($this->files) > 0) {
                    foreach ($this->files as $file) {
                        $message
                            ->addMedia($file->getRealPath())       // путь до tmp файла
                            ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                            ->toMediaCollection('files');   // твоя коллекция
                    }
                }

                if ($this->userToId == 2) {
                    $subject = '📌Открыт новый чат!📌';
                    $userFromName = Auth::user()->getUserFullName();
                    $text = "Автор: {$userFromName} \n $this->text";
                    $chatUrl = match ($chat['model_type']) {
                        'Collection', 'OwnBook', 'ExtPromotion' => $chat->model->adminEditPageWithoutLogin(),
                        default => ViewChat::getUrl(['record' => $chat])
                    };
                    $url = route('login_as_secondary_admin', ['url_redirect' => $chatUrl]);
                    $notification = new TelegramDefaultNotification($subject, $text, $url);
                    TelegramNotificationJob::dispatch($notification);
                }

                session()->flash('swal', [
                    'title' => 'Успешно!',
                    'icon' => 'success',
                    'text' => 'Обсуждение успешно создано!'
                ]);

                $this->redirect(route('account.chats'), navigate: true);
            });
        }

    }
}
