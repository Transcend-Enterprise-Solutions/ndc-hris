<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cos_reg_payrolls')) {
            Schema::create('cos_reg_payrolls', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('sg_step')->nullable();
                $table->double('rate_per_month')->nullable();        
                $table->double('additional_premiums')->nullable();
                $table->double('adjustment')->nullable();
                $table->double('withholding_tax')->nullable();
                $table->double('nycempc')->nullable();
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
        if (Schema::hasTable('cos_reg_payrolls')) {
            Schema::dropIfExists('cos_reg_payrolls');
        }
    }
};
