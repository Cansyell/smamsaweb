<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();
            $table->string('badge_text')->default('PPDB Tahun Ajaran 2025/2026');
            $table->string('title_main')->default('Wujudkan Mimpi');
            $table->string('title_italic')->default('Bersama Kami');
            $table->text('subtitle');
            $table->string('btn_primary_label')->default('Info Pendaftaran');
            $table->string('btn_primary_url')->default('#ppdb');
            $table->string('btn_outline_label')->default('Kenali Sekolah Kami');
            $table->string('btn_outline_url')->default('#visi-misi');
            $table->string('background_image')->nullable()->comment('Path ke gambar background hero');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};