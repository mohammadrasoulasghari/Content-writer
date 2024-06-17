<?php

namespace App\Livewire;

use App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService;
use Livewire\Component;

class SearchResult extends Component
{
    public $topic;


    public function render()
    {
        return view('livewire.search-result');
    }
}
