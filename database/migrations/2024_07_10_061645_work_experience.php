<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_experience', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('start_date')->format('F d Y')->nullable();
            $table->date('end_date')->format('F d Y')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('monthly_salary')->nullable();
            $table->string('sg_step')->nullable();
            $table->string('status_of_appointment')->nullable();
            $table->boolean('gov_service')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experience');
    }
};
