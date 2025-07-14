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
        Schema::table('cos_reg_payslip', function (Blueprint $table) {
            $table->double('rate_per_day')->nullable()->after('rate_per_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cos_reg_payslip', function (Blueprint $table) {
            $table->dropColumn('rate_per_day');
        });
    }
};
