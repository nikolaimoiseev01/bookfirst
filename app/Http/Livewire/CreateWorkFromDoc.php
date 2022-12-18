<?php

namespace App\Http\Livewire;

use App\Models\Work;
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

    public function render()
    {
        return view('livewire.create-work-from-doc', [
            'works' => $this->works ?? 0,
        ]);
    }

    public function mount()
    {

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
            $works[0] = ['title' => '', 'text' => '', 'symbols' => '', 'rows' => '', 'pages' => ''];

            foreach ($phpWord->getSections() as $section) { // Секция обычно только одна

                foreach ($section->getElements() as $e) { // Проходим по каждому элементу (элемент - это одна строчка походу)

                    if (get_class($e) === 'PhpOffice\PhpWord\Element\TextRun') {

                        foreach ($e->getElements() as $text) { // Внутри большого элемента (строчки) может быть много маленьких)
                            if (get_class($text) === 'PhpOffice\PhpWord\Element\TextBreak') {
                                $works[$work_num]['text'] .= '<br>'; // Ставим разделение после большого элемента
                            } else {
                                $font = $text->getFontStyle();
                                if ($font->isBold() === true) {
                                    if ($works[$work_num]['text'] <> '') {
                                        $work_num++;
                                        $works[$work_num] = ['title' => '', 'text' => '', 'symbols' => '', 'rows' => '', 'pages' => ''];
                                    }
                                    $works[$work_num]['title'] .= $text->getText();
                                } else {
                                    $works[$work_num]['text'] .= $text->getText();
                                }

                            }
                        }
                        if ($works[$work_num]['text'] <> '') {
                            $works[$work_num]['text'] .= '<br>'; // Ставим разделение после большого элемента
                        }
                    } else if (get_class($e) === 'PhpOffice\PhpWord\Element\TextBreak') {
                        $works[$work_num]['text'] .= '<br>';
                    }
                }

                break;
            }
            $this->works = $works;

            $this->dispatchBrowserEvent('works_stat');
            File::delete(storage_path('app/livewire-tmp/' . $this->file->getfilename()));
        }

//        $this->dispatchBrowserEvent('loader', [
//            'id' => 'start_doc_scan',
//        ]);
    }


    public function edit_work($id, $title, $text)
    {
        $this->works[$id]['title'] = $title;
        $this->works[$id]['text'] = $text;
    }

    public function delete_work($id)
    {

        unset($this->works[$id]);
        array_unshift($this->works);
    }


    public function work_stat_function()
    {
        $work_num = 0;
        foreach ($this->works as $work) {

            if ($this->works[$work_num]['title'] === '') {
                $this->works[$work_num]['title'] = 'Название неопознано';
            }

            if ($this->works[$work_num]['text'] === '') {
                $this->works[$work_num]['text'] = 'Текст неопознано';
            }


            $symbols = 0;
            $symbols_for_rows = 0;
            $rows = 1;
            $pages = 1;
            $len = mb_strlen($work['text'], 'UTF-8');
            $result = [];
            for ($i = 0; $i < $len; $i++) {
                if (mb_substr($work['text'], $i, 1, 'UTF-8') === '<' &&
                    mb_substr($work['text'], $i + 1, 1, 'UTF-8') === 'b' &&
                    mb_substr($work['text'], $i + 2, 1, 'UTF-8') === 'r'
                ) {
                    $rows++;
                    $i = $i + 4;
                }
                if ($symbols_for_rows > 50) {
                    $rows++;
                    $symbols_for_rows = 0;
                }
                $symbols++;
            }

            $this->works[$work_num]['symbols'] = $symbols;
            $this->works[$work_num]['rows'] = $rows;
            $this->works[$work_num]['pages'] = ceil($rows / 38);

            $work_num++;


        }
    }

    public function save_all_work()
    {
        function br2nl($str)
        {
            return preg_replace('#<br\s*/?>#i', "\n", $str);
        }

        foreach ($this->works as $work) {
            $new_work = new Work();
            $new_work->title = $work['title'];
            $new_work->text = br2nl(str_replace('<br>', '<br />', $work['text']));
            $new_work->symbols = $work['symbols'];
            $new_work->rows = $work['rows'];
            $new_work->pages = $work['pages'];
            $new_work->upload_type = 'из документа';
            $new_work->user_id = Auth::user()->id;
            $new_work->work_type_id = 999;
            $new_work->work_topic_id = 999;
            $new_work->save();
        }

        session()->flash('show_modal', 'yes');
        session()->flash('alert_type', 'success');
        session()->flash('alert_title', 'Все произведения успешно добавлены!');
        return redirect(Session('back_after_add'));

    }


}
