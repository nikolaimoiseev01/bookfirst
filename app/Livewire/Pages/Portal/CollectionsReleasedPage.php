<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use Livewire\Component;

class CollectionsReleasedPage extends Component
{
    public $collections;

    public $take = 10;
    public $moreCnt = 10;
    public $searchText = null;
    public $totalCnt;


    public function render()
    {
        return view('livewire.pages.portal.collections-released-page');
    }

    public function resetCollections()
    {
        $this->totalCnt = Collection::query()
            ->where('title', 'like', '%' . $this->searchText . '%')
            ->where('status', '<>', CollectionStatusEnums::APPS_IN_PROGRESS)
            ->count();
        $this->take = min($this->totalCnt, $this->take);
        $this->collections = Collection::query()
            ->where('status', '<>', CollectionStatusEnums::APPS_IN_PROGRESS)
            ->where('title', 'like', "%{$this->searchText}%")
            ->orderBy('created_at', 'desc')
            ->take($this->take)
            ->with(['media'])
            ->get();
    }

    public function mount()
    {
        $this->resetCollections();
    }

    public function loadMore()
    {
        $this->take += $this->moreCnt;
        $this->resetCollections();
    }

    public function search()
    {
        $this->take = 10;
        $this->resetCollections();
    }

    public function clearSearch()
    {
        $this->searchText = null;
        $this->take = 10;
        $this->resetCollections();
    }
}
