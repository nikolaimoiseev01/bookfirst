<?php

namespace App\Http\Livewire\Account\CollectionParticipation;

use App\Models\Chat;
use App\Models\Collection;
use App\Models\Participation;
use App\Models\Participation_work;
use App\Models\Printorder;
use App\Models\promocode;
use App\Models\Work;
use App\Notifications\new_participation;
use App\Notifications\TelegramNotification;
use App\Rules\SameParticipation;
use App\Service\ParticipationOutputsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Livewire;
use function Livewire\str;

class CollApplication extends Component
{

    public $app_type;
    public $participation;
    public $works_already_in = [];

    public $collection_id;
    public $collection;

    public $name;
    public $surname;
    public $nickname;

    public $works;
    public $rows;
    public $pages;

    public $print_need;
    public $prints = 1;
    public $send_to_name;
    public $send_to_country;
    public $send_to_city;
    public $send_to_index;
    public $send_to_tel;
    public $send_to_address;

    public $need_check;

    public $price_part;
    public $price_print;
    public $price_check;
    public $price_total;

    public $show_promo_input;
    public $promocode_input;
    public $promocode = null;

    public $error_texts = [];
    public $error_fields = [];

    protected $listeners = ['syncWorks', 'storeParticipation'];


    public function render(ParticipationOutputsService $calc_outs)
    {

        $this->collection = Collection::orderBY('id')->find($this->collection_id);

        // Понимаем количество страниц и строк
        if ($this->works) {
            $this->rows = null;
            $this->pages = null;
            foreach ($this->works as $work) {
                $this->rows += $work['rows'];
            }
            if ($this->rows) {
                $this->pages = ceil($this->rows / 33);
            }
        } else {
            $this->pages = 0;
        }

        // Узнаем цены участия
        $result = $calc_outs->calculate(
            $this->pages,
            $this->print_need,
            $this->prints,
            $this->need_check,
            $this->promocode['discount'] ?? 0
        );


        $this->price_part = $result['price_part'];
        $this->price_print = $result['price_print'];
        $this->price_check = $result['price_check'];
        $this->price_total = $result['price_total'];


        return view('livewire.account.collection-participation.coll-application');

    }

    public function mount(Request $request, $type, $part_id)
    {
        // Куда нужно перейти после сохранения работ
        $currenturl = url()->full();
        $back_after_work_adding = [
            'button_text' => 'Сохранить и вернуться к заявке',
            'url' => $currenturl
        ];
        $request->session()->put('back_after_work_adding', $back_after_work_adding);

        // Создание или редактирование?
        $this->app_type = $type;

        if ($this->app_type === 'create') { // Если это новая заявка, подставляем только известные поля
            $this->works_already_in = null;

            $this->name = Auth::user()->name;
            $this->surname = Auth::user()->surname;
            $this->nickname = Auth::user()->nickname;
            $this->send_to_name = Auth::user()->surname . ' ' . Auth::user()->name;

        } elseif ($this->app_type === 'edit') { // Если это редактирование заявки, подставляем все поля из заявки

            $this->participation = Participation::where('id', $part_id)->first();

            $this->name = $this->participation['name'];
            $this->surname = $this->participation['surname'];
            $this->nickname = $this->participation['nickname'];

            // Создаем работы, которые учавствуют
            $works_already_in_orig = Participation_work::where('participation_id', $this->participation['id'])->get()->toArray();

            foreach ($works_already_in_orig as $work) {
                array_push($this->works_already_in, $work['work_id']);
            }


            // Сразу нужно подгрузить в компонент работы, которые учавствуют
            $works_already_in_orig_ids = collect($works_already_in_orig)->pluck('work_id')->toArray();
            $sort_order = implode(',', $works_already_in_orig_ids);
            $this->works = Work::where('user_id', Auth::user()->id)
                ->whereIn('id', $this->works_already_in)
                ->orderByRaw("FIELD(id,$sort_order)")
                ->get()
                ->toArray();

            // Если есть заказ печатных экземпляров
            if ($this->participation->printorder) {
                $this->print_need = true;
                $this->prints = $this->participation->printorder['books_needed'];
                $this->send_to_name = $this->participation->printorder['send_to_name'];
                $this->send_to_country = $this->participation->printorder['send_to_country'];
                $this->send_to_city = $this->participation->printorder['send_to_city'];
                $this->send_to_index = $this->participation->printorder['send_to_index'];
                $this->send_to_tel = $this->participation->printorder['send_to_tel'];
                $this->send_to_address = $this->participation->printorder['send_to_address'];
            } else {
                $this->print_need = false;
                $this->send_to_name = Auth::user()->surname . ' ' . Auth::user()->name;
            }

            // Если есть проверка
            if ($this->participation['check_price'] > 0) {
                $this->need_check = true;
            } else {
                $this->need_check = false;
            }

            // Если есть промокод
            if ($this->participation['promocode']) {
                $this->promocode = promocode::where('promocode', $this->participation['promocode'])->first();
            }

            // Заполняем остальные поля стоимости
            $this->price_part = $this->participation['part_price'];
            $this->price_print = $this->participation['print_price'];
            $this->price_check = $this->participation['check_price'];
            $this->price_total = $this->participation['total_price'];

        }

    }


    public function syncWorks($works)
    {
        // Из компоненты выбора работ постоянно присылается список выбранных
        $this->works = $works;
    }


    public function check_promo()
    {
        $found_promo = promocode::where('promocode', $this->promocode_input)->first();

        if ($found_promo ?? null) {
            $this->promocode = $found_promo;
            $text = 'Применен промокод ' . $this->promocode['promocode'] . '. Учтена скидка в ' . $this->promocode['discount'] . '%!';
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Отлично!',
                'text' => $text,
            ]);
            $this->show_promo_input = false;
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Упс!',
                'text' => 'Мы не смогли найти такой промокод в системе',
            ]);
        }
    }

    public function check_app() // Одна общая проверка на данные
    {

        $this->error_texts = [];
        $this->error_fields = [];

        $is_same_part = Participation::where('user_id', Auth::user()->id)->Where('collection_id', $this->collection_id)->value('user_id');

        if ($this->app_type === 'create' && $is_same_part > 0) {
            array_push($this->error_texts, 'Вы уже участвуете в этом сборнике!');
        }

        if (!$this->works ?? null) {
            array_push($this->error_fields, 'works');
            array_push($this->error_texts, 'Произведения не добавлены!');
        }

        if (!$this->name) {
            array_push($this->error_fields, 'name');
            array_push($this->error_texts, 'Имя не заполнено!');
        }

        if (!$this->surname) {
            array_push($this->error_fields, 'surname');
            array_push($this->error_texts, 'Фамилия не заполнена!');
        }

        if ($this->participation) { // Если работаем с редактированием заявки

            $print_order_old = $this->participation->printorder; // Был уже заказ печатных?

            // Если сборник уже пошел, а человек решил заменить произведения
            $old_works = Participation_work::where('participation_id', $this->participation['id'])->pluck('work_id')->toArray();
            $new_works = collect($this->works)->pluck('id')->toArray();


            if ($this->collection['col_status_id'] >= 2 && !($new_works == $old_works)) {
                array_push($this->error_texts, 'На этом этапе сборника нельзя менять произведения!');
            }

            // Если сборник уже издан, нельзя редактировать!
            if ($this->collection['col_status_id'] >= 3) {
                array_push($this->error_texts, 'На этом этапе сборника нельзя изменять заявку!');
            }

            if ($this->participation['paid_at']) { // Если заказ уже оплачен

                if ($this->participation['total_price'] > $this->price_total) {
                    array_push($this->error_texts, 'Нельзя сделать сумму меньше оплаченной! Уже оплачено: ' . $this->participation['total_price'] . ' руб.');
                }

                if ($print_order_old && (!$this->print_need)) {
                    array_push($this->error_texts, 'Нельзя удалить оплаченный заказ печатных экземпляров!');
                }
                if ($print_order_old && ($this->prints < $print_order_old['books_needed'])) {
                    array_push($this->error_texts, 'Нельзя поставить меньше экземпляров, чем оплачено! Уже оплачено: ' . $print_order_old['books_needed'] . '.');
                }

            }
        }

        if (($this->print_need ?? null) && (!$this->send_to_country || !$this->send_to_city || !$this->send_to_address || !$this->send_to_name || !$this->send_to_tel || !$this->send_to_index)) {

            if (!$this->send_to_country) {
                array_push($this->error_fields, 'send_to_country');
            }
            if (!$this->send_to_city) {
                array_push($this->error_fields, 'send_to_city');
            }
            if (!$this->send_to_address) {
                array_push($this->error_fields, 'send_to_address');
            }
            if (!$this->send_to_name) {
                array_push($this->error_fields, 'send_to_name');
            }
            if (!$this->send_to_index) {
                array_push($this->error_fields, 'send_to_index');
            }
            if (!$this->send_to_tel) {
                array_push($this->error_fields, 'send_to_tel');
            }

            array_push($this->error_texts, 'Не вся информация о получаетеле заполнена!');
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

    public function confirm_save()
    {
        if ($this->check_app()) {

            $nickname = ($this->nickname) ? ' (' . $this->nickname . ')' : null;
            $author_name = $this->name . ' ' . $this->surname . $nickname;

            $work_files_text = count($this->works);
            $check_text = ($this->need_check ? 'нужна (' . $this->price_check . ' руб.)' : 'не нужна');
            $print_text = ($this->print_need) ?
                'экземпляров: ' . $this->prints
                . '. Получаетель: ' . $this->send_to_name . ', ' . $this->send_to_country . ', ' . $this->send_to_city
                . ', ' . $this->send_to_address . ', ' . $this->send_to_index . ', ' . $this->send_to_name . ', ' . $this->send_to_tel : 'не нужна.';

            $html = "<div style='display: flex; flex-direction: column; gap: 10px;'>
                <p><b>Имя в сборнике:</b> {$author_name} </p>
                <p><b>Загружено файлов: {$work_files_text}. </b>(страниц: {$this->pages})</p>
                <p><b>Проверка текста:</b> {$check_text}</p>
                <p><b>Печать:</b> {$print_text}</p>
                </div>";

            $this->dispatchBrowserEvent('swal:confirm', [
                'title' => 'Проверьте, пожалуйста, заявку: ',
                'html' => $html,
                'onconfirm' => 'storeParticipation'
            ]);
        }
    }

    public function get_notify_text()
    {
        $nickname = ($this->nickname) ? ' (' . $this->nickname . ')' : null;
        $author_name = $this->name . ' ' . $this->surname . $nickname;
        $promocode = ($this->promocode['promocode'] ?? null) ? $this->promocode['promocode'] . ' (' . $this->promocode['discount'] . '%)' : 'нет';
        $check = ($this->need_check ?? null) ? 'нужна (' . $this->price_check . ' ₽)' : 'нет';
        $print = ($this->print_need ?? null) ? $this->prints . " шт. (" . $this->price_print . ' ₽)' : 'нет';

        $text = "*Автор:* " . $author_name .
            "\n*Страниц:* " . $this->pages . " стр. (" . $this->price_part . ' ₽)' .
            "\n*Промокод:* " . str_replace('_', '', $promocode) .
            "\n*Печать:* " . $print .
            "\n*Проверка:* " . $check .
            "\n\n*ИТОГО:* " . $this->price_total . " руб.";

        return $text;
    }

    public function editParticipation()
    {

        if ($this->check_app()) {
            // Понимаем, какой статус ставить человеку.

            $old_works = Participation_work::where('participation_id', $this->participation['id'])->pluck('work_id')->toArray();
            $new_works = collect($this->works)->pluck('id')->toArray();

            if (($this->participation['total_price'] === $this->price_total)
                && $this->participation['pat_status_id'] > 2
                && $old_works == $new_works) { // Если цена осталась неизменна, и он уже оплатил, а работы не поменялись
                $pat_status_id = 3;
            } elseif ($this->participation['total_price'] !== $this->price_total && $old_works == $new_works && $this->participation['pat_status_id'] >= 2) { // Если цена изменилась, но не менялись произведения
                $pat_status_id = 2;
            } else {
                $pat_status_id = 1;
            }

            // Редактируем заявку
            Participation::where('id', $this->participation['id'])->update([
                'name' => $this->name,
                'surname' => $this->surname,
                'nickname' => $this->nickname,
                'works_number' => count($this->works),
                'rows' => $this->rows,
                'pages' => $this->pages,
                'pat_status_id' => $pat_status_id,
                'part_price' => $this->price_part,
                'print_price' => $this->price_print,
                'check_price' => $this->price_check,
                'total_price' => $this->price_total,
            ]);

            // Редактируем заказ печатных экземпляров

            $print_order_old = $this->participation->printorder; // Был уже заказ печатных?

            if ($print_order_old) { // Уже был
                if ($this->print_need ?? null) { // Редактируем, если нужен
                    PrintOrder::where('id', $print_order_old['id'])->update([
                        'books_needed' => $this->prints,
                        'send_to_name' => $this->send_to_name,
                        'send_to_tel' => $this->send_to_tel,
                        'send_to_address' => $this->send_to_address,
                        'send_to_country' => $this->send_to_country,
                        'send_to_city' => $this->send_to_city,
                        'send_to_index' => $this->send_to_index,
                    ]);
                } else { // Удаляем, раз не нужно (оплаченный не удалится по ошибкам в проверке
                    PrintOrder::where('id', $print_order_old['id'])->delete();
                    Participation::where('id', $this->participation['id'])->update([
                        'printorder_id' => null,
                    ]);
                }

            } else { // Еще не было -> создаем, если нужно
                if ($this->print_need ?? null) {
                    $new_PrintOrder = new PrintOrder();
                    $new_PrintOrder->participation_id = $this->participation['id'];
                    $new_PrintOrder->collection_id = $this->collection['id'];
                    $new_PrintOrder->user_id = Auth::user()->id;
                    $new_PrintOrder->books_needed = $this->prints;
                    $new_PrintOrder->send_to_name = $this->send_to_name;
                    $new_PrintOrder->send_to_tel = $this->send_to_tel;
                    $new_PrintOrder->send_to_country = $this->send_to_country;
                    $new_PrintOrder->send_to_city = $this->send_to_city;
                    $new_PrintOrder->send_to_index = $this->send_to_index;
                    $new_PrintOrder->send_to_address = $this->send_to_address;
                    $new_PrintOrder->save();
                    Participation::where('id', $this->participation['id'])->update([
                        'printorder_id' => $new_PrintOrder->id,
                    ]);
                }
            }

            // Заново записываем все работы, так как работы и порядок могли поменяться

            Participation_work::where('participation_id', $this->participation['id'])->delete();

            foreach ($this->works as $work) {
                $new_participation_work = new Participation_work();
                $new_participation_work->participation_id = $this->participation['id'];
                $new_participation_work->work_id = $work['id'];
                $new_participation_work->save();
            }

            // Оповещение нам в телеграм
            $title = '💥 *Изменение заявки в ' . $this->collection['title'] . '!* 💥';
            $text = $this->get_notify_text();
            $button_text = "Его страница участия";
            $url = route('user_participation', 1);

            // Посылаем Telegram уведомление нам
            Notification::route('telegram', '-506622812')
                ->notify(new TelegramNotification($title, $text, $button_text, $url));

            // Показываем успешно уведомление
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Заявка успешно сохранена.');
            return redirect('/myaccount/collections/' . $this->collection['id'] . '/participation/' . $this->participation['id']);


        }
    }

    public
    function storeParticipation()
    {

        // Создаем новый Заказ печатных!
        if ($this->print_need ?? null) {
            $new_PrintOrder = new PrintOrder();
            $new_PrintOrder->collection_id = $this->collection['id'];
            $new_PrintOrder->user_id = Auth::user()->id;
            $new_PrintOrder->books_needed = $this->prints;
            $new_PrintOrder->send_to_name = $this->send_to_name;
            $new_PrintOrder->send_to_tel = $this->send_to_tel;
            $new_PrintOrder->send_to_country = $this->send_to_country;
            $new_PrintOrder->send_to_city = $this->send_to_city;
            $new_PrintOrder->send_to_index = $this->send_to_index;
            $new_PrintOrder->send_to_address = $this->send_to_address;
            $new_PrintOrder->save();
        }

        // Создаем новую заявку
        $new_participation = new Participation();
        $new_participation->user_id = Auth::user()->id;
        $new_participation->collection_id = $this->collection['id'];
        $new_participation->name = $this->name;
        $new_participation->surname = $this->surname;
        $new_participation->nickname = $this->nickname;
        $new_participation->works_number = count($this->works);
        $new_participation->rows = $this->rows;
        $new_participation->pages = $this->pages;
        $new_participation->pat_status_id = 1;
        $new_participation->promocode = $this->promocode['promocode'] ?? null;
        $new_participation->part_price = $this->price_part;
        $new_participation->print_price = $this->price_print;
        $new_participation->check_price = $this->price_check;
        $new_participation->total_price = $this->price_total;

        $new_participation->save();

        if ($this->print_need ?? null) {
            $new_participation->update([
                'printorder_id' => $new_PrintOrder->id
            ]);
            $new_participation->save();

            $new_PrintOrder->update([
                'participation_id' => $new_participation->id
            ]);
            $new_PrintOrder->save();
        }

        // Создаем произведения в participation_works
        foreach ($this->works as $work) {
            $new_participation_work = new Participation_work();
            $new_participation_work->participation_id = $new_participation->id;
            $new_participation_work->work_id = $work['id'];
            $new_participation_work->save();
        }
        // ----------------------------------------------------------- //

        // Создаем ЧАТ
        $new_chat = new Chat();
        $new_chat->user_created = Auth::user()->id;
        $new_chat->user_to = 2;
        $new_chat->flg_admin_chat = 1;
        $new_chat->title = 'Личный чат по сборнику: ' . $this->collection['title'];
        $new_chat->collection_id = $this->collection_id;
        $new_chat->chat_status_id = 9;
        $new_chat->save();

        $new_participation->update([
            'chat_id' => $new_chat->id
        ]);
        $new_participation->save();
        // ------------------------------------


        // Оповещение нам в телеграм
        $title = '💥 *Новая заявка в ' . $this->collection['title'] . '!* 💥';
        $text = $this->get_notify_text();
        $button_text = "Его страница участия";
        $url = route('user_participation', 1);

        // Посылаем Telegram уведомление нам
        Notification::route('telegram', '-506622812')
            ->notify(new TelegramNotification($title, $text, $button_text, $url));

        // Переводим на страницу участия
        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Заявка успешно отправлена!');
        session()->flash('alert_text', 'На этой странице отображается весь процесс Вашего участия: оплата, предварительные материалы, отслеживание сборника и т.д.');
        return redirect('/myaccount/collections/' . $this->collection['id'] . '/participation/' . $new_participation->id);

    }


}
