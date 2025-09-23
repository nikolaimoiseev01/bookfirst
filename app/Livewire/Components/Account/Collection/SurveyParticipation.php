<?php

namespace App\Livewire\Components\Account\Collection;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyCompleted;
use App\Models\Transaction;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SurveyParticipation extends Component
{
    use WithCustomValidation;
    public $rating = 0;
    public $step = 0;
    public $text;
    public $participation;
    public $checkNeedForSurvey;

    public function render()
    {
        $this->checkNeedForSurvey = $this->participation->surveyCompleted ? true : null;
        return view('livewire.components.account.collection.survey-participation');
    }

    public function rules() {
        return [
          'rating' => 'integer|min:1'
        ];
    }

    public function messages() {
        return [
            'rating.min' => 'Выберите рейтинг!'
        ];
    }

    public function makeSurvey() {
        $surveyCompleted = SurveyCompleted::create([
            'user_id' => $this->participation['user_id'],
            'model_type' => 'Participation',
            'model_id' => $this->participation['id'],
            'title' => 'Заявка участия в сборнике'
        ]);
        SurveyAnswer::create([
            'survey_completed_id' => $surveyCompleted->id,
            'step' => 1,
            'stars' => $this->rating,
            'question' => 'Общая оценка',
        ]);
        if($this->rating < 5) {
            SurveyAnswer::create([
                'survey_completed_id' => $surveyCompleted->id,
                'step' => 2,
                'text' => $this->text,
                'question' => 'В чем проблема?',
            ]);
        }
        $this->dispatch('swal',
            type: 'success',
            title: 'Спасибо!',
            text: 'Ваш отзыв отправлен!'
        );
    }

    public function sendSurvey() {

        if ($this->customValidate()) {
            DB::transaction(function () {
                if ($this->rating < 5) {
                    $this->step = 1;
                } else {
                    $this->makeSurvey();
                }
            });
        }
    }
}
