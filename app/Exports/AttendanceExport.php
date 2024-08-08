<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Exceptions\InvalidFormatException;

class AttendanceExport implements FromCollection, WithEvents
{
    use Exportable;

    protected $filters;
    protected $rowNumber = 0;

    public function __construct($filters){
        $this->filters = $filters;
    }

    public function registerEvents(): array{
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->addCustomHeader($event);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(30);
                for ($col = 'F'; $col <= 'O'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }


                // Set row height for row 4
                $sheet->getRowDimension(4)->setRowHeight(40); 
; 

                // Merge cells A12 and A13
                $sheet->setCellValue('A4', '');
                $sheet->setCellValue('B4', 'NAME');
                $sheet->setCellValue('C4', 'EMPLOYEE NO.');
                $sheet->setCellValue('D4', 'POSITION');
                $sheet->setCellValue('E4', 'DEPARTMENT');
                $sheet->setCellValue('F4', 'OFFICE/DIVISION');
                $sheet->setCellValue('G4', 'MORNING IN');
                $sheet->setCellValue('H4', 'MORNING OUT');
                $sheet->setCellValue('I4', 'AFTERNOON IN');
                $sheet->setCellValue('J4', 'AFTERNOON OUT');
                $sheet->setCellValue('K4', 'LATE/UNDERTIME');
                $sheet->setCellValue('L4', 'OVERTIME');
                $sheet->setCellValue('M4', 'TOTAL HOURS RENDERED');
                $sheet->setCellValue('N4', 'LOCATION');
                $sheet->setCellValue('O4', 'REMARKS');

                // Apply word wrap
                $sheet->getStyle('A:O')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A1:O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:O4')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);

                // Rows
                $sheet->getStyle('A4:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C4:O' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;
        $formatDate = function($value) {
            $date = Carbon::parse($value)->format('F d, Y');
            return $date;
        };

        // Add custom header
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        $sheet->mergeCells('A3:O3');
        $sheet->setCellValue('A3', "Attendance for " . $formatDate($this->filters['date']));

        // Apply some basic styling
        $sheet->getStyle('A1:O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:O3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('A4:O4')->getFont()->setBold(true);
        $sheet->getStyle('2:2')->getFont()->setSize(16);

        $sheet->getStyle('A4:O4')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'], // Black color
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFF0F0F0'], // Light gray background
            ],
        ]);
    }

    public function collection(){
        $query = User::join('employees_dtr', 'employees_dtr.user_id', 'users.id')
                ->join('payrolls', 'payrolls.user_id', 'users.id')
                ->where('users.user_role', 'emp')
                ->where(function ($query) {
                    $query->where('employees_dtr.remarks', 'Present')
                            ->orWhere('employees_dtr.remarks', 'Late');
                })
                ->select('users.email', 'employees_dtr.*', 'payrolls.*');

        if ($this->filters && isset($this->filters['date'])) {
            try {
                $query->where('employees_dtr.date', $this->filters['date']);
            } catch (InvalidFormatException $e) {
                throw $e;
            }
        }

        return $query->get()
            ->map(function ($user) {
                $this->rowNumber++;
                return [
                    $this->rowNumber,
                    'NAME' => $user->name,
                    'EMPLOYEE ID' => $user->employee_number,
                    'POSITION' => $user->position,
                    'DEPARTMENT' => $user->department,
                    'OFFICE/DIVISION' => $user->office_division,
                    'MORNING IN' => $user->morning_in,
                    'MORNING OUT' => $user->morning_out,
                    'AFTERNOON IN' => $user->afternoon_in,
                    'AFTERNOON OUT' => $user->afternoon_out,
                    'LATE/UNDERTIME' => $user->late,
                    'OVERTIME' => $user->overtime,
                    'TOTAL HOURS RENDERED' => $user->total_hours_rendered,
                    'LOCATION' => $user->location,
                    'REMARKS' => $user->remarks,
                ];
            });
    }
}
