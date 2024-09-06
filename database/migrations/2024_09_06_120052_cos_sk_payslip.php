<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cos_sk_payslip')) {
            Schema::create('cos_sk_payslip', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('salary_grade')->nullable();
                $table->double('rate_per_month')->nullable();
                $table->integer('days_covered')->nullable();
                $table->double('gross_salary')->nullable();
                $table->integer('absences_days')->nullable();
                $table->double('absences_amount')->nullable();
                $table->integer('late_undertime_hours')->nullable();
                $table->double('late_undertime_hours_amount')->nullable();
                $table->integer('late_undertime_minutes')->nullable();
                $table->double('late_undertime_minutes_amount')->nullable();
                $table->double('gross_salary_less')->nullable();
                $table->double('additional_premiums')->nullable();
                $table->double('adjustment')->nullable();
                $table->double('w_holding_tax')->nullable();
                $table->double('nycempc')->nullable();
                $table->double('other_deductions')->nullable();
                $table->double('total_deduction')->nullable();             
                $table->double('net_amount_received')->nullable();            
                $table->date('start_date')->nullable();             
                $table->date('end_date')->nullable();             
                $table->string('prepared_by_name')->nullable();             
                $table->string('prepared_by_position')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cos_sk_payslip')) {
            Schema::dropIfExists('cos_sk_payslip');
        }
    }
};
