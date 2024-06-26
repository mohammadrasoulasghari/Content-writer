<?php

namespace App\Services\Articles;

use App\Models\Prompt;
use Filament\Notifications\Notification;

class PromptService
{
    private array $titles;

    public function __construct(array $titles)
    {
        $this->titles = $titles;
    }

    public function getPrompts()
    {
        $prompts = Prompt::whereIn('title', $this->titles)->pluck('prompt', 'title')->toArray();

        $missingPrompts = array_diff($this->titles, array_keys($prompts));
        if (!empty($missingPrompts)) {
            $missingPromptsString = implode(', ', $missingPrompts);
            Notification::make()
                ->title('Error')
                ->body("پرامپت‌های زیر یافت نشدند: $missingPromptsString")
                ->danger()
                ->send();

            throw new \Exception("Missing prompts: $missingPromptsString");
        }

        return $prompts;
    }
}
