<?php

namespace App\Livewire\Components\Account;

use App\Models\Chat\Message;
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

    public $chat;
    public $text;

    public $files = [];


    protected $listeners = ['refreshChat' => '$refresh'];

    public function render()
    {
        return view('livewire.components.account.chat');
    }

    public function mount($chat)
    {
        $this->chat = $chat->load(['messages.user', 'chatStatus']);
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Сообщение обязательно для заполнения',
            'files.*.max'   => 'Размер файла не должен превышать 30 МБ',
        ];
    }

    public function custom_validation()
    {
        try {
            $this->validate([
                'text' => 'required',
                'files.*' => 'max:102',
            ]);

            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $messages = collect($e->validator->errors()->all())->implode("<br>");
            $this->dispatch('swal', title: 'Ошибка', text: $messages);
            return false;
        }
    }


    public function send()
    {
        dd($this->files);
        if ($this->custom_validation()) {
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
            });
        }

//        dd($this->file);
    }
}
