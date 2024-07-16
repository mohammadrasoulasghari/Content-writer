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
use JetBrains\PhpStorm\NoReturn;
use App\Jobs\ProcessOpenAIRequest;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    #[NoReturn]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            unset($data['english_texts']);//موقت فعلا
            $writingSteps = WritingStep::where('content_type_id', $data['content_type_id'])
                ->orderBy('order')
                ->get();

            $content = '';
            $chatId = null;

            DB::transaction(function () use ($writingSteps, $data, &$content, &$chatId) {
                foreach ($writingSteps as $step) {
                    $prompt = $this->generatePrompt($step->prompt, $data['title']);
                    ProcessOpenAIRequest::dispatch($data['ai_model_id'], $prompt, $chatId)
                        ->onQueue('openai');

                    $response = $this->sendToOpenAI($data['ai_model_id'], $prompt, $chatId);

                    RequestLog::create([
                        'loggable_type' => Article::class,
                        'loggable_id' => 0,
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
            $data['content'] = $content;
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

    private function sendToOpenAI(int $aiModelId, string $prompt, ?string $chatId)
    {
        $openAIService = new OpenAIService($aiModelId, 'سیستم', 'دستیار');
        return $openAIService->createChat($prompt, $chatId);
    }

    private function handleException(\Exception $e): void
    {
        \Log::error('Error while creating article: ' . $e->getMessage(), [
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
}
