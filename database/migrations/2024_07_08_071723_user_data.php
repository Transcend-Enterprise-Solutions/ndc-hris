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
        if (!Schema::hasTable('user_data')) {
            Schema::create('user_data', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('surname');
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('name_extension')->nullable();
                $table->date('date_of_birth')->format('F d Y');
                $table->string('sex');
                $table->string('citizenship');
                $table->string('civil_status');
                $table->string('height');
                $table->string('weight');
                $table->string('blood_type', 5)->nullable();
                $table->string('gsis', 50);
                $table->string('pagibig', 50);
                $table->string('philhealth', 50);
                $table->string('sss', 50);
                $table->string('tin', 50);
                $table->string('agency_employee_no', 50);
                $table->string('tel_number')->nullable();
                $table->string('mobile_number');
                $table->string('email');
                $table->string('permanent_selectedRegion', 200);
                $table->string('permanent_selectedProvince', 200);
                $table->string('permanent_selectedCity', 200);
                $table->string('permanent_selectedBarangay', 200);
                $table->string('p_house_street', 200);
                $table->string('residential_selectedRegion', 200);
                $table->string('residential_selectedProvince', 200);
                $table->string('residential_selectedCity', 200);
                $table->string('residential_selectedBarangay', 200);
                $table->string('r_house_street', 200);
                $table->string('spouse_name')->nullable();
                $table->date('spouse_birth_date')->format('F d Y')->nullable();
                $table->string('spouse_occupation')->nullable();
                $table->string('spouse_employer')->nullable();
                $table->string('fathers_name');
                $table->string('mothers_maiden_name');
                $table->string('educ_background');
                $table->string('name_of_school');
                $table->string('degree');
                $table->date('period_start_date')->format('F d Y');
                $table->date('period_end_date')->format('F d Y');
                $table->string('year_graduated');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_data')) {
            Schema::dropIfExists('user_data');
        }
    }
};
