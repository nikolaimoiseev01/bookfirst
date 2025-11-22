<?php

namespace App\Http\Livewire\Account\CollectionParticipation\PartPageBlocks;

use App\Models\Collection;
use App\Models\collection_winner;
use App\Models\Participation;
use App\Models\vote;
use App\Service\PartPageBlockStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VoteBlock extends Component
{
    public $vote_to;
    public $voted_to;
    public $votes_for_me;
    public $collection;
    public $participants;
    public $winners;


    public $participation;
    public $status_icon;
    public $page_style;
    public $page_title;
    public $color;

    public function render()
    {
        return view('livewire.account.collection-participation.part-page-blocks.vote-block');

    }

    public function make_blocK_status() {

        $col_status_id = $this->collection['col_status_id'];


        if (($col_status_id === 1)  // Еще не время сборника,
            || ($col_status_id >= 2 && $this->participation['pat_status_id'] === 2) // или клиент не оплатил
            || ($this->participation->collection['col_status_id'] >= 2 && !($this->participation['paid_at'] ?? null))) { // Или неоплаченная заявка
            $this->color = 'grey';
            $this->page_title = 'Голосование за лучшего автора';
        } elseif ($col_status_id === 2 && !$this->voted_to && count($this->winners) === 0) {
            $this->color = 'yellow';
            $this->page_title = 'Голосование за лучшего автора';
        } elseif ($this->voted_to && count($this->winners) === 0) {
            $this->color = 'green';
            $this->page_title = 'Голосование за лучшего автора';
        }
        elseif ($col_status_id >= 3 || $this->voted_to || count($this->winners) > 0) { // Успешно оплатил
            $this->color = 'green';
            $this->page_title = 'Голосование завершено';
        };

        $found_status = (new PartPageBlockStatus())->get_status('.vote_block_wrap', $this->color);

        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];
    }

    public function mount($participation)
    {
        $this->collection = $participation->collection;

        $this->participants = Participation::where('collection_id', $this->collection['id'])->where('pat_status_id', 3)->where('user_id', '<>', Auth::user()->id)->get();
        $this->voted_to = Participation::where('collection_id', $this->collection['id'])
            ->where('user_id', vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->collection['id'])->value('user_id_to'))
            ->first();
        $this->winners = collection_winner::where('collection_id', $this->collection['id'])->orderby('place')->get();
        $this->votes_for_me = vote::where('collection_id', $this->collection['id'])->where('user_id_to', Auth::user()->id)->count();



        $this->make_blocK_status();
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
            $new_vote->collection_id = $this->participation['collection_id'];
            $new_vote->save();

            $this->voted_to = Participation::where('collection_id', $this->participation['collection_id'])
                ->where('user_id', vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->participation['collection_id'])->value('user_id_to'))
                ->first();


            session()->flash('success', 'success');
            session()->flash('alert_title','Успешно!');
            session()->flash('alert_text','Ваш голос учтен, спасибо!');

            return redirect()->to(url()->previous());


        }


    }

    public function delete_vote()
    {
        vote::where('user_id_from', Auth::user()->id)->where('collection_id', $this->participation['collection_id'])->delete();

        session()->flash('success', 'success');
        session()->flash('alert_title','Ваш голос удален!');
        session()->flash('alert_text','Теперь Вы можете проголосовать заново.');

        return redirect()->to(url()->previous());
    }
}
