<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
<<<<<<< HEAD
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use OpenAI\Laravel\Facades\OpenAI;
=======
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Prompt;
>>>>>>> Dusk

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

<<<<<<< HEAD
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
=======
    #[NoReturn]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $keyWords = $this->formatKeywords($data['keywords'] ?? []);
        $englishSentences = $this->formatEnglishSentences($data['english_sentences'] ?? []);

        $promptTemplate = Prompt::where('title', 'main')->first()->prompt;
        $prompt = $this->generatePrompt($promptTemplate, $data['title'], $data['description'] ?? '', $keyWords, $englishSentences);
        $response = $this->getOpenAIResponse($prompt);

        $data['content'] = $response['choices'][0]['message']['content'] ?? '';
        $data['chat_id'] = $response['id'] ?? '';
        return $data;
    }

    private function formatKeywords(array $keywords): string
    {
        return implode(',', $keywords);
    }

    private function formatEnglishSentences(array $sentences): string
    {
        return implode('. ', $sentences);
    }

    private function generatePrompt(string $template, string $title, string $description, string $keywords, string $englishSentences): string
    {
        $prompt = str_replace('{title}', $title, $template);

        if (!empty($keywords)) {
            $prompt .= "\nبیا از این کلمات کلیدی حتما توی مقاله استفاده کن: $keywords";
        }

        if (!empty($description)) {
            $prompt .= "\n$description";
        }

        if (!empty($englishSentences)) {
            $prompt .= "\nلطفاً این جملات انگلیسی را نیز در مقاله بگنجان: $englishSentences";
        }

        return $prompt;
    }

    private function getOpenAIResponse(string $prompt)
    {
        return OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
                ['role' => 'system', 'content' => "تو باید توی کل محتوایی که مینویسی همیشه لحن ات صمیمی باشه و هیچ جا رسمی نگو و حتما خروجی ات رو با تگ های html بده و heading رو توی مقاله ات رعایت کن"],
                [
                    'role' => 'assistant',
                    'content' => "لطفاً اطلاعات بیشتری در مورد موضوع مقاله و جزئیات مورد نیاز فراهم کنید تا بتوانم دقیق‌تر پاسخ بدهم."
                ]
            ],
        ]);
>>>>>>> Dusk
    }

    protected function afterCreate(): void
    {
        $this->redirect(ArticleResource::getUrl('edit', ['record' => $this->record->getKey()]));
    }
}
