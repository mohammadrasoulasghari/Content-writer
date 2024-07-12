<?php

namespace App\Filament\Resources\WritingStepResource\Pages;

use App\Filament\Resources\WritingStepResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWritingStep extends EditRecord
{
    protected static string $resource = WritingStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
