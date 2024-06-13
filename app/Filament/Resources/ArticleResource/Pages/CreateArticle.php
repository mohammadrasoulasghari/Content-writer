<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Prompt;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    #[NoReturn]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $keyWords = $this->formatKeywords($data['keywords'] ?? []);
        $englishSentences = $this->formatEnglishSentences($data['english_sentences'] ?? []);

        $promptTemplate = Prompt::where('title', 'main')->first()->prompt;
        $prompt = $this->generatePrompt($promptTemplate, $data['title'], $data['description'] ?? '', $keyWords, $englishSentences);
        $response = $this->getOpenAIResponse($prompt);

        $data['content'] = $response['choices'][0]['message']['content'] ?? '';
        $data['chat_id'] = $response['id'] ?? '';
        return $data;
    }

    private function formatKeywords(array $keywords): string
    {
        return implode(',', $keywords);
    }

    private function formatEnglishSentences(array $sentences): string
    {
        return implode('. ', $sentences);
    }

    private function generatePrompt(string $template, string $title, string $description, string $keywords, string $englishSentences): string
    {
        $prompt = str_replace('{title}', $title, $template);

        if (!empty($keywords)) {
            $prompt .= "\nبیا از این کلمات کلیدی حتما توی مقاله استفاده کن: $keywords";
        }

        if (!empty($description)) {
            $prompt .= "\n$description";
        }

        if (!empty($englishSentences)) {
            $prompt .= "\nلطفاً این جملات انگلیسی را نیز در مقاله بگنجان: $englishSentences";
        }

        return $prompt;
    }

    private function getOpenAIResponse(string $prompt)
    {
        return OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
                ['role' => 'system', 'content' => "تو باید توی کل محتوایی که مینویسی همیشه لحن ات صمیمی باشه و هیچ جا رسمی نگو و حتما خروجی ات رو با تگ های html بده و heading رو توی مقاله ات رعایت کن"],
                [
                    'role' => 'assistant',
                    'content' => "لطفاً اطلاعات بیشتری در مورد موضوع مقاله و جزئیات مورد نیاز فراهم کنید تا بتوانم دقیق‌تر پاسخ بدهم."
                ]
            ],
        ]);
    }

    protected function afterCreate(): void
    {
        $this->redirect(ArticleResource::getUrl('edit', ['record' => $this->record->getKey()]));
    }
}
