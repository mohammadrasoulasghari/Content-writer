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
    return redirect('/admin');
});
Route::get('/s', function () {
    $r = GoogleChromeScrapingService::scrape('site:7learn.com pwa');
    dd($r);
});
