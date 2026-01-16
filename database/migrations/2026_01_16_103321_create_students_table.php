<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id', 10)->unique();
            $table->string('full_name');
            $table->enum('gender', ['M', 'F']);
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->text('address');
            $table->string('phone_number', 15);
            $table->string('previous_school');
            $table->enum('specialization', ['tahfiz', 'language'])->nullable();
            $table->enum('validation_status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
