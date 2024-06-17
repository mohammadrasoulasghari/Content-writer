<div>
    <h3>نتایج جستجو</h3>
    @php
        $results =  App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService::scrape("site:7learn.com".$topic->title);
    @endphp
    <ul style="list-style-type: none; padding: 0;">
        @foreach ($results as $result)
            <li style="border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; border-radius: 5px;">
                <div>
                    {!! nl2br(e($result)) !!}
                </div>
            </li>
        @endforeach
    </ul>
</div>

