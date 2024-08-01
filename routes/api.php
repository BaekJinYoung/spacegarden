<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InquiryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(InquiryController::class)->group(function () {
    Route::post('/inquiry', 'store');
});

Route::controller(IndexController::class)->group(function () {
    Route::get('/main', 'mainRespond');
    Route::get('/announcement', 'announcement');
    Route::get('/question', 'question');
    Route::get('/review', 'review');
    Route::get('/sns', 'sns');
    Route::get('/inquiry', 'inquiry');
});

Route::controller(DetailController::class)->group(function () {
    Route::get('/company/{id}', 'company_detail');
    Route::get('/youtube/{id}', 'youtube_detail');
    Route::get('/announcement/{id}', 'announcement_detail');
    Route::get('/share/{id}', 'share_detail');
    Route::get('download', 'downloadFile');
});
