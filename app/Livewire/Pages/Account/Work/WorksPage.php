<?php

namespace App\Livewire\Pages\Account\Work;

use App\Models\Work\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class WorksPage extends Component
{
    use WithPagination;

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
            $work = Work::where('id', $id)->first();
            if ($work->getFirstMediaUrl('cover') ?? null) {
                $work->clearMediaCollection('cover');
            }
            $work->delete();
            $this->dispatch('toast', type: 'success', text: 'Произведение удалено');
        });
    }
}
