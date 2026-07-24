<?php

namespace App\Filament\Pages;

use App\Filament\Resources\EmailMarketing\EmailCampaigns\EmailCampaignResource;
use App\Jobs\BulkCreateEmailCampaignsJob;
use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailMarketing\EmailTemplate;
use App\Services\EmailMarketing\EmailTemplateRenderService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class BulkCreateEmailCampaign extends Page implements HasForms
{
    use InteractsWithSchemas;

    protected static string $resource = EmailCampaignResource::class;
    protected string $view = 'filament.pages.bulk-create-email-campaign';
    protected static ?string $navigationLabel = 'Массовое создание кампаний';
    protected static ?string $title = 'Массовое создание Email кампаний';

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
                Section::make('Общие настройки')->schema([
                    Select::make('email_template_id')
                        ->label('Email шаблон')
                        ->options(EmailTemplate::pluck('name', 'id'))
                        ->required()
                        ->preload()
                        ->searchable()
                        ->live(),

                    TextInput::make('subject')
                        ->label('Тема письма')
                        ->required()
                        ->maxLength(255),

                    Actions::make([
                        Action::make('previewTemplate')
                            ->label('Открыть предпросмотр шаблона')
                            ->icon('heroicon-o-eye')
                            ->color('primary')
                            ->modalHeading('Предпросмотр письма')
                            ->modalWidth('7xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Закрыть')
                            ->disabled(fn (Get $get): bool => blank($get('email_template_id')))
                            ->modalContent(function (Get $get) {
                                $templateId = $get('email_template_id');
                                $recipientListId = $get('campaigns.0.email_recipient_list_id');

                                if (!$templateId) {
                                    return '';
                                }

                                $recipientList = $recipientListId ? EmailRecipientList::find($recipientListId) : null;
                                $utmCampaign = $recipientList?->utm_campaign;
                                $promoCode = $recipientList?->promocode?->name;

                                $renderedHtml = app(EmailTemplateRenderService::class)->renderHTML($templateId, $utmCampaign, $promoCode);

                                return view('filament.components.template-preview', [
                                    'html' => $renderedHtml,
                                ]);
                            }),
                    ]),
                ]),

                Section::make('Кампании')->schema([
                    Repeater::make('campaigns')
                        ->label('')
                        ->schema([
                            Select::make('email_recipient_list_id')
                                ->label('Список получателей')
                                ->options(EmailRecipientList::pluck('name', 'id'))
                                ->required()
                                ->preload()
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    if ($state) {
                                        $recipientList = EmailRecipientList::find($state);
                                        if ($recipientList) {
                                            $set('name', $recipientList->name);
                                        }
                                    }
                                }),

                            TextInput::make('name')
                                ->label('Название кампании')
                                ->required()
                                ->maxLength(255)
                                ->readOnly(),

                            DateTimePicker::make('scheduled_at')
                                ->label('Запланировать отправку')
                                ->seconds(false)
                                ->required()
                                ->helperText('Дата и время отправки для этой кампании'),
                        ])
                        ->minItems(1)
                        ->defaultItems(1)
                        ->columns(3),
                ]),
            ]);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        BulkCreateEmailCampaignsJob::dispatch($data);

        Notification::make()
            ->title('Кампании создаются в фоновом режиме')
            ->success()
            ->send();

        $this->redirect(EmailCampaignResource::getUrl('index'));
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Создать кампании')
                ->submit('create'),
        ];
    }
}
