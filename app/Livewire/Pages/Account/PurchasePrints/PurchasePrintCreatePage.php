<?php

namespace App\Livewire\Pages\Account\PurchasePrints;

use App\Enums\ChatStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Enums\TransactionStatusEnums;
use App\Filament\Resources\Collection\Participations\Pages\EditParticipation;
use App\Filament\Resources\PrintOrder\PrintOrders\Pages\EditPrintOrder;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Chat;
use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use App\Models\PrintOrder\PrintOrder;
use App\Notifications\Collection\ParticipationCreatedNotification;
use App\Notifications\TelegramDefaultNotification;
use App\Rules\ParticipationLessPrice;
use App\Services\PriceCalculation\CalculateOwnBookService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PurchasePrintCreatePage extends Component
{
    use WithCustomValidation;

    private const COLLECTION_INSIDE_COLOR = 'Черно-белый';
    private const COLLECTION_COVER_TYPE = 'Мягкая';
    private const LOGISTIC_COMPANY_ID = 2;
    private const PRINTING_COMPANY_ID = 3;
    private const CHAT_TITLE_PREFIX = 'Личный чат по отдельной печати {type}: {title}';

    public $type;
    public $model;

    public $formType = 'create';

    public $showChosenAddress = false;

    public $pages;
    public $booksCnt = 4;
    public $receiverName;
    public $receiverTelephone;
    public $country = 'Россия';
    public $addressType = 'СДЭК';
    public $addressJson;
    public $coverType = self::COLLECTION_COVER_TYPE;
    public $insideColor = self::COLLECTION_INSIDE_COLOR;
    public $pagesColor;

    public $pricePrint = 100;

    protected $listeners = ['getAddress', 'saveApplication'];

    public function render()
    {
        return view('livewire.pages.account.purchase-prints.purchase-print-create-page')->layout('layouts.account');
    }

    public function mount($type, $id)
    {
        $this->type = match ($type) {
            'collection' => 'Collection',
            'own-book' => 'OwnBook',
        };

        $this->model = match ($this->type) {
            'Collection' => Collection::where('id', $id)->first(),
            'OwnBook' => OwnBook::where('id', $id)->first(),
        };

        if ($this->type === 'OwnBook') {
            $this->pages = $this->model['pages'];
            $this->pagesColor = $this->model->initialPrintOrder['pages_color'] ?? 0;
            $this->insideColor = $this->model->initialPrintOrder['inside_color'] ?? 0;
        }

        $this->updatePrice();
    }

    protected function rules(): array
    {
        $rules = [
            'model' => [
                Rule::unique('print_orders', 'model_id')
                    ->where(fn ($q) => $q
                        ->where('model_type', $this->type)
                        ->where('user_id', Auth::id())
                        ->whereNot('status', PrintOrderStatusEnums::SENT)
                    )
            ],
            'booksCnt' => 'required|integer|min:4',
            'receiverName' => 'required',
            'receiverTelephone' => 'required',
            'country' => [
                Rule::requiredIf(fn() => $this->addressType == 'foreign'),
                'min:1',
            ],
            'addressJson' => 'required',
        ];

        if ($this->insideColor == 'Цветной') {
            $rules['pagesColor'] = 'required|integer|min:1|lte:pages';
        }

        return $rules;
    }

    protected function messages(): array
    {
        $messages = [
            'receiverName.required' => 'Имя получателя обязательно для заполнения',
            'receiverTelephone.required' => 'Телефон получателя обязателен для заполнения',
            'country.required' => 'Страна получателя должна быть заполнена',
            'country.min' => 'Страна получателя должна быть заполнена',
            'booksCnt.min' => 'Минимальное количество экземпляров: :min',
            'model.unique' => 'У вас уже есть заказ печати на это издание!',
            'pagesColor.required' => 'Если цветной внутренний блок, введите количество цветных страниц',
            'pagesColor.min' => 'Если цветной внутренний блок, количество цветных страниц должно быть больше нуля',
            'pagesColor.lte' => 'Цветных страниц не может быть больше, чем всего страниц.',
        ];

        if ($this->addressType === 'СДЭК') {
            $messages['addressJson.required'] = 'Пожалуйста, выберите офис сдэк для отправки (кнопка "выбрать" на карте)';
        } else {
            $messages['addressJson.required'] = 'Для международной доставки адрес обязателен';
        }

        return $messages;
    }

    public function getAddress($country, $addressType, $addressJson)
    {
        $this->country = $country;
        $this->addressType = $addressType;
        $this->addressJson = $addressJson;
    }

    public function collectionPrintPrice() {
        if ($this->booksCnt <= 5) {
            $printsDiscount = 1;
        } else if ($this->booksCnt <= 10) {
            $printsDiscount = 0.95;
        } else if ($this->booksCnt <= 20) {
            $printsDiscount = 0.90;
        } else {
            $printsDiscount = 0.85;
        }
        return ($this->booksCnt * 400) * $printsDiscount;
    }

    public function updatePrice()
    {
        if ($this->type === 'Collection') {
            $this->pricePrint = $this->collectionPrintPrice();
        } else {
            $this->pricePrint = ((new CalculateOwnBookService(
                pages: $this->pages,
            )
            )->calculatePrintPrice(
                pagesColor: $this->pagesColor,
                booksCnt: $this->booksCnt,
                coverType: $this->coverType
            ));
        }
    }

    public function updated($property)
    {
        $this->updatePrice();
    }

    public function getConfirmText()
    {
        $text =
            "<b>Издание:</b> " . $this->model->title .
            "<br><b>Экземпляров:</b> " . $this->booksCnt .
            "<br><b>Адрес:</b> " . $this->addressJson['string'] .
            "<br><b>Получатель:</b> " . $this->receiverName .
            "<br><b>Телефон для связи:</b> " . $this->receiverTelephone .
            "<br><br><b>ИТОГО:</b> " . $this->pricePrint . " руб.";

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

    function saveApplication()
    {
        if ($this->customValidate()) DB::transaction(function () {
            $newPrintOrder = PrintOrder::updateOrCreate([
                'user_id' => Auth::user()->id,
                'model_type' => $this->type,
                'model_id' => $this->model['id'],
            ],
                [
                    'status' => PrintOrderStatusEnums::CREATED,
                    'type' => $this->type == 'Collection' ? PrintOrderTypeEnums::COLLECTION_ONLY : PrintOrderTypeEnums::OWN_BOOK_ONLY,
                    'books_cnt' => $this->booksCnt,
                    'inside_color' => $this->insideColor,
                    'pages_color' => $this->pagesColor,
                    'price_print' => $this->pricePrint,
                    'cover_type' => $this->coverType,
                    'receiver_name' => $this->receiverName,
                    'receiver_telephone' => $this->receiverTelephone,
                    'country' => $this->country,
                    'address_type' => $this->addressType,
                    'address_json' => $this->addressJson,
                    'logistic_company_id' => self::LOGISTIC_COMPANY_ID,
                    'printing_company_id' => self::PRINTING_COMPANY_ID
                ]);

            $chatTitle = strtr(self::CHAT_TITLE_PREFIX, [
                '{type}' => $this->type == 'Collection' ? 'сборника' : 'книги',
                '{title}' => $this->model->title,
            ]);

            Chat::firstOrCreate([
                'user_created' => Auth::user()->id,
                'model_type' => 'PrintOrder',
                'model_id' => $newPrintOrder['id'],
            ], [
                'user_to' => 2,
                'title' => $chatTitle,
                'status' => ChatStatusEnums::EMPTY,
                'flg_admin_chat' => true,
            ]);


            $subject = "💥 Новая заявка на отдельную печать: {$this->model['title']}! 💥";
            $userFromName = Auth::user()->getUserFullName();
            $text = "*Автор:* {$userFromName} \n*Количество:* {$this->booksCnt} \n*Цена:* {$this->pricePrint} руб.";
            $url = route('login_as_secondary_admin', ['url_redirect' => EditPrintOrder::getUrl(['record' => $newPrintOrder])]);
            $notification = new TelegramDefaultNotification($subject, $text, $url);
            TelegramNotificationJob::dispatch($notification);


            if ($this->formType == 'create') {
                $alert_text = 'Заявка на печать успешно создана! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.';
            } else {
                $alert_text = 'Заявка на печать успешно изменена!';
            }


            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alert_text
            ]);

            $this->redirect(route('account.purchase-print.index', $newPrintOrder['id']), navigate: true);
        });
    }

}
