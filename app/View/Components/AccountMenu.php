<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class AccountMenu extends Component
{
    public $links;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->links = [
            [
                'name' => 'Моя страница',
                'icon' => 'bi-person',
                'url' => route('social.user', Auth::user()->id),
                'new' => false
            ],
            [
                'name' => 'Мои сборники',
                'icon' => 'uni-books-o',
                'url' => route('account.participations'),
                'new' => false
            ],
            [
                'name' => 'Собственные книги',
                'icon' => 'uni-book-alt-o',
                'url' => route('account.own_books'),
                'new' => false
            ],
            [
                'name' => 'Продвижение',
                'icon' => 'akar-statistic-up',
                'url' => route('account.ext_promotions'),
                'new' => false
            ],
            [
                'name' => 'Произведения',
                'icon' => 'bi-feather',
                'url' => route('account.works'),
                'new' => false
            ],
            [
                'name' => 'Сообщения',
                'icon' => 'bi-chat',
                'url' => route('account.chats'),
                'new' => false
            ],
            [
                'name' => 'Приведи друга',
                'icon' => 'bi-person-add',
                'url' => route('account.friend-invite'),
                'new' => true
            ],
            [
                'name' => 'Избранные авторы',
                'icon' => 'bi-star',
                'url' => route('account.subscriptions'),
                'new' => false
            ],
            [
                'name' => 'Мои покупки',
                'icon' => 'bi-cart',
                'url' => route('account.purchases'),
                'new' => false
            ],
            [
                'name' => 'Мои настройки',
                'icon' => 'bi-gear',
                'url' => route('account.settings'),
                'new' => false
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.account-menu');
    }
}
