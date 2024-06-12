<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;
    #[NoReturn] protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd('hi');

        $data['content'] = $response->json('choices')[0]['text'];

        return $data;
    }
}
