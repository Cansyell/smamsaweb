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
        Schema::create('student_criterion_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->onDelete('cascade');
            $table->foreignId('criteria_id')
                  ->constrained('criterias')
                  ->onDelete('cascade');
            $table->decimal('raw_value', 10, 2); // Nilai asli (mentah)
            $table->decimal('normalized_value', 10, 8)->nullable(); // Nilai ternormalisasi (hasil SAW)
            $table->text('notes')->nullable();
            $table->timestamps();

            // Satu siswa hanya punya satu nilai per kriteria
            $table->unique(['student_id', 'criteria_id'], 'student_criteria_unique');

            // Index untuk query
            $table->index('student_id');
            $table->index('criteria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_criterion_values');
    }
};