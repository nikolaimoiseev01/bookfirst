<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationLabel = 'Опросы';

    protected static ?string $navigationGroup = 'Остальное';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Placeholder::make('Название')
                        ->content(fn(Survey $record): string => $record->title),
                    Placeholder::make('Кто?')
                        ->content(fn(Survey $record): string => $record->user->name . ' ' . $record->user->surname),
                    Placeholder::make('Когда')
                        ->content(fn(Survey $record): string => $record->created_at->toFormattedDateString())
                ]),
                Placeholder::make('')
                    ->content(function (Survey $record): HtmlString {
                        $answers = $record->survey_text;

                        $html_string = '';

                        $step = 1;

                        foreach ($answers as $answer) {
                            if ($answer['stars']) {
                                $stars = ' (оценка: ' . $answer['stars'] . '/5)';
                            } else {
                                $stars = null;
                            }

                            if ($answer['text']) {
                                $text = 'Ответ: ' . $answer['text'];
                            } else {
                                $text = null;
                            }

                            $html_string .= '<b>Шаг ' . $step . '</b><br>' .
                                'Вопрос: ' . $answer['question'] . $stars . '<br>'
                                . $text . '<br>';
                            $step += 1;
                        }
                        return new HtmlString($html_string);
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название опроса'),
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->formatStateUsing(function (Survey $record) {
                        return $record->user->name . ' ' . $record->user->surname;
                    }),
                TextColumn::make('rating')
                    ->label('Первая оценка')
                    ->formatStateUsing(function (Survey $record) {
                        return $record->survey_text->first()['stars'] . '/5';
                    }),
                TextColumn::make('created_at')->date()->label('Создан')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Отзыв пользователя'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSurveys::route('/'),
//            'view' => Pages\ViewCustom::route('/{record}'),
        ];
    }
}
