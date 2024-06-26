<?php

namespace App\Services\OpenAi;

use App\Models\AiModel;
use OpenAI\Laravel\Facades\OpenAI;

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

    public function createChat(string $prompt)
    {
        $model = AIModel::find($this->aiModelId);
        if (!$model) {
            throw new \Exception('AI model not found');
        }
        return OpenAI::chat()->create([
            'model' => $model->identifier,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
                ['role' => 'system', 'content' => $this->systemPrompt],
                ['role' => 'assistant', 'content' => $this->assistantPrompt],
            ],
        ]);
    }
}
