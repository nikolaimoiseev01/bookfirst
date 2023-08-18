<?php

namespace App\View\Components\Social;

use App\Models\award;
use App\Models\user_subscription;
use App\Models\Work;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserPageHeader extends Component
{
    public $user;
    public $awards;
    public $user_stat_readers;
    public $user_stat_reads;
    public $works;


    /**
     * Create a new component instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->awards = award::where('user_id', $this->user['id'])->orderBy('award_type_id')->get();
        $this->user_stat_readers = user_subscription::where('subscribed_to_user_id', $this->user['id'])->get();
        $this->user_stat_reads = user_subscription::where('user_id', $this->user['id'])->get();
        $this->works = Work::where('user_id', $this->user['id'])->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.social.user-page-header');
    }
}
