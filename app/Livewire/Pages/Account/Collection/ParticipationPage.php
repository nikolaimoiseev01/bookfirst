<?php

namespace App\Livewire\Pages\Account\Collection;

use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\Collection\Participation;
use App\Services\PaymentService;
use Livewire\Component;

class ParticipationPage extends Component
{
    public $participation;
    public $isSending;

    protected $listeners = ['updateParticipationPage' => '$refresh'];
    public function render()
    {
        return view('livewire.pages.account.collection.participation-page')->layout('layouts.account');
    }

    public function mount($participation_id)
    {
        $this->participation = Participation::where('id', $participation_id)
            ->with(['collection', 'chat', 'participationWorks', 'participationWorks.work', 'previewComments'])
            ->first();
    }

    public function createPayment($amount)
    {
        $paymentService = new PaymentService();
        $description = "Оплата участия в сборнике '{$this->participation->collection['title_short']}' от автора {$this->participation['author_name']} (participation_id: {$this->participation['id']})";
        $transactionData = [
            'type' => TransactionTypeEnums::COLLECTION_PARTICIPATION->value,
            'description' => $description,
            'model_type' => 'Participation',
            'model_id' => $this->participation['id'],
            'data' => [
                'participation_id' => $this->participation['id'],
            ]
        ];
        $urlRedirect = route('account.participation.index', $this->participation['id'])  . '?confirm_payment=collection_participation';
        $paymentUrl = $paymentService->createPayment(
            amount: $amount,
            urlRedirect: $urlRedirect,
            transactionData: $transactionData
        );
        $this->redirect($paymentUrl);
    }
}
