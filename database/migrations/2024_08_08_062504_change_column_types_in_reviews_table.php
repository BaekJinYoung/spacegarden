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
        // NULL을 허용하도록 먼저 변경
        DB::statement('ALTER TABLE reviews MODIFY filter_category INT NULL;');
        DB::statement('ALTER TABLE reviews MODIFY filter_area INT NULL;');

        // 이후에 NOT NULL로 변경
        DB::statement('ALTER TABLE reviews MODIFY filter_category INT NOT NULL;');
        DB::statement('ALTER TABLE reviews MODIFY filter_area INT NOT NULL;');
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
