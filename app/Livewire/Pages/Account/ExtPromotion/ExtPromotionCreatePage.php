<?php

namespace App\Livewire\Pages\Account\ExtPromotion;

use App\Enums\ChatStatusEnums;
use App\Enums\ExtPromotionStatusEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Chat;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\Promocode;
use App\Notifications\ExtPromotion\ExtPromotionCreatedNotification;
use App\Services\PriceCalculation\CalculateExtPromotionService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ExtPromotionCreatePage extends Component
{

    use WithCustomValidation;

    public $days = 10;
    public $site = 'stihi';
    public $options = [
        ['value' => 'stihi', 'label' => 'Стихи.ру'],
        ['value' => 'proza', 'label' => 'Проза.ру']
    ];
    public $promocode;
    public $hasPromo;
    public $promocodeInput;
    public $prices;

    public $login;
    public $password;
    public $rulesAgreed;

    protected $listeners = ['saveApplication'];

    public function render()
    {
        return view('livewire.pages.account.ext-promotion.ext-promotion-create-page')->layout('layouts.account');
    }

    public function mount()
    {
        $this->updated();
    }

    public function rules()
    {
        return [
            'rulesAgreed' => 'required',
            'login' => Rule::requiredIf(fn() => $this->rulesAgreed),
            'password' => Rule::requiredIf(fn() => $this->rulesAgreed),
        ];
    }

    public function messages()
    {
        return [
            'rulesAgreed.required' => 'Вы должны согласиться с правилами продвижения',
            'login.required' => 'Вы должны ввести логин',
            'password.required' => 'Вы должны ввести пароль',
        ];
    }

    public function checkPromo()
    {
        if (mb_strlen($this->promocodeInput) > 0) {
            $promocode = Promocode::where('name', 'like', "%{$this->promocodeInput}%")->first();
            if ($promocode) {
                $this->promocode = $promocode;
                $this->hasPromo = false;
                $discount = $promocode['discount'];
                $this->updated();
                $this->dispatch('swal', type: 'success', text: "Промокод применен! Теперь в цене учитывается скидка в {$discount}%.");
            } else {
                $this->dispatch('swal', type: 'error', title: 'Ошибка', text: "Промокод {$this->promocodeInput} не найден в системе");
            }
        } else {
            $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Введите промокод');
        }
    }

    public function updated()
    {
        $this->prices = ((new CalculateExtPromotionService(
            $this->site
            , $this->days
            , ($this->promocode['discount'] ?? 0)))
            ->calculate());
    }

    public function getConfirmText()
    {

        $text = "<b>Сайт для продвижения:</b> " . $this->site .
            "<br>Данные, с помощью которых можно войти в ваш аккаунт <b>на сайте {$this->site}</b>:<br>Логин: <b>{$this->login}</b>; Пароль:<b>{$this->password}</b>" .
            "<br><a target='_blank' class='text-green-500' href='/fixed/ext_promotion_rules.pdf'>Почему это безопасно</a>" .
            "<br><b>Дней продвижения:</b> " . $this->days .
            "<br><br><b>Общая стоимость:</b> " . $this->prices['priceTotal'] . " руб.";
        return $text;
    }

    public function checkAndConfirm()
    {
        if ($this->customValidate()) {
            $this->dispatch('swal',
                title: 'Давайте все проверим',
                text: $this->getConfirmText(),
                confirmButtonText: 'Да, все верно',
                livewireMethod: ['saveApplication']
            );
        }
    }

    public function saveApplication()
    {
        DB::transaction(function () {
            $newExtPromotion = ExtPromotion::create([
                    'user_id' => Auth::user()->id,
                    'status' => ExtPromotionStatusEnums::REVIEW,
                    'login' => $this->login,
                    'password' => $this->password,
                    'site' => $this->site,
                    'days' => $this->days,
                    'price_total' => $this->prices['priceTotal'],
                    'price_executor' => $this->prices['priceExecutor'],
                    'price_our' => $this->prices['priceOur'],
                    'promocode_id' => $this->promocode['id'] ?? null
                ]
            );
            Chat::create([
                'user_created' => Auth::user()->id,
                'model_type' => 'ExtPromotion',
                'model_id' => $newExtPromotion['id'],
                'user_to' => 2,
                'title' => 'Личный чат по продвижению на сайте: ' . $this->site,
                'status' => ChatStatusEnums::EMPTY,
                'flg_admin_chat' => true,
            ]);

            TelegramNotificationJob::dispatch(new ExtPromotionCreatedNotification($newExtPromotion));

            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => 'Заявка на продвижение создана! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.'
            ]);

            $this->redirect(route('account.ext_promotion.index', $newExtPromotion['id']), navigate: true);
        });
    }
}
