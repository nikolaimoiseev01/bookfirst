<?php

namespace App\Livewire\Pages\Account\Work;

use App\Models\Chat\Chat;
use App\Models\Chat\Message;
use App\Models\Work\Work;
use App\Models\Work\WorkTopic;
use App\Models\Work\WorkType;
use App\Services\WorkStatService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class WorkCreateManualPage extends Component
{
    use WithFilePond;
    use WithCustomValidation;

    public $cameFromAppUrl;
    public $isSending;
    public $title;
    public $text;
    public $files = [];
    public $workTypeOptions;
    public $workType;

    public $workTopicOptions;
    public $workTopic;

    public function render()
    {
        return view('livewire.pages.account.work.work-create-manual-page')->layout('layouts.account');
    }

    public function mount()
    {
        $this->cameFromAppUrl = Session::get('cameFromAppUrl');
        $this->workTypeOptions = WorkType::all()
            ->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
            ->toArray();
        $this->workTopicOptions = WorkTopic::all()
            ->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
            ->toArray();
    }


    protected function rules(): array
    {
        return [
            'text' => 'required',
            'title' => 'required',
            'workType' => 'required',
            'workTopic' => 'required',
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст произведения обязателен для заполнения',
            'title.required' => 'Название обязательно для заполнения',
            'workType.required' => 'Тип произведения обязателен для заполнения',
            'workTopic.required' => 'Тема произведения обязательна для заполнения'
        ];
    }

    public function createWork(WorkStatService $work_stat)
    {
        if ($this->customValidate()) {
            DB::transaction(function () use ($work_stat) {
                $work_stat_response = $work_stat->calculate($this->text);

                $work = Work::create([
                    'user_id' => Auth::user()->id,
                    'title' => $this->title,
                    'work_type_id' => $this->workType,
                    'work_topic_id' => $this->workTopic,
                    'text' => $this->text,
                    'upload_type' => 'вручную',
                    'symbols' => $work_stat_response['symbols'],
                    'rows' => $work_stat_response['rows'],
                    'pages' => $work_stat_response['pages'],
                ]);

                if (count($this->files) > 0) {
                    foreach ($this->files as $file) {
                        $work
                            ->addMedia($file->getRealPath())       // путь до tmp файла
                            ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                            ->toMediaCollection('cover');   // твоя коллекция
                    }
                }

                if($this->cameFromAppUrl) {
                    $alert_text = 'Произведение успешно добавлено! Теперь их можно добавить в заявке (поле "Произведения для участия")';
                } else {
                    $alert_text = 'Произведение успешно добавлено!';
                }


                session()->flash('swal', [
                    'title' => 'Успешно!',
                    'icon' => 'success',
                    'text' => $alert_text
                ]);

                $url_back = $this->cameFromAppUrl ?? route('account.works');

                $this->redirect($url_back, navigate: true);
            });
        }

    }
}
