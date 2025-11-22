<?php

namespace App\Http\Livewire\Components;

use App\Models\Work;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WorkChoose extends Component
{
    public $works_to_choose = [];
    public $works_to_part = [];
    public $show_menu = false;

    protected $listeners = ['updateWorkOrder', 'work_remove'];


    public function render()
    {
        return view('livewire.components.work-choose');
    }


    public function mount($works_already_in)
    {
        if ($works_already_in) {
            $this->works_to_choose = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->whereNotIn('id', $works_already_in)->get()->toArray();
            $sort_order = implode(',', $works_already_in);
            $this->works_to_part = Work::where('user_id', Auth::user()->id)
                ->whereIn('id', $works_already_in)
                ->orderByRaw("FIELD(id,$sort_order)")
                ->get()
                ->toArray();
        } else {
            $this->works_to_choose = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->get()->toArray();
        }

    }

    public function work_add($id)
    {
        foreach ($this->works_to_choose as $key => $object) {
            if ($object['id'] === $id) {
                $found_work = $this->works_to_choose[$key];
                unset($this->works_to_choose[$key]);
                break; // Если нужно удалить только первый объект с таким ID
            }
        }
        array_push($this->works_to_part, $found_work);

        $this->show_menu = true;
    }

    public function work_remove($id)
    {

        $this->show_menu = true;

        foreach ($this->works_to_part as $key => $object) {
            if ($object['id'] === $id) {
                $found_work = $this->works_to_part [$key];
                unset($this->works_to_part [$key]);
                break; // Если нужно удалить только первый объект с таким ID
            }
        }

        array_push($this->works_to_choose, $found_work);


    }

    public function updateWorkOrder($order_list)
    {

        // Создаем временный массив для сортировки
        $temp_array = [];

        // Заполняем временный массив с ключами, равными значениям из $order_list
        foreach ($order_list as $key => $work_id) {
            foreach ($this->works_to_part as $work) {
                if ($work['id'] === intval($work_id)) {
                    $temp_array[$key] = $work;
                    break;
                }
            }
        }

        // Обновляем массив $works_to_part отсортированным значением
        $this->works_to_part = array_values($temp_array);

    }

    public function dehydrate()
    {
        $this->emit('syncWorks', $this->works_to_part);
    }
}
