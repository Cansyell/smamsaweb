<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialization_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 9);
            $table->integer('tahfiz_quota')->default(0);
            $table->integer('language_quota')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialization_quotas');
    }
};
