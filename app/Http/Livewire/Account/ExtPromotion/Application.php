<?php

namespace App\Http\Livewire\Account\ExtPromotion;

use App\Models\Chat;
use App\Models\ext_promotion;
use App\Models\promocode;
use App\Notifications\TelegramNotification;
use App\Service\ExtPromotionOutputsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Application extends Component
{
    public $error_texts = [];
    public $error_fields = [];

    public $login;
    public $password;
    public $site = 'stihi';
    public $days = 5;
    public $new_ext_promotion;

    private $calc_result;

    public $show_promo_input;
    public $promocode_input;
    public $promocode = null;

    public function render(ExtPromotionOutputsService $calc_outs)
    {

        $this->calc_result = $calc_outs->calculate($this->site, $this->days, $this->promocode['discount'] ?? 0);

        $this->price_total = $this->calc_result['price_total'];

        return view('livewire.account.ext-promotion.application');
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

    public function save_application(ExtPromotionOutputsService $calc_outs)
    {

        $this->calc_result = $calc_outs->calculate($this->site, $this->days, $this->promocode['discount'] ?? 0);

        $has_promos = ext_promotion::where('user_id', Auth::user()->id)
            ->where('site', $this->site)
            ->where('ext_promotion_status_id', '<', 9)
            ->get();

        $this->error_texts = [];
        $this->error_fields = [];

        if (count($has_promos ?? null) > 0) {
            array_push($this->error_texts, 'У вас уже есть активная заявка на продвижение на этом сайте!');
        }

        if (!$this->login ?? null) {
            array_push($this->error_fields, 'login');
            array_push($this->error_texts, 'Логин не заполнен!');
        }
        if (!$this->password ?? null) {
            array_push($this->error_fields, 'password');
            array_push($this->error_texts, 'Пароль не заполнен!');
        }

        if (!empty($this->error_texts)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $this->error_texts),
            ]);
        } else {

            DB::transaction(function () { // Чтобы не записать ненужного

                $this->new_ext_promotion = ext_promotion::create([
                    'user_id' => Auth::user()->id,
                    'ext_promotion_status_id' => 1,
                    'login' => $this->login,
                    'password' => $this->password,
                    'site' => $this->site,
                    'days' => $this->days,
                    'price_total' => $this->price_total,
                    'price_executor' => $this->calc_result['price_executor'],
                    'price_our' => $this->calc_result['price_our'],
                    'promocode_id' => $this->promocode['id'] ?? null
                ]);

                // Создаем ЧАТ
                $new_chat = new Chat();
                $new_chat->user_created = Auth::user()->id;
                $new_chat->user_to = 2;
                $new_chat->flg_admin_chat = 1;
                $new_chat->title = 'Личный чат по продвижению на сайте: ' . $this->site;
                $new_chat->chat_status_id = 9;
                $new_chat->save();

                $this->new_ext_promotion->update([
                    'chat_id' => $new_chat->id
                ]);
            });


            $user = Auth::user();

            Notification::route('telegram', '-4120321987')
                ->notify(new TelegramNotification('💥 *Новая заявка на продвижение!* 💥',
                    "*Автор*: {$user['surname']} {$user['name']}\n*Логин*: {$this->login}\n*Сайт*: {$this->site}\n*Дней*: {$this->days}\n*Общая стоимость*: {$this->price_total}\n" ,
                    null,
                    null));

            // Показываем успешно уведомление
            session()->flash('show_modal', 'yes');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Заявка успешно сохранена.');
            return redirect(route('index_ext_promotion', $this->new_ext_promotion->id));
        }

    }
}
