<?php

namespace App\Filament\Resources\WritingStepResource\Pages;

use App\Filament\Resources\WritingStepResource;
use App\Models\WritingStep;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateWritingStep extends CreateRecord
{
    protected static string $resource = WritingStepResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (WritingStep::where('order', $data['order'])->exists()) {
            Notification::make()
                ->title('خطا')
                ->body('ترتیب این مرحله قبلا انتخاب شده است.')
                ->danger()
                ->send();

            throw new \Exception('ترتیب این مرحله قبلا انتخاب شده است.');
        }

        return $data;
    }
}
