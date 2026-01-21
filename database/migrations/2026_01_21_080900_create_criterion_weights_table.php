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
        Schema::create('criterion_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
            $table->foreignId('criteria_id')
                  ->constrained('criterias')
                  ->onDelete('cascade');
            $table->enum('specialization', ['tahfiz', 'language']);
            $table->decimal('weight', 10, 8); // Hasil perhitungan AHP (bobot akhir)
            $table->decimal('priority_vector', 10, 8)->nullable(); // Eigen vector
            $table->decimal('lambda_max', 10, 8)->nullable(); // λmax untuk CI
            $table->decimal('consistency_index', 10, 8)->nullable(); // CI = (λmax - n) / (n - 1)
            $table->decimal('consistency_ratio', 10, 8)->nullable(); // CR = CI / RI
            $table->boolean('is_consistent')->default(true); // CR <= 0.1
            $table->json('calculation_detail')->nullable(); // Detail perhitungan untuk audit
            $table->timestamp('calculated_at')->nullable();
            $table->foreignId('calculated_by')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->timestamps();

            // Unique constraint: satu kriteria hanya punya satu bobot per tahun ajaran & spesializasi
            $table->unique(
                ['academic_year_id', 'criteria_id', 'specialization'],
                'weight_unique'
            );

            // Index untuk query
            $table->index(['academic_year_id', 'specialization'], 'weight_year_spec_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterion_weights');
    }
};