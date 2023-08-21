<?php

namespace App\Http\Livewire\Account;

use App\Models\own_books_works;
use App\Models\Participation_work;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyWorks extends Component
{

    public $works;
    public $works_orig;
    public $search_input;
    public $take_num = 10;

    public $total_cnt;
    public $loaded_cnt;


    protected $listeners = ['delete_work'];
    protected $queryString = ['search_input'];

    public function render()
    {
        // Все работы автора
        $this->works_orig = Work::where('user_id', Auth::user()->id)
            ->withCount('work_like')
            ->withCount('work_comment')
            ->orderBy('created_at', 'desc')
            ->get();

        // Фильтруем по поиску
        $this->works = $this->works_orig->when($this->search_input, function ($item) {
            return $item->filter(function ($q) {
                $search = mb_strtolower($this->search_input);
                return preg_match("/$search/", mb_strtolower($q['title'])) || preg_match("/$search/", mb_strtolower($q['text']));
            });
        })->take($this->take_num);

        return view('livewire.account.my-works');
    }

    public function mount(Request $request) {
        $currenturl = url()->full();
        $back_after_work_adding = [
            'button_text' => 'Загрузить в систему',
            'url' => $currenturl
        ];
        $request->session()->put('back_after_work_adding', $back_after_work_adding);
    }


    public function delete_confirm($work_id)
    {

        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Вы уверены, что хотите удалить произведение?',
            'onconfirm' => 'delete_work',
            'id' => $work_id
        ]);
    }

    public function delete_work($work_id)
    {
        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];


        $work_in_collections = Participation_work::where('work_id', $work_id)->get() ?? 0;
        $work_in_own_book = own_books_works::where('work_id', $work_id)->get() ?? 0;

        if (count($work_in_collections) > 0) {
            array_push($errors_array, 'Это произведение используется в сборнике! Его нельзя удалить сейчас.');
        }

        if (count($work_in_own_book) > 0) {
            array_push($errors_array, 'Это произведение используется в собственной книге! Его нельзя удалить сейчас.');
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

            $work_name = Work::where('id', $work_id)->value('title');
            \App\Models\Work::where('id', $work_id)->delete();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Успешно!',
                'text' => 'Произведение "' . $work_name . '" удалено.',
            ]);
        }

    }

    public function search()
    {
        $this->search_input = $this->search_input;
    }

    public function clear_search()
    {
        $this->search_input = null;
    }

    public function load_more() {
        $this->take_num += 10;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

}
