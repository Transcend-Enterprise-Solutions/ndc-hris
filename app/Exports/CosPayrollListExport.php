<?php

namespace App\Exports;

use App\Models\CosPayrolls;
use App\Models\Payrolls;
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

class CosPayrollListExport implements FromCollection, WithEvents
{
    use Exportable;

    protected $filters;
    protected $rowNumber = 0;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection(){
        $formatCurrency = function($value) {
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $query = User::query()
                ->join('cos_payrolls', 'cos_payrolls.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id');

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('employee_number', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('sg_step', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('position', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }

        return $query->get()->map(function ($payroll) use ($formatCurrency) {
            $this->rowNumber++;
            return [
                $this->rowNumber,
                'name' => $payroll->name,
                'employee_number' => $payroll->emp_code,
                'position' => $payroll->position,
                'office_division' => $payroll->office_division,
                'sg_step' => $payroll->sg_step,
                'rate_per_month' => $formatCurrency($payroll->rate_per_month),
            ];
        });
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
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('E')->setWidth(30);
                for ($col = 'F'; $col <= 'G'; $col++) {
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
                $sheet->setCellValue('E4', 'OFFICE/ DIVISION');
                $sheet->setCellValue('F4', 'SG/STEP');
                $sheet->setCellValue('G4', 'RATE PER MONTH');

                // Apply word wrap
                $sheet->getStyle('A4:G4')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);

                // Rows
                $sheet->getStyle('A4:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C4:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;

        // Add custom header
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', "COS Payroll List");

        // Apply some basic styling
        $sheet->getStyle('A1:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:G3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('2:2')->getFont()->setSize(16);

        $sheet->getStyle('A4:G4')->applyFromArray([
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
    
}
