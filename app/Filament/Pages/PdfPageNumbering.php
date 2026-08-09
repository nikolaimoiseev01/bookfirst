<?php

namespace App\Filament\Pages;

use App\Services\PdfService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;

class PdfPageNumbering extends Page implements HasForms
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.pdf-page-numbering';
    protected static ?string $navigationLabel = 'Нумерация страниц PDF';
    protected static ?string $title = 'Нумерация страниц PDF';

    /** Отступы в мм: outer — от внешнего края, top — сверху */
    private const STYLES = [
        'dukh' => ['label' => 'ДУХ', 'outer' => 14.0, 'top' => 9.0],
        'mysli' => ['label' => 'МЫСЛИ', 'outer' => 13.0, 'top' => 7.0],
    ];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->schema([
                Section::make('Параметры')->schema([
                    FileUpload::make('pdf')
                        ->label('PDF файл')
                        ->acceptedFileTypes(['application/pdf'])
                        ->storeFiles(false)
                        ->required(),

                    TextInput::make('start_page')
                        ->label('Начать со страницы')
                        ->numeric()
                        ->minValue(1)
                        ->default(4)
                        ->required(),

                    TextInput::make('end_page')
                        ->label('Закончить на странице')
                        ->numeric()
                        ->minValue(1)
                        ->placeholder('По умолчанию: количество страниц − 1'),

                    Select::make('style')
                        ->label('Стиль')
                        ->options(collect(self::STYLES)->map(fn (array $style) => $style['label']))
                        ->default('dukh')
                        ->required(),
                ])->columns(3),
            ]);
    }

    public function process()
    {
        $data = $this->form->getState();

        $file = $data['pdf'];
        $style = self::STYLES[$data['style']];

        try {
            $binary = app(PdfService::class)->numberPagesToString(
                $file->getRealPath(),
                (int) $data['start_page'],
                filled($data['end_page']) ? (int) $data['end_page'] : null,
                $style['outer'],
                $style['top'],
            );
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Не удалось обработать PDF')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        } finally {
            $file->delete();
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_numbered.pdf';

        return response()->streamDownload(
            fn () => print($binary),
            $fileName,
            ['Content-Type' => 'application/pdf'],
        );
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('process')
                ->label('Проставить номера и скачать')
                ->submit('process'),
        ];
    }
}
