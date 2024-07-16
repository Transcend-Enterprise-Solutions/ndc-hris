<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class EmployeesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.id');

        if ($this->filters['name']) {
            $query->addSelect('users.name');
        }
        if ($this->filters['date_of_birth']) {
            $query->addSelect('user_data.date_of_birth');
        }
        if ($this->filters['place_of_birth']) {
            $query->addSelect('user_data.place_of_birth');
        }
        if ($this->filters['sex']) {
            $query->addSelect('user_data.sex');
        }
        if ($this->filters['citizenship']) {
            $query->addSelect('user_data.citizenship');
        }
        if ($this->filters['civil_status']) {
            $query->addSelect('user_data.civil_status');
        }
        if ($this->filters['height']) {
            $query->addSelect('user_data.height');
        }
        if ($this->filters['weight']) {
            $query->addSelect('user_data.weight');
        }
        if ($this->filters['blood_type']) {
            $query->addSelect('user_data.blood_type');
        }
        if ($this->filters['gsis']) {
            $query->addSelect('user_data.gsis');
        }
        if ($this->filters['pagibig']) {
            $query->addSelect('user_data.pagibig');
        }
        if ($this->filters['philhealth']) {
            $query->addSelect('user_data.philhealth');
        }
        if ($this->filters['sss']) {
            $query->addSelect('user_data.sss');
        }
        if ($this->filters['tin']) {
            $query->addSelect('user_data.tin');
        }
        if ($this->filters['agency_employee_no']) {
            $query->addSelect('user_data.agency_employee_no');
        }

        return $query->get();
    }

    public function headings(): array
    {
        $headings = ['User ID'];

        if ($this->filters['name']) {
            $headings[] = 'Name';
        }
        if ($this->filters['date_of_birth']) {
            $headings[] = 'Birth Date';
        }
        if ($this->filters['place_of_birth']) {
            $headings[] = 'Birth Place';
        }
        if ($this->filters['sex']) {
            $headings[] = 'Sex';
        }
        if ($this->filters['citizenship']) {
            $headings[] = 'Citizenship';
        }
        if ($this->filters['civil_status']) {
            $headings[] = 'Civil Status';
        }
        if ($this->filters['height']) {
            $headings[] = 'Height';
        }
        if ($this->filters['weight']) {
            $headings[] = 'Weight';
        }
        if ($this->filters['blood_type']) {
            $headings[] = 'Blood Type';
        }
        if ($this->filters['gsis']) {
            $headings[] = 'GSIS ID No.';
        }
        if ($this->filters['pagibig']) {
            $headings[] = 'PAGIBIG ID No.';
        }
        if ($this->filters['philhealth']) {
            $headings[] = 'PhilHealth ID No.';
        }
        if ($this->filters['sss']) {
            $headings[] = 'SSS No.';
        }
        if ($this->filters['tin']) {
            $headings[] = 'Tin No.';
        }
        if ($this->filters['agency_employee_no']) {
            $headings[] = 'Agency Employee No.';
        }
        // Add more headings based on filters

        return $headings;
    }
}
