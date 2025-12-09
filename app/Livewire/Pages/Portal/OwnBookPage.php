<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\TransactionTypeEnums;
use App\Models\DigitalSale;
use App\Models\OwnBook\OwnBook;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OwnBookPage extends Component
{
    public $ownBook;
    public $info;
    public $tabs;

    public function render()
    {
        return view('livewire.pages.portal.own-book-page');
    }

    public function mount($slug)
    {
        $this->ownBook = OwnBook::where('slug', $slug)->with('user')->with(['media', 'user.media'])->with('printOrders')->first();
        if ($this->ownBook) {
            $this->info = [
                'Кол-во страниц' => $this->ownBook['pages'],
                'Первоначальный тираж' => ($this->ownBook->printOrders[0]['books_cnt'] ?? 0) + 16,
                'Обложка' => $this->ownBook->printOrders[0]['cover_type'] ?? 'Твердая',
                'Внутренний блок' => $this->ownBook->printOrders[0]['inside_type'] ?? 'Черно-белый',
            ];
            $this->tabs = [
                'default' => 'read_part',
                'tabs' => [
                    'read_part' => 'Читать фрагмент'
                ]
            ];
        }
    }

    public function createPayment($amount)
    {
        $user = Auth::user();
        $alreadyHasOwnBook = DigitalSale::query()
            ->where('model_type', 'OwnBook')
            ->where('model_id', $this->ownBook['id'])
            ->where('user_id', $user->id)
            ->exists();

        if(!$alreadyHasOwnBook) {
            $userName = $user->getUserFullName();
            $paymentService = new PaymentService();
            $description = "Покупка электронной книги '{$this->ownBook['title']}' от автора $userName";
            $transactionData = [
                'type' => TransactionTypeEnums::OWN_BOOK_EBOOK_PURCHASE->value,
                'description' => $description,
                'model_type' => 'OwnBook',
                'model_id' => $this->ownBook['id'],
                'data' => [
                    'own_book_id' => $this->ownBook['id'],
                    'user_id' => Auth::user()->id
                ]
            ];
            $urlRedirect = route('account.purchases')  . '?confirm_payment=collection_purchase';
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
