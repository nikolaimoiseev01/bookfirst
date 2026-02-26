<?php

namespace App\Filament\Resources\Collection\Collections\Pages;

use App\Enums\CollectionStatusEnums;
use App\Enums\OwnBookStatusEnums;
use App\Enums\ParticipationStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Filament\Resources\Collection\Collections\CollectionResource;
use App\Jobs\EmailNotificationJob;
use App\Jobs\PdfCutJob;
use App\Models\Collection\Collection;
use App\Models\OwnBook\OwnBook;
use App\Notifications\Collection\CollectionStatusUpdate;
use App\Notifications\Collection\CollectionWinnerNotification;
use App\Services\InnerTasksService;
use App\Services\PdfService;
use App\Services\WordService;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Shared\ZipArchive;

class EditCollection extends EditRecord
{
    protected static string $resource = CollectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('makeWord')
                ->label('Скачать верстку')
                ->action(function () {
                    return response()->download(
                        (new WordService())->makeCollection($this->record),
                        $this->record['title'] . '.docx'
                    );
                }),
            Action::make('makeFiles')
                ->label('Скачать файлы')
                ->action(function () {
                    ini_set('memory_limit', '2048M');
                    // Имя zip, которое увидит пользователь
                    $zipDownloadName = 'Медиа всех сборников.zip';

                    // Временный файл под архив
                    $tmpFile = tempnam(sys_get_temp_dir(), 'collection_files_');
                    $zip = new ZipArchive();

                    if ($zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                        $this->notify('danger', 'Не удалось создать архив.');
                        return null;
                    }

//                    // Подгружаем участия сразу с медиа, чтобы не ловить N+1
//                    $collections = Collection::query()->where('status', CollectionStatusEnums::DONE)
//                        ->with('media')
//                        ->where('id', '<=', 50)
//                        ->get();
//
//                    $filesAdded = 0;
//
//                    foreach ($collections as $collection) {
//                        // если у Participation есть HasMedia
//                        $media = $collection->getFirstMedia('cover_front');
//
//                        if (! $media) {
//                            continue;
//                        }
//
//                        $filePath = $media->getPath();
//
//                        if (! $filePath || ! file_exists($filePath)) {
//                            continue;
//                        }
//
//                         $fileNameInZip = 'collection-' . $collection->id . '.png';
//
//                        $zip->addFile($filePath, $fileNameInZip);
//                        $filesAdded++;
//                    }

                    // Подгружаем участия сразу с медиа, чтобы не ловить N+1
                    $ownBooks = OwnBook::query()->where('own_books.status_general', OwnBookStatusEnums::DONE)
                        ->with('media')
                        ->where('id', '>', 100)
                        ->where('id', '<', 201)
                        ->get();

                    $filesAdded = 0;

                    foreach ($ownBooks as $ownBook) {
                        // если у Participation есть HasMedia
                        $media = $ownBook->getFirstMedia('cover_front');

                        if (! $media) {
                            continue;
                        }

                        $filePath = $media->getPath();

                        if (! $filePath || ! file_exists($filePath)) {
                            continue;
                        }

                        $fileNameInZip = 'ownbook-' . $ownBook->id . '.png';

                        $zip->addFile($filePath, $fileNameInZip);
                        $filesAdded++;
                    }

                    $zip->close();

                    if ($filesAdded === 0) {
                        @unlink($tmpFile);
                        $this->notify('warning', 'Подходящих файлов не найдено.');
                        return null;
                    }

                    // Отдаём архив и удаляем временный файл после отправки
                    return response()->download($tmpFile, $zipDownloadName)->deleteFileAfterSend(true);
                })
        ];
    }


    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status')) {
            $participations = $this->record->approvedParticipations()->get();

            if ($this->record['status'] == CollectionStatusEnums::PRINT_PREPARE) {
                $this->record->participations()->where('status', '<>', ParticipationStatusEnums::APPROVED)->update([
                    'status' => ParticipationStatusEnums::NOT_ACTUAL
                ]);
            }
            if ($this->record['status'] == CollectionStatusEnums::PRINTING) {
                foreach ($participations as $participation) {
                    if ($participation->printOrder ?? null) {
                        $participation->printOrder->update([
                            'status' => PrintOrderStatusEnums::PRINTING
                        ]);
                    }
                }

                PdfCutJob::dispatch(
                    $this->record,
                    $this->record->getFirstMediaPath('inside_file'),
                    10,
                    'inside_file_preview'
                );
            }

            foreach ($participations as $participation) {
                $notification = new CollectionStatusUpdate($this->record, $participation['id'], $this->record['status']);
                EmailNotificationJob::dispatch($participation['user_id'], $notification);
            }
        }

        if ($this->record->wasChanged('winner_participations')) {
            foreach ($this->record->winner_participations_ordered as $key => $winnerParticipation) {
                $notification = new CollectionWinnerNotification($this->record, $key + 1, $winnerParticipation['id']);
                EmailNotificationJob::dispatch($winnerParticipation['user_id'], $notification);
            }
        }

        (new InnerTasksService())->update();
    }

    public
    function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public
    function getTitle(): string
    {
        return $this->record['title'];
    }

    public function getContentTabLabel(): ?string
    {
        return 'Общее';
    }
}
