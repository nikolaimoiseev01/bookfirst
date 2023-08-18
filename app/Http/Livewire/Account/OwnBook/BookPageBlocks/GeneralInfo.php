<?php

namespace App\Http\Livewire\Account\OwnBook\BookPageBlocks;

use App\Models\own_book;
use App\Models\Printorder;
use App\Service\OwnBookOutputsService;
use App\Service\PartPageBlockStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GeneralInfo extends Component
{
    public $own_book;
    public $printorder;
    public $status_color;
    public $status_color_shadow;
    public $status_icon;
    public $page_style;
    public $error_fields = [];

    public $need_print;
    public $prints = 10;
    public $cover_type = 'soft';
    public $inside_color = '0';
    public $pages_color = '0';
    public $print_text;

    public $send_to_name;
    public $send_to_country;
    public $send_to_city;
    public $send_to_index;
    public $send_to_tel;
    public $send_to_address;


    public function render(OwnBookOutputsService $calc_outs)
    {
        if ($this->inside_color == "0") {
            $this->pages_color = 0;
        }

        // Узнаем цены участия
        $result = $calc_outs->calculate(
            $this->own_book['pages'],
            $this->pages_color,
            0,
            0,
            0,
            true,
            $this->prints,
            $this->cover_type,
            1
        );

        $this->price_print = $result['price_print'];
        $uploaded_text = ($this->own_book['inside_type'] == 'by_file') ? 'файлов: ' . count($this->own_book->own_book_files) : 'работ: ' . count($this->own_book->own_books_works);
        $design_text = ($this->own_book['need_design']  == 1 ? 'необходим дизайн' : '');
        $check_text = ($this->own_book['need_check'] == 1 ? 'необходима проверка' : '');
        $inside_text = ($this->own_book['need_design']  == 1 || $this->own_book['need_check']  == 1) ? $design_text . (($this->own_book['need_design'] && $this->own_book['need_check']) ? ', ' : '') . $check_text : 'полностью готов к печати';
        $cover_text = ($this->own_book['cover_price'] > 0) ? 'необходимо создание' : 'полностью готова';
        $promo_text = $this->own_book['promo_price'] > 0 ? 'нужен ' . $this->own_book['promo_type'] . ' вариант' : 'не нужно.';


        $this->app_text = "<div style='display: flex; flex-direction: column; gap: 10px;'>
                <p><b>Книга: </b>{$this->own_book['author']}: '{$this->own_book['title']}'</p>
                <p><b>Загружено {$uploaded_text}. </b>(страниц: {$this->own_book['pages']})</p>
                <p><b>Внутренний блок:</b> {$inside_text}</p>
                <p><b>Обложка:</b> {$cover_text}</p>
                <p><b>Продвижение:</b> {$promo_text}</p>
                </div>";

        $this->print_text = ($this->own_book['print_price'] > 0) ?
            $this->prints . ' экземпляров. '
            . 'Обложка: '  . ($this->cover_type == 'soft' ? 'мягкая' : 'твердая')
            . '. Внутренний блок: ' . ($this->inside_color == '0' ? 'ч/б' : 'цветной (' . $this->pages_color . ' цветных страниц).')
            : 'не нужна.';
//        dd($this->print_text);

        return view('livewire.account.own-book.book-page-blocks.general-info');
    }

    public function mount(PartPageBlockStatus $status)
    {

        if (1 === 1) {
            $color = 'green';
        }

        $found_status = $status->get_status('.general_info_wrap', $color);

        $this->status_color = $found_status['status_color'];
        $this->status_color_shadow = $found_status['status_color_shadow'];
        $this->status_icon = $found_status['status_icon'];
        $this->page_style = $found_status['page_style'];;

        $this->printorder = $this->own_book->printorder;

        if ($this->printorder) {
            $this->prints = $this->printorder['books_needed'];
            $this->pages_color = $this->printorder['color_pages'];
            $this->cover_type = $this->printorder['cover_type'];
            $this->inside_color = $this->printorder['inside_color'];
            $this->send_to_name = $this->printorder['send_to_name'];
            $this->send_to_tel = $this->printorder['send_to_tel'];
            $this->send_to_country = $this->printorder['send_to_country'];
            $this->send_to_city = $this->printorder['send_to_city'];
            $this->send_to_address = $this->printorder['send_to_address'];
            $this->send_to_index = $this->printorder['send_to_index'];
        }
    }

    public function check_print()
    {

        // --------- Ищем ошибки в заполнении  --------- //
        $this->error_texts = [];
        $this->error_fields = [];

        if ($this->send_to_name === null || $this->send_to_name === "") {
            array_push($this->error_texts, 'Введите имя получателя!');
            array_push($this->error_fields, 'send_to_name');
        }
        if ($this->send_to_tel === null || $this->send_to_tel === "") {
            array_push($this->error_texts, 'Введите телефон получателя!');
            array_push($this->error_fields, 'send_to_tel');
        }
        if ($this->send_to_country === null || $this->send_to_country === "") {
            array_push($this->error_texts, 'Введите страну получателя!');
            array_push($this->error_fields, 'send_to_country');
        }
        if ($this->send_to_city === null || $this->send_to_city === "") {
            array_push($this->error_texts, 'Введите город получателя!');
            array_push($this->error_fields, 'send_to_city');
        }
        if ($this->send_to_address === null || $this->send_to_address === "") {
            array_push($this->error_texts, 'Введите адрес получателя!');
            array_push($this->error_fields, 'send_to_address');
        }
        if ($this->send_to_index === null || $this->send_to_index === "") {
            array_push($this->error_texts, 'Введите индекс получателя!');
            array_push($this->error_fields, 'send_to_index');
        }


        if (!empty($this->error_texts)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $this->error_texts),
            ]);
            return false;
        } else {
            return true;
        }
    }

    public function edit_printorder()
    {

        if ($this->check_print()) {

            if ($this->printorder) { // Информация о заказе
                $this->printorder->update([ // Обновляем, если уже был
                    'books_needed' => $this->prints,
                    'cover_type' => $this->cover_type,
                    'inside_color' => $this->inside_color,
                    'color_pages' => intval($this->pages_color),
                    'send_to_name' => $this->send_to_name,
                    'send_to_tel' => $this->send_to_tel,
                    'send_to_country' => $this->send_to_country,
                    'send_to_city' => $this->send_to_city,
                    'send_to_address' => $this->send_to_address,
                    'send_to_index' => $this->send_to_index
                ]);
            } else { // Создаем, если еще не было заказа
                $new_PrintOrder = new PrintOrder();
                $new_PrintOrder->own_book_id = $this->own_book['id'];
                $new_PrintOrder->user_id = Auth::user()->id;
                $new_PrintOrder->books_needed = $this->prints;
                $new_PrintOrder->cover_type = $this->cover_type;
                $new_PrintOrder->inside_color = $this->inside_color;
                $new_PrintOrder->color_pages = $this->pages_color;

                $new_PrintOrder->send_to_name = $this->send_to_name;
                $new_PrintOrder->send_to_tel = $this->send_to_tel;
                $new_PrintOrder->send_to_country = $this->send_to_country;
                $new_PrintOrder->send_to_city = $this->send_to_city;
                $new_PrintOrder->send_to_address = $this->send_to_address;
                $new_PrintOrder->send_to_index = $this->send_to_index;
                $new_PrintOrder->save();
            }

            // Сохраняем информацию о ценах
            $total_price = $this->own_book['total_price'] - $this->own_book['print_price'] + $this->price_print;
            $this->own_book->update([
                'print_price' => $this->price_print,
                'total_price' => $total_price,
                'color_pages' => $this->pages_color
            ]);

            $this->need_print = false;
            $this->emit('$refresh');
            $this->emit('refreshOwnBookPayBlock');
            $this->emit('refreshOwnBookTrackBlock');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Успешно!',
                'text' => 'Заказ печатных экземпляр сохранен.',
            ]);
        }
    }
}
