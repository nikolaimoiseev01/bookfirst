<?php

namespace App\Livewire\Components\Account\Collection;

use App\Enums\ChatStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Enums\TransactionStatusEnums;
use App\Filament\Resources\Collection\Participations\Pages\EditParticipation;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Chat;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Work\Work;
use App\Notifications\Collection\ParticipationCreatedNotification;
use App\Rules\ParticipationLessPrice;
use App\Services\PriceCalculation\CalculateParticipationService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ParticipationForm extends Component
{
    use WithCustomValidation;

    private const INSIDE_COLOR = 'Черно-белый';
    private const COVER_TYPE = 'Мягкая';
    private const LOGISTIC_COMPANY_ID = 2;
    private const PRINTING_COMPANY_ID = 3;
    private const CHAT_TITLE_PREFIX = 'Личный чат по участию в сборнике {collection_title}';

    public $collection;
    public $participation;
    public $formType;
    public $userWorks;
    public $selectedWorks = [];
    public $pages;
    public $rows;
    public $authorName;
    public $promocodeInput;
    public $hasPromo = false;
    public $promocode;
    public $needPrint = false;
    public $booksCnt = 1;
    public $receiverName;
    public $receiverTelephone;
    public $country = 'Россия';
    public $addressType = 'СДЭК';
    public $addressJson;
    public $needCheck;
    public $prices;

    public $oldSelectedWorks;

    public $showChosenAddress = false;
    protected $listeners = ['getAddress', 'saveApplication'];

    public function render()
    {
        return view('livewire.components.account.collection.participation-form');
    }

    public function mount($formType, $collection = null, $participation = null)
    {
        Session::put('cameFromAppUrl', URL::current());
        if ($formType == 'create') {
            $this->collection = $collection;
            $this->authorName = Auth::user()->name . ' ' . Auth::user()->surname;
        } elseif ($formType == 'edit') {
            $this->participation = $participation;
            $this->collection = $this->participation->collection;
            $this->authorName = $this->participation['author_name'];
            $this->pages = $this->participation['pages'];
            $this->rows = $this->participation['rows'];
            $this->needCheck = $participation['price_check'] > 0;
            $this->needPrint = $this->participation->printOrder ? true : false;
            $this->promocode = $this->participation->promocode ?? null;
            $this->prices = [
                'pricePart' => $this->participation['price_part'],
                'priceCheck' => $this->participation['price_check'],
                'priceTotal' => $this->participation['price_total'],
                'pricePrint' => 0
            ];
            if ($this->needPrint) {
                $this->prices['pricePrint'] = $this->participation->printOrder['price_print'];
                $this->booksCnt = $this->participation->printOrder['books_cnt'];
                $this->receiverName = $this->participation->printOrder['receiver_name'];
                $this->receiverTelephone = $this->participation->printOrder['receiver_telephone'];
                $this->country = $this->participation->printOrder['country'];
                $this->addressType = $this->participation->printOrder['address_type'];
                $this->addressJson = $this->participation->printOrder['address_json'];
                $this->showChosenAddress = true;
            }
            foreach ($this->participation->participationWorks as $participationWork) {
                $this->selectedWorks[] = [
                    'id' => $participationWork->work['id'],
                    'title' => $participationWork->work['title'],
                    'rows' => $participationWork->work['rows'],
                ];
                $this->oldSelectedWorks = $this->selectedWorks;
            }
        }
        $selectedWorksIds = [];
        foreach ($this->selectedWorks as $selectedWorks) {
            $selectedWorksIds[] = $selectedWorks['id'];
        }

        $this->userWorks = Work::where('user_id', Auth::user()->id)
            ->whereNotIn('id', $selectedWorksIds)
            ->get(['id', 'title', 'rows']);
    }


    protected function rules(): array
    {
        return [
            'authorName' => 'required',
            'selectedWorks' => 'required',
            'receiverName' => Rule::requiredIf(fn() => $this->needPrint),
            'receiverTelephone' => Rule::requiredIf(fn() => $this->needPrint),
            'country' => [
                Rule::requiredIf(fn() => $this->needPrint && $this->addressType == 'foreign'),
                'min:1',
            ],
            'addressJson' => Rule::requiredIf(fn() => $this->needPrint),
            'collection' => [
                Rule::when(
                    $this->formType === 'create',
                    Rule::unique('participations', 'collection_id')
                        ->where(fn($q) => $q->where('user_id', Auth::id()))
                ),
            ],
            'prices.priceTotal' => [
                Rule::when(
                    $this->formType === 'edit' && $this->participation->transactions()->exists(),
                    function () {
                        $paidAmount = $this->participation->transactions->where('status', TransactionStatusEnums::CONFIRMED)->sum('amount');
                        $currentPriceWithPrint = $this->prices['priceTotal'] + $this->prices['pricePrint'];
                        return new ParticipationLessPrice($paidAmount, $currentPriceWithPrint);
                    }
                ),
            ],
        ];
    }

    protected function messages(): array
    {
        $messages = [
            'authorName.required' => 'Имя в сборнике обязательно для заполнения',
            'selectedWorks.required' => "Нужно добавить произведения к заявке 'Произведения для участия' (кнопка с большим плюсом)",
            'receiverName.required' => 'Имя получателя обязательно для заполнения',
            'receiverTelephone.required' => 'Телефон получателя обязателен для заполнения',
            'country.required' => 'Страна получателя должна быть заполнена',
            'country.min' => 'Страна получателя должна быть заполнена',
            'collection.unique' => 'Вы уже участвуете в этом сборнике!',
        ];

        if ($this->addressType === 'СДЭК') {
            $messages['addressJson.required'] = 'Пожалуйста, выберите офис сдэк для отправки (кнопка "выбрать" на карте)';
        } else {
            $messages['addressJson.required'] = 'Для международной доставки адрес обязателен';
        }

        return $messages;
    }

    public function updated($value)
    {
        if ($value == 'needPrint' || $value == 'needCheck' || $value == 'booksCnt') {
            $this->updatePrices();
        }
    }

    public function getAddress($country, $addressType, $addressJson)
    {
        $this->country = $country;
        $this->addressType = $addressType;
        $this->addressJson = $addressJson;
    }

    public function checkPromo()
    {
        if (mb_strlen($this->promocodeInput) > 0) {
            $promocode = Promocode::where('name', 'like', "%{$this->promocodeInput}%")->first();
            if ($promocode) {
                $this->promocode = $promocode;
                $this->hasPromo = false;
                $discount = $promocode['discount'];
                $this->updatePrices();
                $this->dispatch('swal', type: 'success', text: "Промокод применен! Теперь в цене учитывается скидка в {$discount}%.");
            } else {
                $this->dispatch('swal', type: 'error', title: 'Ошибка', text: "Промокод {$this->promocodeInput} не найден в системе");
            }
        } else {
            $this->dispatch('swal', type: 'error', title: 'Ошибка', text: 'Введите промокод');
        }
    }

    public function updatedSelectedWorks()
    {
        $this->updatePrices();
        $this->rows = collect($this->selectedWorks)->sum('rows');
        $this->pages = round(ceil($this->rows / 38));
    }

    public function updatePrices()
    {
        $this->prices = ((new CalculateParticipationService(
            $this->pages,
            $this->needPrint,
            $this->booksCnt,
            $this->needCheck,
            $this->promocode['discount'] ?? 0)
        )->calculate());
    }

    public function getNotifyText()
    {
        $promocode = ($this->promocode['name'] ?? null) ? $this->promocode['name'] . ' (' . $this->promocode['discount'] . '%)' : 'нет';
        $check = ($this->needCheck ?? null) ? 'нужна (' . $this->prices['priceCheck'] . ' ₽)' : 'нет';
        $print = ($this->needPrint ?? null) ? $this->booksCnt . " шт. (" . $this->prices['pricePrint'] . ' ₽)' : 'нет';

        $text = "*Автор:* " . $this->authorName .
            "\n*Страниц:* " . $this->pages . " стр. (" . $this->prices['pricePart'] . ' ₽)' .
            "\n*Промокод:* " . str_replace('_', '', $promocode) .
            "\n*Печать:* " . $print .
            "\n*Проверка:* " . $check .
            "\n\n*ИТОГО:* " . ($this->prices['priceTotal'] + $this->prices['pricePrint'])  . " руб.";

        return $text;
    }

    public function getConfirmText()
    {
        $promocode = ($this->promocode['name'] ?? null) ? $this->promocode['name'] . ' (' . $this->promocode['discount'] . '%)' : 'нет';
        $check = ($this->needCheck ?? null) ? 'нужна (' . $this->prices['priceCheck'] . ' ₽)' : 'нет';
        $print = ($this->needPrint ?? null) ? $this->booksCnt . " шт. (" . $this->prices['pricePrint'] . ' ₽). Адрес: ' . $this->addressJson['string'] : 'нет';

        $text = "<b>Автор:</b> " . $this->authorName .
            "<br><b>Произведения:</b> " . count($this->selectedWorks) . 'шт.' . $this->pages . " стр. (" . $this->prices['pricePart'] . ' ₽)' .
            "<br><b>Промокод:</b> " . str_replace('_', '', $promocode) .
            "<br><b>Печать:</b> " . $print .
            "<br><b>Проверка:</b> " . $check .
            "<br><br><b>ИТОГО:</b> " . ($this->prices['priceTotal'] + $this->prices['pricePrint']) . " руб.";

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

    public function getParticipationStatus()
    {
        $oldIds = collect($this->oldSelectedWorks)->pluck('id')->sort()->values();
        $newIds = collect($this->selectedWorks)->pluck('id')->sort()->values();

        if ($this->participation) {
            $paidAmount = $this->participation->transactions?->where('status', TransactionStatusEnums::CONFIRMED)->sum('amount');
            $currentPriceWithPrint = $this->prices['priceTotal'] + $this->prices['pricePrint'];
            if ($paidAmount == $currentPriceWithPrint || $paidAmount == 0) {
                $isSameAmount = true;
            } else {
                $isSameAmount = false;
            }
        } else {
            $isSameAmount = false;
        }

        $isSameWorks = $oldIds->toArray() === $newIds->toArray();

        $needToNotify = false;
        if (!$isSameWorks) {
            $status = ParticipationStatusEnums::APPROVE_NEEDED;
            $needToNotify = true;
        } elseif (!$isSameAmount) {
            $status = ParticipationStatusEnums::PAYMENT_REQUIRED;
        } else {
            $status = $this->participation['status'];
        }
        return [
            'status' => $status,
            'needToNotify' => $needToNotify
        ];
    }

    /** @noinspection D */
    public function saveApplication()
    {
        if ($this->customValidate()) DB::transaction(function () {
            $newParticipation = Participation::updateOrCreate(
                [
                    'collection_id' => $this->collection['id'],
                    'user_id' => Auth::id(),
                ],
                [
                    'author_name' => $this->authorName,
                    'works_number' => count($this->selectedWorks),
                    'rows' => $this->rows,
                    'pages' => $this->pages,
                    'status' => $this->getParticipationStatus()['status'],
                    'promocode_id' => $this->promocode ? $this->promocode['id'] : null,
                    'price_part' => $this->prices['pricePart'],
                    'price_check' => $this->prices['priceCheck'],
                    'price_total' => $this->prices['priceTotal'],
                ]
            );
            ParticipationWork::where('participation_id', $newParticipation['id'])->delete();
            foreach ($this->selectedWorks as $work) {
                ParticipationWork::create([
                    'participation_id' => $newParticipation['id'],
                    'work_id' => $work['id']
                ]);
            }
            Chat::firstOrCreate([
                'user_created' => Auth::user()->id,
                'model_type' => 'Participation',
                'model_id' => $newParticipation['id'],
            ], [
                'user_to' => 2,
                'title' => str_replace('{collection_title}', $this->collection['title'], self::CHAT_TITLE_PREFIX),
                'status' => ChatStatusEnums::EMPTY,
                'flg_admin_chat' => true,
            ]);
            if ($this->needPrint) {
                $newPrintOrder = PrintOrder::updateOrCreate([
                    'user_id' => Auth::user()->id,
                    'model_type' => 'Collection',
                    'model_id' => $this->collection['id'],
                ],
                    [
                        'status' => PrintOrderStatusEnums::CREATED,
                        'type' => PrintOrderTypeEnums::COLLECTION_PARTICIPATION,
                        'books_cnt' => $this->booksCnt,
                        'inside_color' => self::INSIDE_COLOR,
                        'pages_color' => null,
                        'price_print' => $this->prices['pricePrint'],
                        'cover_type' => self::COVER_TYPE,
                        'receiver_name' => $this->receiverName,
                        'receiver_telephone' => $this->receiverTelephone,
                        'country' => $this->country,
                        'address_type' => $this->addressType,
                        'address_json' => $this->addressJson,
                        'logistic_company_id' => self::LOGISTIC_COMPANY_ID,
                        'printing_company_id' => self::PRINTING_COMPANY_ID
                    ]);
                $newParticipation->update(['print_order_id' => $newPrintOrder['id']]);
            } else {
                if ($this->participation && ($this->participation->printOrder ?? null)) {
                    $this->participation->update([
                        'print_order_id' => null
                    ]);
                    $this->participation->printOrder->delete();
                }
            }

            $url = route('login_as_secondary_admin', ['url_redirect' => EditParticipation::getUrl(['record' => $newParticipation])]);
            if ($this->getParticipationStatus()['needToNotify']) {
                $subject = $this->formType == 'create' ?
                    '💥 *Новая заявка в ' . $this->collection['title_short'] . '!* 💥' . "\n\n" :
                    '💥 *Изменение заявки в ' . $this->collection['title_short'] . '!* 💥' . "\n\n";
                TelegramNotificationJob::dispatch(new ParticipationCreatedNotification($this->collection, $subject, $this->getNotifyText(), $url));
            }


            if ($this->formType == 'create') {
                $alert_text = 'Участие создано! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.';
            } else {
                $alert_text = 'Участие успешно изменено!';
            }


            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alert_text
            ]);

            $this->redirect(route('account.participation.index', $newParticipation['id']), navigate: true);
        });

    }
}
