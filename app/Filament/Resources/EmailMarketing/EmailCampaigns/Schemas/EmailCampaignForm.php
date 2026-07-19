<?php

namespace App\Filament\Resources\EmailMarketing\EmailCampaigns\Schemas;

use App\Models\EmailMarketing\EmailRecipientList;
use App\Models\EmailMarketing\EmailTemplate;
use App\Services\EmailMarketing\EmailTemplateRenderService;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class EmailCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Hidden::make('preview_html')
                    ->dehydrated(false),

                Hidden::make('html_content'),

                Section::make('Основная информация')->schema([
                    TextInput::make('name')
                        ->label('Название кампании')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('subject')
                        ->label('Тема письма')
                        ->required()
                        ->maxLength(255),
                ]),

                Section::make('Настройки')->schema([
                    Select::make('email_recipient_list_id')
                        ->label('Список получателей')
                        ->relationship('recipientList', 'name')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (?int $state, Set $set): void {
                            if (! $state) {
                                return;
                            }

                            $recipientList = EmailRecipientList::find($state);

                            $set('utm_campaign', str($recipientList->utm_campaign));
                        }),

                    Select::make('email_template_id')
                        ->label('Email шаблон')
                        ->relationship('emailTemplate', 'name')
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (?int $state, Set $set, Get $get): void {
                            if (! $state) {
                                $set('preview_html', null);

                                return;
                            }

                            $utmCampaign = $get('utm_campaign');
                            $recipientListId = $get('email_recipient_list_id');
                            $promoCode = null;

                            if ($recipientListId) {
                                $recipientList = EmailRecipientList::find($recipientListId);
                                if ($recipientList && $recipientList->promocode) {
                                    $promoCode = $recipientList->promocode->name;
                                }
                            }

                            $renderedHtml = self::renderTemplatePreview($state, $utmCampaign, $promoCode);
                            $set('preview_html', $renderedHtml);
                            $set('html_content', $renderedHtml);
                        })
                        ->nullable(),

                    Actions::make([
                        Action::make('previewTemplate')
                            ->label('Открыть предпросмотр шаблона')
                            ->icon('heroicon-o-eye')
                            ->color('primary')
                            ->modalHeading('Предпросмотр письма')
                            ->modalWidth('7xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Закрыть')
                            ->disabled(fn (Get $get): bool => blank($get('preview_html')))
                            ->modalContent(fn (Get $get) => view('filament.components.template-preview', [
                                'html' => $get('preview_html'),
                            ])),
                    ]),

                    DateTimePicker::make('scheduled_at')
                        ->label('Запланировать отправку')
                        ->seconds(false)
                        ->nullable()
                        ->helperText('Оставьте пустым для сохранения как черновик'),

                    Select::make('status')
                        ->label('Статус')
                        ->options([
                            'draft' => 'Черновик',
                            'scheduled' => 'Запланировано',
                            'sending' => 'Отправка',
                            'sent' => 'Отправлено',
                            'failed' => 'Ошибка',
                        ])
                        ->default('draft')
                        ->required(),
                ]),
            ]);
    }

    private static function renderTemplatePreview(int $templateId, ?string $utmCampaign, ?string $promoCode = null): string
    {
        $template = EmailTemplate::query()->find($templateId);

        if (! $template) {
            return '';
        }

        return app(EmailTemplateRenderService::class)->renderHTML($templateId, $utmCampaign, $promoCode);
    }
}
