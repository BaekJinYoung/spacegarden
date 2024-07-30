<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 제목
            $table->string('content'); // 내용
            $table->string('filter_category'); // 필터: 유형
            $table->string('filter_area'); // 필터: 평수
            $table->string('image')->nullable(); // 대표사진(썸네일)
            $table->string('file')->nullable(); // 첨부파일
            $table->unsignedBigInteger('views')->default(0); // 조회수
            $table->boolean('is_featured')->default(false); // 메인 게시
            $table->timestamps();
            $table->softDeletes(); // 삭제일
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
