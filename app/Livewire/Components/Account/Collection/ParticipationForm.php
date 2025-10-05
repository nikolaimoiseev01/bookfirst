<?php

namespace App\Livewire\Components\Account\Collection;

use App\Models\Chat\Chat;
use App\Models\Collection\Collection;
use App\Models\Collection\Participation;
use App\Models\Collection\ParticipationWork;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Promocode;
use App\Models\Work\Work;
use App\Services\CalculateParticipationService;
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
    private const ADDRESS_TYPE_ID = 4;
    private const LOGISTIC_COMPANY_ID = 1;
    private const PRINTING_COMPANY_ID = 1;
    private const CHAT_TITLE_PREFIX = 'Личный чат по участию в сборнике {collection_title}';

    public $collection;
    public $participation;
    public $formType;

    public $userWorks;
    public $selectedWorks = [];

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
            $this->prices = [
                'pricePart' => $this->participation['price_part'],
                'pricePrint' => $this->participation['price_print'],
                'priceCheck' => $this->participation['price_check'],
                'priceTotal' => $this->participation['price_total']
            ];
            foreach ($this->participation->works as $participationWork) {
                $this->selectedWorks[] = [
                    'id' => $participationWork->work['id'],
                    'title' => $participationWork->work['title'],
                    'rows' => $participationWork->work['rows'],
                ];
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
                Rule::requiredIf(fn () => $this->needPrint && $this->addressType == 'foreign'),
                'min:1',
            ],
            'addressJson' => Rule::requiredIf(fn() => $this->needPrint),
            'collection' => [
                Rule::unique('participations', 'collection_id')
                    ->where(fn($q) => $q->where('user_id', Auth::user()->id)),
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

    public function updatedNeedPrint()
    {
        $this->updatePrices();
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
    }

    public function updatePrices()
    {
        $this->prices = ((new CalculateParticipationService())->calculate(7, false, 1, true, 20));
    }

    public function storeApp()
    {

    }

    public function checkAndConfirm()
    {
        if ($this->customValidate()) {
            $this->dispatch('swal',
                title: 'Давайте все проверим',
                text: 'Вот такая заявка',
                confirmButtonText: 'Да, все верно',
                livewireMethod: ['saveApplication']
            );
        }
    }

    public function saveApplication()
    {
        if ($this->customValidate()) DB::transaction(function () {
            $rows = collect($this->selectedWorks)->sum('rows');
            $pages = round(ceil($rows / 38));
            $newParticipation = Participation::create([
                'collection_id' => $this->collection['id'],
                'user_id' => Auth::user()->id,
                'author_name' => $this->authorName,
                'works_number' => count($this->selectedWorks),
                'rows' => $rows,
                'pages' => $pages,
                'participation_status_id' => 1,
                'promocode_id' => $this->promocode ? $this->promocode['id'] : null,
                'price_part' => $this->prices['pricePart'],
                'price_print' => $this->prices['pricePrint'],
                'price_check' => $this->prices['priceCheck'],
                'price_send' => $this->prices['priceSend'],
                'price_total' => $this->prices['priceTotal'],
            ]);
            foreach ($this->selectedWorks as $work) {
                ParticipationWork::create([
                    'participation_id' => $newParticipation['id'],
                    'work_id' => $work['id']
                ]);
            }
            Chat::create([
                'user_created' => Auth::user()->id,
                'user_to' => 2,
                'title' => str_replace(self::CHAT_TITLE_PREFIX, '{collection_title}', $this->collection['title']),
                'chat_status_id' => 1,
                'model_type' => 'Participation',
                'model_id' => $newParticipation['id'],
                'flg_admin_chat' => true,
            ]);
            if ($this->needPrint) {
                $newPrintOrder = PrintOrder::create([
                    'user_id' => Auth::user()->id,
                    'print_order_status_id' => 1,
                    'model_type' => 'Participation',
                    'model_id' => $newParticipation['id'],
                    'books_cnt' => $this->booksCnt,
                    'inside_color' => self::INSIDE_COLOR,
                    'pages_color' => null,
                    'price_print' => $this->prices['pricePrint'],
                    'cover_type' => self::COVER_TYPE,
                    'receiver_name' => $this->receiverName,
                    'receiver_telephone' => $this->receiverTelephone,
                    'country' => $this->country,
                    'address_type_id' => self::ADDRESS_TYPE_ID,
                    'address_json' => $this->addressJson,
                    'logistic_company_id' => self::LOGISTIC_COMPANY_ID,
                    'printing_company_id' => self::PRINTING_COMPANY_ID
                ]);
                $newParticipation->update(['print_order_id' => $newPrintOrder['id']]);
            }
            $alert_text = 'Участие создано! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.';
            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alert_text
            ]);

            $this->redirect(route('account.participation.index', $newParticipation['id']), navigate: true);
        });

    }
}
