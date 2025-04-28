<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees_dtr', function (Blueprint $table) {
            // Add nullable updated_by column after updated_at
            $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            // Add new time tracking columns
            $table->string('up_morning_in', 255)->nullable()->default(null);
            $table->string('up_morning_out', 255)->nullable()->default(null);
            $table->string('up_afternoon_in', 255)->nullable()->default(null);
            $table->string('up_afternoon_out', 255)->nullable()->default(null);
            $table->string('up_late', 255)->nullable()->default(null);
            $table->string('up_ut', 255)->nullable()->default(null);
            $table->string('up_ot', 255)->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('employees_dtr', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['updated_by']);
            // Then drop the column
            $table->dropColumn('updated_by');

            // Drop the added columns
            $table->dropColumn([
                'up_morning_in',
                'up_morning_out',
                'up_afternoon_in',
                'up_afternoon_out',
                'up_late',
                'up_ut',
                'up_ot'
            ]);
        });
    }
};
