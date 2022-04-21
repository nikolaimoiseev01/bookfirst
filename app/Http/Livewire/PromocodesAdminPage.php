<?php

namespace App\Http\Livewire;

use App\Models\promocode;
use Livewire\Component;

class PromocodesAdminPage extends Component
{
    Public $promocode = [];
    Public $discount = [];

    Public $new_promocode;
    Public $new_discount;




    public function render()
    {
        return view('livewire.promocodes-admin-page', [
            'promocodes' => $this->promocodes
        ]);
    }

    public function mount() {
        $this->promocodes = promocode::orderby('id')->get();

        foreach (promocode::orderby('id', 'desc')->get()->toArray() as $promocode)
        {
            $this->promocode[$promocode['id']] = $promocode['promocode'];
            $this->discount[$promocode['id']] = $promocode['discount'];
        }
    }


    public function save_promocode($promocode_id)
    {

        if(!($this->promocode[$promocode_id]) || !($this->discount[$promocode_id]))
        {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так',
                'text' => 'Ни одно поле не должно быть пустым!',
            ]);}
        else
        {
            // ---- Редактируем промокод! ---- //
            promocode::where('id', $promocode_id)->update([
                'promocode' => $this->promocode[$promocode_id],
                'discount' => $this->discount[$promocode_id],
            ]);
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Промокод успешно изменен');
            return redirect(route('promocodes_page'));

// ----------------------------------------------------------- //

        }
    }

    public function add_promocode()
    {

        if(!($this->new_promocode) || !($this->new_discount))
        {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так',
                'text' => 'Ни одно поле не должно быть пустым!',
            ]);}
        else
        {
            $add_promo = new promocode();
            $add_promo->promocode = $this->new_promocode;
            $add_promo->discount = ($this->new_discount);
            $add_promo->save();
            session()->flash('success', 'change_printorder');
            session()->flash('alert_type', 'success');
            session()->flash('alert_title', 'Отлично!');
            session()->flash('alert_text', 'Промокод успешно добавлен');
            return redirect(route('promocodes_page'));

// ----------------------------------------------------------- //

        }
    }
}
