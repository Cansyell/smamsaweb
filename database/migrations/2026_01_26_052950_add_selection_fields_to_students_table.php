<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->text('validation_notes')->nullable()->after('validation_status');
            $table->timestamp('validated_at')->nullable()->after('validation_notes');
            
            $table->unsignedInteger('ranking')->nullable()->after('validated_at');
            $table->string('final_class_type')->nullable()->after('ranking');
            $table->string('final_status')->nullable()->after('final_class_type');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'validation_notes',
                'validated_at',
                'ranking',
                'final_class_type',
                'final_status'
            ]);
        });
    }
};
