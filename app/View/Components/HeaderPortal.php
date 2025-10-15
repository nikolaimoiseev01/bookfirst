<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class HeaderPortal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if (Str::contains(request()->url(), 'social'))
            $links = [
                [
                    'name' => 'Произведения',
                    'url_part' => route('social.works_feed'),
                    'route' => route('social.works_feed')
                ],
                [
                    'name' => 'Продвижение',
                    'url_part' => 'ext-promotion',
                    'route' => route('portal.ext_promotion')
                ],
                [
                    'name' => 'Разместить',
                    'url_part' => 'ext-promotion',
                    'route' => route('portal.ext_promotion')
                ],
                [
                    'name' => 'Конкурсы',
                    'url_part' => 'ext-promotion',
                    'route' => route('portal.ext_promotion')
                ],
            ];
        else {
            $links = [
                [
                    'name' => 'Сборники',
                    'url_part' => 'collection',
                    'routes' => [
                        [
                            'name' => 'Актуальные',
                            'link' => route('portal.collections.actual')
                        ],
                        [
                            'name' => 'Изданные',
                            'link' => route('portal.collections.released')
                        ],
                    ]
                ],
                [
                    'name' => 'Собственные книги',
                    'url_part' => 'own-book',
                    'routes' => [
                        [
                            'name' => 'Подробнее',
                            'link' => route('portal.own_book.application')
                        ],
                        [
                            'name' => 'Изданные',
                            'link' => route('portal.own_books.released')
                        ],
                    ]
                ],
                [
                    'name' => 'Продвижение',
                    'url_part' => 'ext-promotion',
                    'route' => route('portal.ext_promotion')
                ],
                [
                    'name' => 'Еще',
                    'url_part' => 'about',
                    'routes' => [
                        [
                            'name' => 'О нас',
                            'link' => route('portal.about')
                        ],
                        [
                            'name' => 'Отзывы',
                            'link' => route('portal.index') . '#reviews'
                        ],
                    ]
                ]
            ];
        }
        return view('components.header-portal', [
            'links' => $links
        ]);
    }
}
