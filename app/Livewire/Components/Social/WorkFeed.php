<?php

namespace App\Livewire\Components\Social;

use App\Models\Work\Work;
use App\Models\Work\WorkTopic;
use App\Models\Work\WorkType;
use Livewire\Component;

class WorkFeed extends Component
{
    public $take = 5;
    public $works;
    public $totalWorks;
    public $workTypeOptions;
    public $workType;
    public $workTopicOptions;
    public $workTopic;

    public $sortOptions;
    public $sortOptionsDict;
    public $sortOption = 'first_new';
    public $userId;

    public $layout = 'blocks';

    public function render()
    {
        $query = Work::query()
            ->when($this->workTopic, fn($q) => $q->where('works.work_topic_id', $this->workTopic))
            ->when($this->workType, fn($q) => $q->where('works.work_type_id', $this->workType))
            ->when($this->userId, fn($q) => $q->where('works.user_id', $this->userId));

        // считаем общее количество записей по фильтрам
        $this->totalWorks = $query->count();

        // применяем сортировку и берём только часть
        $this->works = $query
            ->orderBy(
                $this->sortOptionsDict[$this->sortOption]['value'],
                $this->sortOptionsDict[$this->sortOption]['dir']
            )
            ->with(['user', 'workTopic', 'workType'])
            ->withCount(['likes', 'comments'])
            ->take($this->take)
            ->get();
        return view('livewire.components.social.work-feed');
    }

    public function updated($property)
    {
        $properties = ['workTopic', 'workType', 'sortOption'];
        if (in_array($property, $properties, true)) {
            $this->take = 5;
        }
    }

    public function makeFilters()
    {
        $this->workTypeOptions = WorkType::all()
            ->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
            ->toArray();
        array_unshift($this->workTypeOptions, [
            'value' => null,
            'label' => 'Все типы',
        ]);
        $this->workTopicOptions = WorkTopic::all()
            ->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
            ->toArray();
        array_unshift($this->workTopicOptions, [
            'value' => null,
            'label' => 'Все темы',
        ]);
    }

    public function makeSort()
    {
        $this->sortOptions = [
            [
                'value' => 'first_new',
                'label' => 'Сначала новые',
            ],
            [
                'value' => 'first_old',
                'label' => 'Сначала старые',
            ],
            [
                'value' => 'popularity',
                'label' => 'По популярности',
            ],
        ];
        $this->sortOptionsDict = [
            'first_new' => [
                'value' => 'created_at',
                'dir' => 'desc'
            ],
            'first_old' => [
                'value' => 'created_at',
                'dir' => 'asc'
            ],
            'popularity' => [
                'value' => 'likes_count',
                'dir' => 'desc'
            ],
        ];
    }


    public function mount()
    {
        $this->makeFilters();
        $this->makeSort();
    }

    public function loadMore()
    {
        $this->take += 5;
    }

    public function changeLayout($layout)
    {
        $this->layout = $layout;
        $this->take = 5;
    }
}
