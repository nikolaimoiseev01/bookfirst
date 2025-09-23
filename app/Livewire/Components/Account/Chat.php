<?php

namespace App\Livewire\Components\Account;

use App\Models\Chat\Message;
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

    public $files = [];

    public $isSending = false;


    protected $listeners = ['refreshChat' => '$refresh'];

    public function render()
    {
        return view('livewire.components.account.chat');
    }

    public function mount($chat)
    {
        $this->chat = $chat->load(['messages.user', 'chatStatus']);
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст сообщения обязателен для заполнения'
        ];
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
                $this->dispatch('scrollChatToEnd');
                $this->reset('files');
                $this->text = '';
                $this->dispatch('filepond-reset-files');
            });
        }

        $this->isSending = false;

//        dd($this->file);
    }
}
