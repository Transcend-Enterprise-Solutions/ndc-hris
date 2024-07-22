<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('leave_application')) {
            Schema::create('leave_application', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('name');
                $table->string('office_or_department');
                $table->date('date_of_filing')->default(DB::raw('CURRENT_DATE'));
                $table->string('position');
                $table->decimal('salary', 8, 2);
                $table->string('type_of_leave');
                $table->text('details_of_leave')->nullable();
                $table->integer('number_of_days');
                $table->date('start_date');
                $table->date('end_date');
                $table->string('commutation')->default('Not Requested');
                $table->string('status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leave_application')) {
            Schema::dropIfExists('leave_application');
        }
    }
};
