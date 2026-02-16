<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('resubmission_count')->default(0)->after('validated_at');
            $table->timestamp('resubmitted_at')->nullable()->after('resubmission_count');
            $table->text('resubmission_notes')->nullable()->after('resubmitted_at');
            $table->boolean('has_pending_resubmission')->default(false)->after('resubmission_notes');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'resubmission_count',
                'resubmitted_at',
                'resubmission_notes',
                'has_pending_resubmission',
            ]);
        });
    }
};