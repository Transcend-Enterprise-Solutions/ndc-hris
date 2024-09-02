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
        Schema::table('leave_application', function (Blueprint $table) {
            $table->unsignedBigInteger('endorser1_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('endorser2_id')->nullable()->after('endorser1_id');

            // Optionally, add foreign key constraints
            $table->foreign('endorser1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('endorser2_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_application', function (Blueprint $table) {
            $table->dropForeign(['endorser1_id']);
            $table->dropForeign(['endorser2_id']);
            $table->dropColumn(['endorser1_id', 'endorser2_id']);
        });
    }
};
