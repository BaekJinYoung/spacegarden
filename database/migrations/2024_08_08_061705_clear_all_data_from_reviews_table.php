<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 외래 키 제약 조건을 비활성화 (필요한 경우)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 테이블의 모든 데이터 삭제
        DB::table('reviews')->delete();

        // 외래 키 제약 조건을 다시 활성화
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
};
