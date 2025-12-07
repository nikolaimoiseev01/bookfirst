<?php

namespace App\Livewire\Pages\Account\OwnBook;

use App\Enums\ChatStatusEnums;
use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\PrintOrderTypeEnums;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Jobs\TelegramNotificationJob;
use App\Models\Chat\Chat;
use App\Models\OwnBook\OwnBook;
use App\Models\OwnBook\OwnBookWork;
use App\Models\PrintOrder\AddressType;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Work\Work;
use App\Notifications\OwnBook\OwnBookCreatedNotification;
use App\Services\InnerTasksService;
use App\Services\PriceCalculation\CalculateOwnBookService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class OwnBookCreatePage extends Component
{
    use WithFilePond;
    use WithCustomValidation;

    public $author;
    public $title;

    public $pages;

    public $insideType = 'Файлом';
    public $commentAuthorInside;
    public $insideFiles = [];
    public $needTextCheck;
    public $needTextDesign;
    public $coverReady = false;
    public $commentAuthorCover;
    public $coverFiles = [];
    public $needPrint = false;
    public $booksCnt = 1;
    public $coverType = 'Мягкая';
    public $insideColor = 'Черно-белый';
    public $pagesColor;

    public $addressType = 'СДЭК';
    public $country = 'Россия';
    public $addressJson;

    public $receiverName;
    public $receiverTelephone;

    public $needPromo;
    public $internalPromoType;

    public $prices;


    public $rusFlg;
    public $isSending;
    public $userWorks;
    public $selectedWorks = [];

    public $minPages = 30;

    private const CHAT_TITLE_PREFIX = 'Чат с менеджером по книге {title}';
    private const LOGISTIC_COMPANY_ID = 1;
    private const PRINTING_COMPANY_ID = 1;

    protected $listeners = ['getAddress', 'saveApplication'];

    public function render()
    {
        return view('livewire.pages.account.own-book.own-book-create-page')->layout('layouts.account');
    }

    public function mount()
    {
        $this->userWorks = Work::where('user_id', Auth::user()->id)
            ->get(['id', 'title', 'rows', 'pages']);
        $this->updatePrices();
    }

    public function updated($property)
    {
        if ($property == 'selectedWorks') {
            $this->pages = collect($this->selectedWorks)->sum('pages');
        }
        if ($property == 'insideColor') {
            $this->pagesColor = 0;
        }
        $this->updatePrices();
    }


    protected function rules(): array
    {
        $rules = [
            'author' => 'required',
            'title' => 'required',
            'pages' => "required|integer|min:" . $this->minPages,
            'commentAuthorCover' => 'required',
            'insideFiles' => Rule::requiredIf(fn() => $this->insideType == 'Файлом'),
            'selectedWorks' => Rule::requiredIf(fn() => $this->insideType == 'Из системы'),
            'coverFiles' => Rule::requiredIf(fn() => $this->coverReady),
            'receiverName' => Rule::requiredIf(fn() => $this->needPrint),
            'receiverTelephone' => Rule::requiredIf(fn() => $this->needPrint),
            'internalPromoType' => Rule::requiredIf(fn() => $this->needPromo),
            'addressJson' => Rule::requiredIf(fn() => $this->needPrint),
            'country' => [
                Rule::requiredIf(fn() => $this->needPrint && $this->addressType == 'foreign'),
                'min:1',
            ],
        ];

        if ($this->needPrint && $this->insideColor == 'Цветной') {
            $rules['pagesColor'] = 'required|integer|min:1|lte:pages';
        }

        return $rules;
    }

    public function getAddress($country, $addressType, $addressJson)
    {
        $this->country = $country;
        $this->addressType = $addressType;
        $this->addressJson = $addressJson;
    }

    protected function messages(): array
    {
        $messages =  [
            'author.required' => 'Имя автора обязательно для заполнения',
            'title.required' => 'Название книги обязательно для заполнения',
            'pages.required' => 'Количество страниц книги обязательно для заполнения',
            'pagesColor.lte' => 'Цветных страниц не может быть больше, чем всего страниц.',
            'pages.min' => 'Минимальное количество страниц: :min',
            'insideFiles.required' => 'Обязательно нужно прикрепить файл внутреннего блогка (иконка скрепки)',
            'selectedWorks.required' => "Нужно добавить произведения к заявке 'Внутренний блок' (кнопка с большим плюсом)",
            'commentAuthorCover.required' => 'Комментарий по обложке обязателен для заполнения',
            'coverFiles.required' => 'Файлы обложкки обязательны, если обложка уже готова',
            'receiverName.required' => 'Имя получателя обязательно для заполнения',
            'receiverTelephone.required' => 'Телефон получателя обязателен для заполнения',
            'internalPromoType.required' => 'Выберите вариант продвижения, или снимите соответствующую галочку',
            'pagesColor.required' => 'Если цветной внутренний блок, введите количество цветных страниц',
            'pagesColor.min' => 'Если цветной внутренний блок, количество цветных страниц должно быть больше нуля',
            'country.required' => 'Страна получателя должна быть заполнена',
            'country.min' => 'Страна получателя должна быть заполнена',
        ];

        if ($this->addressType === 'СДЭК') {
            $messages['addressJson.required'] = 'Пожалуйста, выберите офис сдэк для отправки (кнопка "выбрать" на карте)';
        } else {
            $messages['addressJson.required'] = 'Для международной доставки адрес обязателен';
        }

        return $messages;
    }

    public function logPrices()
    {
        $log = [
            'pages' => $this->pages,
            'pagesColor' => $this->pagesColor,
            'needTextDesign' => $this->needTextDesign,
            'needTextCheck' => $this->needTextCheck,
            'coverReady' => $this->coverReady,
            'needPrint' => $this->needPrint,
            'booksCnt' => $this->booksCnt,
            'coverType' => $this->coverType,
            'internalPromoType' => $this->internalPromoType,
        ];

        $this->js('console.log(' . json_encode($log, JSON_UNESCAPED_UNICODE) . ')');
        $this->js('console.log(' . json_encode($this->prices, JSON_UNESCAPED_UNICODE) . ')');
    }

    public function updatePrices()
    {
        $this->prices = (new CalculateOwnBookService(
            $this->pages,
            $this->pagesColor,
            $this->needTextDesign,
            $this->needTextCheck,
            $this->coverReady,
            $this->needPrint,
            $this->booksCnt,
            $this->coverType,
            $this->internalPromoType,
        ))->calculate();
    }

    public function getConfirmText() {
        $uploadedText = ($this->insideType == 'Файлом') ? 'файлов: ' . count($this->insideFiles) : 'работ: ' . count($this->selectedWorks);
        $designText = ($this->needTextDesign ? 'необходим дизайн (' . $this->prices['priceTextDesign'] . ' руб.); ' : '');
        $checkText = ($this->needTextCheck ? 'необходима проверка (' . $this->prices['priceTextCheck'] . ' руб.); ' : '');
        $insideText = $uploadedText . $designText . $checkText;
        $coverText = $this->coverReady ? 'необходимо создание (1500 руб.).' : 'полностью готова.';
        $printText = ($this->needPrint) ?
            'экземпляров: ' . $this->booksCnt
            . '. Стоимость: ' . $this->prices['pricePrint'] . ' руб'
            . '. Обложка: ' . $this->coverType
            . '. Внутренний блок: ' . $this->insideColor . ($this->pagesColor > 0 ? $this->pagesColor . ' цветных страниц).' : '')
            . "<br><b>Адрес:</b> " . $this->addressJson['string']
            : 'не нужна. ';
        $promoText = $this->needPromo ? 'нужен ' . $this->internalPromoType . ' вариант. (' . $this->prices['pricePromo'] . ' руб.)' : 'не нужно.';
        $fullTotalPrice = $this->prices['priceTotal'] + $this->prices['pricePrint'] ?? 0;
        return "<p><b>Книга:</b> {$this->author}: '{$this->title}'</p>
                <p><b>Загружено {$uploadedText}. </b>(страниц: {$this->pages})</p>
                <p><b>Внутренний блок:</b> {$insideText}</p>
                <p><b>Обложка:</b> {$coverText}</p>
                <p><b>Печать:</b> {$printText}</p>
                <p><b>Продвижение:</b> {$promoText}</p>
                <br><p><b>ИТОГО:</b> {$fullTotalPrice} руб.</p>";

    }

    public function getNotifyText() {
        $coverText = $this->coverReady ? 'готовая от автора' : 'нужно делать';
        $printText = ($this->needPrint) ?
            $this->prices['pricePrint'] . ' руб. ' . $this->booksCnt . ' экз. '
            . $this->coverType
            . '. ВБ: ' . $this->insideColor . ($this->pagesColor > 0 ? $this->pagesColor . ' цветных страниц).' : '')
            : 'не нужна.';
        $fullTotalPrice = $this->prices['priceTotal'] + $this->prices['pricePrint'] ?? 0;
        return "*Автор:* " . $this->author .
            "\n*Название:* " . $this->title .
            "\n*Страниц:* " . $this->pages .
            "\n*Редактура:* " . $this->prices['priceTextDesign'] + $this->prices['priceTextCheck'] . ' руб.' .
            "\n*Обложка:* " . $coverText .
            "\n*Печать:* " . $printText .
            "\n*Промо:* " . $this->prices['pricePromo'] . ' руб.' .
            "\n\n*Выручка:* " . $fullTotalPrice . ' руб.';
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

    /** @noinspection D */
    public function saveApplication()
    {
        if ($this->customValidate()) DB::transaction(function () {
            $newOwnBook = OwnBook::create([
                'user_id' => Auth::user()->id,
                'author' => $this->author,
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'status_general' => OwnBookStatusEnums::REVIEW,
                'status_cover' => OwnBookCoverStatusEnums::DEVELOPMENT,
                'status_inside' => OwnBookInsideStatusEnums::DEVELOPMENT,
                'pages' => $this->pages,
                'inside_type' => $this->insideType,
                'need_text_design' => $this->needTextDesign,
                'need_text_check' => $this->needTextCheck,
                'cover_ready' => $this->coverReady,
                'comment_author_inside' => $this->commentAuthorInside,
                'comment_author_cover' => $this->commentAuthorCover,
                'internal_promo_type' => $this->needPromo ? $this->internalPromoType : null,
                'price_text_design' => $this->prices['priceTextDesign'],
                'price_text_check' => $this->prices['priceTextCheck'],
                'price_inside' => $this->prices['priceInside'],
                'price_cover' => $this->prices['priceCover'],
                'price_promo' => $this->prices['pricePromo'],
                'price_total' => $this->prices['priceTotal'],
            ]);
            foreach ($this->selectedWorks as $work) {
                OwnBookWork::create([
                    'own_book_id' => $newOwnBook['id'],
                    'work_id' => $work['id']
                ]);
            }
            if ($this->insideType == 'Файлом') {
                foreach ($this->insideFiles as $file) {
                    $newOwnBook
                        ->addMedia($file->getRealPath())       // путь до tmp файла
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                        ->toMediaCollection('from_author_inside');   // твоя коллекция
                }
            }
            foreach ($this->coverFiles as $file) {
                $newOwnBook
                    ->addMedia($file->getRealPath())       // путь до tmp файла
                    ->usingName($file->getClientOriginalName())
                    ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                    ->toMediaCollection('from_author_cover');   // твоя коллекция
            }
            Chat::create([
                'user_created' => Auth::user()->id,
                'user_to' => 2,
                'title' => str_replace(self::CHAT_TITLE_PREFIX, '{title}', $this->title),
                'status' => ChatStatusEnums::EMPTY,
                'model_type' => 'OwnBook',
                'model_id' => $newOwnBook['id'],
                'flg_admin_chat' => true,
            ]);
            if ($this->needPrint) {
                $newPrintOrder = PrintOrder::create([
                    'user_id' => Auth::user()->id,
                    'type' => PrintOrderTypeEnums::OWN_BOOK_PUBLISH,
                    'status' => PrintOrderStatusEnums::CREATED,
                    'model_type' => 'OwnBook',
                    'model_id' => $newOwnBook['id'],
                    'books_cnt' => $this->booksCnt,
                    'inside_color' => $this->insideColor,
                    'pages_color' => $this->pagesColor,
                    'cover_type' => $this->coverType,
                    'receiver_name' => $this->receiverName,
                    'receiver_telephone' => $this->receiverTelephone,
                    'country' => $this->country,
                    'address_type' => $this->addressType,
                    'address_json' => $this->addressJson,
                    'price_print' => $this->prices['pricePrint'],
                    'logistic_company_id' => self::LOGISTIC_COMPANY_ID,
                    'printing_company_id' => self::PRINTING_COMPANY_ID
                ]);
                $newOwnBook->update(['print_order_id' => $newPrintOrder['id']]);
            }

            $adminRedirect = route('login_as_secondary_admin', ['url_redirect' => EditOwnBook::getUrl(['record' => $newOwnBook])]);
            $subject = '💥 Новая книга от ' . Auth::user()->name . ' ' . Auth::user()->surname . "!💥" . "\n\n";
            $notification = new OwnBookCreatedNotification($subject, $this->getNotifyText(), $adminRedirect);
            TelegramNotificationJob::dispatch($notification);

            $alert_text = 'Заявка на издание книги создана! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.';
            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alert_text
            ]);
            (new InnerTasksService())->update();
            $this->redirect(route('account.own_book.index', $newOwnBook['id']), navigate: true);
        });
    }
}
