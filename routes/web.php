<?php

use App\Http\Controllers\TopicImportController;
use App\Services\GoogleSearchService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Tests\Browser\GoogleSearchTest;

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
    $basePath = base_path();

    // تغییر مسیر به دایرکتوری پروژه و اجرای دستور تست Dusk
    $output = shell_exec("cd $basePath && php artisan dusk --filter=GoogleSearchTest");

    // استخراج خروجی JSON از خروجی کامل دستور
    preg_match('/\[(.*?)\]/s', $output, $matches);
    $results = isset($matches[0]) ? json_decode($matches[0], true) : [];

    dd($results);
});
