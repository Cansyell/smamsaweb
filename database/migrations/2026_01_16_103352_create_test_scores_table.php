<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('quran_achievement', 5, 2)->default(0);
            $table->decimal('quran_reading', 5, 2)->default(0);
            $table->decimal('interview', 5, 2)->default(0);
            $table->decimal('public_speaking', 5, 2)->default(0);
            $table->decimal('dialogue', 5, 2)->default(0);
            $table->decimal('average_score', 5, 2)->nullable();
            $table->foreignId('input_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_scores');
    }
};