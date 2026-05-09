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
        Schema::table('students', function (Blueprint $table) {
            $table->text('preference_reason')->nullable()->after('specialization');
            $table->integer('quran_memorization')->nullable()->after('preference_reason');
            $table->enum('language_interest', ['arabic', 'english', 'both'])->nullable()->after('quran_memorization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['preference_reason', 'quran_memorization', 'language_interest']);
        });
    }
};
