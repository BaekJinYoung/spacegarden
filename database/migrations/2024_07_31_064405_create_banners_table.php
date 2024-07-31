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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 제목
            $table->string('subTitle'); // 소제목
            $table->string('image'); // 이미지
            $table->string('mobile_image'); // 모바일 이미지
            $table->string('link')->nullable(); // 링크
            $table->timestamps();
            $table->softDeletes(); //삭제일
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
