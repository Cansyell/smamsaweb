<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // approved, rejected, resubmitted, doc_validated, doc_rejected
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('resubmission_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validation_logs');
    }
};