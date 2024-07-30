<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::controller(FileController::class)->group(function () {
        Route::post('/upload', 'uploadFile')->name("admin.uploadFile");
    });

    Route::prefix('announcement')->group(function () {
        Route::controller(AnnouncementController::class)->group(function () {
            Route::get('/', 'index')->name("admin.announcementIndex");
            Route::get('/create', 'create')->name("admin.announcementCreate");
            Route::post('/store', 'store')->name("admin.announcementStore");
            Route::get('/{announcement}/edit', 'edit')->name("admin.announcementEdit");
            Route::patch('/{announcement}', 'update')->name("admin.announcementUpdate");
            Route::delete('/{announcement}', 'delete')->name("admin.announcementDelete");
        });
    });

    Route::prefix('question')->group(function () {
        Route::controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name("admin.questionIndex");
            Route::get('/create', 'create')->name("admin.questionCreate");
            Route::post('/store', 'store')->name("admin.questionStore");
            Route::get('/{question}/edit', 'edit')->name("admin.questionEdit");
            Route::patch('/{question}', 'update')->name("admin.questionUpdate");
            Route::delete('/{question}', 'delete')->name("admin.questionDelete");
        });
    });

    Route::prefix('review')->group(function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/', 'index')->name("admin.reviewIndex");
            Route::get('/create', 'create')->name("admin.reviewCreate");
            Route::post('/store', 'store')->name("admin.reviewStore");
            Route::get('/{review}/edit', 'edit')->name("admin.reviewEdit");
            Route::patch('/{review}', 'update')->name("admin.reviewUpdate");
            Route::delete('/{review}', 'delete')->name("admin.reviewDelete");
        });
    });
});
