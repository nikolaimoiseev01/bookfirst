<?php

namespace App\Livewire\Components\Account;

use App\Models\Cdek\CdekCity;
use App\Models\Cdek\CdekRegion;
use App\Models\Work\WorkType;
use Livewire\Component;

class AddressChoose extends Component
{
    public $citySearch;
    public $fastCities;
    public $cityResults;

    public $country;
    public $addressJson = [];
    public $addressType = 'СДЭК';

    public function render()
    {
        return view('livewire.components.account.address-choose');
    }

    public function mount()
    {
    }

    public function updated($property)
    {
        if ($property == 'country' || $property == 'addressJson' || $property == 'addressType') {
            $this->dispatch('getAddress',
                country: $this->country,
                addressType: $this->addressType,
                addressJson: $this->addressJson
            );
        }
    }

    public function updatedcitySearch()
    {
        $this->cityResults = CdekCity::query()
            ->where('city', 'like', '%' . $this->citySearch . '%')
            ->get();
    }

    public function test()
    {
        dd($this->country);
    }
}
