<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('office_division_units', function (Blueprint $table) {
            $table->string('sign_name')->nullable()->after('unit');
            $table->string('sign_pos')->nullable()->after('sign_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('office_division_units', function (Blueprint $table) {
            $table->dropColumn(['sign_name', 'sign_pos']);
        });
    }
};
