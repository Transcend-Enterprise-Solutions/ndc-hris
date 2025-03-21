<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plantilla_payslip')) {
            Schema::create('plantilla_payslip', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('sg_step')->nullable();
                $table->double('rate_per_month')->nullable();
                $table->double('personal_economic_relief_allowance')->nullable();
                $table->double('gross_amount')->nullable();
                $table->double('additional_gsis_premium')->nullable();
                $table->double('lbp_salary_loan')->nullable();
                $table->double('nycea_deductions')->nullable();
                $table->double('sc_membership')->nullable();
                $table->double('total_loans')->nullable();
                $table->double('salary_loan')->nullable();
                $table->double('policy_loan')->nullable();
                $table->double('eal')->nullable();
                $table->double('emergency_loan')->nullable();
                $table->double('mpl')->nullable();
                $table->double('housing_loan')->nullable();
                $table->double('ouli_prem')->nullable();
                $table->double('gfal')->nullable();
                $table->double('cpl')->nullable();
                $table->double('pagibig_mpl')->nullable();
                $table->double('other_deduction_philheath_diff')->nullable();
                $table->double('life_retirement_insurance_premiums')->nullable();
                $table->double('pagibig_contribution')->nullable();
                $table->double('w_holding_tax')->nullable();
                $table->double('philhealth')->nullable();
                $table->double('other_deductions')->nullable();
                $table->double('total_deduction')->nullable();             
                $table->double('net_amount_received')->nullable();             
                $table->double('first_half_amount')->nullable();             
                $table->double('second_half_amount')->nullable();             
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
        if (Schema::hasTable('plantilla_payslip')) {
            Schema::dropIfExists('plantilla_payslip');
        }
    }
};
