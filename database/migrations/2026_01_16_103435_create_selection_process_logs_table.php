<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selection_process_logs', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 9);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed']);
            $table->integer('total_students_processed')->default(0);
            $table->integer('total_accepted_tahfiz')->default(0);
            $table->integer('total_accepted_language')->default(0);
            $table->integer('total_regular')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selection_process_logs');
    }
};
