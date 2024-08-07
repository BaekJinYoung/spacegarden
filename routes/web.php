<?php

use App\Http\Controllers\admin\AnnouncementController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\FileController;
use App\Http\Controllers\admin\InquiryController;
use App\Http\Controllers\admin\PopupController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\admin\ReviewController;
use App\Http\Controllers\admin\YoutubeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::controller(FileController::class)->group(function () {
        Route::post('/upload', 'uploadFile')->name("admin.uploadFile");
    });

    Route::prefix('popup')->group(function () {
        Route::controller(PopupController::class)->group(function () {
            Route::get('/', 'index')->name("admin.popupIndex");
            Route::get('/create', 'create')->name("admin.popupCreate");
            Route::post('/store', 'store')->name("admin.popupStore");
            Route::get('/{popup}/edit', 'edit')->name("admin.popupEdit");
            Route::patch('/{popup}', 'update')->name("admin.popupUpdate");
            Route::delete('/{popup}', 'delete')->name("admin.popupDelete");
        });
    });

    Route::prefix('banner')->group(function () {
        Route::controller(BannerController::class)->group(function () {
            Route::get('/', 'index')->name("admin.bannerIndex");
            Route::get('/create', 'create')->name("admin.bannerCreate");
            Route::post('/store', 'store')->name("admin.bannerStore");
            Route::get('/{banner}/edit', 'edit')->name("admin.bannerEdit");
            Route::patch('/{banner}', 'update')->name("admin.bannerUpdate");
            Route::delete('/{banner}', 'delete')->name("admin.bannerDelete");
        });
    });

    Route::prefix('youtube')->group(function () {
        Route::controller(YoutubeController::class)->group(function () {
            Route::get('/', 'index')->name("admin.youtubeIndex");
            Route::get('/create', 'create')->name("admin.youtubeCreate");
            Route::post('/store', 'store')->name("admin.youtubeStore");
            Route::get('/{youtube}/edit', 'edit')->name("admin.youtubeEdit");
            Route::patch('/{youtube}', 'update')->name("admin.youtubeUpdate");
            Route::delete('/{youtube}', 'delete')->name("admin.youtubeDelete");
        });
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

    Route::prefix('inquiry')->group(function () {
        Route::controller(InquiryController::class)->group(function () {
            Route::get('/', 'index')->name("admin.inquiryIndex");
            Route::get('/{inquiry}/edit', 'edit')->name("admin.inquiryEdit");
            Route::delete('/{inquiry}', 'delete')->name("admin.inquiryDelete");
        });
    });
});
