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
        Schema::table('saw_results', function (Blueprint $table) {
            // Rank di antara SEMUA siswa di track ini (global, untuk cross-accepted)
            // Field 'rank' yang sudah ada tetap dipakai sebagai rank global
            
            // Rank di antara siswa yang MEMILIH specialization ini (untuk lulus/tidak)
            $table->unsignedInteger('primary_rank')->nullable()->after('rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saw_results', function (Blueprint $table) {
            //
        });
    }
};
