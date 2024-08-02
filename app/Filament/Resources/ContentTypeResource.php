<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentTypeResource\Pages;
use App\Filament\Resources\ContentTypeResource\RelationManagers;
use App\Models\ContentType;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentTypeResource extends Resource
{
    protected static ?string $model = ContentType::class;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $label = 'نوع محتوا';
    protected static ?string $pluralLabel = 'دسته بندی محتوا';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('نام نوع محتوا')
                            ->required(),
                        Textarea::make('description')
                            ->label('توضیحات')
                            ->columnSpanFull(),
                        Textarea::make('system_prompt')
                            ->label('پرامپت سیستم')
                            ->helperText('این پرامپت به عنوان راهنمایی برای سیستم استفاده می‌شود.')
                            ->nullable()
                            ->columnSpanFull(),

                        Textarea::make('assistant_prompt')
                            ->label('پرامپت دستیار')
                            ->helperText('این پرامپت به عنوان راهنمایی برای دستیار استفاده می‌شود.')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام نوع محتوا')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('توضیحات')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('name')
                    ->label('نام')
                    ->query(fn (Builder $query): Builder => $query->where('name', true)),
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
            'index' => Pages\ListContentTypes::route('/'),
            'create' => Pages\CreateContentType::route('/create'),
            'edit' => Pages\EditContentType::route('/{record}/edit'),
        ];
    }
}
