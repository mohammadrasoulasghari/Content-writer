<?php

namespace App\Services\Scraping\GoogleChrome;

class GoogleChromeScrapingService
{
    public static function scrape(string $searchQuery, int $resultCount = 3): array
    {
        $basePath = base_path();

        $output = shell_exec("cd $basePath && SEARCH_QUERY=\"$searchQuery\" RESULT_COUNT=\"$resultCount\" php artisan dusk --filter=GoogleSearchTest");

        preg_match('/\[(.*?)\]/s', $output, $matches);
        return isset($matches[0]) ? json_decode($matches[0], true) : [];
    }
}
