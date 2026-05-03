<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // 1. Kolom baru di tabel students (Fitur 1: dual pass)
        // -------------------------------------------------------
        Schema::table('students', function (Blueprint $table) {
            // Apakah siswa lulus di kedua spesialisasi (tahfiz & bahasa)
            $table->boolean('dual_pass')->default(false)->after('final_status');

            // Spesialisasi yang disarankan sistem (jika dual_pass = true)
            $table->string('recommended_specialization')->nullable()->after('dual_pass');

            // Spesialisasi yang benar-benar diterima (bisa berbeda dari pilihan)
            $table->string('accepted_specialization')->nullable()->after('recommended_specialization');

            // Apakah diterima di spesialisasi yang BUKAN pilihannya
            $table->boolean('cross_accepted')->default(false)->after('accepted_specialization');
        });

        // -------------------------------------------------------
        // 2. Kolom baru di tabel academic_years (Fitur 3: publish)
        // -------------------------------------------------------
        Schema::table('academic_years', function (Blueprint $table) {
            // Status publikasi hasil: draft → reviewing → published
            $table->enum('result_status', ['draft', 'reviewing', 'published'])
                ->default('draft')
                ->after('is_active');

            // Waktu publikasi
            $table->timestamp('published_at')->nullable()->after('result_status');

            // Siapa yang mempublikasikan
            $table->foreignId('published_by')
                ->nullable()
                ->after('published_at')
                ->constrained('users')
                ->nullOnDelete();

            // Catatan publikasi (opsional)
            $table->text('publish_notes')->nullable()->after('published_by');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'dual_pass',
                'recommended_specialization',
                'accepted_specialization',
                'cross_accepted',
            ]);
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropForeign(['published_by']);
            $table->dropColumn([
                'result_status',
                'published_at',
                'published_by',
                'publish_notes',
            ]);
        });
    }
};