<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyChats extends Component
{
    Public $chat_group;

    protected $listeners = ['delete'];

    public function render()
    {
        if ($this->chat_group === 1)
        {
            $ids = [ 4, 1, 2, 3, 9];
            $chats = $chats_check = Chat::where('chat_status_id', '<>', 3)
            ->where(function($q) {
                $q->where('user_to', Auth::user()->id)
                    ->orWhere('user_created', Auth::user()->id);
            })
            ->where('collection_id', null)
            ->where('own_book_id', null)
            ->orderByRaw('FIELD (chat_status_id, ' . implode(', ', $ids) . ')')
            ->get();}
        else {$chats = Chat::where('chat_status_id', 3)
            ->where(function($q) {
                $q->where('user_to', Auth::user()->id)
                    ->orWhere('user_created', Auth::user()->id);
            })
            ->get();}
        return view('livewire.my-chats', [
            'chats' => $chats,
            'chats_check2' => 2,
        ]);
    }
    public function delete_confirm($chat_id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
        'type' => 'warning',
        'title' => 'Вы уверены, что хотите удалить вопрос?',
         'id' =>  $chat_id
        ]);
    }

    public function delete($chat_id)
    {
        \App\Models\Chat::where('id', $chat_id)->update(array('chat_status_id' => '3'));
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Вопрос успешно закрыт!',
            'text' => 'Теперь этот вопрос находится в архиве. Его можно восстановить в любой момент!',
        ]);

    }

}
