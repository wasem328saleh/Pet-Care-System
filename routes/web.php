<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    $youtubeLink = 'https://www.youtube.com/watch?v=OKMgyF5ezFs';
    $pattern = '/(?:https?:\/\/(?:www\.)?youtube\.com\/watch\?v=|https?:\/\/youtu\.be\/|https?:\/\/(?:www\.)?youtube\.com\/embed\/)([\w-]{11})/';
    preg_match($pattern, $youtubeLink, $matches);
    $videoId=$matches[1] ?? null;
    echo $videoId;
});

Route::get('/validate-youtube-video', [App\Http\Controllers\Controller::class,'validateVideo']);
