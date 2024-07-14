<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('users')
            ->join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.user_role',
                'users.active_status',
                'user_data.first_name',
                'user_data.middle_name',
                'user_data.surname',
                'user_data.name_extension',
                'user_data.sex',
                'user_data.date_of_birth',
                'user_data.place_of_birth',
                'user_data.citizenship',
                'user_data.civil_status',
                'user_data.height',
                'user_data.weight',
                'user_data.blood_type',
                'user_data.gsis',
                'user_data.pagibig',
                'user_data.philhealth',
                'user_data.sss',
                'user_data.tin',
                'user_data.agency_employee_no',
                'user_data.permanent_selectedRegion',
                'user_data.permanent_selectedProvince',
                'user_data.permanent_selectedCity',
                'user_data.permanent_selectedBarangay',
                'user_data.p_house_street',
                'user_data.residential_selectedRegion',
                'user_data.residential_selectedProvince',
                'user_data.residential_selectedCity',
                'user_data.residential_selectedBarangay',
                'user_data.r_house_street',
                'user_data.tel_number',
                'user_data.mobile_number',
                'user_data.spouse_name',
                'user_data.spouse_birth_date',
                'user_data.spouse_occupation',
                'user_data.spouse_employer',
                'user_data.fathers_name',
                'user_data.mothers_maiden_name',
                'user_data.educ_background',
                'user_data.name_of_school',
                'user_data.degree',
                'user_data.period_start_date',
                'user_data.period_end_date',
                'user_data.year_graduated'
            )->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Email',
            'User Role',
            'Active Status',
            'First Name',
            'Middle Name',
            'Surname',
            'Suffix',
            'Sex',
            'Date of Birth',
            'Place of Birth',
            'Citizenship',
            'Civil Status',
            'Height',
            'Weight',
            'Blood Type',
            'GSIS',
            'Pag-IBIG',
            'PhilHealth',
            'SSS',
            'TIN',
            'Agency Employee No.',
            'Permanent Region',
            'Permanent Province',
            'Permanent City',
            'Permanent Barangay',
            'Permanent House/Street',
            'Residential Region',
            'Residential Province',
            'Residential City',
            'Residential Barangay',
            'Residential House/Street',
            'Telephone Number',
            'Mobile Number',
            'Spouse Name',
            'Spouse Birth Date',
            'Spouse Occupation',
            'Spouse Employer',
            'Father\'s Name',
            'Mother\'s Maiden Name',
            'Educational Background',
            'Name of School',
            'Degree',
            'Period Start Date',
            'Period End Date',
            'Year Graduated',
        ];
    }
}
