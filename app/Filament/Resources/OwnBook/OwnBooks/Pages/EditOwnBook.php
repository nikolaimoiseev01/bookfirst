<?php

namespace App\Filament\Resources\OwnBook\OwnBooks\Pages;

use App\Enums\OwnBookStatusEnums;
use App\Enums\PrintOrderStatusEnums;
use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Jobs\EmailNotificationJob;
use App\Jobs\InnerTaskUpdateJob;
use App\Jobs\PdfCutJob;
use App\Notifications\Collection\ParticipationStatusUpdate;
use App\Notifications\OwnBook\OwnBookStatusUpdateNotification;
use App\Services\InnerTasksService;
use App\Services\PdfService;
use App\Services\PriceCalculation\CalculateOwnBookService;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class EditOwnBook extends EditRecord
{
    protected static string $resource = OwnBookResource::class;
    protected array $mediaBefore = [];

    public $oldStatusGeneral;
    public $oldStatusInside;
    public $oldStatusCover;

    protected function getHeaderActions(): array
    {
        return [
//            ViewAction::make(),
//            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->oldStatusGeneral = $this->record->getOriginal('status_general');
        $this->oldStatusInside = $this->record->getOriginal('status_inside');
        $this->oldStatusCover = $this->record->getOriginal('status_cover');
    }

    protected function sendStatusUpdateNotification(): void
    {
        foreach (['general', 'inside', 'cover'] as $type) {
            $field = "status_{$type}";
            $old = "oldStatus" . ucfirst($type);
            if ($this->record->wasChanged($field)) {
                EmailNotificationJob::dispatch(
                    $this->record->user_id,
                    new OwnBookStatusUpdateNotification(
                        $this->record,
                        $field,
                        $this->$old->value,
                        $this->record[$field]->value
                    )
                );
            }
        }
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('author_name')) {
            dd('author_name changed!');
        }
        $this->sendStatusUpdateNotification();

        if ($this->record->wasChanged('status_general') && $this->record['status_general'] == OwnBookStatusEnums::PRINTING) {
            $this->record->initialPrintOrder?->update([
                'status' => PrintOrderStatusEnums::PRINTING->value,
            ]);
            $this->record->update([
                'deadline_print' => Carbon::now()->addDays(18),
            ]);
        }

        $this->updatePagesWhenMediaUpdated();

        InnerTaskUpdateJob::dispatch();
    }

    public function updatePagesWhenMediaUpdated(): void
    {
        $mediaAfter = $this->record->getMedia('inside_file');

        $beforeUuid = $this->mediaBefore[0] ?? null;
        $afterUuid = $mediaAfter->first()?->uuid;

        // файл добавлен или заменён
        if ($afterUuid && $beforeUuid !== $afterUuid) {

            $pdfService = new PdfService();

            $newPages = $pdfService->getPageCount(
                $mediaAfter->first()->getPath()
            );

            $oldPages = $this->record->pages;

            if ($oldPages != $newPages) {

                $this->record->pages = $newPages;
                $this->record->saveQuietly();

                $diff = $newPages - $oldPages;
                $sign = $diff > 0 ? '+' : '';

                Notification::make()
                    ->title('Изменилось количество страниц')
                    ->body("{$oldPages} → {$newPages} ({$sign}{$diff})")
                    ->success()
                    ->send();
            }

            if ($this->record->initialPrintOrder ?? null) {
                $this->updatePrintPrice($newPages);
            }


        }
    }

    public function updatePrintPrice($pages): void
    {
        $printOrder = $this->record->initialPrintOrder;

        $oldPrice = $printOrder->price_print;

        $newPrintPrice = (new CalculateOwnBookService(
            pages: $pages,
        ))->calculatePrintPrice(
            pagesColor: $printOrder->pages_color ?? 0,
            booksCnt: $printOrder->books_cnt,
            coverType: $printOrder->cover_type
        );

        if ($oldPrice != $newPrintPrice) {

            $printOrder->price_print = $newPrintPrice;
            $printOrder->saveQuietly();

            $diff = $newPrintPrice - $oldPrice;
            $sign = $diff > 0 ? '+' : '';

            Notification::make()
                ->title('Изменилась цена печати')
                ->body("{$oldPrice} ₽ → {$newPrintPrice} ₽ ({$sign}{$diff} ₽)")
                ->success()
                ->send();
        }
    }


    public function getTitle(): HtmlString
    {
        $author = $this->record['author'];
        $title = $this->record['title'];
        $user_id = $this->record['user_id'];
        return new HtmlString("<a class='text-primary-500' href='/admin/user/users/{$user_id}/edit'>{$author}</a>: {$title}");
    }
}
