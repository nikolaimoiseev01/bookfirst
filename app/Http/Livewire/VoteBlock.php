<?php

namespace App\Http\Livewire;

use App\Models\Participation;
use App\Models\vote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VoteBlock extends Component
{
    public $vote_to;
    public $voted_to;
    public $collection_id;
    public $participants;

    public function render()
    {
        return view('livewire.vote-block', [
            'participants' => $this->participants,
            'voted_to' => $this->voted_to,
        ]);

    }

    public function mount($collection_id)
    {
        $this->collection_id = $collection_id;
        $this->participants = Participation::where('collection_id', $this->collection_id)->get();
        $this->voted_to = Participation::where('collection_id', $this->collection_id)
            ->where('user_id', vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->collection_id)->value('user_id_to'))
            ->first();
    }


    public function make_vote()
    {
        $errors_array = [];

        if (!$this->vote_to) {
            array_push($errors_array, 'Выберите одного автора.');
        }


        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //


        if (empty($errors_array)) {
            $new_vote = new vote();
            $new_vote->user_id_from = Auth::user()->id;
            $new_vote->user_id_to = $this->vote_to;
            $new_vote->collection_id = $this->collection_id;
            $new_vote->save();

            $this->voted_to = Participation::where('collection_id', $this->collection_id)
                ->where('user_id', vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->collection_id)->value('user_id_to'))
                ->first();


            session()->flash('success', 'success');

            session()->flash('alert_title','Ваш голос учтен, спасибо!');

            return redirect()->to(url()->previous());


        }


    }

    public function delete_vote()
    {
        vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->collection_id)->delete();

        session()->flash('success', 'success');
        session()->flash('alert_title','Ваш голос удален!');
        session()->flash('alert_text','Теперь Вы можете проголосовать заново.');

        return redirect()->to(url()->previous());
    }
}
