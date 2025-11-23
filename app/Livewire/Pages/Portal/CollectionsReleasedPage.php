<?php

namespace App\Livewire\Pages\Portal;

use App\Enums\CollectionStatusEnums;
use App\Enums\TransactionTypeEnums;
use App\Models\Collection\Collection;
use App\Models\DigitalSale;
use App\Models\OwnBook\OwnBook;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class CollectionsReleasedPage extends Component
{
    public $collections;

    public $take = 10;
    public $moreCnt = 10;
    #[Url]
    public $searchText;
    public $totalCnt;


    public function render()
    {
        return view('livewire.pages.portal.collections-released-page');
    }

    public function resetCollections()
    {
        $this->totalCnt = Collection::query()
            ->where('title', 'like', '%' . $this->searchText . '%')
            ->where('status', '<>', CollectionStatusEnums::APPS_IN_PROGRESS)
            ->count();
        $this->take = min($this->totalCnt, $this->take);
        $this->collections = Collection::query()
            ->where('status', '<>', CollectionStatusEnums::APPS_IN_PROGRESS)
            ->where('title', 'like', "%{$this->searchText}%")
            ->orderBy('created_at', 'desc')
            ->take($this->take)
            ->with(['media'])
            ->get();
    }

    public function mount()
    {
        $this->resetCollections();
    }

    public function loadMore()
    {
        $this->take += $this->moreCnt;
        $this->resetCollections();
    }

    public function search()
    {
        $this->take = 10;
        $this->resetCollections();
    }

    public function clearSearch()
    {
        $this->searchText = null;
        $this->take = 10;
        $this->resetCollections();
    }

    public function createPayment($id, $amount)
    {
        $collection = Collection::query()->where('id', $id)->first();
        $user = Auth::user();
        $alreadyHasCollection = DigitalSale::query()
            ->where('model_type', 'Collection')
            ->where('model_id', $id)
            ->where('user_id', $user->id)
            ->exists();

        if(!$alreadyHasCollection) {
            $userName = $user->getUserFullName();
            $paymentService = new PaymentService();
            $description = "Покупка электронного сборника '{$collection['title_short']}' от автора $userName";
            $transactionData = [
                'type' => TransactionTypeEnums::COLLECTION_EBOOK_PURCHASE->value,
                'description' => $description,
                'model_type' => 'Collection',
                'model_id' => $collection['id'],
                'data' => [
                    'collection_id' => $collection['id'],
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
