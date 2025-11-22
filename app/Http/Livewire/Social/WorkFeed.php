<?php

namespace App\Http\Livewire\Social;

use App\Models\user_subscription;
use App\Models\Work;
use App\Models\work_topic;
use App\Models\work_type;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function Livewire\str;

class WorkFeed extends Component
{
    public $user_id;
    public $works_orig;
    public $works;
    public $works_amt = 10;
    public $user_page_flag;

    public $user_subed_to_ids;

    public $work_topics;
    public $work_topic = "0";

    public $sort_options;
    public $sort_option = "1";

    public $author_filters;
    public $author_filter;


    protected $listeners = ['filter_topic', 'make_sorting', 'load_more', 'author_filter'];

    public function render()
    {

        if ($this->sort_option == "1") {
            $sort_by = 'created_at';
        } elseif ($this->sort_option == "2") {
            $sort_by = 'work_like_count';
        }

        // Фильтруем по поиску
        $this->works = $this->works_orig
            ->when($this->work_topic !== "0", function ($item) {
                return $item->where('work_topic_id', $this->work_topic);
            })
            ->when($this->author_filters === "2", function ($item) {
                return $item->whereIn('user_id', $this->user_subed_to_ids);
            })
            ->loadcount('work_like')
            ->sortByDesc($sort_by)
            ->take($this->works_amt);

//        dd($sort_by);
        $this->work_topics = work_topic::where('id', '<>', 999)->orderBy('name')->get();

        return view('livewire.social.work-feed');

    }


    public function mount($works, $user_page_flag)
    {
        $this->sort_options = collect([
            ['id' => 1, 'name' => 'По дате'],
            ['id' => 2, 'name' => 'По популярности']
        ]);

        $this->author_filters = collect([
            ['id' => 1, 'name' => 'Все авторы'],
            ['id' => 2, 'name' => 'Только избранные']
        ]);

        if(Auth::user()) {
            $this->user_subed_to_ids = user_subscription::where('user_id', Auth::user()->id)->pluck('subscribed_to_user_Id')->toArray() ?? null;
        }

        $this->user_page_flag = $user_page_flag;
        $this->works_orig = $works;

    }

    public function load_more()
    {
        $this->works_amt = $this->works_amt + 10;
    }

}
