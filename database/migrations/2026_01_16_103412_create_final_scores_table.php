<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('academic_score', 5, 2)->default(0);
            $table->decimal('test_score', 5, 2)->default(0);
            $table->decimal('total_score', 5, 2)->default(0);
            $table->integer('academic_weight')->default(40);
            $table->integer('test_weight')->default(60);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_scores');
    }
};
