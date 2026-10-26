<?php

namespace App\Livewire\Pages\Account\Ai;

use App\Models\Ai\AiAssistantConversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AiAssistantTaskStatus extends Component
{
    public int $taskId;
    public bool $completionNotified = false;

    public function mount(int $taskId): void
    {
        $this->taskId = $taskId;
    }

    public function refreshStatus(): void
    {
        if ($this->completionNotified) return;

        $task = AiAssistantConversation::query()
            ->where('user_id', Auth::id())
            ->whereKey($this->taskId)
            ->first();

        if ($task && !in_array($task->status, ['pending', 'running'], true)) {
            $this->completionNotified = true;
            $this->dispatch('ai-task-finished');
        }
    }

    public function render()
    {
        $task = AiAssistantConversation::query()
            ->where('user_id', Auth::id())
            ->whereKey($this->taskId)
            ->first();

        return view('livewire.pages.account.ai.ai-assistant-task-status', [
            'task' => $task,
            'isRunning' => $task && in_array($task->status, ['pending', 'running'], true),
        ]);
    }
}
