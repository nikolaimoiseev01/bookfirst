<?php

namespace App\Livewire\Components\Account;

use App\Models\Collection\Collection;
use App\Models\PreviewComment;
use App\Traits\WithCustomValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PreviewComments extends Component
{
    use WithCustomValidation;

    public $comments;
    public $modelId;
    public $modelType;
    public $commentType;
    public $text;
    public $page;
    public $isSending;

    public function render()
    {
        $this->comments = PreviewComment::where('model_type', $this->modelType)->where('model_id', $this->modelId)->get();
        return view('livewire.components.account.preview-comments');
    }

    public function mount($modelType, $modelId, $commentType)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->commentType = $commentType;
    }

    protected function rules(): array
    {
        return [
            'text' => 'required',
            'page' => Rule::requiredIf(fn() => $this->commentType == 'inside'), // ~10MB
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'Текст сообщения обязателен для заполнения',
            'page.required' => 'Страница обязательна для заполнения'
        ];
    }

    public function sendMessage()
    {
        if ($this->customValidate()) {
            DB::transaction(function () {
                PreviewComment::create([
                    'user_id' => Auth::user()->id,
                    'model_type' => $this->modelType,
                    'model_id' => $this->modelId,
                    'comment_type' => $this->commentType,
                    'page' => $this->page,
                    'text' => $this->text,
                    'flg_done' => false
                ]);
                $this->text = null;
                $this->page = null;
            });
        }
        $this->isSending = false;
    }
}
