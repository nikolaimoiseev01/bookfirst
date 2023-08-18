<?php

namespace App\Http\Livewire\Account\Work;

use App\Models\Work;
use App\Service\WorkStatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use PhpOffice\PhpWord\IOFactory;
use Livewire\WithFileUploads;

class CreateWorkFromDoc extends Component
{
    use WithFileUploads;

    public $works = [];
    public $test_text;
    public $file;

    public $back_after_work_adding;

    public function render()
    {
        return view('livewire.account.work.create-work-from-doc', [
            'works' => $this->works ?? 0,
        ]);
    }

    public function mount(Request $request)
    {
        $this->back_after_work_adding = $request->session()->get('back_after_work_adding');
    }

    public function read_doc()
    {
        $this->dispatchBrowserEvent('loader', [
            'id' => 'start_doc_scan',
        ]);

        if (is_null($this->file)) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Что-то пошло не так!',
                'text' => 'Пожалуйста, укажите файл для анализа',
            ]);
            $this->dispatchBrowserEvent('loader', [
                'id' => 'start_doc_scan',
            ]);
        } else {

            $source = storage_path('app/livewire-tmp/' . $this->file->getfilename());
            $works[] = array();
            $objReader = IOFactory::createReader('Word2007');
            $phpWord = $objReader->load($source);
            $work_num = 0;
            $works[0] = ['title' => '', 'text' => '', 'symbols' => '', 'rows' => '', 'pages' => '', 'editing' => false];

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
                                        $works[$work_num] = ['title' => '', 'text' => '', 'symbols' => '', 'rows' => '', 'pages' => '', 'editing' => false];
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
            $this->works = $works;


            $this->dispatchBrowserEvent('trigger_all_js');
            File::delete(storage_path('app/livewire-tmp/' . $this->file->getfilename()));
        }

    }


    public function make_editable($id)
    {
        $this->works[$id]['editing'] = true;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function save($id)
    {
        $this->works[$id]['editing'] = false;
        $this->dispatchBrowserEvent('trigger_all_js');
    }

    public function delete_work($id)
    {
        unset($this->works[$id]);
        array_unshift($this->works);
        $this->dispatchBrowserEvent('trigger_all_js');
    }


    public function save_all_work(Request $request, WorkStatService $work_stat)
    {

        foreach ($this->works as $work) {

            $work_stat_response = $work_stat->calculate($work['text']);

            $new_work = new Work();
            $new_work->title = $work['title'];
            $new_work->text = $work['text'];
            $new_work->symbols = $work_stat_response['symbols'];
            $new_work->rows = $work_stat_response['rows'];
            $new_work->pages = $work_stat_response['pages'];
            $new_work->upload_type = 'из документа';
            $new_work->user_id = Auth::user()->id;
            $new_work->work_type_id = 999;
            $new_work->work_topic_id = 999;
            $new_work->save();
        }


        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Все произведения успешно добавлены!');

        return redirect($this->back_after_work_adding['url']);

    }


}
