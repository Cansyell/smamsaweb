<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Untuk MySQL, kita perlu mengubah kolom enum dengan ALTER TABLE
        DB::statement("ALTER TABLE students MODIFY COLUMN specialization ENUM('tahfiz', 'language', 'regular') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum sebelumnya (hanya tahfiz dan language)
        DB::statement("ALTER TABLE students MODIFY COLUMN specialization ENUM('tahfiz', 'language') NULL");
    }
};