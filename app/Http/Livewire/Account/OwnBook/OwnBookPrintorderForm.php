<?php

namespace App\Http\Livewire\Account\OwnBook;

use App\Models\own_book;
use App\Models\Printorder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OwnBookPrintorderForm extends Component
{
    public $form_type;
    public $own_book;

    public $pages;
    public $color_pages;

    public $cover_type;
    public $cover_color;

    public $books_needed;
    public $send_to_name;
    public $send_to_address;
    public $send_to_tel;
    public $print_price;


    protected $listeners = [
        'create_printorder',
        'edit_printorder',
    ];


    public function render()
    {
        return view('livewire.account.own-book.own-book-printorder-form', [
            'form_type' => $this->form_type,
        ]);
    }

    public function mount($own_book, $form_type)
    {
        $this->own_book = $own_book;
        $this->form_type = $form_type;

        $this->send_to_name = $this->own_book->printorder->send_to_name ?? '';
        $this->send_to_address = $this->own_book->printorder->send_to_address ?? '';
        $this->send_to_tel = $this->own_book->printorder->send_to_tel ?? '';
    }

    public function create_printorder()
    {
        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($this->print_price > 0 & ($this->send_to_name === null || $this->send_to_name === "")) {
            array_push($errors_array, 'Введите имя получателя!');
        }
        if ($this->print_price > 0 & ($this->send_to_tel === null || $this->send_to_tel === "")) {
            array_push($errors_array, 'Введите телефон получателя!');
        }

        if ($this->print_price > 0 & ($this->send_to_address === null || $this->send_to_address === "")) {
            array_push($errors_array, 'Введите адрес получателя!');
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


            own_book::where('id', $this->own_book->id)->update(array(
                'print_price' => $this->print_price,
                'total_price' => $this->own_book->total_price + $this->print_price,
            ));



            if ($this->own_book->own_book_status_id == 9) {
                own_book::where('id', $this->own_book->id)->update([
                    'own_book_status_id' => 4,
                ]);
            }


            // ---- Создаем новый Заказ печатных! ---- //
            $new_PrintOrder = new PrintOrder();
            $new_PrintOrder->own_book_id = $this->own_book->id;
            $new_PrintOrder->user_id = Auth::user()->id;
            $new_PrintOrder->books_needed = $this->books_needed;
            $new_PrintOrder->cover_type = $this->cover_type;
            $new_PrintOrder->cover_color = $this->cover_color;
            $new_PrintOrder->color_pages = $this->color_pages;
            $new_PrintOrder->send_to_name = $this->send_to_name;
            $new_PrintOrder->send_to_tel = $this->send_to_tel;
            $new_PrintOrder->send_to_address = $this->send_to_address;
            $new_PrintOrder->save();


            if ($this->own_book->own_book_status_id > 3) {
                $alert_text = 'Заказ печатных экземпляров успешно создан! Теперь его можно оплатить в блоке "Печать книги".';
            }
            else {
                $alert_text = 'Заказ печатных экземпляров успешно создан! Процесс печати будет доступен только после утверждения макетов.';
            }

            session()->flash('success', 'success');
            session()->flash('alert_text', $alert_text);
            return redirect()->to(url()->previous());
        }
    }


    public function edit_printorder()
    {

        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($this->print_price > 0 & ($this->send_to_name === null || $this->send_to_name === "")) {
            array_push($errors_array, 'Введите имя получателя!');
        }
        if ($this->print_price > 0 & ($this->send_to_tel === null || $this->send_to_tel === "")) {
            array_push($errors_array, 'Введите телефон получателя!');
        }

        if ($this->print_price > 0 & ($this->send_to_address === null || $this->send_to_address === "")) {
            array_push($errors_array, 'Введите адрес получателя!');
        }


        own_book::where('id', $this->own_book->id)->update(array(
            'print_price' => $this->print_price,
        ));

        own_book::where('id', $this->own_book->id)->update([
            'own_book_status_id' => 4,
        ]);


        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //


        if (empty($errors_array)) {

            own_book::where('id', $this->own_book->id)->update(array(
                'print_price' => $this->print_price,
            ));


            PrintOrder::where('own_book_id', $this->own_book->id)->update([
                'books_needed' => $this->books_needed,
                'send_to_name' => $this->send_to_name,
                'send_to_tel' => $this->send_to_tel,
                'send_to_address' => $this->send_to_address,
                'cover_type' => $this->cover_type,
                'cover_color' => $this->cover_color,
                'color_pages' => $this->color_pages,
            ]);

            own_book::where('id', $this->own_book->id)->update(array(
                'print_price' => $this->print_price,
                'total_price' => $this->own_book->total_price + $this->print_price,
            ));


            session()->flash('success', 'success');
            session()->flash('alert_text','Статус изменен!');
            session()->flash('alert_text', 'Параметры печати были успешно изменены!');
            return redirect()->to(url()->previous());
        }
    }
}
