<?php

use App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $result = GoogleChromeScrapingService::scrape("site:7learn.com powerbi",5);

    dd($result);
});
