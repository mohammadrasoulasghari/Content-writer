<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromptResource\Pages;
use App\Filament\Resources\PromptResource\RelationManagers;
use App\Models\Prompt;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromptResource extends Resource
{
    protected static ?string $model = Prompt::class;
    protected static ?string $label = 'پرامپت';
    protected static ?string $pluralLabel = 'پرامپت ها';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان')
                            ->placeholder('عنوان پرامپت را وارد کنید')
                            ->helperText('این عنوان برای شناسایی پرامپت استفاده می‌شود.')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\RichEditor::make('prompt')
                            ->label('پرامپت')
                            ->placeholder('پرامپت را وارد کنید')
                            ->helperText('پرامپت خود را در اینجا وارد کنید.')
                            ->required()
                            ->columnSpan(2),
                    ])
                    ->columns(2) // تعداد ستون‌های داخل کارت
                    ->columnSpan('full'), // کارت در تمام عرض صفحه قرار بگیرد
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPrompts::route('/'),
            'create' => Pages\CreatePrompt::route('/create'),
            'edit' => Pages\EditPrompt::route('/{record}/edit'),
        ];
    }
}
