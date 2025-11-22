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
    public $userOnline;

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
                'value' => $user->subscribers_count,
                'icon' => 'bi-people-fill',
            ],
            [
                'title' => 'Читает',
                'value' => $user->subscribed_to_users_count,
                'icon' => 'bi-eye',
            ],
            [
                'title' => 'Работ',
                'value' => $user->works_count,
                'icon' => 'bi-book-half',
            ],
            [
                'title' => 'Наград',
                'value' => $user->awards_count,
                'icon' => 'bi-award',
            ]
        ];
        $this->userIsSubscribed = auth()->id() ? $user->subscribers()->where('user_id', auth()->user()->id)->exists() : false;
        $this->userOnline = $user->isOnline();
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
            $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Нельзя написать сообщение себе');
        } else {
            $this->redirect(route('account.chat_create',['userToId' => $this->user['id']]));
        }
    }
}
