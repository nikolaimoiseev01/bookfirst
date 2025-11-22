<?php

namespace App\Services;

use App\Enums\CollectionStatusEnums;
use App\Enums\InnerTaskTypeEnums;
use App\Enums\OwnBookCoverStatusEnums;
use App\Enums\OwnBookInsideStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Models\Collection\Collection;
use App\Models\InnerTask;
use App\Models\OwnBook\OwnBook;
use Illuminate\Support\Facades\DB;

class InnerTasksService
{

    public function createCollectionTasks()
    {
        $collections = Collection::get();
        foreach ($collections as $collection) {

            [$title, $deadline] = match ($collection['status']) {
                CollectionStatusEnums::APPS_IN_PROGRESS => [
                    "Нужно сверстать сборник {$collection['title_short']}",
                    $collection['date_preview_start']
                ],
                CollectionStatusEnums::PREVIEW => [
                    "Нужно выбрать победителей в сборник {$collection['title_short']}",
                    $collection['date_preview_end']
                ],
                CollectionStatusEnums::PRINT_PREPARE => [
                    "Нужно отправить сборник {$collection['title_short']} в печать",
                    $collection['date_print_start']
                ],
                CollectionStatusEnums::PRINTING => [
                    "Нужно отправить сборник {$collection['title_short']} авторам",
                    $collection['date_print_end']
                ],
                default => [null, null]
            };

            if ($title) {
                InnerTask::create([
                    'type' => InnerTaskTypeEnums::COLLECTION,
                    'model_type' => 'Collection',
                    'model_id' => $collection['id'],
                    'title' => $title,
                    'deadline' => $deadline,
                    'flg_custom_task' => false
                ]);
            }
        }
    }

    private function createOwnBookTask($ownBook, $title, $deadline, $type)
    {
        if (!$title) {
            return;
        }

        InnerTask::create([
            'type' => $type,
            'model_type' => 'OwnBook',
            'model_id' => $ownBook['id'],
            'title' => $title,
            'deadline' => $deadline,
            'flg_custom_task' => false,
        ]);
    }


    private function handleGeneralStatus($ownBook)
    {
        return match ($ownBook['status_general']) {
            OwnBookStatusEnums::REVIEW =>
            ["Нужно принять заявку на издание книги: {$ownBook['title']}",
                $ownBook['created_at']->copy()->addDays(3)],

            OwnBookStatusEnums::PRINT_WAITING =>
            ["Нужно отправить книгу в печать: {$ownBook['title']}",
                $ownBook['paid_at_print_only']->addDays(3)],

            OwnBookStatusEnums::PRINTING =>
            ["Нужно отправить книгу автору: {$ownBook['title']}",
                $ownBook['deadline_print']],

            default => [null, null]
        };
    }

    private function handleCoverStatus($ownBook)
    {
        return match ($ownBook['status_cover']) {
            OwnBookCoverStatusEnums::DEVELOPMENT =>
            ["Нужно сделать обложку: {$ownBook['author']}",
                $ownBook['deadline_cover']],

            OwnBookCoverStatusEnums::CORRECTIONS =>
            ["Нужно исправить обложку: {$ownBook['author']}",
                $ownBook['deadline_cover']],

            default => [null, null]
        };
    }

    private function handleInsideStatus($ownBook)
    {
        return match ($ownBook['status_inside']) {
            OwnBookInsideStatusEnums::DEVELOPMENT =>
            ["Нужно сделать ВБ: {$ownBook['author']}",
                $ownBook['deadline_inside']],

            OwnBookInsideStatusEnums::CORRECTIONS =>
            ["Нужно исправить ВБ: {$ownBook['author']}",
                $ownBook['deadline_inside']],

            default => [null, null]
        };
    }

    public function createOwnBookTasks()
    {
        $ownBooks = OwnBook::query()
            ->whereIn('status_general', [
                    OwnBookStatusEnums::REVIEW,
                    OwnBookStatusEnums::WORK_IN_PROGRESS,
                    OwnBookStatusEnums::PRINT_WAITING,
                    OwnBookStatusEnums::PRINTING,
                ]
            )
            ->where('id', 316)
            ->get();
        foreach ($ownBooks as $ownBook) {
            dd($ownBook['paid_at_print_only']->addDays(3));
            [$title, $deadline] = $this->handleGeneralStatus($ownBook);
            $this->createOwnBookTask($ownBook, $title, $deadline, InnerTaskTypeEnums::OWN_BOOK_GENERAL);
            if ($ownBook['status_general'] == OwnBookStatusEnums::WORK_IN_PROGRESS) {
                [$title, $deadline] = $this->handleCoverStatus($ownBook);
                $this->createOwnBookTask($ownBook, $title, $deadline, InnerTaskTypeEnums::OWN_BOOK_COVER);

                [$title, $deadline] = $this->handleInsideStatus($ownBook);
                $this->createOwnBookTask($ownBook, $title, $deadline, InnerTaskTypeEnums::OWN_BOOK_INSIDE);
            }
        }
    }

    public function update()
    {
        InnerTask::truncate();
        $this->createCollectionTasks();
        $this->createOwnBookTasks();
    }
}
