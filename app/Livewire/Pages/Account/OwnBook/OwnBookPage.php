<?php

namespace App\Livewire\Pages\Account\OwnBook;

use App\Enums\TransactionTypeEnums;
use App\Models\OwnBook\OwnBook;
use App\Services\PaymentService;
use Livewire\Component;

class OwnBookPage extends Component
{
    public $ownBook;

    protected $listeners = ['updateOwnBookPage' => '$refresh'];
    public function render()
    {
        return view('livewire.pages.account.own-book.own-book-page')->layout('layouts.account');
    }

    public function mount($own_book_id) {
        $this->ownBook = OwnBook::where('id', $own_book_id)->with('chat', 'works', 'media', 'ownBookStatus', 'ownBookCoverStatus', 'ownBookInsideStatus')->first();
    }

    public function createPayment($amount, $type)
    {
        $typeRus = match($type) {
            'firstPayment' => 'издания',
            'printOnly' => 'печати',
        };
        $transactionType = match($type) {
            'firstPayment' => TransactionTypeEnums::OWN_BOOK_WO_PRINT,
            'printOnly' => TransactionTypeEnums::OWN_BOOK_PRINT,
        };
        $urlRedirectType = match($type) {
            'firstPayment' => 'own_book_without_print',
            'printOnly' => 'own_book_print_only',
        };
        $urlRedirect = route('account.own_book.index', $this->ownBook['id'])  . "?confirm_payment={$urlRedirectType}";
        $description = "Оплата {$typeRus} книги '{$this->ownBook['title']}' от автора {$this->ownBook->user->getUserFullName()} (own_book_id: {$this->ownBook['id']})";
        $transactionData = [
            'type' => $transactionType,
            'description' => $description,
            'model_type' => 'OwnBook',
            'model_id' => $this->ownBook['id'],
            'data' => [
                'own_book_id' => $this->ownBook['id'],
            ]
        ];

        $paymentService = new PaymentService();
        $paymentUrl = $paymentService->createPayment(
            amount: $amount,
            urlRedirect: $urlRedirect,
            transactionData: $transactionData
        );
        $this->redirect($paymentUrl);
    }
}
