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
        Schema::create('ahp_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
            $table->enum('specialization', ['tahfiz', 'language']);
            $table->foreignId('criteria_row_id')
                  ->constrained('criterias')
                  ->onDelete('cascade');
            $table->foreignId('criteria_col_id')
                  ->constrained('criterias')
                  ->onDelete('cascade');
            $table->decimal('comparison_value', 10, 6); // Nilai perbandingan 1-9 atau pecahan (1/3, 1/5, dst)
            $table->text('notes')->nullable(); // Catatan perbandingan
            $table->timestamps();

            // Pastikan tidak ada duplikat perbandingan
            $table->unique(
                ['academic_year_id', 'specialization', 'criteria_row_id', 'criteria_col_id'],
                'ahp_unique_comparison'
            );

            // Index untuk query cepat
            $table->index(['academic_year_id', 'specialization'], 'ahp_year_spec_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_matrices');
    }
};