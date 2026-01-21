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
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // 'nilai_agama', 'membaca_alquran', 'hafalan'
            $table->string('name'); // Nama kriteria untuk display
            $table->enum('specialization', ['tahfiz', 'language']); // Untuk spesializasi mana
            $table->enum('attribute_type', ['benefit', 'cost'])->default('benefit'); // Jenis atribut SAW
            $table->string('data_source')->nullable(); // 'report_grades.islamic_studies', 'test_scores.quran_reading'
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // Urutan tampilan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};