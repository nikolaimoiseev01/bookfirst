<?php

namespace App\Http\Livewire;

use App\Models\Donate;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MakeDonateForm extends Component
{
    public $user_to;
    public $user_to_wallet;

    public function render()
    {
        $this->user_from_wallet = Auth::user()->UserWallet;
        return view('livewire.make-donate-form', [
            'user' => $this->user_to,
            'user_wallet' => $this->user_from_wallet,
        ]);
    }

    public function mount($user_to)
    {
        $this->user_to = $user_to;
    }


    public function make_donate($formData)
    {

        $user_from = User::where('id', Auth::user()->id)->first();
        $user_to = User::where('id', $this->user_to['id'])->first();
        $cur_user_from_amount = intval($user_from->UserWallet['cur_amount']);
        $cur_user_to_amount = intval($user_to->UserWallet['cur_amount']);
        $amount_to_transfer = intval($formData['amount']);

        // --------- Ищем ошибки в заполнении  --------- //
        $errors_array = [];

        if ($amount_to_transfer === 0 || $amount_to_transfer === null || $amount_to_transfer === '') {
            array_push($errors_array, 'Введите сумму!');
        }


        if ($amount_to_transfer > $cur_user_from_amount) {
            array_push($errors_array, 'Для совершения такого перевода на Вашем аккаунте не хватает ' . (intval($amount_to_transfer) - intval($cur_user_from_amount)) . ' руб.!');
        }

        if ($amount_to_transfer < 0) {
            array_push($errors_array, 'Нельзя переводить отрицательные суммы!');
        }

        if (!empty($errors_array)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => implode("<br>", $errors_array),
            ]);
        }

        // --------- //Ищем ошибки в заполнении  --------- //


        if (empty($errors_array)) {
            UserWallet::where('user_id', $user_from['id'])->update(array('cur_amount' => $cur_user_from_amount - $amount_to_transfer));
            UserWallet::where('user_id', $user_to['id'])->update(array('cur_amount' => $cur_user_to_amount + $amount_to_transfer));

            $this->user_to_wallet = Auth::user()->UserWallet;

            $new_donate = new Donate();
            $new_donate->user_id_from = $user_from['id'];
            $new_donate->user_id_to = $user_to['id'];
            $new_donate->amount = $amount_to_transfer;
            $new_donate->save();

            $this->user_from_wallet = Auth::user()->UserWallet;



            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Выполнено!',
                'text' => 'Вы успешно перевели ' . $amount_to_transfer . ' руб.!',
            ]);

            $this->dispatchBrowserEvent('close_form');

            $this->emit('refreshComponent');

        }
    }
}
