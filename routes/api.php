<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('/main', 'mainRespond');
    Route::get('/announcement', 'announcement');
    Route::get('/question', 'question');
    Route::get('/review', 'review');
    Route::get('/inquiry', 'inquiry');
});

//Route::get('/blog', [BlogController::class, 'getBlogPosts']);
//Route::get('/instagram', [BlogController::class, 'getInstagramPosts']);
//Route::get('/youtube', [YoutubeController::class, 'index']);
//Route::get('/redirect', [YoutubeController::class, 'index']);

Route::controller(DetailController::class)->group(function () {
    Route::get('/announcement/{id}', 'announcement_detail');
    Route::get('/review/{id}', 'review_detail');
    Route::get('download', 'downloadFile');
});

Route::controller(InquiryController::class)->group(function () {
    Route::post('/inquiry', 'store');
});
