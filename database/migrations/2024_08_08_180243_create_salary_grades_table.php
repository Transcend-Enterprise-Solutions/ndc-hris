<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(){
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_grade');
            $table->decimal('step1', 10, 2);
            $table->decimal('step2', 10, 2);
            $table->decimal('step3', 10, 2);
            $table->decimal('step4', 10, 2);
            $table->decimal('step5', 10, 2);
            $table->decimal('step6', 10, 2);
            $table->decimal('step7', 10, 2);
            $table->decimal('step8', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
