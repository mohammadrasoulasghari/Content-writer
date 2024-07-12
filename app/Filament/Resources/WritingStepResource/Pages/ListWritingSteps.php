<?php

namespace App\Filament\Resources\WritingStepResource\Pages;

use App\Filament\Resources\WritingStepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWritingSteps extends ListRecords
{
    protected static string $resource = WritingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
