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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 제목
            $table->text('content'); // 내용
            $table->string('file_path')->nullable(); // 첨부파일
            $table->unsignedBigInteger('views')->default(0); // 조회수
            $table->boolean('is_featured')->default(false); // 상단 공지
            $table->timestamps();
            $table->softDeletes(); // 삭제일
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
