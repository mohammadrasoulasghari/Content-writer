<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WritingStepResource\Pages;
use App\Models\WritingStep;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                        Grid::make(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('نام مرحله')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('order')
                                    ->label('ترتیب')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                                Toggle::make('status')
                                    ->label('وضعیت')
                                    ->inline(false)
                                    ->default(true)
                                    ->columnSpan(1),
                            ]),
                        Textarea::make('prompt')
                            ->label('پرامپت')
                            ->required()
                            ->columnSpanFull(),
                        Repeater::make('placeholders')
                            ->label('متغیرهای جایگزینی')
                            ->helperText('مثال: {subject} که به موضوع مقاله جایگزین می‌شود. متغیر پیش‌فرض: {title}')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('key')
                                            ->label('کلید')
                                            ->required()
                                            ->default('title')
                                            ->hint('کلید جایگزینی باید منحصربه‌فرد باشد و نباید تکراری باشد.')
                                            ->columnSpan(1),
                                    ]),
                            ])
                            ->columnSpanFull()
                            ->minItems(2),
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
            ])
            ->filters([
                Filter::make('status')
                    ->label('فعال/غیرفعال')
                    ->query(fn (Builder $query): Builder => $query->where('status', true)),
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
