<?php
namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Prompt;
use App\Services\Articles\PromptService;
use App\Services\OpenAi\OpenAIService;
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;
use Filament\Notifications\Notification;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    #[NoReturn]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $titles = ['main', 'system', 'assistant', 'keywords', 'description', 'english_sentences'];
            $promptService = new PromptService($titles);

            $prompts = $promptService->getPrompts();

            $keyWords = $this->generatePrompt($prompts['keywords'], '', '', implode(',', $data['keywords'] ?? []), '');
            $description = $this->generatePrompt($prompts['description'], '', $data['description'] ?? '', '', '');
            $englishSentences = $this->generatePrompt($prompts['english_sentences'], '', '', '', implode('. ', $data['english_sentences'] ?? []));

            $systemPrompt = $prompts['system'];
            $assistantPrompt = $prompts['assistant'];
            $aiModelId = $data['ai_model_id'];
            $openAIService = new OpenAIService($aiModelId, $systemPrompt, $assistantPrompt);
            $mainPrompt = $this->generatePrompt($prompts['main'], $data['title'], $description, $keyWords, $englishSentences);
            $response = $openAIService->createChat($mainPrompt);

            if (!isset($response['choices'][0]['message']['content'])) {
                throw new \Exception('Main prompt response not received.');
            }
            $data['content'] = $response['choices'][0]['message']['content'];
            $data['chat_id'] = $response['id'];
            $data['prompts'] = $mainPrompt;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('خطا در حین ایجاد مقاله => ' . $e->getMessage())
                ->danger()
                ->send();

            return $data;
        }

        return $data;
    }

    private function generatePrompt(string $template, string $title, string $description, string $keywords, string $englishSentences): string
    {
        $prompt = str_replace('{title}', $title, $template);
        $prompt = str_replace('{keywords}', $keywords, $prompt);
        $prompt = str_replace('{description}', $description, $prompt);
        $prompt = str_replace('{english_sentences}', $englishSentences, $prompt);

        // Add specific instructions to ensure detailed and comprehensive content
        $prompt .= "\nلطفا مقاله ای جامع و کامل با حداقل 5000 کلمه بنویسید که شامل توضیحات دقیق و مثال های کاربردی باشد.";
        $prompt .= "\nلطفاً از لحن صمیمی و دوستانه استفاده کنید و سعی کنید مقاله را جذاب و گیرا نگه دارید.";
        $prompt .= "\nدر طول مقاله به نقل قول ها و منابع معتبر اشاره کنید و برای هر بخش از تگ های HTML مناسب استفاده کنید.";

        return $prompt;
    }

    protected function afterCreate(): void
    {
        $this->redirect(ArticleResource::getUrl('edit', ['record' => $this->record->getKey()]));
    }
}
