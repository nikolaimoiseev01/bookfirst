<?php

namespace App\Livewire\Pages\Account\OwnBook;

use App\Models\Chat\Chat;
use App\Models\Collection\ParticipationWork;
use App\Models\OwnBook\OwnBook;
use App\Models\OwnBook\OwnBookWork;
use App\Models\PrintOrder\PrintOrder;
use App\Models\Work\Work;
use App\Services\CalculateOwnBookService;
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

    public $receiverCountry;
    public $address_type_id;
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
        return [
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
            'pagesColor' => Rule::requiredIf(fn() => $this->insideColor == 'Цветной'),
        ];
    }

    protected function messages(): array
    {
        return [
            'author.required' => 'Имя автора обязательно для заполнения',
            'title.required' => 'Название книги обязательно для заполнения',
            'pages.required' => 'Количество страниц книги обязательно для заполнения',
            'pages.min' => 'Минимальное количество страниц: :min',
            'insideFiles.required' => 'Обязательно нужно прикрепить файл внутреннего блогка (иконка скрепки)',
            'selectedWorks.required' => "Нужно добавить произведения к заявке 'Внутренний блок' (кнопка с большим плюсом)",
            'commentAuthorCover.required' => 'Комментарий по обложке обязателен для заполнения',
            'coverFiles.required' => 'Файлы обложкки обязательны, если обложка уже готова',
            'receiverName.required' => 'Имя получателя обязательно для заполнения',
            'receiverTelephone.required' => 'Телефон получателя обязателен для заполнения',
            'internalPromoType.required' => 'Выберите вариант продвижения, или снимите соответствующую галочку',
            'pagesColor.required' => 'Если цветной внутренний блок, введите количество цветных страниц'
        ];
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

    public function saveApplication()
    {
        if ($this->customValidate()) DB::transaction(function () {
            $new_own_book = OwnBook::create([
                'user_id' => Auth::user()->id,
                'author' => $this->author,
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'own_book_status_id' => 1,
                'own_book_cover_status_id' => 1,
                'own_book_inside_status_id' => 1,
                'pages' => $this->pages,
                'inside_type' => $this->insideType,
                'need_text_design' => $this->needTextDesign,
                'need_text_check' => $this->needTextCheck,
                'cover_ready' => $this->coverReady,
                'comment_author_inside' => $this->commentAuthorInside,
                'comment_author_cover' => $this->commentAuthorCover,
                'internal_promo_type' => $this->needPromo ? $this->internalPromoType : null,
                'price_text_design' => $this->prices['priceTextDesign'],
                'price_text_check' => $this->prices['priceTextDesign'],
                'price_cover' => $this->prices['priceTextDesign'],
                'price_promo' => $this->prices['priceTextDesign'],
                'price_total' => $this->prices['priceTotal'],
            ]);
            foreach ($this->selectedWorks as $work) {
                OwnBookWork::create([
                    'own_book_id' => $new_own_book['id'],
                    'work_id' => $work['id']
                ]);
            }
            if ($this->insideType == 'Файлом') {
                foreach ($this->insideFiles as $file) {
                    $new_own_book
                        ->addMedia($file->getRealPath())       // путь до tmp файла
                        ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                        ->toMediaCollection('insideFiles');   // твоя коллекция
                }
            }
            foreach ($this->coverFiles as $file) {
                $new_own_book
                    ->addMedia($file->getRealPath())       // путь до tmp файла
                    ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                    ->toMediaCollection('coverFiles');   // твоя коллекция
            }
            Chat::create([
                'user_created' => Auth::user()->id,
                'user_to' => 2,
                'title' => str_replace(self::CHAT_TITLE_PREFIX, '{title}', $this->title),
                'chat_status_id' => 1,
                'model_type' => 'OwnBook',
                'model_id' => $new_own_book['id'],
                'flg_admin_chat' => true,
            ]);
            if ($this->needPrint) {
                $newPrintOrder = PrintOrder::create([
                    'user_id' => Auth::user()->id,
                    'print_order_status_id' => 1,
                    'model_type' => 'OwnBook',
                    'model_id' => $new_own_book['id'],
                    'books_cnt' => $this->booksCnt,
                    'inside_color' => $this->insideColor,
                    'pages_color' => $this->pagesColor,
                    'cover_type' => $this->coverType,
                    'receiver_name' => $this->receiverName,
                    'receiver_telephone' => $this->receiverTelephone,
                    'country' => $this->receiverCountry,
                    'address_type_id' => $this->address_type_id,
                    'address_json' => $this->addressJson,
                    'logistic_company_id' => self::LOGISTIC_COMPANY_ID,
                    'printing_company_id' => self::PRINTING_COMPANY_ID
                ]);
                $new_own_book->update(['print_order_id' => $newPrintOrder['id']]);
            }

            $alert_text = 'Заявка на издание книги создана! На этой странице вы можете следить за всей информацией. Чат с личным менеджером тоже здесь.';
            session()->flash('swal', [
                'title' => 'Успешно!',
                'type' => 'success',
                'text' => $alert_text
            ]);

            $this->redirect(route('account.own_book.index', $new_own_book['id']), navigate: true);
        });
    }
}
