<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class PayrollExport implements FromCollection, WithHeadings
{
    use Exportable;
    protected $payrolls;

    public function __construct($payrolls){
        $this->payrolls = $payrolls;
    }

    public function collection()
    {

        // return $query->get()
        //     ->map(function ($user) {
        //         return [
        //             'ID' => $user->id,
        //             'Surname' => $user->surname,
        //             'First Name' => $user->first_name,
        //             'Middle Name' => $user->middle_name,
        //             'Name Extension' => $user->name_extension,
        //             'Birth Date' => $user->date_of_birth,
        //             'Birth Place' => $user->place_of_birth,
        //             'Sex' => $user->sex,
        //             'Citizenship' => $user->citizenship,
        //             'Civil Status' => $user->civil_status,
        //             'Height' => $user->height,
        //             'Weight' => $user->weight,
        //             'Blood Type' => $user->blood_type,
        //             'GSIS ID No.' => $user->gsis,
        //             'PAGIBIG ID No.' => $user->pagibig,
        //             'PhilHealth ID No.' => $user->philhealth,
        //             'SSS No.' => $user->sss,
        //             'TIN No.' => $user->tin,
        //             'Agency Employee No.' => $user->agency_employee_no,
        //             'Telephone No.' => $user->tel_number,
        //             'Mobile No.' => $user->mobile_number,
        //             'Permanent Address (Province)' => $user->permanent_selectedProvince,
        //             'Permanent Address (City)' => $user->permanent_selectedCity,
        //             'Permanent Address (Barangay)' => $user->permanent_selectedBarangay,
        //             'Permanent Address (Street)' => $user->p_house_street,
        //             'Permanent Address (Zip Code)' => $user->permanent_selectedZipcode,
        //             'Residential Address (Province)' => $user->residential_selectedProvince,
        //             'Residential Address (City)' => $user->residential_selectedCity,
        //             'Residential Address (Barangay)' => $user->residential_selectedBarangay,
        //             'Residential Address (Street)' => $user->r_house_street,
        //             'Residential Address (Zip Code)' => $user->residential_selectedZipcode,
        //         ];
        //     });
    }

    public function headings(): array
    {
        return [
            'NO.',
            'NAME',
            'POSITION',
            'ID NUMBER',
            'SALARY GRADE',
            'NO. OF DAYS COVERED',
            'GROSS SALARY',
            'ABSENCES (DAYS)',
            'ABSENCES (DAYS AMOUNT)',
            'LATE&UNDERTIME (HOURS)',
            'LATE&UNDERTIME (HOURS AMOUNT)',
            'LATE&UNDERTIME (MINS.)',
            'LATE&UNDERTIME (MINS. AMOUNT)',
            'GROSS SALARY LESS (ABSENCES/LATES/UNDERTIME)',
            'WITHHOLDING TAX',
            'NYCEMP',
            'TOTAL DEDUCTIONS',
            'NET AMOUNT DUE',
        ];
    }
}
