<?php

namespace App\Filament\Resources\ParticipationResource\Pages;

use App\Filament\Resources\ParticipationResource;
use App\Models\Participation;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Request;

class ParticipationPage extends Page
{
    public $participation;

    protected static string $resource = ParticipationResource::class;

    protected static string $view = 'filament.resources.participation-resource.pages.participation-page';


    protected function getTitle(): string
    {
        return '';
    }

    public function mount($record)
    {
        $this->participation = Participation::where('id', $record)->first();


        $this->form->fill([
            'name' => $this->participation->name
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
        ];
    }
}
