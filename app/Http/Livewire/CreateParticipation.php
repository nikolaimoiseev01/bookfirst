<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\promocode;
use App\Models\Work;
use App\Notifications\new_participation;
use App\Rules\SameParticipation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use function Livewire\str;

class CreateParticipation extends Component
{

    public $collection_id;
    public $name;
    public $surname;
    public $nickname;
    public $rows;
    public $pages;
    public $number_works = 0;
    public $works;

    public $print_needed;
    public $send_to_name;
    public $send_to_tel;
    public $send_to_address;
    public $errors = [];


    public $part_price;
    public $print_price;
    public $check_needed;
    public $total_price;

    public $promo_search_input;
    public $promo_discount;
    public $promocode;

    protected $listeners = ['storeParticipation'];

    public function render()
    {
        $user_works = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->get() ?? 0;
        $collection = Collection::orderBY('id')->find($this->collection_id);
        $works_to_go = [];
        return view('livewire.create-participation', [
            'collection' => $collection,
            'user_works' => $user_works,
            'works_to_go' => $works_to_go,
            'promo_discount' => $this->promo_discount,
        ]);

//        session(['previous-url' => request()->url()]);
    }

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->surname = Auth::user()->surname;
        $this->nickname = Auth::user()->nickname;
        $this->send_to_name = Auth::user()->surname . ' ' . Auth::user()->name;
    }

    public function check_promo()
    {
        $promocodes = promocode::all();
        foreach ($promocodes as $promocode) {
            if (str_contains(strtoupper($this->promo_search_input), strtoupper($promocode['promocode']))) {
                $this->promo_discount = $promocode['discount'];
                $this->promocode = strtoupper($promocode['promocode']);
            }
        }

        if ($this->promo_discount > 0) {
            $this->dispatchBrowserEvent('update_promo_discount', [
                'promo_discount' => $this->promo_discount,
                'promocode' => strtoupper($this->promo_search_input),
            ]);

        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Мы не смогли найти такой промокод в системе',
            ]);
        }
    }


    public function storeParticipation()
    {


        $errors_array = [];

        $is_same_part = Participation::where('user_id', Auth::user()->id)->Where('collection_id', $this->collection_id)->value('user_id');
        if ($is_same_part > 0) {
            array_push($errors_array, 'Вы уже участвуете в этом сборнике!');
        }
        if (!$this->pages > 0) {
            array_push($errors_array, 'Произведения не добавлены!');
        }

        if ($this->print_price > 0 && (!$this->send_to_address || !$this->send_to_name || !$this->send_to_tel)) {
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

            // ---- Создаем новый Заказ печатных! ---- //
            if ($this->print_price > 0) {
                $new_PrintOrder = new PrintOrder();
                $new_PrintOrder->collection_id = $this->collection_id;
                $new_PrintOrder->user_id = Auth::user()->id;
                $new_PrintOrder->books_needed = $this->print_needed;
                $new_PrintOrder->send_to_name = $this->send_to_name;
                $new_PrintOrder->send_to_tel = $this->send_to_tel;
                $new_PrintOrder->send_to_address = $this->send_to_address;
                $new_PrintOrder->save();
            }
            // ----------------------------------------------------------- //


//     ---- Создаем произведения в participation_works ---- //

            $new_participation = new Participation();
            $new_participation->user_id = Auth::user()->id;
            $new_participation->collection_id = $this->collection_id;
            $new_participation->name = $this->name;
            $new_participation->surname = $this->surname;
            $new_participation->nickname = $this->nickname;
            $new_participation->works_number = $this->number_works;
            $new_participation->rows = $this->rows;
            $new_participation->pages = $this->pages;
            $new_participation->pat_status_id = 1;
            $new_participation->promocode = $this->promocode;
            $new_participation->part_price = $this->part_price;
            $new_participation->print_price = $this->print_price;
            $new_participation->check_price = $this->check_needed;
            $new_participation->total_price = $this->total_price;

            if ($this->print_price > 0) {
                $new_participation->printorder_id = $new_PrintOrder->id;
            }

            $new_participation->save();

            // ----------------------------------------------------------- //


            // ---- Создаем произведения в participation_works ---- //
            $this->works = explode(';', $this->works);

            foreach ($this->works as $work) {
                $new_participation_work = new Participation_work();
                $new_participation_work->participation_id = $new_participation->id;
                $new_participation_work->work_id = $work;
                $new_participation_work->save();
            }
            // ----------------------------------------------------------- //

            // ------------ Создаем ЧАТ ------------
            $new_chat = new Chat();
            $new_chat->user_created = Auth::user()->id;
            $new_chat->user_to = 2;
            $new_chat->title = 'Чат: ' . Collection::where('id', $this->collection_id)->value('title');
            $new_chat->collection_id = $this->collection_id;
            $new_chat->chat_status_id = 9;
            $new_chat->save();
            // ------------------------------------


            $collection_name = Collection::where('id', $this->collection_id)->value('title');

            Notification::route('telegram', '649609693')
                ->notify(new new_participation($collection_name, $this->total_price, $this->pages, $this->print_needed));


            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Заявка успешно отправлена!');
            session()->flash('alert_text', 'На этой странице отображается весь процесс Вашего участия: оплата, предварительные материалы, отслеживание сборника и т.д.');
            return redirect('/myaccount/collections/' . $this->collection_id . '/participation/' . $new_participation->id);

        }
    }


}
