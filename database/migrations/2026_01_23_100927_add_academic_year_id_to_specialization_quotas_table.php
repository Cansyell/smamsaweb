<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specialization_quotas', function (Blueprint $table) {
            // Ubah academic_year menjadi foreign key
            $table->dropColumn('academic_year');
        });

        Schema::table('specialization_quotas', function (Blueprint $table) {
            $table->foreignId('academic_year_id')
                ->after('id')
                ->constrained('academic_years')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('specialization_quotas', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });

        Schema::table('specialization_quotas', function (Blueprint $table) {
            $table->string('academic_year', 9)->after('id');
        });
    }
};