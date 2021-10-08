<?php

namespace App\Http\Livewire;

use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\promocode;
use App\Models\Work;
use App\Rules\SameParticipation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class EditParticipation extends Component
{
    public $collection_id;
    public $participation_id;
    public $printorder_id;


    public $name;
    public $surname;
    public $nickname;
    public $rows;
    public $pages;
    public $number_works = 0;
    public $works = '';

    public $print_needed;
    public $send_to_name;
    public $send_to_tel;
    public $send_to_address;
    public $errors = [];

    public $promocode;


    public $part_price;
    public $print_price;
    public $check_needed;
    public $total_price;

    protected $listeners = ['updateParticipation'];

    /**
     * @var mixed
     */

    public function render()
    {

        $user_works = Work::orderBy('id', 'asc')->where('user_id', Auth::user()->id)->get() ?? 0;
        $collection = Collection::orderBY('id')->find($this->collection_id);
        $works_already_in = Participation_work::where('participation_id', $this->participation_id)->get();
        $participation = Participation::where('id', $this->participation_id)->first();
        $printorder = Printorder::where('id', $this->printorder_id)->first();
        return view('livewire.edit-participation', [
            'collection' => $collection,
            'participation' => $participation,
            'user_works' => $user_works,
            'works_already_in' => $works_already_in,
            'printorder' => $printorder,
            'promocode' => $this->promocode,
        ]);
    }

    public function mount($collection, $participation)
    {

        $this->collection_id = $collection->id;
        $this->participation_id = $participation->id;
        $this->printorder_id = $participation->printorder_id;

        $printorder = Printorder::where('id', $this->printorder_id)->first();

        $this->name = $participation->name;
        $this->surname = $participation->surname;
        $this->nickname = $participation->nickname;

        $this->send_to_name = $printorder->send_to_name ?? '';
        $this->send_to_address = $printorder->send_to_address ?? '';
        $this->send_to_tel = $printorder->send_to_tel ?? '';

        $this->promocode = promocode::where('promocode', $participation['promocode'])->value('discount');


    }


    public function updateParticipation()
    {

        $errors_array = [];

        if (!$this->pages > 0) {
            array_push($errors_array, 'Произведения не добавлены!');
        }

        if ($this->print_price > 0 & !$this->send_to_address || !$this->send_to_name || !$this->send_to_tel) {
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


// ---- Редактируем/создаем/удаляем Заказ печатных! ---- //
//        DD($this->print_price);
            if ($this->print_price > 0) // Если пользователь хочет экземпляры
            {
                if (Participation::where('id', $this->participation_id)
                        ->value('printorder_id') > 0) // Если уже есть заказ, обновляем его
                {
                    PrintOrder::where('id', $this->printorder_id)->update([
                        'books_needed' => $this->print_needed,
                        'send_to_name' => $this->send_to_name,
                        'send_to_tel' => $this->send_to_tel,
                        'send_to_address' => $this->send_to_address,
                    ]);
                } else { // Если заказа нет, создаем новый
                    $new_PrintOrder = new PrintOrder();
                    $new_PrintOrder->collection_id = $this->collection_id;
                    $new_PrintOrder->user_id = Auth::user()->id;
                    $new_PrintOrder->books_needed = $this->print_needed;
                    $new_PrintOrder->send_to_name = $this->send_to_name;
                    $new_PrintOrder->send_to_tel = $this->send_to_tel;
                    $new_PrintOrder->send_to_address = $this->send_to_address;
                    $new_PrintOrder->save();
                    Participation::where('id', $this->participation_id)->update([
                        'printorder_id' => $new_PrintOrder->id,
                    ]);
                }
            } else {
                if (Participation::where('id', $this->participation_id)->value('printorder_id') > 0) {
                    PrintOrder::where('id', $this->printorder_id)->delete();
                }
                Participation::where('id', $this->participation_id)->update([
                    'printorder_id' => null
                ]);
            }
// ----------------------------------------------------------- //

// ---- Редактируем Участие! ---- //
            Participation::where('id', $this->participation_id)->update([
                'name' => $this->name,
                'surname' => $this->surname,
                'nickname' => $this->nickname,
                'works_number' => $this->number_works,
                'rows' => $this->rows,
                'pages' => $this->pages,
                'pat_status_id' => 1,
                'part_price' => $this->part_price,
                'print_price' => $this->print_price,
                'check_price' => $this->check_needed,
                'total_price' => $this->total_price,
            ]);


            // ---- Редактируем произведения в participation_works ---- //
            Participation_work::where('participation_id', $this->participation_id)->delete();
            $this->works = explode(';', $this->works);

            foreach ($this->works as $work) {
                $new_participation_work = new Participation_work();
                $new_participation_work->participation_id = $this->participation_id;
                $new_participation_work->work_id = $work;
                $new_participation_work->save();
            }
// ----------------------------------------------------------- //


            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Заявка успешно отредактирована!');
            session()->flash('alert_text', 'На этой странице отображается весь процесс Вашего участия: оплата, предварительные материалы, отслеживание сборника и т.д.');
            return redirect('/myaccount/collections/' . $this->collection_id . '/participation/' . $this->participation_id);

        }
    }


}
