<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employees_leaves')) {
            Schema::create('employees_leaves', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('paternity')->nullable();
                $table->string('study')->nullable();
                $table->string('maternity')->nullable();
                $table->string('solo_parent')->nullable();
                $table->string('vawc')->nullable();
                $table->string('rehabilitation')->nullable();
                $table->string('leave_for_women')->nullable();
                $table->string('emergency_leave')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('employees_leaves')) {
            Schema::dropIfExists('employees_leaves');
        }
    }
};
