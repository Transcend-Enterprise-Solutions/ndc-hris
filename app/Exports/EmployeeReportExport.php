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

class EmployeeReportExport implements FromCollection, WithEvents
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
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(10);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);


                // Set row height for row 4
                $sheet->getRowDimension(4)->setRowHeight(40); 
; 

                // Merge cells A12 and A13
                $sheet->setCellValue('A4', '');
                $sheet->setCellValue('B4', 'NAME');
                $sheet->setCellValue('C4', 'EMAIL');
                $sheet->setCellValue('D4', 'EMPLOYEE NO.');
                $sheet->setCellValue('E4', 'POSITION');
                $sheet->setCellValue('F4', 'DEPARTMENT');
                $sheet->setCellValue('G4', 'OFFICE/DIVISION');
                $sheet->setCellValue('H4', 'SG/STEP');
                $sheet->setCellValue('I4', 'RATE PER MONTH');
                $sheet->setCellValue('J4', 'DATE EMPLOYED');

                // Apply word wrap
                $sheet->getStyle('A:J')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A1:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);

                // Rows
                $sheet->getStyle('A4:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C4:J' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;

        // Add custom header
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', "Employee List");

        // Apply some basic styling
        $sheet->getStyle('A1:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:J3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('A4:J4')->getFont()->setBold(true);
        $sheet->getStyle('2:2')->getFont()->setSize(16);

        $sheet->getStyle('A4:J4')->applyFromArray([
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
        $formatDate = function($value) {
            $date = Carbon::parse($value)->format('F d, Y');
            return $date;
        };

        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $query = User::join('payrolls', 'payrolls.user_id', 'users.id')
                ->where('users.user_role', 'emp')
                ->select('users.email', 'payrolls.*', 'users.created_at as date_employed');

        if ($this->filters && isset($this->filters['month'])) {
            try {
                $monthDate = Carbon::createFromFormat('Y-m', $this->filters['month']);
                $startDate = $monthDate->copy()->startOfMonth()->toDateString();
                $endDate = $monthDate->copy()->endOfMonth()->toDateString();
                $query->whereBetween('users.created_at', [$startDate, $endDate]);
            } catch (InvalidFormatException $e) {
                throw $e;
            }
        }

        if ($this->filters && isset($this->filters['department'])) {
            try {
                $query->where('payrolls.department', $this->filters['department']);
            } catch (InvalidFormatException $e) {
                throw $e;
            }
        }

        return $query->get()
            ->map(function ($user) use ($formatDate, $formatCurrency) {
                $this->rowNumber++;
                return [
                    $this->rowNumber,
                    'Name' => $user->name,
                    'Email' => $user->email,
                    'Employee ID' => $user->employee_number,
                    'Position' => $user->position,
                    'Department' => $user->department,
                    'Office/Division' => $user->office_division,
                    'SG/STEP' => $user->sg_step,
                    'Rate Per Month' => $formatCurrency($user->rate_per_month),
                    'Date Employed' => $formatDate($user->date_employed),
                ];
            });
    }
}
