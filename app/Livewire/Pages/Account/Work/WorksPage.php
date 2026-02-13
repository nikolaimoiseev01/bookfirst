<?php

namespace App\Livewire\Pages\Account\Work;

use App\Enums\CollectionStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Models\Collection\ParticipationWork;
use App\Models\Work\Work;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class WorksPage extends Component
{
    use WithPagination, WithCustomValidation;

    public $searchText = '';

    protected $listeners = ['deleteWork' => 'deleteWork'];

    public function render()
    {
        return view('livewire.pages.account.work.works-page', [
            'works' => Auth::user()->works()->where('title', 'like', '%' . $this->searchText . '%')->cursorPaginate(10)
        ])->layout('layouts.account');
    }

    public function mount()
    {
        Session::remove('cameFromAppUrl');
    }

    public function search()
    {

    }

    public function clearSearch()
    {
        $this->searchText = null;
    }

    public function deleteConfirm($id)
    {
        $participationWork = ParticipationWork::where('work_id', $id)->with('participation.collection')->first();
        if ($participationWork) {
            $this->dispatch('swal', type: 'error', title: 'Ошибка!', text: 'Нельзя удалить произведение, участвующеее в сборнике');
            return;
        }
        $this->dispatch('swal',
            title: 'Вы уверены?',
            text: 'Вы уверены, что хотите удалить это произведение?',
            confirmButtonText: 'Да, удалить',
            livewireMethod: ['deleteWork', $id]
        );
    }

    public function deleteWork($id)
    {
        DB::transaction(function () use ($id) {
            $participationWork = ParticipationWork::where('work_id', $id)->first();
            if (!$participationWork || $participationWork->participation['status'] == ParticipationStatusEnums::NOT_ACTUAL) {
                $work = Work::where('id', $id)->first();
                if ($work->getFirstMediaUrl('cover') ?? null) {
                    $work->clearMediaCollection('cover');
                }
                $work->likes()->delete();
                $work->comments()->delete();
                $work->delete();
                $this->dispatch('toast', type: 'success', text: 'Произведение удалено');
            } else {
                $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Произведение участвует в сборнике. Его нельзя сейчас удалить.');
            }
        });
    }
}
