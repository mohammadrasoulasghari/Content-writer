<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use OpenAI\Laravel\Facades\OpenAI;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    #[NoReturn] protected function mutateFormDataBeforeCreate(array $data): array
    {
        $keyWords = implode(',',$data['keywords']);

        if (!empty($keyWords)) {
            $keyWordPrompt = "
            بیا از این کلمات کلیدی حتما توی مقاله استفاده کن  $keyWords
            ";
        }
        $prompt = "
        می خوام یه مقاله عالی و با جزئیات کامل و مفید برای خواننده برای من بنویسی.
        موضوع مقاله: {$data['title']}
        نوع مقاله: معرفی زبان یا تکنولوژی یا ابزار
        لحن مقاله: صمیمی و خطاب به دوم شخص مفرد و با مثال های با مزه و جذاب برای نسل z (رعایت این لحن در تمام مقاله بسیار ضروری است)
        اندازه مقاله: 2000 کلمه (لطفا با جزئیات و توضیحات کامل بنویس که اندازه مقاله حتما بالاتر از این تعداد کلمات باشد)
        ";

        if ($keyWordPrompt) {
            $prompt .= $keyWordPrompt;
        }

        if (!empty($data['description'])){
            $prompt.= $data['description'];
        }


        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
                ['role' => 'system', 'content' => "تو باید توی کل محتوایی که مینویسی همیشه لحن ات صمیمی باشه و هیچ جا رسمی نگو و حتما خروجی ات رو با تگ های html بده و heading رو توی مقاله ات رعایت کن"],
            ],
        ]);
        $data['content'] = $response->choices[0]->message->content;
        $data['chat_id'] = $response->id;
        $data['keywords'] = $response->id;
        $data['description'] = $response->id;
        Log::info($response->choices[0]->message->content);
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->redirect(ArticleResource::getUrl('edit', ['record' => $this->record->getKey()]));
    }
}
