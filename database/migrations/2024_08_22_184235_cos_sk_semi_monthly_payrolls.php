<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cos_sk_semi_monthly_payrolls')) {
            Schema::create('cos_sk_semi_monthly_payrolls', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('name')->nullable();
                $table->string('employee_number')->nullable();
                $table->string('office_division')->nullable();
                $table->string('position')->nullable();
                $table->string('salary_grade')->nullable();
                $table->double('daily_salary_rate')->nullable();
                $table->integer('no_of_days_covered')->nullable();
                $table->double('gross_salary')->nullable();
                $table->double('absences_days')->nullable();
                $table->double('absences_amount')->nullable();
                $table->integer('late_undertime_hours')->nullable();
                $table->double('late_undertime_hours_amount')->nullable();
                $table->integer('late_undertime_mins')->nullable();
                $table->double('late_undertime_mins_amount')->nullable();
                $table->double('gross_salary_less')->nullable();
                $table->double('withholding_tax')->nullable();
                $table->double('nycempc')->nullable();
                $table->double('total_deductions')->nullable();
                $table->double('net_amount_due')->nullable();                
                $table->date('start_date')->format('F d Y')->nullable();                
                $table->date('end_date')->format('F d Y')->nullable();                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cos_sk_semi_monthly_payrolls')) {
            Schema::dropIfExists('cos_sk_semi_monthly_payrolls');
        }
    }
};
