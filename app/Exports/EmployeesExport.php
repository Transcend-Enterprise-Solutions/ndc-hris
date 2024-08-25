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
    protected $selectedColumns;

    public function __construct($filters, $selectedColumns)
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
    }

    public function collection()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.id');

        $nameFields = ['surname', 'first_name', 'middle_name', 'name_extension'];
        $nameFieldsSelected = false;

        foreach ($this->selectedColumns as $column) {
            if (in_array($column, $nameFields)) {
                $query->addSelect("user_data.$column");
                $nameFieldsSelected = true;
            } elseif ($column !== 'name') {  // Skip 'name' as we'll handle it separately
                $query->addSelect("user_data.$column");
            }
        }

        // If no specific name fields are selected, but 'name' is, select all name fields
        if (!$nameFieldsSelected && in_array('name', $this->selectedColumns)) {
            foreach ($nameFields as $field) {
                $query->addSelect("user_data.$field");
            }
        }

        // Apply filters
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
            ->map(function ($user) use ($nameFields, $nameFieldsSelected) {
                $userData = ['ID' => $user->id];
                
                if (in_array('name', $this->selectedColumns) || $nameFieldsSelected) {
                    $fullName = trim(implode(' ', [
                        $user->surname ?? '',
                        $user->first_name ?? '',
                        $user->middle_name ?? '',
                        $user->name_extension ?? ''
                    ]));
                    $userData['Name'] = $fullName;
                }

                foreach ($this->selectedColumns as $column) {
                    if ($column !== 'name' && $column !== 'id') {
                        $userData[$this->getColumnHeader($column)] = $user->$column;
                    }
                }
                return $userData;
            });
    }

    public function headings(): array
    {
        $headers = ['ID'];
        if (in_array('name', $this->selectedColumns) || 
            array_intersect(['surname', 'first_name', 'middle_name', 'name_extension'], $this->selectedColumns)) {
            $headers[] = 'Name';
        }
        foreach ($this->selectedColumns as $column) {
            if ($column !== 'name' && $column !== 'id') {
                $headers[] = $this->getColumnHeader($column);
            }
        }
        return $headers;
    }

    private function getColumnHeader($column)
    {
        $headers = [
            'surname' => 'Surname',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'name_extension' => 'Name Extension',
            'date_of_birth' => 'Birth Date',
            'place_of_birth' => 'Birth Place',
            'sex' => 'Sex',
            'citizenship' => 'Citizenship',
            'civil_status' => 'Civil Status',
            'height' => 'Height',
            'weight' => 'Weight',
            'blood_type' => 'Blood Type',
            'gsis' => 'GSIS ID No.',
            'pagibig' => 'PAGIBIG ID No.',
            'philhealth' => 'PhilHealth ID No.',
            'sss' => 'SSS No.',
            'tin' => 'TIN No.',
            'agency_employee_no' => 'Agency Employee No.',
            'tel_number' => 'Telephone No.',
            'mobile_number' => 'Mobile No.',
            'permanent_selectedProvince' => 'Permanent Address (Province)',
            'permanent_selectedCity' => 'Permanent Address (City)',
            'permanent_selectedBarangay' => 'Permanent Address (Barangay)',
            'p_house_street' => 'Permanent Address (Street)',
            'permanent_selectedZipcode' => 'Permanent Address (Zip Code)',
            'residential_selectedProvince' => 'Residential Address (Province)',
            'residential_selectedCity' => 'Residential Address (City)',
            'residential_selectedBarangay' => 'Residential Address (Barangay)',
            'r_house_street' => 'Residential Address (Street)',
            'residential_selectedZipcode' => 'Residential Address (Zip Code)',
        ];

        return $headers[$column] ?? $column;
    }
}