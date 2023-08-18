<?php

namespace App\Http\Livewire\Portal;

use App\Models\Collection;
use Livewire\Component;

class OldCollections extends Component
{
    public $search_input;
    public $collections;
    public $show_clear;
    public $take_num = 9;

    public $total_cnt;
    public $loaded_cnt;

    protected $queryString = ['search_input'];

    public function render()
    {
        $this->collections = Collection::where('title', 'like', '%' . $this->search_input . '%')->where('col_status_id', 9)->orderBy('id','desc')->get();
        $this->total_cnt = count($this->collections);
        $this->collections = $this->collections->take($this->take_num);
        $this->loaded_cnt = count($this->collections);
        return view('livewire.portal.old-collections');
    }


    public function search() {
        $this->take_num = 9;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function clear_search() {
        $this->take_num = 9;
        $this->search_input = null;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function load_more() {
        $this->take_num += 9;
        $this->dispatchBrowserEvent('trigger_all_js');
    }
}
