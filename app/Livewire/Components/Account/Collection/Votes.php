<?php

namespace App\Livewire\Components\Account\Collection;

use App\Models\Collection\CollectionVote;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Votes extends Component
{
    use WithCustomValidation;

    public $collection;
    public $participations;
    public $participationChosen;
    public $participationId;

    public $currentVote;
    public $authorChosen;
    public $userVotes;

    protected $listeners = ['deleteVote' => 'deleteVote'];

    public function render()
    {
        $this->currentVote = CollectionVote::query()->where('participation_id_from', $this->participationId)->first();
        if ($this->currentVote) {
            $this->authorChosen = $this->participations->where('id', $this->currentVote['participation_id_to'])->first()['author_name'];
        }
        $this->userVotes = CollectionVote::query()->where('participation_id_to', $this->participationId)->count() + 3;
        return view('livewire.components.account.collection.votes');
    }

    protected function rules(): array
    {
        return [
            'participationChosen' => 'required'
        ];
    }

    protected function messages(): array
    {
        return [
            'participationChosen.required' => 'Выберите автора!'
        ];
    }

    public function mount($collection, $participationId)
    {
        $this->participations = $collection->participations;
        $this->participationId = $participationId;
    }

    public function save()
    {
        if ($this->customValidate()) {
            DB::transaction(function () {
                $authorChosen = $this->participations->where('id', $this->participationChosen)->first()['author_name'];
                CollectionVote::create([
                    'participation_id_from' => $this->participationId,
                    'collection_id' => $this->collection['id'],
                    'participation_id_to' => $this->participationChosen,
                ]);
                $this->dispatch('updateParticipationPage');
                $this->dispatch('swal',
                    type: 'success',
                    text: "Ваш голос учтен за автора {$authorChosen}"
                );
            });
        }
    }

    public function deleteVote()
    {
        $this->currentVote->delete();
        $this->dispatch('updateParticipationPage');
        $this->dispatch('swal',
            type: 'success',
            text: "Ваш голос был отменен"
        );
    }

    public function confirmDeleteVote()
    {
        $this->dispatch('swal',
            title: 'Внимание!',
            text: 'Вы точно хотите отменить свой голос?',
            confirmButtonText: 'Да, все верно',
            livewireMethod: ['deleteVote']
        );
    }
}
