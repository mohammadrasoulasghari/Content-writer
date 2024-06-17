<?php

namespace App\Models;

use App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $guarded = [];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function searchResult(): array
    {
        return GoogleChromeScrapingService::scrape("site:7learn.com" . $this->title);
    }
}
