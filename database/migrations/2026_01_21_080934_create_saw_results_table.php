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
        Schema::create('saw_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                  ->constrained('students')
                  ->onDelete('cascade');
            $table->foreignId('academic_year_id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
            $table->enum('specialization', ['tahfiz', 'language']);
            $table->decimal('final_score', 10, 8); // Skor SAW akhir (∑ wi × ri)
            $table->integer('rank')->nullable(); // Ranking dalam spesializasi
            $table->json('detail_calculation')->nullable(); // Detail perhitungan: {criteria_id: {weight, normalized, score}}
            $table->timestamp('calculated_at')->nullable();
            $table->foreignId('calculated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamps();

            // Satu siswa hanya punya satu hasil SAW per tahun ajaran & spesializasi
            $table->unique(
                ['student_id', 'academic_year_id', 'specialization'],
                'saw_unique'
            );

            // Index untuk sorting & ranking
            $table->index(['academic_year_id', 'specialization', 'final_score'], 'saw_ranking_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saw_results');
    }
};