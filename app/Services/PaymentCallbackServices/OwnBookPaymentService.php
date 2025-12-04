<?php

namespace App\Services\PaymentCallbackServices;


use App\Enums\AwardTypeEnums;
use App\Enums\OwnBookStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Jobs\TelegramNotificationJob;
use App\Models\Award\Award;
use App\Models\Collection\Participation;
use App\Models\DigitalSale;
use App\Models\OwnBook\OwnBook;
use App\Models\User\User;
use App\Notifications\Collection\PaymentParticipationSuccessNotification;
use App\Notifications\OwnBook\OwnBookPaymentSuccessNotification;
use App\Notifications\OwnBook\OwnBookStatusUpdateNotification;
use App\Notifications\TelegramDefaultNotification;
use App\Services\InnerTasksService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OwnBookPaymentService
{
    private array $yooKassaObject;
    public function __construct(array $yooKassaObject)
    {
        $this->yooKassaObject = $yooKassaObject;
    }

    public function firstPayment() {

        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        Log::info('OwnBookPaymentService started!', $transactionData);
        $ownBook = OwnBook::where('id', $transactionData['own_book_id'])->first();
        $ownBook->update([
            'status_general' => OwnBookStatusEnums::WORK_IN_PROGRESS->value,
            'paid_at_without_print' => Carbon::now(),
            'deadline_inside' => Carbon::now()->addDays(OwnBook::INSIDE_WORK_DAYS),
            'deadline_cover' => Carbon::now()->addDays(OwnBook::COVER_WORK_DAYS)
        ]);
        Award::create([
            'user_id' => $ownBook['user_id'],
            'award_type_id' => AwardTypeEnums::OWN_BOOK_PUBLISHING->id(),
            'model_type' => 'OwnBook',
            'model_id' => $ownBook['id'],
        ]);
        (new InnerTasksService())->update();
        $user = User::where('id', $ownBook['user_id'])->first();
        $user->notify(new OwnBookPaymentSuccessNotification($ownBook, $this->yooKassaObject['amount']['value'], TransactionTypeEnums::OWN_BOOK_WO_PRINT));

    }
    public function firstAuthorPrintPayment() {
        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        Log::info('OwnBookPaymentService started!', $transactionData);
        $ownBook = OwnBook::where('id', $transactionData['own_book_id'])->first();
        $ownBook->update([
            'status_general' => OwnBookStatusEnums::PRINT_WAITING->value,
            'paid_at_print_only' => Carbon::now()
        ]);
        $ownBook->initialPrintOrder->update([
            'status' => PrintOrderStatusEnums::PAID->value,
        ]);
        $user = User::where('id', $ownBook['user_id'])->first();
        $user->notify(new OwnBookPaymentSuccessNotification($ownBook, $this->yooKassaObject['amount']['value'], TransactionTypeEnums::OWN_BOOK_PRINT));
        (new InnerTasksService())->update();
    }

    public function ebookPuchase() {

        $transactionData = json_decode($this->yooKassaObject['metadata']['transaction_data'], true);
        DigitalSale::create([
            'user_id' => $transactionData['user_id'],
            'model_type' => 'OwnBook',
            'model_id' => $transactionData['own_book_id'],
            'price' => $this->yooKassaObject['amount']['value'],
        ]);
        TelegramNotificationJob::dispatch(new TelegramDefaultNotification("💸 Покупка электронной книги 💸", "" ));
    }
}
