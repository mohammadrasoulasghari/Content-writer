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
        $this->browse(function (Browser $browser) {
            $browser->visit('https://www.google.com/search?q=site:7learn.com+pwa')
                ->pause(3000); // برای اطمینان از بارگذاری کامل صفحه

            $results = $browser->elements('.g');
            $output = [];

            // نمایش سه نتیجه اول
            foreach (array_slice($results, 0, 3) as $result) {
                $output[] = $result->getText();
            }

            // چاپ نتایج به صورت JSON
            echo json_encode($output);
        });
    }
}
