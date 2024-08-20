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
                $table->integer('paternity')->nullable();
                $table->integer('study')->nullable();
                $table->integer('maternity')->nullable();
                $table->integer('solo_parent')->nullable();
                $table->integer('vawc')->nullable();
                $table->integer('rehabilitation')->nullable();
                $table->integer('leave_for_women')->nullable();
                $table->integer('emergency_leave')->nullable();
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
