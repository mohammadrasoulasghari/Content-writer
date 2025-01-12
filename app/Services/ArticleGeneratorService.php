<?php

namespace App\Services;

use App\Enums\SupportedServices;
use App\Models\Article;
use App\Models\ArticleTopic;
use App\Models\ContentType;
use Illuminate\Support\Facades\Log;

class ArticleGeneratorService
{
    private const PLACE_HOLDER_KEY = 'title';

    public static function generateArticle(ArticleTopic $topic, SupportedServices $supportedServices = SupportedServices::AVALAI)
    {
        $base_prompt = get_prompt("articles_group");

        $prompt = replace_delimited_placeholder($base_prompt, $topic->topic, self::PLACE_HOLDER_KEY);

        $serive = (new AiServiceResolver($supportedServices))->initializeClient($supportedServices);

        $response = $serive->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 100000,
        ]);

        $content = $response['choices'][0]['message']['content'];
        $article = Article::query()->create([
            'title' => $topic->topic,
            'topic_id' => $topic->id,
            'content' => $content,
            'content_type_id' => ContentType::query()->firstWhere('name', 'مقالات ساده مستقیم ساز')->id,
        ]);

        $topic->status = 'completed';
        $topic->save();

        return $article;
    }

}
