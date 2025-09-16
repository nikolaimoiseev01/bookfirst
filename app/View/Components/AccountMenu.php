<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
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
                'url' => route('portal.index'),
            ],
            [
                'name' => 'Мои сборники',
                'icon' => 'uni-books-o',
                'url' => route('account.collections'),
            ],
            [
                'name' => 'Собственные книги',
                'icon' => 'uni-book-alt-o',
                'url' => route('account.own_books'),
            ],
            [
                'name' => 'Продвижение',
                'icon' => 'akar-statistic-up',
                'url' => route('account.ext_promotions'),
            ],
            [
                'name' => 'Произведения',
                'icon' => 'bi-feather',
                'url' => route('account.works'),
            ],
            [
                'name' => 'Сообщения',
                'icon' => 'bi-chat',
                'url' => route('account.chats'),
            ],
            [
                'name' => 'Избранные авторы',
                'icon' => 'bi-star',
                'url' => route('account.subscriptions'),
            ],
            [
                'name' => 'Мои покупки',
                'icon' => 'bi-cart',
                'url' => route('account.purchases'),
            ],
            [
                'name' => 'Мои настройки',
                'icon' => 'bi-gear',
                'url' => route('account.settings'),
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
