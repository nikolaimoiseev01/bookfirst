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
use Illuminate\Support\Facades\DB;
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

                    ini_set('memory_limit', '256M'); // достаточно

                    $zipDownloadName = 'Медиа всех сборников.zip';
                    $zipPath = storage_path('app/' . uniqid('media_') . '.zip');

                    $filesAdded = 0;
                    $fileList = [];

                    /*
                    |--------------------------------------------------------------------------
                    | Получаем файлы напрямую из таблицы media (БЕЗ Eloquent)
                    |--------------------------------------------------------------------------
                    */

                    DB::table('media')
                        ->where('collection_name', 'cover_front')
                        ->where(function ($q) {
                            $q->where('model_type', \App\Models\Collection\Collection::class)
                                ->whereIn('model_id', function ($sub) {
                                    $sub->select('id')
                                        ->from('collections')
                                        ->where('status', \App\Enums\CollectionStatusEnums::DONE);
                                });
                        })
                        ->orWhere(function ($q) {
                            $q->where('model_type', \App\Models\OwnBook\OwnBook::class)
                                ->whereIn('model_id', function ($sub) {
                                    $sub->select('id')
                                        ->from('own_books')
                                        ->where('status_general', \App\Enums\OwnBookStatusEnums::DONE);
                                });
                        })
                        ->orderBy('id')
                        ->chunk(500, function ($medias) use (&$fileList, &$filesAdded) {

                            foreach ($medias as $media) {

                                $fullPath = storage_path('app/public/' . $media->id . '/' . $media->file_name);

                                if (! file_exists($fullPath)) {
                                    continue;
                                }

                                $fileList[] = escapeshellarg($fullPath);
                                $filesAdded++;
                            }
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Создание архива через системный zip (НЕ PHP ZipArchive)
                    |--------------------------------------------------------------------------
                    */

                    $command = sprintf(
                        'zip -j %s %s',
                        escapeshellarg($zipPath),
                        implode(' ', $fileList)
                    );

                    exec($command);

                    return response()
                        ->download($zipPath, $zipDownloadName)
                        ->deleteFileAfterSend(true);
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
