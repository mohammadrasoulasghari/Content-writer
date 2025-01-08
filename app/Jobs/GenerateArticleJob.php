<?php

namespace App\Jobs;

use App\Models\ArticleTopic;
use App\Services\ArticleGeneratorService;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateArticleJob
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $articleTopic = ArticleTopic::where('status', 'pending')->inRandomOrder()->first();
        if ($articleTopic) {
            $articleGenerator = new ArticleGeneratorService();
            $articleGenerator->generateArticle($articleTopic);
        }
    }
}
