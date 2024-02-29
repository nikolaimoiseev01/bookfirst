<?php

namespace App\Http\Livewire\Surveys;

use App\Models\Survey;
use App\Models\Survey_text;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Livewire\Component;

class ColApplication extends Component
{
    public $stars;
    public $step = 1;
    public $participation;
    public $text;

    protected $listeners = ['refreshSurveySmall' => '$refresh'];

    public function render()
    {
        return view('livewire.surveys.col-application');
    }

    public function save_survey()
    {
        DB::transaction(function () { // Чтобы не записать ненужного
            $new_servey = Survey::create([
                'user_id' => $this->participation['user_id'],
                'participation_id' => $this->participation['id'],
                'title' => 'Опрос по созданию заявки'
            ]);

            Survey_text::create([
                'survey_id' => $new_servey['id'],
                'step' => 1,
                'stars' => $this->stars,
                'question' => 'Общая оценка',
                'text' => null
            ]);

            if ($this->stars < 5) {
                Survey_text::create([
                    'survey_id' => $new_servey['id'],
                    'step' => 2,
                    'stars' => null,
                    'question' => 'В чем проблема?',
                    'text' => $this->text
                ]);
            }

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Спасибо!',
                'text' => 'Вы помогаете нам стать лучше :)'
            ]);

            $this->emit('refreshSurveySmall');
        });
    }

    public function after_first_step()
    {
        if (!$this->stars) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ошибка',
                'text' => 'Выберите оценку, кликнув на звездочку'
            ]);
        } elseif (intval($this->stars < 5)) {
            $this->step = 2;
        } else {
            $this->save_survey();
        }
    }


    public function step_back()
    {
        $this->step = 1;
    }
}
