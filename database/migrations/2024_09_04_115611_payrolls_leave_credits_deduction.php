<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payrolls_leave_credits_deduction')) {
            Schema::create('payrolls_leave_credits_deduction', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->date('month')->nullable();
                $table->double('credits_deducted')->nullable();        
                $table->double('salary_deduction_credits')->nullable();        
                $table->double('salary_deduction_amount')->nullable();        
                $table->boolean('status')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payrolls_leave_credits_deduction')) {
            Schema::dropIfExists('payrolls_leave_credits_deduction');
        }
    }
};
