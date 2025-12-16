<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\Collection\Collection;
use App\Models\DigitalSale;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CollectionPage extends Component
{
    public $collection;
    public $info;
    public $process;
    public $dates;

    public $tabs;

    public function render()
    {
        return view('livewire.pages.portal.collection-page');
    }

    public function mount($slug)
    {
        $this->collection = Collection::where('slug', $slug)->with('media')->first();
        $this->info = [
            'Статус сборника' => 'Идет прием заявок',
            'Тираж сборника' => '~ 100 экземпляров',
            'Обложка' => 'Мягкая, цветная'
        ];

        if ($this->collection['status'] == CollectionStatusEnums::APPS_IN_PROGRESS) {
            $this->info['Фонд конкурса*'] = '5000 руб.!';
            $this->tabs = [
                'default' => 'process',
                'tabs' => [
                    'process' => 'Порядок участия',
                    'calculator' => 'Калькулятор',
                    'dates' => 'Даты издания',
                    'free_participation' => 'Бесплатное участие*'
                ]
            ];
        } else {
            $this->tabs = [
                'default' => 'dates',
                'tabs' => [
                    'dates' => 'Даты издания',
                ]
            ];
            if ($this->collection->getFirstMediaUrl('inside_file_preview')) {
                $this->tabs['tabs']['read_part'] = 'Читать фрагмент';
            }
        }

        $collectionDates = [
            'date_apps_end' => Carbon::parse($this->collection['date_apps_end'])->translatedFormat('j F'),
            'date_preview_start' => Carbon::parse($this->collection['date_preview_start'])->translatedFormat('j F'),
            'date_preview_end' => Carbon::parse($this->collection['date_preview_end'])->translatedFormat('j F'),
            'date_print_start' => Carbon::parse($this->collection['date_print_start'])->translatedFormat('j F'),
            'date_print_end' => Carbon::parse($this->collection['date_print_end'])->translatedFormat('j F'),
        ];
        $this->process = [
            [
                'title' => 'Шаг 1. Страница участия',
                'text' => '
                    Сразу после заполнения заявки, вы будете перенаправлены на отдельную страницу конкретно
                    вашего участия в личном кабинете.
                    Это главная страница участия, на ней вы сможете отслеживать весь процесс издания.
                    Так же на ней будет доступен чат с поддержкой на случай каких-либо вопросов.
                '
            ],
            [
                'title' => 'Шаг 2. Ожидание подтверждения',
                'text' => '
                    После того, как заявка была отправлена, произведения проходят цензуру.
                    В них не должно быть призывов к терроризму или иного запрещенного контента.
                    Сразу после нашего подтверждения заявки, вы получите оповещения (Email в том числе)
                    о необходимости оплаты.
                '
            ],
            [
                'title' => 'Шаг 3. Оплата участия',
                'text' => '
                    После нашей проверки на странице участия в личном кабинете будет доступна форма оплаты.
                    Ее можно будет произвести через одну из многочисленных платежных систем.
                    Если у вас нет счета в банке РФ, можно сделать прямой перевод по нашим иностранным реквизитам (банки Европы и Казахстана).

                '
            ],
            [
                'title' => 'Шаг 4. Предварительная проверка',
                'text' => "
                    {$collectionDates['date_preview_start']} на странице вашего участия в личном
                    кабинете будет открыт блок предварительной проверки.
                    В этом блоке можно будет скачать PDF файл сборника и указать необходимые изменения в
                    специальной форме.
                    Как только исправление будет учтено, вы будете об этом оповещены.
                "
            ],
            [
                'title' => 'Шаг 5. Получение печатного экземпляра',
                'text' => "
                    <p>Если вы заказывали печатные экземпляры, то {$collectionDates['date_print_end']} на странице участия будет доступна ссылка для отслеживания.
                    <span class='text-green-500'>При получении заказа оплачивается фактическая стоимость пересылки.</span>
                    Мы отправляем сборники через СДЭК, но при необходимости готовы
                    использовать любую другую транспортную компанию.</p>
                "
            ],
        ];
        $this->dates = [
            [
                'date' => $collectionDates['date_apps_end'],
                'desc' => 'Конец приема заявок через личный кабинет',
                'tooltip' => 'Прием заявок заканчивается в 23:59 МСК указанного дня'
            ],
            [
                'date' => $collectionDates['date_preview_start'],
                'desc' => 'Отправка предварительного варианта сборника',
                'tooltip' => 'До 23:59 МСК указанного дня в вашем личном кабинете будет доступно скачивание предварительного экземпляра сборника, а также форма указания исправлений. В эту дату также открывается голосование за лучшего автора в сборнике (конкурс).'
            ],
            [
                'date' => $collectionDates['date_preview_end'],
                'desc' => 'Конец предварительной проверки',
                'tooltip' => 'После окончания предварительной проверки исправленный макет снова загружается в систему (без возможности внесения изменений). С этого момента до начала печати победители конкурса присылают информацию о себе'
            ],
            [
                'date' => $collectionDates['date_print_start'],
                'desc' => 'Отправка сборника в печать'
            ],
            [
                'date' => $collectionDates['date_print_end'],
                'desc' => 'Отправка экземпляров авторам',
                'tooltip' => 'После отправки печатных экземпляров в вашем личном кабинете будет доступна ссылка для отслеживания посылки.'
            ]
        ];
    }

    public function createPayment($amount)
    {
        $user = Auth::user();
        $alreadyHasCollection = DigitalSale::query()
            ->where('model_type', 'Collection')
            ->where('model_id', $this->collection['id'])
            ->where('user_id', $user->id)
            ->exists();

        if (!$alreadyHasCollection) {
            $userName = $user->getUserFullName();
            $paymentService = new PaymentService();
            $description = "Покупка электронного сборника '{$this->collection['title_short']}' от автора $userName";
            $transactionData = [
                'type' => TransactionTypeEnums::COLLECTION_EBOOK_PURCHASE->value,
                'description' => $description,
                'model_type' => 'Collection',
                'model_id' => $this->collection['id'],
                'data' => [
                    'collection_id' => $this->collection['id'],
                    'user_id' => Auth::user()->id
                ]
            ];
            $urlRedirect = route('account.purchases') . '?confirm_payment=collection_purchase';
            $paymentUrl = $paymentService->createPayment(
                amount: $amount,
                urlRedirect: $urlRedirect,
                transactionData: $transactionData
            );
            $this->redirect($paymentUrl);
        } else {
            $this->dispatch('swal', type: 'success', text: 'У вас уже куплена эта книга');
        }
    }
}
