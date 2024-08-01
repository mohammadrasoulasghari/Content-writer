<?php

namespace App\Services\OpenAi;

use App\Models\AiModel;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    private int $aiModelId;
    private string $systemPrompt;
    private string $assistantPrompt;

    public function __construct(int $aiModelId, string $systemPrompt, string $assistantPrompt)
    {
        $this->aiModelId = $aiModelId;
        $this->systemPrompt = $systemPrompt;
        $this->assistantPrompt = $assistantPrompt;
    }

    public function createChat(string $prompt, ?string $chatId = null, int $maxTokens = 1000)
    {
        $cacheKey = md5($prompt . $maxTokens . ($chatId ?? ''));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $model = AiModel::find($this->aiModelId);
        if (!$model) {
            throw new \Exception('مدل هوش مصنوعی پیدا نشد.');
        }

        $messages = [
            ['role' => 'user', 'content' => $prompt],
            ['role' => 'system', 'content' => $this->systemPrompt],
            ['role' => 'assistant', 'content' => $this->assistantPrompt],
        ];
        if ($chatId) {
            $messages[] = ['role' => 'system', 'content' => "\n" . $chatId];
        }

        $response = OpenAI::chat()->create([
            'model' => $model->identifier,
            'messages' => $messages,
            'max_tokens' => $maxTokens
        ]);

        Cache::put($cacheKey, $response, now()->addMinutes(10));

        return $response;
    }
}
