<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GoogleSearchTest extends DuskTestCase
{

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testGoogleSearch()
    {
        $searchQuery = getenv('SEARCH_QUERY');
        $resultCount = getenv('RESULT_COUNT') ?: 3; // گرفتن آرگومان تعداد نتایج یا مقدار پیش‌فرض

        if (empty($searchQuery)) {
            $this->fail('The search query parameter is required.');
        }

        $this->browse(function (Browser $browser) use ($searchQuery, $resultCount) {
            $browser->visit('https://www.google.com/search?q=' . urlencode($searchQuery))
                ->pause(3000);

            $results = $browser->elements('.g');
            $output = [];

            foreach (array_slice($results, 0, $resultCount) as $result) {
                $output[] = $result->getText();
            }

            echo json_encode($output);
        });
    }
}
