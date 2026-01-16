<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selection_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('selected_specialization', ['tahfiz', 'language', 'regular']);
            $table->integer('specialization_rank')->nullable();
            $table->integer('regular_rank')->nullable();
            $table->enum('admission_status', ['accepted_specialization', 'regular', 'not_accepted'])->default('not_accepted');
            $table->text('notes')->nullable();
            $table->timestamp('announcement_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selection_results');
    }
};
