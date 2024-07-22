<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employees_payroll')) {
            Schema::create('employees_payroll', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('employee_number')->nullable();
                $table->string('position')->nullable();
                $table->string('sg/step')->nullable();
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
                $table->double('total_deduction')->nullable();
                $table->double('net_amount_received')->nullable();
                $table->double('amount_due_first_half')->nullable();
                $table->double('amount_due_second_half')->nullable();                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employees_payroll')) {
            Schema::dropIfExists('employees_payroll');
        }
    }
};
