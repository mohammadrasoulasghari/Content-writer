<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WritingStepResource\Pages;
use App\Models\WritingStep;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class WritingStepResource extends Resource
{
    protected static ?string $model = WritingStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-radio';
    protected static ?string $label = 'مرحله نوشتن';
    protected static ?string $pluralLabel = 'مراحل نوشتن';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('نام مرحله')
                                            ->required()
                                            ->helperText('نام مرحله نوشتن را مشخص کنید.')
                                            ->columnSpan(2),
                                        TextInput::make('order')
                                            ->label('ترتیب')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('ترتیب اجرای مرحله را مشخص کنید.')
                                            ->columnSpan(1),
                                        TextInput::make('max_tokens')
                                            ->label('حداکثر توکن مصرفی')
                                            ->numeric()
                                            ->default(1500)
                                            ->helperText('حداکثر تعداد توکن برای تولید محتوا.')
                                            ->columnSpan(1),
                                        TextInput::make('temperature')
                                            ->label('میزان دقت/خلاقیت')
                                            ->numeric()
                                            ->step(0.1)
                                            ->default(0.7)
                                            ->maxValue(1)
                                            ->minValue(0.1)
                                            ->helperText('مقدار بین 0 و 1 برای تعیین میزان خلاقیت.')
                                            ->columnSpan(1),
                                        Select::make('content_type_id')
                                            ->label('نوع محتوا')
                                            ->relationship('contentType', 'name')
                                            ->required()
                                            ->helperText('نوع محتوای مرتبط با این مرحله.')
                                            ->columnSpan(1),
                                    ]),
                                Textarea::make('prompt')
                                    ->label('پرامپت')
                                    ->required()
                                    ->helperText('پرامپت اصلی برای مدل هوش مصنوعی.')
                                    ->columnSpan(3)->rows(7),
                                Repeater::make('placeholders')
                                    ->label('متغیر جایگزینی')
                                    ->schema([
                                        Grid::make(1)
                                            ->schema([
                                                TextInput::make('key')
                                                    ->label('کلید')
                                                    ->required()
                                                    ->default('title')
                                                    ->hint('کلید جایگزینی باید منحصربه‌فرد باشد و نباید تکراری باشد.')
                                                    ->helperText('کلید جایگزینی برای متن‌های متغیر.')
                                                    ->columnSpan(2),
                                            ]),
                                    ])
                                    ->minItems(1)
                                    ->columnSpan(3),  // تنظیم اندازه برای کوچکتر شدن
                            ])
                            ->columns(6),
                        Toggle::make('status')
                            ->label('وضعیت')
                            ->inline(true)
                            ->default(true)
                            ->helperText('فعال یا غیرفعال بودن مرحله.')
                            ->columnSpan(1),
                    ])
                    ->columns(1),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام مرحله')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prompt')
                    ->label('پرامپت')
                    ->sortable()
                    ->searchable()
                    ->limit(25),
                TextColumn::make('order')
                    ->label('ترتیب')
                    ->sortable()
                    ->searchable(),
                BooleanColumn::make('status')
                    ->label('وضعیت')
                    ->sortable(),
                TextColumn::make('contentType.name')
                    ->label('نوع محتوا')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('content_type_id')
                    ->label('نوع محتوا')
                    ->relationship('contentType', 'name')
                    ->placeholder('همه انواع محتوا'),
                Filter::make('status')
                    ->label('فعال/غیرفعال')
                    ->query(fn(Builder $query): Builder => $query->where('status', true)),
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
            'index' => Pages\ListWritingSteps::route('/'),
            'create' => Pages\CreateWritingStep::route('/create'),
            'edit' => Pages\EditWritingStep::route('/{record}/edit'),
        ];
    }
}
