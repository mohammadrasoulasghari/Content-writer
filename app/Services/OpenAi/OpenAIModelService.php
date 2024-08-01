<?php
namespace App\Services\OpenAi;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class OpenAIModelService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function listModels()
    {
        $cacheKey = 'openai_models';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = $this->client->get('https://api.openai.com/v1/models', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        Cache::put($cacheKey, $data, 3600);

        return $data;
    }
}
