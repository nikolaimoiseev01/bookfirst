<?php

namespace App\Livewire\Components\Account;

use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\Pages\EditOwnBook;
use App\Jobs\PdfCutJob;
use App\Jobs\TelegramNotificationJob;
use App\Models\OwnBook\OwnBook;
use App\Models\PreviewComment;
use App\Notifications\TelegramDefaultNotification;
use App\Traits\WithCustomValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PreviewComments extends Component
{
    use WithCustomValidation;

    public $comments;
    public $modelId;
    public $modelType;
    public $commentType;
    public $commentTypeRus;

    public $text;
    public $page;
    public $isSending;
    public $disabled;
    public $ownBook;

    public function render()
    {
        $this->comments = PreviewComment::where('model_type', $this->modelType)->where('model_id', $this->modelId)->where('comment_type', $this->commentType)->get();

        return view('livewire.components.account.preview-comments');
    }

    public function mount($modelType, $modelId, $commentType, $disabled = false)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->commentType = $commentType;
        $this->disabled = $disabled;
        $this->commentTypeRus = $this->commentType == 'inside' ? 'внутреннему блоку' : 'обложке';
        $this->ownBook = OwnBook::where('id', $this->modelId)->first();
    }

    protected function rules(): array
    {
        return ['text' => 'required', 'page' => Rule::requiredIf(fn() => $this->commentType == 'inside'), // ~10MB
        ];
    }

    protected function messages(): array
    {
        return ['text.required' => 'Текст сообщения обязателен для заполнения', 'page.required' => 'Страница обязательна для заполнения'];
    }

    public function sendMessage()
    {
        if ($this->customValidate()) {
            DB::transaction(function () {
                PreviewComment::create(['user_id' => Auth::user()->id, 'model_type' => $this->modelType, 'model_id' => $this->modelId, 'comment_type' => $this->commentType, 'page' => $this->page, 'text' => $this->text, 'flg_done' => false]);
                $this->text = null;
                $this->page = null;
            });
        }
        $this->isSending = false;
    }

    public function sendCorrectionNotification($correctDeadline)
    {
        $correctDeadlineRus = formatDate($correctDeadline, 'j F');
        $text = "*Книга:* " . $this->ownBook->author . ': "' . $this->ownBook->title . '"' .
            "\n*Деадлайн на исправление:* {$correctDeadlineRus}";
        $url = route('login_as_admin', ['url_redirect' => EditOwnBook::getUrl(['record' => $this->ownBook])]);
        Notification::route('telegram', getTelegramChatId())
            ->notify(new TelegramDefaultNotification(
                "✍ Автор послал исправления по {$this->commentTypeRus}! ✍",
                $text,
                $url
            ));
    }

    public function sendApproverdNotification()
    {
        $text = "*Книга:* " . $this->ownBook->author . ': "' . $this->ownBook->title;
        $url = route('login_as_admin', ['url_redirect' => EditOwnBook::getUrl(['record' => $this->ownBook])]);
        $notification = new TelegramDefaultNotification(
            "✅ Автор утвердил работу по {$this->commentTypeRus}! ✅",
            $text,
            $url
        );
        TelegramNotificationJob::dispatch($notification);
    }

    public function sendToCorrect()
    {
        $correctDeadline = Carbon::now()->addDays(4);
        $this->ownBook->update([
            "status_{$this->commentType}" => $this->commentType == 'inside' ? OwnBookInsideStatusEnums::CORRECTIONS : OwnBookCoverStatusEnums::CORRECTIONS,
            "deadline_{$this->commentType}" => $correctDeadline
        ]);
        $this->sendCorrectionNotification($correctDeadline);
        $this->dispatch('updateOwnBookPage');
        $this->dispatch('swal', type: 'success', text: "Исправления приняты! В течение 4 дней здесь появится обновленная версия.");
    }

    /** @noinspection D */
    public function approve()
    {
        DB::transaction(function (): void {
            $this->ownBook->update([
                "status_{$this->commentType}" => $this->commentType == 'inside' ? OwnBookInsideStatusEnums::READY_FOR_PUBLICATION : OwnBookCoverStatusEnums::READY_FOR_PUBLICATION
            ]);
            if ($this->ownBook['status_cover'] == OwnBookCoverStatusEnums::READY_FOR_PUBLICATION && $this->ownBook['status_inside'] == OwnBookInsideStatusEnums::READY_FOR_PUBLICATION) {
                $text = 'Поздравляем! Внутренний блок и обложка были утверждены! ' . ($this->ownBook->initialPrintOrder ? 'Далее, чтобы продолжить, в блоке "Печать" необходимо оплатить финальную стоимость печати.' : 'Так как печать не требуется, мы поздравляем Вас с окончанием процесса издания!');
                $this->ownBook->update(['status_general' => $this->ownBook->initialPrintOrder ? OwnBookStatusEnums::PRINT_PAYMENT_REQUIRED : OwnBookStatusEnums::DONE]);
            } else {
                $text = 'Отлично!' . $this->commentType == 'inside' ? 'Внутренний блок утвержден.' : 'Обложка утверждена.' . ' Как только будут утверждены и ВБ и обложка, можно будет приступить к печати.';
            }
            PdfCutJob::dispatch(
                $this->ownBook,
                $this->ownBook->getFirstMediaPath('inside_file'),
                10,
                'inside_file_preview'
            );
            $this->sendApproverdNotification();
            $this->dispatch('updateOwnBookPage');
            $this->dispatch('swal', type: 'success', text: $text);
        });

    }

}
