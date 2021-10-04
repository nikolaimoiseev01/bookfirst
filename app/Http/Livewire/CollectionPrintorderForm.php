<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Participation;
use App\Models\Transaction;
use App\Service\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CollectionPrintorderForm extends Component
{
    Public $participation;
    Public $collection;

    Public $pay_extra;

    public $print_needed;
    public $send_to_name;
    public $send_to_tel;
    public $send_to_address;
    public $errors = [];


    public $print_price = 300;

    protected $listeners = [
        'save_printorder'
    ];

    public function render()
    {
        return view('livewire.collection-printorder-form', [
            'form_type' => $this->form_type,
            'participation' => $this->participation,
        ]);
    }

    public function mount($participation, $form_type)
    {
        $this->participation = $participation;
        $this->form_type = $form_type;
        $this->collection = Collection::where('id', $participation['collection_id'])->first();
//
        $this->send_to_name = $this->participation->printorder->send_to_name ?? $participation['name'] . " " . $participation['surname'];
        $this->send_to_address = $this->participation->printorder->send_to_address ?? '';
        $this->send_to_tel = $this->participation->printorder->send_to_tel ?? '';
    }

    public function save_printorder(PaymentService $service)
    {
        $errors_array = [];


        if (!$this->send_to_address || !$this->send_to_name || !$this->send_to_tel) {
            array_push($errors_array, 'Не вся информация о получаетеле заполнена!');
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
            $description = "Бронирование дополнительных печатных экземпляров (" . "шт) сборника '" . $this->collection['title'] . "'";
            $url_redirect = url()->previous();
            if ($this->form_type === 'create') {
                $amount = round(intval($this->print_price));
            }
            elseif ($this->form_type === 'edit') {
                $amount = round(intval($this->pay_extra));
            }



            // Записываем данные транзакции
            $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->amount = $amount;
            $transaction->description = $description;
            $transaction->save();

            if ($transaction) {
                $link = $service->createPayment($amount, $description, $url_redirect, [
                    'transaction_id' => $transaction->id,
                    'user_id' => Auth::user()->id,
                    'participation_id' => $this->participation['id'],
                    'col_adit_print_needed' => $this->print_needed,
                    'col_adit_print_type' => $this->form_type,
                    'col_adit_send_to_name' => $this->send_to_name,
                    'col_adit_send_to_tel' => $this->send_to_tel,
                    'col_adit_send_to_address' => $this->send_to_address,
                    'url_redirect' => $url_redirect
                ]);

                if (isset($link)) {
                    return redirect()->away($link);
                }
            }
        }
        }

}
