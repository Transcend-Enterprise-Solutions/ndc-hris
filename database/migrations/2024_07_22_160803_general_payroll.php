<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('general_payroll')) {
            Schema::create('general_payroll', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->double('net_amount_received')->nullable();
                $table->double('amount_due_first_half')->nullable();
                $table->double('amount_due_second_half')->nullable();                
                $table->double('gross_salary_less')->nullable();                
                $table->double('late_absences')->nullable();                
                $table->double('leave_without_pay')->nullable();                
                $table->double('others')->nullable();                
                $table->double('total_earnings')->nullable();                
                $table->date('date')->format('F d Y')->nullable();                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('general_payroll')) {
            Schema::dropIfExists('general_payroll');
        }
    }
};
