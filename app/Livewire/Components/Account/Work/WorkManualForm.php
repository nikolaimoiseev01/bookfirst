<?php

namespace App\Livewire\Components\Account\Work;

use App\Enums\CollectionStatusEnums;
use App\Models\Collection\ParticipationWork;
use App\Models\Work\Work;
use App\Models\Work\WorkTopic;
use App\Models\Work\WorkType;
use App\Services\WorkStatService;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class WorkManualForm extends Component
{
    use WithFilePond;
    use WithCustomValidation;

    public $cameFromAppUrl;
    public $isSending;
    public $title;
    public $text;
    public $files = [];
    public $deleteImageFlg = false;
    public $workTypeOptions;
    public $workType;

    public $workTopicOptions;
    public $workTopic;

    public $formType;
    public $work;

    public $workStatResponse;

    public function render()
    {
        return view('livewire.components.account.work.work-manual-form');
    }

    public function mount($formType, $work_id = null)
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
        if ($formType == 'edit') {
            $this->work = Work::with('media')->findOrFail($work_id);
            $this->title = $this->work['title'];
            $this->text = $this->work['text'];
            $this->workType = $this->work['work_type_id'];
            $this->workTopic = $this->work['work_topic_id'];
            if (!($this->work->getFirstMediaUrl('cover') ?? null)) {
                $this->deleteImageFlg = true;
            }
        } else {
            $this->deleteImageFlg = true;
        }
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
            'title' => 'required',
            'workType' => 'required',
            'workTopic' => 'required',
            'files'     => 'nullable|array',
            'files.*'   => 'file|mimetypes:image/jpg,image/jpeg,image/png|max:3000',
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст произведения обязателен для заполнения',
            'title.required' => 'Название обязательно для заполнения',
            'workType.required' => 'Тип произведения обязателен для заполнения',
            'workTopic.required' => 'Тема произведения обязательна для заполнения',
            'files.*.mimetypes' => 'У прикрепленного файла должен быть другой формат: jpg, jpeg, png!',
            'files.max:3000' => 'Файл должен быть менее 3МБ'
        ];
    }

    public function makeWorkStat($workStat)
    {
        $this->workStatResponse = $workStat->calculate($this->text);
    }

    public function createWork($urlBack)
    {
        $newWork = Work::create([
            'user_id' => Auth::user()->id,
            'title' => $this->title,
            'work_type_id' => $this->workType,
            'work_topic_id' => $this->workTopic,
            'text' => $this->text,
            'upload_type' => 'вручную',
            'symbols' => $this->workStatResponse['symbols'],
            'rows' => $this->workStatResponse['rows'],
            'pages' => $this->workStatResponse['pages'],
        ]);
        if (count($this->files) > 0) {
            foreach ($this->files as $file) {
                $newWork
                    ->addMedia($file->getRealPath())       // путь до tmp файла
                    ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                    ->toMediaCollection('cover');   // твоя коллекция
            }
        }
        $this->redirect($urlBack, navigate: true);
    }

    public function updateWork($urlBack)
    {
        $participationWork = ParticipationWork::where('work_id', $this->work->id)->with(['participation', 'participation.collection'])->first();
        if ($participationWork && $participationWork->participation->collection->status != CollectionStatusEnums::DONE) {
            $this->dispatch('swal', type: 'error', title: 'Ошибка!', text: 'Нельзя редактировать произведение, участвующеее в сборнике, который в процессе издания');
            return;
        }

        $this->work->update([
            'title' => $this->title,
            'work_type_id' => $this->workType,
            'work_topic_id' => $this->workTopic,
            'text' => $this->text,
            'upload_type' => 'вручную',
            'symbols' => $this->workStatResponse['symbols'],
            'rows' => $this->workStatResponse['rows'],
            'pages' => $this->workStatResponse['pages'],
        ]);
        if ($this->deleteImageFlg) {
            $this->work->clearMediaCollection('cover');
        }

        if ((count($this->files) > 0) && $this->deleteImageFlg) {
            foreach ($this->files as $file) {
                $this->work
                    ->addMedia($file->getRealPath())       // путь до tmp файла
                    ->usingFileName($file->getClientOriginalName()) // оригинальное имя
                    ->toMediaCollection('cover');   // твоя коллекция
            }
        }
        $this->redirect($urlBack, navigate: true);

    }


    public function saveWork(WorkStatService $workStat, $urlBack)
    {
        if ($this->customValidate()) {
            DB::transaction(function () use ($workStat, $urlBack) {
                $this->makeWorkStat($workStat);
                if ($this->formType == 'create') {
                    $this->createWork($urlBack);
                } else {
                    $this->updateWork($urlBack);
                }
            });

        }

    }

    public function createAndOut()
    {
        $workAddWord = $this->formType == 'create' ? 'добавлено' : 'изменено';
        if ($this->cameFromAppUrl) {
            $alertText = "Произведение успешно {$workAddWord}! Теперь его можно прикрепить в заявке (поле 'Произведения для участия')";
        } else {
            $alertText = "Произведение успешно {$workAddWord}!";
        }
        session()->flash('swal', [
            'title' => 'Успешно!',
            'type' => 'success',
            'text' => $alertText
        ]);

        $urlBack = $this->cameFromAppUrl ?? route('account.works');

        $this->saveWork(app(WorkStatService::class), $urlBack);
    }

    public function createAndAnouther()
    {
        session()->flash('swal', [
            'title' => 'Успешно!',
            'type' => 'success',
            'text' => 'Произведение успешно добавлено! Теперь можно добавить еще одно'
        ]);

        $this->saveWork(app(WorkStatService::class), route('account.works.create.manual'));
    }

    public function removeCover()
    {
        $this->deleteImageFlg = true;
    }
}
