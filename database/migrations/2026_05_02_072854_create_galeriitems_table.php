<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_items', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('caption')->nullable();
            $table->enum('tipe', ['foto', 'video'])->default('foto');
            $table->string('gambar_path')->nullable()->comment('Path file gambar (untuk tipe foto)');
            $table->string('video_url')->nullable()->comment('URL embed YouTube/video (untuk tipe video)');
            $table->string('alt_text')->nullable()->comment('Teks alternatif untuk aksesibilitas');
            $table->unsignedTinyInteger('urutan')->default(0)->comment('Urutan tampil di galeri');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_items');
    }
};