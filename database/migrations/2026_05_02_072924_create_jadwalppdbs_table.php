<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_ppdb', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran')->default('2025/2026');
            $table->unsignedTinyInteger('nomor_urut');
            $table->string('tanggal_label')->comment('Contoh: 1 – 15 Juni 2025');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('judul')->comment('Contoh: Pembukaan Pendaftaran Online');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['upcoming', 'active', 'done'])->default('upcoming');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_ppdb');
    }
};