<?php

namespace App\Livewire\Pages\Account\Work;

use App\Models\Work\Work;
use App\Services\WorkStatService;
use App\Traits\WithCustomValidation;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use PhpOffice\PhpWord\IOFactory;
use Spatie\LivewireFilepond\WithFilePond;

class WorkCreateFromFilePage extends Component
{
    use WithFilePond, WithCustomValidation;

    public $file;
    public $fileWorks = null;
    public $isSending = null;
    public $cameFromAppUrl;

    protected $listeners = ['saveAllWorks'];
    public function render()
    {
        return view('livewire.pages.account.work.work-create-from-file-page')->layout('layouts.account');
    }

    public function mount() {
        $this->cameFromAppUrl = Session::get('cameFromAppUrl');
    }

    public function rules()
    {
        return [
            'file' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/octet-stream',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Загрузите файл для распознавания',
            'file.mimetypes' => 'Файл должен быть формата .docx',
        ];
    }

    public function makeWorksFromFile($filePath)
    {
        $objReader = IOFactory::createReader('Word2007');
        $phpWord = $objReader->load($filePath);
        $work_num = 0;
        $works[0] = ['title' => '', 'text' => ''];

        foreach ($phpWord->getSections() as $section) { // Секция обычно только одна

            foreach ($section->getElements() as $e) { // Проходим по каждому элементу (элемент - это одна строчка походу)

                if (get_class($e) === 'PhpOffice\PhpWord\Element\TextRun') {

                    foreach ($e->getElements() as $text) { // Внутри большого элемента (строчки) может быть много маленьких)
                        if (get_class($text) === 'PhpOffice\PhpWord\Element\TextBreak') {
                            $works[$work_num]['text'] .= '<br>'; // Ставим разделение после большого элемента
                            $works[$work_num]['text'] = str_replace("<br>", "\n", $works[$work_num]['text']);
                        } else {
                            $font = $text->getFontStyle();
                            if ($font->isBold() === true) {
                                if ($works[$work_num]['text'] <> '') {
                                    $work_num++;
                                    $works[$work_num] = ['title' => '', 'text' => ''];
                                }
                                $works[$work_num]['title'] .= $text->getText();
                            } else {
                                $works[$work_num]['text'] .= $text->getText();
                            }

                        }
                    }
                    if ($works[$work_num]['text'] <> '') {
                        $works[$work_num]['text'] .= '<br>'; // Ставим разделение после большого элемента
                        $works[$work_num]['text'] = str_replace("<br>", "\n", $works[$work_num]['text']);
                    }
                } else if (get_class($e) === 'PhpOffice\PhpWord\Element\TextBreak') {
                    $works[$work_num]['text'] .= '<br>';
                    // Работаем с переносом строк (чтобы правильно Livewire подтягивал)
                    $works[$work_num]['text'] = str_replace("<br>", "\n", $works[$work_num]['text']);
                }
            }

            break;
        }
        return $works;
    }

    public function scan()
    {
        if ($this->customValidate()) {
            try {
                $this->fileWorks = $this->makeWorksFromFile($this->file->getRealPath());
                $this->dispatch('swal', type: 'success', title: 'Успешно!', text: 'Нам удалось распознать произведения в файле. Пожалуйста проверьте все найденные произведения ниже. При необходимости внесите в них изменения и сохраните их в систему, когда все будет готово.');
            } catch (Exception $e) {
                $this->dispatch('swal', type: 'error', title: 'Внимание!', text: 'При обработке файла что-то пошло не так. Пожалуйста, убедитесь, что файл отредактирован в соответствие с правилами.');
            }
        }
    }

    public function confirmSaveAllWorks()
    {
        $worksCount = count($this->fileWorks);
        $this->dispatch('swal',
            title: 'Давайте все проверим',
            text: "Загружаем работ: {$worksCount}",
            confirmButtonText: 'Да, все верно',
            livewireMethod: ['saveAllWorks']
        );
    }

    public function saveAllWorks(WorkStatService $workStat)
    {
        DB::transaction(function() use($workStat) {
            foreach ($this->fileWorks as $work) {
                $workStatResponse = $workStat->calculate($work['text']);
                Work::create([
                    'user_id' => Auth::user()->id,
                    'title' => $work['title'],
                    'work_type_id' => 999,
                    'work_topic_id' => 999,
                    'text' => $work['text'],
                    'upload_type' => 'из файла',
                    'symbols' => $workStatResponse['symbols'],
                    'rows' => $workStatResponse['rows'],
                    'pages' => $workStatResponse['pages'],
                ]);
            }
        });

        session()->flash('swal', [
            'title' => 'Успешно!',
            'type' => 'success',
            'text' => "Произведения успешно добавлены!" . ($this->cameFromAppUrl ? " Теперь их можно прикрепить в заявке (поле 'Произведения для участия')" : "")
        ]);

        $urlBack = $this->cameFromAppUrl ?? route('account.works');

        $this->redirect($urlBack, navigate: true);

    }
}
