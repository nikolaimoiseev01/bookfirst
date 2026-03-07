<?php

namespace App\Livewire\Pages\Account\PurchasePrints;

use App\Enums\PrintOrderTypeEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\PrintOrder\PrintOrder;
use App\Services\PaymentService;
use Livewire\Component;

class PurchasePrintPage extends Component
{
    public $printOrder;

    public function render()
    {
        return view('livewire.pages.account.purchase-prints.purchase-print-page')->layout('layouts.account');
    }

    public function mount($print_order_id) {
        $this->printOrder = PrintOrder::query()->where('id', $print_order_id)->with('model')->first();
    }

    public function createPayment($amount)
    {
        $paymentService = new PaymentService();
        $description = "Оплата отдельной печати издания '{$this->printOrder->model['title']}' от автора {$this->printOrder->user->getUserFullName()} (print_order_id: {$this->printOrder['id']})";
        $transactionType = match($this->printOrder->type) {
            PrintOrderTypeEnums::OWN_BOOK_ONLY->value => TransactionTypeEnums::OWN_BOOK_ONLY,
            PrintOrderTypeEnums::COLLECTION_ONLY->value => TransactionTypeEnums::COLLECTION_ONLY,
        };
        $transactionData = [
            'type' => $transactionType,
            'description' => $description,
            'model_type' => 'PrintOrder',
            'model_id' => $this->printOrder['id'],
            'data' => [
                'print_order_id' => $this->printOrder['id'],
            ]
        ];
        $urlRedirect = route('account.purchase-print.index', $this->printOrder['id'])  . '?confirm_payment=purchase_print';
        $paymentUrl = $paymentService->createPayment(
            amount: $amount,
            urlRedirect: $urlRedirect,
            transactionData: $transactionData
        );
        $this->redirect($paymentUrl);
    }
}
