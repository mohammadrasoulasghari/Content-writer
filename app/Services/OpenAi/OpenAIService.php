<?php

namespace App\Services\OpenAi;

use App\Models\AiModel;
use Illuminate\Support\Facades\Cache;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService
{
    private int $aiModelId;
    private static array $prompts = [
        'system' => "هدف از این مقاله ارائه یک معرفی کامل و صمیمی از موضوع به خواننده است. لحن باید دوستانه و به صورت دوم شخص مفرد باشد و شامل مثال‌های جذاب و داستانی باشد. لطفاً خروجی را با تگ‌های HTML ارائه کن تا در ویرایشگر به خوبی نمایش داده شود.",
        'assistant' => "از اطلاعات داده شده استفاده کن تا یک مقاله دوستانه و صمیمی ایجاد کنی که به خواننده احساس راحتی و ارتباط مستقیم بدهد. باید ساده و با مثال‌های جالب برای نسل Z نوشته شود."
    ];


    public function __construct(int $aiModelId)
    {
        $this->aiModelId = $aiModelId;

    }

    public function createChat(string $prompt, ?string $chatId = null, int $maxTokens = 1000, float $temperature = 0.7)
    {
        $cacheKey = md5($prompt . $maxTokens . $temperature . ($chatId ?? ''));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $model = AiModel::find($this->aiModelId);
        if (!$model) {
            throw new \Exception('مدل هوش مصنوعی پیدا نشد.');
        }

        $messages = [
            ['role' => 'user', 'content' => $prompt],
            ['role' => 'system', 'content' => $this->getPrompt('system')],
            ['role' => 'assistant', 'content' => $this->getPrompt('assistant')],
        ];
        if ($chatId) {
            $messages[] = ['role' => 'system', 'content' => "\n" . $chatId];
        }

        $response = OpenAI::chat()->create([
            'model' => $model->identifier,
            'messages' => $messages,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);

        Cache::put($cacheKey, $response, now()->addMinutes(10));

        return $response;
    }

    private function getPrompt(string $role): string
    {
        return self::$prompts[$role] ?? '';
    }
}
