<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Models\Topic;
use App\Models\User;
use App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Table;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?string $label = 'موضوع پیشنهادی';
    protected static ?string $pluralLabel = 'موضوعات پیشنهادی';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    TextInput::make('title')
                        ->required()
                        ->label('عنوان')
                        ->columnSpanFull(),
                    TextInput::make('english_name')
                        ->required()
                        ->label('نام به انگلیسی')
                        ->columnSpan(1),
                    TextInput::make('slug')
                        ->required()
                        ->label('Slug')
                        ->unique(ignoreRecord: true)
                        ->columnSpan(1),
                    Textarea::make('description')
                        ->label('توضیحات')
                        ->columnSpanFull(),
                    DatePicker::make('approved_at')
                        ->label('تاریخ تایید')
                        ->default(null)
                        ->visible(fn ($livewire) => $livewire->record && $livewire->record->is_approved)
                        ->columnSpan(1),
                    Select::make('priority')
                        ->label('اولویت')
                        ->options([
                            1 => '1',
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                            7 => '7',
                            8 => '8',
                            9 => '9',
                            10 => '10',
                        ])
                        ->default(0)
                        ->helperText("به ترتیب ۱۰ بیشترین و ۱ کمترین")
                        ->columnSpan(1),
                    Select::make('author_id')
                        ->label('نویسنده')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload()
                        ->columnSpan(1),
                    Toggle::make('is_approved')
                        ->label('تایید شده')
                        ->default(false)
                        ->columnSpan(1),
                ])->columns(2), // تعداد ستون‌ها را مشخص کنید
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان'),
                TextColumn::make('english_name')->label('نام به انگلیسی'),
                TextColumn::make('slug')->label('Slug'),
                TextColumn::make('description')->label('توضیحات'),
                TextColumn::make('priority')->label('اولویت'),
                TextColumn::make('tags')->label('برچسب‌ها'),
                TextColumn::make('author.name')->label('نویسنده'),
                TextColumn::make('related_articles_count')->label('تعداد مقالات مرتبط'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewResults')
                    ->label('مشاهده نتایج')
                    ->modalHeading('نتایج جستجو')
                    ->modalContent(fn (Topic $topic) => view(
                        "livewire.search-result",
                        ["topic" => $topic]
                    ))
                    ->modalButton('بستن')

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}
