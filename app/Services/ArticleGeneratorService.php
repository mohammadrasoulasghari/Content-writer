<?php

namespace App\Services;

use App\Enums\SupportedServices;
use App\Models\Article;
use App\Models\ArticleTopic;
use App\Models\ContentType;
use Illuminate\Support\Facades\Log;

class ArticleGeneratorService
{
    public function generateArticle(ArticleTopic $topic, SupportedServices $supportedServices = SupportedServices::AVALAI)
    {
        Log::info("creating article generator");
        $prompt = "یه مقاله ۵۰۰ کلمه ای میخوام درباره ی " . $topic->topic;

        $serive = (new AiServiceResolver($supportedServices))->initializeClient($supportedServices);

        $response = $serive->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $content = $response['choices'][0]['message']['content'];

        $article = Article::query()->create([
            'title' => $topic->topic,
            'topic_id' => $topic->id,
            'content' => $content,
            'content_type_id' =>ContentType::query()->firstWhere('name','مقالات ساده مستقیم ساز')->id,
        ]);

        $topic->status = 'completed';
        $topic->save();

        return $article;
    }

}
