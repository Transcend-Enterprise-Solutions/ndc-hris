<?php

namespace App\Exports;

use App\Models\LearningAndDevelopment;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class EmployeesExport implements FromCollection, WithEvents
{
    use Exportable;

    protected $filters;
    protected $selectedColumns;
    protected $rowNumber = 0;

    public function __construct($filters = [], $selectedColumns = [])
    {
        $this->filters = array_merge([
            'sex' => null,
            'civil_status' => [],
            'selectedProvince' => [],
            'selectedCity' => [],
            'selectedBarangay' => [],
            'selectedLD' => [],
            'office_division' => '',
            'unit' => '',
        ], $filters);

        $this->selectedColumns = $selectedColumns;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->addCustomHeader($event);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $this->formatSheet($event);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event)
    {
        $sheet = $event->sheet;
        $lastColumn = $this->getLastColumnLetter();

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        
        $sheet->mergeCells("A3:{$lastColumn}3");
        $sheet->setCellValue('A3', "Employee List");

        // Add column headers in row 4
        $headers = $this->getColumnHeaders();
        foreach ($headers as $index => $header) {
            $column = $this->getColumnLetter($index + 1);
            $sheet->setCellValue("{$column}4", $header);
        }

        $sheet->getStyle('A1:' . $lastColumn . '4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:' . $lastColumn . '4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastColumn . '3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('2:2')->getFont()->setSize(16);
        $sheet->getStyle("A4:{$lastColumn}4")->getFont()->setBold(true);
    }

    private function formatSheet(AfterSheet $event)
    {
        $sheet = $event->sheet;
        $lastColumn = $this->getLastColumnLetter();
        $highestRow = $sheet->getHighestRow();

        // Set column widths
        $columnCount = count($this->getColumnHeaders());
        for ($i = 1; $i <= $columnCount; $i++) {
            $column = $this->getColumnLetter($i);
            if($column == 'A'){
                $sheet->getColumnDimension($column)->setWidth(5);
            }else{
                $sheet->getColumnDimension($column)->setWidth(20);
            }
        }

        // Format header row
        $sheet->getStyle("A4:{$lastColumn}4")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFF0F0F0'],
            ],
        ]);

        // Set text wrapping and alignment for all cells
        $sheet->getStyle("A4:{$lastColumn}{$highestRow}")->applyFromArray([
            'alignment' => [
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align all columns except 'Name'
        $nameColumnIndex = array_search('Name', $this->getColumnHeaders());
        foreach (range('A4', $lastColumn . '10000') as $column) {
            if ($column !== chr(65 + $nameColumnIndex)) { // 65 is ASCII for 'A'
                $sheet->getStyle("{$column}5:{$column}{$highestRow}")
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        }
    }

    private function getLastColumnLetter()
    {
        return $this->getColumnLetter(count($this->getColumnHeaders()));
    }

    private function getColumnLetter($columnNumber){
        $columnLetter = '';
        while ($columnNumber > 0) {
            $modulo = ($columnNumber - 1) % 26;
            $columnLetter = chr(65 + $modulo) . $columnLetter;
            $columnNumber = (int)(($columnNumber - $modulo) / 26);
        }
        return $columnLetter;
    }

    public function collection()
    {
        $query = User::join('user_data', 'users.id', '=', 'user_data.user_id')
                ->leftJoin('learning_and_development', 'learning_and_development.user_id', 'users.id');
    
        $columnsToSelect = ['users.id'];
        $columnsToGroupBy = ['users.id'];
    
        $nameFields = ['surname', 'first_name', 'middle_name', 'name_extension'];
        $nameFieldsSelected = false;
    
        foreach ($this->selectedColumns as $column) {
            if ($column !== 'years_in_gov_service' && $column !== 'learning_and_development') {
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
            $query->addSelect(DB::raw('(
                SELECT FLOOR(SUM(
                    CASE
                        WHEN work_experience.toPresent = "Present" THEN TIMESTAMPDIFF(MONTH, work_experience.start_date, CURDATE())
                        WHEN work_experience.end_date IS NOT NULL THEN TIMESTAMPDIFF(MONTH, work_experience.start_date, work_experience.end_date)
                        ELSE 0
                    END
                ) / 12)
                FROM work_experience
                WHERE work_experience.user_id = users.id AND work_experience.gov_service = 1
            ) as years_in_gov_service'));
        }
    
        // Apply filters
        if (!empty($this->filters['sex'])) {
            if($this->filters['sex'] == 'others'){
                $query->where('user_data.sex', '!=', 'Female')
                      ->where('user_data.sex', '!=', 'Male');
            } else {
                $query->where('user_data.sex', $this->filters['sex']);
            }
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
        if (!empty($this->filters['selectedLD'])) {
            $query->whereIn('learning_and_development.type_of_ld', $this->filters['selectedLD']);
            $columnsToSelect[] = 'learning_and_development.user_id as learning_and_development';
            $columnsToGroupBy[] = 'learning_and_development.user_id';
        }
    
        $query->groupBy($columnsToGroupBy);
    
        return $query->get()
            ->map(function ($user) use ($nameFields, $nameFieldsSelected) {
                $this->rowNumber++;
                $userData = [$this->rowNumber];
                
                if (in_array('name', $this->selectedColumns) || $nameFieldsSelected) {
                    $fullName = trim(implode(' ', [
                        $user->surname ?? '',
                        $user->first_name ?? '',
                        $user->middle_name ?? '',
                        $user->name_extension ?? ''
                    ]));
                    $userData[] = $fullName;
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
                            $userData[] = $statusMapping[$user->active_status] ?? 'Unknown';
                        } elseif ($column === 'years_in_gov_service') {
                            $userData[] = $user->years_in_gov_service ?? 'N/A';
                        } elseif ($column === 'date_of_birth' || $column === 'date_hired') {
                            $userData[] = $user->$column ? Carbon::parse($user->$column)->format('F d, Y') : 'N/A';
                        } elseif ($column === 'sex') {
                            $userData[] = $user->$column == 'No' ? 'Prefer Not To Say' : $user->$column;
                        } elseif ($column === 'learning_and_development') {
                            $lds = LearningAndDevelopment::where('user_id', $user->id)->get();
                            if(!$lds->isEmpty()){
                                $leardDev = '';
                                foreach($lds as $ld){
                                    $leardDev = $leardDev . (' • ' . $ld->type_of_ld);
                                }
                                $userData[] = $leardDev;
                            }else{
                                $userData[] = 'N/A';
                            }
                        } else {
                            $userData[] = $user->$column ?? 'N/A';
                        }
                    }
                }
                return $userData;
            });
    }

    private function getColumnHeaders(): array
    {
        $headers = ['#'];
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
            'learning_and_development' => 'Learning and Development',
        ];

        return $headers[$column] ?? $column;
    }
}