<?php

namespace App\Jobs;

use App\Services\OpenAi\OpenAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOpenAIRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $aiModelId;
    protected string $prompt;
    protected $chatId;

    public function __construct(int $aiModelId, string $prompt, ?string $chatId = null)
    {
        $this->aiModelId = $aiModelId;
        $this->prompt = $prompt;
        $this->chatId = $chatId;
    }

    /**
     * @throws \Exception
     */
    public function handle(OpenAIService $openAIService)
    {
        $openAIService->createChat($this->prompt, $this->chatId);
    }
}
