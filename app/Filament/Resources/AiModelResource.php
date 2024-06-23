<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiModelResource\Pages;
use App\Filament\Resources\AiModelResource\RelationManagers;
use App\Models\AiModel;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AiModelResource extends Resource
{
    protected static ?string $model = AiModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('نام مدل')
                        ->columnSpan(6),
                    TextInput::make('identifier')
                        ->required()
                        ->label('شناسه مدل')
                        ->columnSpan(6),
                    Textarea::make('description')
                        ->label('توضیحات مدل')
                        ->columnSpanFull(),
                    Repeater::make('advantages')
                        ->label('مزایا')
                        ->schema([
                            TextInput::make('advantage')
                                ->label('مزیت')
                        ])
                        ->collapsible()
                        ->columnSpan(6),
                    Repeater::make('disadvantages')
                        ->label('معایب')
                        ->schema([
                            TextInput::make('disadvantage')
                                ->label('عیب')
                        ])
                        ->collapsible()
                        ->columnSpan(6),
                    Select::make('category')
                        ->label('دسته‌بندی')
                        ->options([
                            'text_generation' => 'تولید متن',
                            'image_generation' => 'تولید تصویر',
                            'speech_recognition' => 'تشخیص گفتار',
                            'translation' => 'ترجمه',
                            'sentiment_analysis' => 'تحلیل احساسات',
                            'question_answering' => 'پاسخ به سوالات',
                            'text_summarization' => 'خلاصه‌سازی متن',
                            'object_detection' => 'شناسایی اشیاء',
                            'recommendation_systems' => 'سیستم‌های پیشنهاد دهنده',
                            'voice_synthesis' => 'تولید صدا',
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Section::make('راهنما')
                        ->description('در این بخش می‌توانید مدل‌های هوش مصنوعی مختلف را اضافه کنید. مدل‌ها شامل توضیحات، مزایا، معایب و دسته‌بندی‌ها هستند. شما می‌توانید لیست مدل‌ها را از طریق همین پنل مدیریت مشاهده و ویرایش کنید.')
                        ->columns(1),
                ])->columns(12)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام مدل'),
                TextColumn::make('identifier')->label('شناسه مدل'),
                TextColumn::make('category')->label('دسته‌بندی'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiModels::route('/'),
            'create' => Pages\CreateAiModel::route('/create'),
            'edit' => Pages\EditAiModel::route('/{record}/edit'),
        ];
    }
}
