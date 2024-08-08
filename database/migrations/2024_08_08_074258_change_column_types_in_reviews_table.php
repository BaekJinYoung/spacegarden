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
        // Update existing NULL values
        DB::table('reviews')->whereNull('image')->update(['image' => 'images/TEST_웹.jpg']);

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('image')->default('images/TEST_웹.jpg')->change(); // 대표사진(썸네일)
        });
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
