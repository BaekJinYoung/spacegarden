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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 이름
            $table->string('contact'); // 연락처
            $table->boolean('agreement')->default(true); // 개인정보처리방침 동의
            $table->string('inquiry_category')->nullable(); // 문의유형
            $table->string('email')->nullable(); // 이메일
            $table->text('message')->nullable(); // 문의 내용(텍스트)
            $table->timestamps();
            $table->softDeletes(); // 삭제일
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
