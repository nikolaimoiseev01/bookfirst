<?php

namespace App\Livewire\Components\Social;

use App\Models\User\User;
use App\Models\User\UserXUserSubscription;
use Livewire\Component;

class UserHeader extends Component
{
    public $user;
    public $userStat;
    public $userIsSubscribed;

    public function render()
    {
        return view('livewire.components.social.user-header');
    }

    public function mount($user)
    {
        $this->user = $user;
        $this->userStat = [
            [
                'title' => 'Читателей',
                'value' => 0
            ],
            [
                'title' => 'Читает',
                'value' => 0
            ],
            [
                'title' => 'Работ',
                'value' => 0
            ],
            [
                'title' => 'Наград',
                'value' => 0
            ]
        ];
        $this->userIsSubscribed = auth()->id() ? $user->subscribers()->where('user_id', auth()->user()->id)->exists() : false;
    }

    public function subscribe()
    {
        if (auth()->user()->id == $this->user['id']) {
            $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Нельзя подписаться на себя');
        } else {
            if ($this->userIsSubscribed) {
                UserXUserSubscription::where([
                    'user_id' => auth()->user()->id,
                    'subscribed_to_user_id' => $this->user['id']
                ])->delete();
            } else {
                UserXUserSubscription::create([
                    'user_id' => auth()->user()->id,
                    'subscribed_to_user_id' => $this->user['id']
                ]);
            }
        }
    }

    public function sendMessage() {
        if (auth()->user()->id == $this->user['id']) {
            $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Нельзя подписаться на себя');
        } else {
            $this->redirect(route('account.chat_create'));
        }
    }
}
