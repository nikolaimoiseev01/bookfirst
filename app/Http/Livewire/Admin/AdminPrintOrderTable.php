<?php

namespace App\Http\Livewire\Admin;

use App\Models\Participation;
use App\Models\Printorder;
use Livewire\Component;

class AdminPrintOrderTable extends Component
{
    Public $track_number = [];
    Public $send_price = [];
    Public $participations;
    Public $print_order_id = 99999;
    Public $collection_id;
    Public $show_input = 0;
    Public $show_input_send = 0;


    protected $listeners = [
        'save_track_number',
        'save_send_price'
    ];

    public function render()
    {
        return view('livewire.admin.admin-print-order-table', [
            'participations' => $this->participations,
        ]);
    }

    public function mount($collection_id)
    {
        $this->participations = Participation::where('collection_id', $collection_id)->where('pat_status_id', 3)->orderBy('surname')->get();

        $this->collection_id = $collection_id;
        foreach (Printorder::where('collection_id', $this->collection_id)->get()->toArray() as $print_order)
        {
            $this->track_number[$print_order['id']] = $print_order['track_number'];
        }

    }

//    public function  show_1() {
//        $this->show_input = 1;
//    }
//
//    public function  show_0() {
//        $this->show_input = 0;
//    }


    public function save_track_number($print_order_id)
    {

        if($this->track_number[$print_order_id] === '' || $this->track_number[$print_order_id] === null)
        {
           $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так',
                'text' => 'Ни одно поле не должно быть пустым!',
            ]);}
        else
        {
            // ---- Редактируем Заказ печатных! ---- //
            Printorder::where('id', $print_order_id)->update([
                'track_number' => $this->track_number[$print_order_id],
            ]);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Трек номер успешно добавлен!',
                'text' => '']);
            $this->participations = Participation::where('collection_id', $this->collection_id)->where('pat_status_id', 3)->orderBy('surname')->get();
// ----------------------------------------------------------- //

            $this->show_input = 0;
        }
    }


    public function save_send_price($print_order_id)
    {

        if($this->send_price[$print_order_id] === '' || $this->send_price[$print_order_id] === null)
        {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так',
                'text' => 'Ни одно поле не должно быть пустым!',
            ]);}
        else
        {
            // ---- Редактируем Заказ печатных! ---- //
            Printorder::where('id', $print_order_id)->update([
                'send_price' => $this->send_price[$print_order_id],
            ]);
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Цена пересылки успешно добавлена!',
                'text' => '']);
            $this->participations = Participation::where('collection_id', $this->collection_id)->where('pat_status_id', 3)->orderBy('surname')->get();
// ----------------------------------------------------------- //

            $this->show_input_send = 0;
        }
    }
}
