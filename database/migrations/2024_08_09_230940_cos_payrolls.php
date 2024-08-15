<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cos_payrolls')) {
            Schema::create('cos_payrolls', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('sg_step')->nullable();
                $table->double('rate_per_month')->nullable();        
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cos_payrolls')) {
            Schema::dropIfExists('cos_payrolls');
        }
    }
};
