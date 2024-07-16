<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class EmployeesExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select(
                'users.id', 
                'users.name', 
                'user_data.surname', 
                'user_data.first_name', 
                'user_data.middle_name', 
                'user_data.name_extension', 
                'user_data.date_of_birth', 
                'user_data.place_of_birth', 
                'user_data.sex', 
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
                'user_data.tel_number', 
                'user_data.mobile_number', 
                'user_data.permanent_selectedProvince', 
                'user_data.permanent_selectedCity', 
                'user_data.permanent_selectedBarangay', 
                'user_data.p_house_street', 
                'user_data.permanent_selectedZipcode',
                'user_data.residential_selectedProvince', 
                'user_data.residential_selectedCity', 
                'user_data.residential_selectedBarangay', 
                'user_data.r_house_street', 
                'user_data.residential_selectedZipcode',
            );

        if ($this->filters['sex']) {
            $query->where('user_data.sex', $this->filters['sex']);
        }

        if ($this->filters['civil_status']) {
            $query->where('user_data.civil_status', $this->filters['civil_status']);
        }

        if ($this->filters['selectedProvince']) {
            $query->where('user_data.province', $this->filters['selectedProvince']);
        }

        if ($this->filters['selectedCity']) {
            $query->where('user_data.city', $this->filters['selectedCity']);
        }

        if ($this->filters['selectedBarangay']) {
            $query->where('user_data.barangay', $this->filters['selectedBarangay']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Surname',
            'First Name',
            'Middle Name',
            'Name Extension',
            'Birth Date',
            'Birth Place',
            'Sex',
            'Citizenship',
            'Civil Status',
            'Height',
            'Weight',
            'Blood Type',
            'GSIS ID No.',
            'PAGIBIG ID No.',
            'PhilHealth ID No.',
            'SSS No.',
            'TIN No.',
            'Agency Employee No.',
            'Telephone No.',
            'Mobile No.',
            'Permanent Address (Province)',
            'Permanent Address (City)',
            'Permanent Address (Barangay)',
            'Permanent Address (Street)',
            'Permanent Address (Zip Code)',
            'Residential Address (Province)',
            'Residential Address (City)',
            'Residential Address (Barangay)',
            'Residential Address (Street)',
            'Residential Address (Zip Code)'
        ];
    }
}
