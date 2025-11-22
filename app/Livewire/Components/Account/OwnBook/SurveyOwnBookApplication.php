<?php

namespace App\Livewire\Components\Account\OwnBook;

use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyCompleted;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SurveyOwnBookApplication extends Component
{
    use WithCustomValidation;
    public $rating = 0;
    public $step = 0;
    public $text;
    public $ownBook;
    public $checkNeedForSurvey;


    public function render()
    {
        $this->checkNeedForSurvey = $this->ownBook->surveyCompleted ? true : null;
        return view('livewire.components.account.own-book.survey-own-book-application');
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
            'user_id' => $this->ownBook['user_id'],
            'model_type' => 'OwnBook',
            'model_id' => $this->ownBook['id'],
            'title' => 'Заявка на издание книги'
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
                if ($this->step == 0 && $this->rating < 5) {
                    $this->step = 1;
                } else {
                    $this->makeSurvey();
                }
            });
        }
    }
}
