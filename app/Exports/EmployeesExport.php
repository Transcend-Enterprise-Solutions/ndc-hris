<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class EmployeesExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select(
                'users.id',
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
            $query->where('user_data.permanent_selectedProvince', $this->filters['selectedProvince']);
        }

        if ($this->filters['selectedCity']) {
            $query->where('user_data.permanent_selectedCity', $this->filters['selectedCity']);
        }

        if ($this->filters['selectedBarangay']) {
            $query->where('user_data.permanent_selectedBarangay', $this->filters['selectedBarangay']);
        }

        return $query->get()
            ->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'Surname' => $user->surname,
                    'First Name' => $user->first_name,
                    'Middle Name' => $user->middle_name,
                    'Name Extension' => $user->name_extension,
                    'Birth Date' => $user->date_of_birth,
                    'Birth Place' => $user->place_of_birth,
                    'Sex' => $user->sex,
                    'Citizenship' => $user->citizenship,
                    'Civil Status' => $user->civil_status,
                    'Height' => $user->height,
                    'Weight' => $user->weight,
                    'Blood Type' => $user->blood_type,
                    'GSIS ID No.' => $user->gsis,
                    'PAGIBIG ID No.' => $user->pagibig,
                    'PhilHealth ID No.' => $user->philhealth,
                    'SSS No.' => $user->sss,
                    'TIN No.' => $user->tin,
                    'Agency Employee No.' => $user->agency_employee_no,
                    'Telephone No.' => $user->tel_number,
                    'Mobile No.' => $user->mobile_number,
                    'Permanent Address (Province)' => $user->permanent_selectedProvince,
                    'Permanent Address (City)' => $user->permanent_selectedCity,
                    'Permanent Address (Barangay)' => $user->permanent_selectedBarangay,
                    'Permanent Address (Street)' => $user->p_house_street,
                    'Permanent Address (Zip Code)' => $user->permanent_selectedZipcode,
                    'Residential Address (Province)' => $user->residential_selectedProvince,
                    'Residential Address (City)' => $user->residential_selectedCity,
                    'Residential Address (Barangay)' => $user->residential_selectedBarangay,
                    'Residential Address (Street)' => $user->r_house_street,
                    'Residential Address (Zip Code)' => $user->residential_selectedZipcode,
                ];
            });
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
