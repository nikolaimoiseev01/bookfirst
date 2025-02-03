<?php

namespace App\Http\Livewire\Account\CollectionParticipation;

use App\Models\almost_complete_action;
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
use Illuminate\Support\Facades\DB;
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

    public $print_need = False;
    public $prints = 1;
    public $send_to_name;
    public $send_to_tel;

    public $delivery_country = 'rus';

    public $address;
    public $send_to_country;
    public $send_to_city;
    public $send_to_address;
    public $send_to_index;
    public $address_default_string;


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

    protected $listeners = ['syncWorks', 'storeParticipation', 'editParticipation', 'new_almost_complete_action', 'confirm_step_2'];


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
                $printorder = $this->participation->printorder;
                $address = json_decode($printorder['address'], true);
                $this->prints = $printorder['books_needed'];
                $this->send_to_name = $printorder['send_to_name'];
                $this->send_to_tel = $printorder['send_to_tel'];
                $this->address = $address;
                $this->delivery_country = $printorder['address_country'] == 'Россия' ? 'rus' : 'foreign';

                if ($printorder['address_country'] == 'Россия') { // Если в Россию, то заполняем только строку ввода
                    $this->address_default_string = $address['value'];
                } else { // Если нет, то заполняем отдельно поля
                    $this->send_to_country = $address['data']['country'];
                    $this->send_to_city = $address['data']['city'];
                    $this->send_to_index = $address['data']['index'];
                    $this->send_to_address = $address['data']['address'];
                }

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

    public function postAddressInit()
    {
        $this->updatePostWidget();
    }

    public function updatedPrints()
    {
        $this->updatePostWidget();
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

        $is_same_part = Participation::where('user_id', Auth::user()->id)->Where('collection_id', $this->collection_id)->where('pat_status_id', '<>', 99)->value('user_id');

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

        $has_foreign_address_flg = $this->send_to_country && $this->send_to_city && $this->send_to_address && $this->send_to_index;

        if ($this->print_need ?? null) {

            if (!$this->send_to_name) {
                array_push($this->error_texts, 'Укажите имя получателя!');
                array_push($this->error_fields, 'send_to_name');
            }

            if (!$this->send_to_tel) {
                array_push($this->error_texts, 'Укажите телефон получателя!');
                array_push($this->error_fields, 'send_to_tel');
            }

            $has_ru_address = ($this->address ?? null) && ($this->address ?? null);
            if ($this->delivery_country == 'rus' && !$has_ru_address) {
                array_push($this->error_texts, 'Введите адрес и выберите его из подсказки!');
            }

            if ($this->delivery_country == 'foreign' && !$has_foreign_address_flg) {
                array_push($this->error_texts, 'Не вся информация об адресе заполнена!');
            }
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

    public function confirm_step_1()
    {
        if ($this->check_app()) { // Если прошла все проверки
            // Если в адресе не оказалось квартиры
            if ($this->print_need ?? null) {
                if ($this->delivery_country == 'rus' && $this->address['type'] == 'DaData RUS') {
                    if (!$this->address['data']['flat'] || !$this->address['data']['street']) {
                        $this->dispatchBrowserEvent('swal:confirm', [
                            'title' => 'Точно такой адрес?',
                            'html' => "<p>В выбранном адресе вы что-то пропустили (квартиру, улицу). Это нормально, если это, например, частный дом. Это точно правильный адрес? <br> {$this->address['unrestricted_value']}</p>",
                            'onconfirm' => 'confirm_step_2'
                        ]);
                    } else {
                        $this->confirm_step_2();
                    };
                } else {
                    $this->confirm_step_2();
                };
            } else {
                $this->confirm_step_2();
            };

        };
    }

    public function confirm_step_2()
    {


        $nickname = ($this->nickname) ? ' (' . $this->nickname . ')' : null;
        $author_name = $this->name . ' ' . $this->surname . $nickname;

        $work_files_text = count($this->works);
        $check_text = ($this->need_check ? 'нужна (' . $this->price_check . ' руб.)' : 'не нужна');

        if ($this->print_need ?? null) {
            if ($this->delivery_country == 'rus') {
                $delivery_text = "РФ, {$this->address['unrestricted_value']}";
            } elseif ($this->delivery_country == 'foreign') {
                $delivery_text = "$this->send_to_country, $this->send_to_city, $this->send_to_address, $this->send_to_index";
            }
        }

        $print_text = ($this->print_need) ?
            "экземпляров: $this->prints
                <p><b>Получатель:</b> $this->send_to_name, $this->send_to_tel</p>
                <p><b>Адрес:</b>  $delivery_text</p>" : 'не нужна.';

        $html = "<div style='display: flex; flex-direction: column; gap: 10px;'>
                <p><b>Имя в сборнике:</b> {$author_name} </p>
                <p><b>Произведений:</b> {$work_files_text} (~страниц: {$this->pages})</p>
                <p><b>Проверка текста:</b> {$check_text}</p>
                <p><b>Печать:</b> {$print_text}</p>
                </div>";

        $onconfirm = $this->app_type == 'create' ? 'storeParticipation' : 'editParticipation';
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Проверьте, пожалуйста, заявку: ',
            'html' => $html,
            'onconfirm' => $onconfirm
        ]);

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

    public function makeAddressJSON()
    {
        if ($this->delivery_country == 'rus') {
            $this->address = json_encode($this->address);

        } elseif ($this->delivery_country == 'foreign') {
            $address = [
                'type' => 'foreign',
                'data' => [
                    'country' => $this->send_to_country,
                    'city' => $this->send_to_city,
                    'address' => $this->send_to_address,
                    'index' => $this->send_to_index
                ],
                'unrestricted_value' => "$this->send_to_country, $this->send_to_city, $this->send_to_address, $this->send_to_index"
            ];
            $this->address = json_encode($address);
        }
    }

    public function storeParticipation()
    {
        DB::transaction(function () use (&$new_participation) {
            // Создаем новый Заказ печатных!
            if ($this->print_need ?? null) {

                $this->makeAddressJSON();

                $new_PrintOrder = Printorder::create([
                    'collection_id' => $this->collection['id'],
                    'user_id' => Auth::user()->id,
                    'books_needed' => $this->prints,
                    'send_to_name' => $this->send_to_name,
                    'send_to_tel' => $this->send_to_tel,
                    'address' => $this->address,
                    'address_country' => $this->delivery_country == 'rus' ? 'Россия' : $this->send_to_country
                ]);
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
        });
        // ------------------------------------


        // Оповещение нам в телеграм
        $title = '💥 *Новая заявка в ' . $this->collection['title'] . '!* 💥';
        $text = $this->get_notify_text();
        $button_text = "Его страница участия";
        $url = "https://www.vk.com";

        // Посылаем Telegram уведомление нам
        Notification::route('telegram', config('cons.telegram_chat_id'))
            ->notify(new TelegramNotification($title, $text, $button_text, $url));

        // Переводим на страницу участия
        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Заявка успешно отправлена!');
        session()->flash('alert_text', 'На этой странице отображается весь процесс Вашего участия: оплата, предварительные материалы, отслеживание сборника и т.д.');
        return redirect('/myaccount/collections/' . $this->collection['id'] . '/participation/' . $new_participation->id);

    }

    public function editParticipation()
    {
        DB::transaction(function () {
            // Понимаем, какой статус ставить человеку.

            $old_works = Participation_work::where('participation_id', $this->participation['id'])->pluck('work_id')->toArray();
            $new_works = collect($this->works)->pluck('id')->toArray();
            $participation = Participation::where('id', $this->participation['id'])->first();

            $comparison = [];

            // Запоминаем все старые значения
            $old_name = $participation['name'];
            $old_surname = $participation['surname'];
            $old_nickname = $participation['nickname'];
            $old_works_number = $participation['works_number'];
            $old_price_check = $participation['price_check'];

            if ($old_name != $this->name) {
                array_push($comparison, "*Имя.* Было '{$old_name}', стало '{$this->name}'");
            }
            if ($old_surname != $this->surname) {
                array_push($comparison, "*Фамилия.* Было '{$old_surname}', стало '{$this->surname}'");
            }
            if ($old_nickname != $this->nickname) {
                array_push($comparison, "*Псевдоним.* Было '{$old_nickname}', стало '{$this->nickname}'");
            }
            if ($old_works_number != count($this->works)) {
                array_push($comparison, "*Кол-во работ.* Было {$old_works_number}, стало " . count($this->works));
            }
            if ($old_price_check != $this->price_check) {
                array_push($comparison, "*Стоимость проверки.* Было " . $old_price_check ?? 0 . ", стало " . $this->price_check);
            }


            if (($this->participation['total_price'] === $this->price_total)
                && $this->participation['pat_status_id'] > 2
                && $old_works == $new_works) { // Если цена осталась неизменна, и он уже оплатил, а работы не поменялись
                $pat_status_id = 3;
            } // Если цена изменилась, но не менялись произведения
            elseif ($this->participation['total_price'] !== $this->price_total && $old_works == $new_works && $this->participation['pat_status_id'] >= 2) {
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

                // Запоминаем все старые значения
                $old_prints = $print_order_old['books_needed'];
                $old_send_to_name = $print_order_old['send_to_name'];
                $old_send_to_tel = $print_order_old['send_to_tel'];
                $old_send_to_address = $print_order_old['send_to_address'];
                $old_send_to_country = $print_order_old['send_to_country'];
                $old_send_to_city = $print_order_old['send_to_city'];
                $old_send_to_index = $print_order_old['send_to_index'];

                if ($old_prints != $this->prints) {
                    array_push($comparison, "*Кол-во экземпляров.* Было {$old_prints}, стало " . $this->prints);
                }
                if ($old_send_to_name != $this->send_to_name) {
                    array_push($comparison, "*Имя получателя.* Было '{$old_send_to_name}', стало '{$this->send_to_name}'");
                }
                if ($old_send_to_tel != $this->send_to_tel) {
                    array_push($comparison, "*Телефон.* Было '{$old_send_to_tel}', стало '{$this->send_to_tel}'");
                }
                if ($old_send_to_address != $this->send_to_address) {
                    array_push($comparison, "*Адрес.* Было '{$old_send_to_address}', стало '{$this->send_to_address}'");
                }
                if ($old_send_to_country != $this->send_to_country) {
                    array_push($comparison, "*Страна.* Было '{$old_send_to_country}', стало '{$this->send_to_country}'");
                }
                if ($old_send_to_city != $this->send_to_city) {
                    array_push($comparison, "*Город.* Было '{$old_send_to_city}', стало '{$this->send_to_city}'");
                }
                if ($old_send_to_index != $this->send_to_index) {
                    array_push($comparison, "*Индекс.* Было '{$old_send_to_index}', стало '{$this->send_to_index}'");
                }

                if ($this->print_need ?? null) { // Редактируем, если нужен
                    $this->makeAddressJSON();
                    PrintOrder::where('id', $print_order_old['id'])->update([
                        'books_needed' => $this->prints,
                        'send_to_name' => $this->send_to_name,
                        'send_to_tel' => $this->send_to_tel,
                        'address' => $this->address,
                        'address_country' => $this->delivery_country == 'rus' ? 'Россия' : $this->send_to_country
                    ]);
                } else { // Удаляем, раз не нужно (оплаченный не удалится по ошибкам в проверке
                    PrintOrder::where('id', $print_order_old['id'])->delete();
                    array_push($comparison, "*Заказ печатных.* Был, а теперь нет.");
                    Participation::where('id', $this->participation['id'])->update([
                        'printorder_id' => null,
                    ]);
                }

            } else { // Еще не было -> создаем, если нужно
                if ($this->print_need ?? null) {
                    array_push($comparison, "*Добавилась печать.* Раньше не было");
                    $this->makeAddressJSON();
                    $new_PrintOrder = Printorder::create([
                        'collection_id' => $this->collection['id'],
                        'user_id' => Auth::user()->id,
                        'books_needed' => $this->prints,
                        'send_to_name' => $this->send_to_name,
                        'send_to_tel' => $this->send_to_tel,
                        'address' => $this->address,
                        'address_country' => $this->delivery_country == 'rus' ? 'Россия' : $this->send_to_country
                    ]);
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


            if ($comparison ?? null && $pat_status_id == 1) { // Если что-то поменялось и нужно апрувить
                // Оповещение нам в телеграм
                $nickname = ($this->nickname) ? ' (' . $this->nickname . ')' : null;
                $author_name = $this->name . ' ' . $this->surname . $nickname;
                $title = '💥 *Изменение заявки в ' . $this->collection['title'] . '!* 💥';
                $text = "*Автор:* {$author_name} \n*Изменилось:* \n" . implode("\n", $comparison);
                $button_text = "Его страница участия";
                $url = "https://vk.com";

                // Посылаем Telegram уведомление нам
                Notification::route('telegram', '-506622812')
                    ->notify(new TelegramNotification($title, $text, $button_text, $url));
            }


            // Показываем успешно уведомление
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Заявка успешно сохранена.');
            return redirect('/myaccount/collections/' . $this->collection['id'] . '/participation/' . $this->participation['id']);


        });
    }


    public function new_almost_complete_action()
    {

        $already_has_action = almost_complete_action::where('user_id', Auth::user()->id)
            ->where('collection_id', $this->collection_id)
            ->first();
        if (!($already_has_action ?? null)) {
            almost_complete_action::firstOrCreate([
                'user_id' => Auth::user()->id,
                'almost_complete_action_type_id' => 1,
                'collection_id' => $this->collection_id,
                'cnt_email_sent' => 0
            ]);
        }
    }

}
