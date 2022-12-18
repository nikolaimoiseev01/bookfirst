<?php

namespace App\Http\Livewire;

use App\Models\own_books_works;
use App\Models\Participation_work;
use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MyWorks extends Component
{

    protected $listeners = ['delete'];
    private $works;
    private $work_input_search = "no_search";

    public function render()
    {
//        dd($this->page_type);
        if ($this->page_type == 'no_search') {
            $this->works = Work::where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $this->works = Work::where('user_id', Auth::user()->id)
                ->where(function ($q) {
                    $q->where('text', 'like', '%' . $this->work_input_search . '%')
                        ->orWhere('title', 'like', '%' . $this->work_input_search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('livewire.my-works', [
            'works' => $this->works,
            'work_input_search' => $this->work_input_search,
        ]);
    }

    public function mount($page_type, $work_input_text)
    {
        $this->page_type = $page_type;
        $this->work_input_search = $work_input_text;
    }


    public function delete_confirm($work_id)
    {

        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'title' => 'Вы уверены, что хотите удалить произведение?',
            'id' => $work_id
        ]);
    }

    public function delete($work_id)
    {
        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];


$work_in_collections=Participation_work::where('work_id', $work_id)->get() ?? 0;
$work_in_own_book=own_books_works::where('work_id', $work_id)->get() ?? 0;

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
            session()->flash('success', 'success');
            session()->flash('alert_title', 'Успешно!');
            session()->flash('alert_text', 'Произведение "' . $work_name . '" было полностью удалено из системы!');
            return redirect('/myaccount/work');
        }

    }


}
