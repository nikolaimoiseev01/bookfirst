<?php

namespace App\View\Components;

use App\Models\User;
use App\Models\Work;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class HeaderSocial extends Component
{
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct(Request $request)
    {
        if ($request::route() ?? null) {
            $route_name = $request::route()->getName() ?? null;
        } else {
            $route_name = null;
        }

        if ($route_name == 'social.user_page') {
            $user_id = Route::current()->parameter('user_id');
            $this->user = User::where('id', $user_id)->first();
        } elseif ($route_name == 'social.work_page') {
            $work_id = Route::current()->parameter('work_id');
            $user_id = Work::where('id', $work_id)->get()->value('user_id');
            $this->user = User::where('id', $user_id)->first();
        } else {
            $this->user = null;
        }


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header-social');
    }
}
