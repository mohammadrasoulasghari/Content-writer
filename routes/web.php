<?php

use App\Jobs\GenerateArticleJob;
use App\Models\ArticleTopic;
use App\Services\ArticleGeneratorService;
use App\Services\Scraping\GoogleChrome\GoogleChromeScrapingService;
use Illuminate\Support\Facades\Artisan;
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
    dd(ArticleGeneratorService::generateArticle(ArticleTopic::query()->firstOrFail()));
});
