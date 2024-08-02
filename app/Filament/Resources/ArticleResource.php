<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\AiModel;
use App\Models\Article;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                    Select::make('content_type_id')
                        ->label('نوع محتوا')
                        ->relationship('contentType', 'name')
                        ->required(),
                    Textarea::make('description')
                        ->label("سناریو ")
                        ->columnSpan(2),
                    TagsInput::make('keywords')
                        ->required()
                        ->columnSpan(2)
                        ->label('کلید واژه های  مقاله')
                        ->placeholder('بعد از وارد کردن هر کلید واژه یه enter بزنید'),
                    Select::make('ai_model_id')
                        ->label('مدل هوش مصنوعی مدنظر')
                        ->relationship('aiModel', 'name')
                        ->required(),
                    Repeater::make('english_texts')
                        ->maxItems(3)
                        ->label("متون انگلیسی")
                        ->schema([
                            Textarea::make('متن')
                                ->columns(2),
                        ])
                        ->columnSpan(2),
                ])
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان مقاله')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                // اضافه کردن ستون جدید برای نمایش نام مدل هوش مصنوعی
                TextColumn::make('aiModel.name')
                    ->label('مدل هوش مصنوعی')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                // اضافه کردن ستون جدید برای نمایش نوع محتوا
                TextColumn::make('contentType.name')
                    ->label('نوع محتوا')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->sortable()
                    ->dateTime('d-m-Y'),
            ])
            ->filters([
                SelectFilter::make('ai_model_id')
                    ->label('مدل هوش مصنوعی')
                    ->relationship('aiModel', 'name')
                    ->placeholder('همه مدل‌ها'),

                SelectFilter::make('content_type_id')
                    ->label('نوع محتوا')
                    ->relationship('contentType', 'name')
                    ->placeholder('همه انواع محتوا'),

                Filter::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->form([
                        DatePicker::make('created_from')->label('از تاریخ'),
                        DatePicker::make('created_to')->label('تا تاریخ'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_to'], fn(Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }


}
