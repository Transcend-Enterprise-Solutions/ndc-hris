<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;

class EmployeesExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $filters;
    protected $selectedColumns;

    public function __construct($filters = [], $selectedColumns = [])
    {
        $this->filters = array_merge([
            'sex' => null,
            'civil_status' => [],
            'selectedProvince' => [],
            'selectedCity' => [],
            'selectedBarangay' => [],
        ], $filters);
    
        $this->selectedColumns = $selectedColumns;
    }
    
    public function collection()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id');
    
        $columnsToSelect = ['users.id'];
        $columnsToGroupBy = ['users.id'];
    
        $nameFields = ['surname', 'first_name', 'middle_name', 'name_extension'];
        $nameFieldsSelected = false;
    
        foreach ($this->selectedColumns as $column) {
            if ($column !== 'years_in_gov_service') {
                if (in_array($column, $nameFields)) {
                    $columnsToSelect[] = "user_data.$column";
                    $columnsToGroupBy[] = "user_data.$column";
                    $nameFieldsSelected = true;
                } elseif ($column !== 'name') {
                    if ($column === 'active_status') {
                        $columnsToSelect[] = "users.$column";
                        $columnsToGroupBy[] = "users.$column";
                    } else {
                        $columnsToSelect[] = "user_data.$column";
                        $columnsToGroupBy[] = "user_data.$column";
                    }
                }
            }
        }
    
        if (!$nameFieldsSelected && in_array('name', $this->selectedColumns)) {
            foreach ($nameFields as $field) {
                $columnsToSelect[] = "user_data.$field";
                $columnsToGroupBy[] = "user_data.$field";
            }
        }
    
        $query->select($columnsToSelect);
    
        if (in_array('years_in_gov_service', $this->selectedColumns)) {
            $query->leftJoin('work_experience', 'users.id', '=', 'work_experience.user_id')
                ->addSelect(DB::raw('FLOOR(DATEDIFF(IFNULL(MAX(work_experience.end_date), NOW()), MIN(work_experience.start_date)) / 365) as years_in_gov_service'));
        }
    
        // Apply filters
        if (!empty($this->filters['sex'])) {
            $query->where('user_data.sex', $this->filters['sex']);
        }
        if (!empty($this->filters['civil_status'])) {
            $query->whereIn('user_data.civil_status', $this->filters['civil_status']);
        }
        if (!empty($this->filters['selectedProvince'])) {
            $query->whereIn('user_data.permanent_selectedProvince', $this->filters['selectedProvince']);
        }
        if (!empty($this->filters['selectedCity'])) {
            $query->whereIn('user_data.permanent_selectedCity', $this->filters['selectedCity']);
        }
        if (!empty($this->filters['selectedBarangay'])) {
            $query->whereIn('user_data.permanent_selectedBarangay', $this->filters['selectedBarangay']);
        }
    
        $query->groupBy($columnsToGroupBy);
    
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
                        if ($column === 'active_status') {
                            $statusMapping = [
                                0 => 'Inactive',
                                1 => 'Active',
                                2 => 'Retired',
                                3 => 'Resigned'
                            ];
                            $userData[$this->getColumnHeader($column)] = $statusMapping[$user->active_status] ?? 'Unknown';
                        } elseif ($column === 'years_in_gov_service') {
                            $userData[$this->getColumnHeader($column)] = $user->years_in_gov_service ?? 'N/A';
                        } else {
                            $userData[$this->getColumnHeader($column)] = $user->$column;
                        }
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
            'active_status' => 'Active Status',
            'appointment' => 'Nature of Appointment',
            'date_hired' => 'Date Hired',
            'years_in_gov_service' => 'Years in Government Service',
        ];

        return $headers[$column] ?? $column;
    }
}