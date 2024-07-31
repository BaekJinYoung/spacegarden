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
        Schema::create('youtubes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 제목
            $table->string('link'); // 링크
            $table->unsignedBigInteger('views')->default(0); // 조회수
            $table->boolean('is_featured'); // 메인 페이지 노출
            $table->timestamps();
            $table->softDeletes(); // 삭제일
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtubes');
    }
};
