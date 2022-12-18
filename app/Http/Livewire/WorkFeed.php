<?php

namespace App\Http\Livewire;

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
    public $works;
    public $works_amt = 10;
    public $show_load_more;
    public $all_works;
    public $user_page_flag;
    public $work_topic = 'work_topic_0';
    public $work_sort_by = 'created_at';
    public $authors_filter = 'all_authors';
    public $user_subed_to_ids;
    public $works_num;
    public $all_works_num;

    protected $listeners = ['filter_topic', 'make_sorting', 'load_more', 'author_filter'];

    public function render()
    {


        $work_topics = work_topic::where('id', '<>', 999)->orderBy('name')->get();

        if (count($this->works) < $this->works_amt) {
            $this->show_load_more = false;
        } else {
            $this->show_load_more = true;
        };
        return view('livewire.work-feed', [
            'works' => $this->works,
            'show_load_more' => $this->show_load_more,
            'user_page_flag' => $this->user_page_flag,
            'work_topics' => $work_topics,
            'works_num' => $this->works_num,
        ]);

    }


    public function mount($works, $user_page_flag)
    {
        $this->user_page_flag = $user_page_flag;

        $this->all_works = $works;
        $this->works_num = $this->all_works->sortByDesc($this->work_sort_by)->count();
        $this->all_works_num = $this->works_num;
        $this->works = $this->all_works->sortByDesc($this->work_sort_by)->take($this->works_amt);
        if (Auth::user()) {
            $this->user_subed_to_ids = user_subscription::where('user_id', Auth::user()->id)->pluck('subscribed_to_user_Id')->toArray();
        } else {
            $this->user_subed_to_ids = null;
        }
    }

    public function load_more()
    {
        $this->works_amt = $this->works_amt + 10;
        $work_topic_id = substr($this->work_topic, 11, 100);

        if ($this->work_topic === 'work_topic_0') { // Если все темы выбраны
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->take($this->works_amt);
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt);
            }

        } else {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->take($this->works_amt);
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt);
            }
        }
    }

    public function filter_topic()
    {
        $this->works_amt = 10;

        // Меняем тему
        $work_topic_id = substr($this->work_topic, 11, 100);
        if ($this->work_topic === 'work_topic_0') {// Если все темы выбраны
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->take($this->works_amt); // Берем только их нужно количество
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt); // Берем только их нужно количество
            }
        } else {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->take($this->works_amt); // Берем только их нужно количество
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt); // Берем только их нужно количество
            }
        }

    }

    public function make_sorting()
    {
        $this->works_amt = 10;

        $work_topic_id = substr($this->work_topic, 11, 100);

        if ($this->work_topic === 'work_topic_0') {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->take($this->works_amt);
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt);
            }

        } else {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->take($this->works_amt);
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt);
            }
        }

    }


    public function author_filter()
    {
        $this->works_amt = 10;

        $work_topic_id = substr($this->work_topic, 11, 100);
        if ($this->work_topic === 'work_topic_0') {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->count();  // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->take($this->works_amt); // Берем только их нужно количество
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt); // Берем только их нужно количество
            }

        } else {
            if ($this->authors_filter === 'all_authors') { // Если все авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->take($this->works_amt); // Берем только их нужно количество
            } else if ($this->authors_filter === 'sub_only_authors') { // Если только избранные авторы выбраны
                $this->works_num = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->count(); // Считаем кол-во все работ
                $this->works = $this->all_works->loadcount('work_like')->sortByDesc($this->work_sort_by)->where('work_topic_id', $work_topic_id)->wherein('user_id', $this->user_subed_to_ids)->take($this->works_amt); // Берем только их нужно количество
            }
        }

    }
}
