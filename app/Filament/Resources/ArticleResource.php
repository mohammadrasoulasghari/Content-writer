<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $label = 'مقاله';
    protected static ?string $pluralLabel = 'مقالات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('title')
                        ->label("عنوان مقاله")
                        ->required()
                        ->columnSpan(2),
                    Textarea::make('description')
                        ->label("سناریو ")
                        ->columnSpan(2),
                    TagsInput::make('keywords')
                        ->required()
                        ->columnSpan(2)
                        ->label('کلید واژه های  مقاله')
                        ->placeholder('بعد از وارد کردن هر کلید واژه یه enter بزنید'),
                    Repeater::make('english_texts')
                        ->maxItems(3)
                        ->label("متون انگلیسی")
                        ->schema([
                            Textarea::make('متن')
                                ->columns(2),
                        ])
                        ->columnSpan(2)
                ])
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('عنوان مقاله'),
                Tables\Columns\TextColumn::make('keywords')->label('کلمات کلیدی'),
                Tables\Columns\TextColumn::make('description')->label('توضیحات'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }


}
