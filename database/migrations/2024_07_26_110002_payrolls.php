<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payrolls')) {
            Schema::create('payrolls', function (Blueprint $table) {
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
                $table->double('pagibig_calamity_loan')->nullable();
                $table->double('pagibig_contribution')->nullable();
                $table->double('pagibig_gs')->nullable();
                $table->double('lwop')->nullable();
                $table->double('gsis_rlip')->nullable();
                $table->double('gsis_gs')->nullable();
                $table->double('gsis_ecip')->nullable();
                $table->double('nycempc_mpl')->nullable();
                $table->double('nycempc_educ_loan')->nullable();
                $table->double('nycempc_pi')->nullable();
                $table->double('nycempc_business_loan')->nullable();
                $table->double('nycempc_total')->nullable();
                $table->double('w_holding_tax')->nullable();
                $table->double('philhealth')->nullable();
                $table->double('philhealth_es')->nullable();
                $table->double('other_deductions')->nullable();
                $table->double('total_deduction')->nullable();             
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payrolls')) {
            Schema::dropIfExists('payrolls');
        }
    }
};
