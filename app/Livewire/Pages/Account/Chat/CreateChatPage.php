<?php

namespace App\Livewire\Pages\Account\Chat;

use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;

class CreateChatPage extends Component
{
    use WithFilePond;
    use WithCustomValidation;

    public $title;
    public $text;
    public $files = [];

    public function render()
    {
        return view('livewire.pages.account.chat.create-chat-page')->layout('layouts.account');
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
            'title' => 'required', // ~10MB
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
                    'user_to' => 2,
                    'title' => $this->title,
                    'chat_status_id' => 1,
                    'flg_admin_chat' => true
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

                session()->flash('swal', [
                    'title' => 'Успешно!',
                    'icon' => 'success',
                    'text' => 'Обсуждение успешно создано! Мы ответим в ближайшее время.'
                ]);

                $this->redirect(route('account.chats'), navigate: true);
            });
        }

    }
}
