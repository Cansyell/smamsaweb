<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_grades', function (Blueprint $table) {
            $table->decimal('ppkn', 5, 2)->nullable()->after('english_language');
            $table->decimal('mtk', 5, 2)->nullable()->after('ppkn');
            $table->decimal('ipa', 5, 2)->nullable()->after('mtk');
            $table->decimal('seni_budaya', 5, 2)->nullable()->after('ipa');
            $table->decimal('penjas', 5, 2)->nullable()->after('seni_budaya');
            $table->decimal('prakarya', 5, 2)->nullable()->after('penjas');
        });
    }

    public function down(): void
    {
        Schema::table('report_grades', function (Blueprint $table) {
            $table->dropColumn(['ppkn', 'mtk', 'ipa', 'seni_budaya', 'penjas', 'prakarya']);
        });
    }
};