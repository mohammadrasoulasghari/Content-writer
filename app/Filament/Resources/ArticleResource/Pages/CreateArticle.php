<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\Models\WritingStep;
use App\Models\RequestLog;
use App\Services\OpenAi\OpenAIService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    #[NoReturn]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            unset($data['english_texts']);

            $writingSteps = WritingStep::where('content_type_id', $data['content_type_id'])
                ->orderBy('order')
                ->get();

            $content = '';
            $chatId = null;

            DB::transaction(function () use ($writingSteps, $data, &$content, &$chatId) {
                foreach ($writingSteps as $step) {
                    $prompt = $this->generatePrompt($step->prompt, $data['title']);
                    $response = $this->sendToOpenAI($data['ai_model_id'], $prompt, $chatId, $step->max_tokens, $step->temperature);
                    $request_log = RequestLog::create([
                        'loggable_type' => Article::class,
                        'loggable_id' => 0, // موقت، بعد از ایجاد مقاله به روز رسانی می‌شود
                        'writing_step_id' => $step->id,
                        'request' => $prompt,
                        'response' => $response['choices'][0]['message']['content'] ?? null,
                        'sequence' => $step->order,
                        'chat_id' => $response['id'] ?? null,
                    ]);

                    $content .= ($response['choices'][0]['message']['content'] ?? '') . "\n";
                    $chatId = $response['id'] ?? $chatId;
                }
            });

            $data['content'] = $this->cleanHtmlTagsFromContent($content);
        } catch (\Exception $e) {
            $this->handleException($e);
            return $data;
        }

        return $data;
    }

    private function generatePrompt(string $template, string $title): string
    {
        return str_replace('{title}', $title, $template);
    }

    /**
     * @throws \Exception
     */
    private function sendToOpenAI(int $aiModelId, string $prompt, ?string $chatId, int $maxTokens, float $temperature)
    {
        $openAIService = new OpenAIService($aiModelId, 'لطفا خروجی را با تگ html بده', 'دستیار');
        return $openAIService->createChat($prompt, $chatId, $maxTokens, $temperature);
    }

    private function handleException(\Exception $e): void
    {
        Log::error('Error while creating article: ' . $e->getMessage(), [
            'exception' => $e,
        ]);

        Notification::make()
            ->title('خطا')
            ->body('خطا در حین ایجاد مقاله: ' . $e->getMessage())
            ->danger()
            ->send();
    }

    protected function afterCreate(): void
    {
        RequestLog::where('loggable_id', 0)
            ->update(['loggable_id' => $this->record->getKey()]);
        $this->redirect(ArticleResource::getUrl('edit', ['record' => $this->record->getKey()]));
    }

    protected function cleanHtmlTagsFromContent($content): array|string|null
    {
        return preg_replace('/```html|```/', '', $content);
    }

}
