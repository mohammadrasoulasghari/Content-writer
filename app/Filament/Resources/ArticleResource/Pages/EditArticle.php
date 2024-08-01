<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label("عنوان مقاله")
                    ->required()
                    ->columnSpan(1),
                TagsInput::make('keywords')
                    ->required()
                    ->columnSpan(1)
                    ->label('کلید واژه های  مقاله')
                    ->placeholder('بعد از وارد کردن هر کلید واژه یه enter بزنید'),
                    TinyEditor::make('content')
                        ->required()
                        ->columnSpan('full')
                        ->language('fa')
                        ->showMenuBar()->template('content ')

                        ,
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
