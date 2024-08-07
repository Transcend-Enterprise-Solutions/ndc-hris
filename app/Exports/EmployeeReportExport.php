<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EmployeeReportExport implements FromCollection, WithEvents
{
    use Exportable;

    protected $filters;

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

                $firstHalf = $this->startDateFirstHalf && $this->endDateFirstHalf
                ? Carbon::parse($this->startDateFirstHalf)->format('F d') . '-' . Carbon::parse($this->endDateFirstHalf)->format('d, Y')
                : 'Date range not set';
    
                $secondHalf = $this->startDateSecondHalf && $this->endDateSecondHalf
                    ? Carbon::parse($this->startDateSecondHalf)->format('F d') . '-' . Carbon::parse($this->endDateSecondHalf)->format('d, Y')
                    : 'Date range not set';
                
                // Apply borders to the data table
                $sheet->getStyle('A12:AF' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Black color
                        ],
                    ],
                ]);
                // Apply border to range AC2:AF2
                $sheet->getStyle('AC2:AF2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Black color
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Apply border and center text alignment to range AC3:AF3
                $sheet->getStyle('AC3:AF3')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Black color
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Apply word wrap
                $sheet->getStyle('A12:AF12')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A13:AF13')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A12:AF12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Rows
                $sheet->getStyle('A13:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C13:C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('D13:AF' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(8);
                for ($col = 'F'; $col <= 'K'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
                for ($col = 'L'; $col <= 'V'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
                $sheet->getColumnDimension('W')->setWidth(15);
                $sheet->getColumnDimension('X')->setWidth(15);
                $sheet->getColumnDimension('Y')->setWidth(15);
                $sheet->getColumnDimension('Z')->setWidth(15);
                $sheet->getColumnDimension('AA')->setWidth(15);
                $sheet->getColumnDimension('AB')->setWidth(15);
                for ($col = 'AC'; $col <= 'AF'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }

                // Set row height for row 12
                $sheet->getRowDimension(13)->setRowHeight(70); 

                // Set vertical text rotation for a specific cell or range
                $sheet->getStyle('A12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('B12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('D12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('E12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('H12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('M13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('N13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('O13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('P13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('Q13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('R13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('S13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('T13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('U13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('V13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('W12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('X12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('Y13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('Z13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('AA13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('AB13')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('AC12')->getAlignment()->setTextRotation(90); 
                $sheet->getStyle('AD12')->getAlignment()->setTextRotation(90); 

                // Merge cells A12 and A13
                $sheet->mergeCells('A12:A13');
                $sheet->setCellValue('A12', 'SERIAL NO.');
                $sheet->mergeCells('B12:B13');
                $sheet->setCellValue('B12', 'EMPLOYEE NO.');
                $sheet->mergeCells('C12:C13');
                $sheet->setCellValue('C12', 'NAME');
                $sheet->mergeCells('D12:D13');
                $sheet->setCellValue('D12', 'POSITION');
                $sheet->mergeCells('E12:E13');
                $sheet->setCellValue('E12', 'SG/STEP');
                $sheet->mergeCells('F12:F13');
                $sheet->setCellValue('F12', 'Rate Per Month (per NBC 591) dtd. January 2023');
                $sheet->mergeCells('G12:G13');
                $sheet->setCellValue('G12', 'Personal Economic Relief Allowance');
                $sheet->mergeCells('H12:H13');
                $sheet->setCellValue('H12', 'GROSS AMT.');
                $sheet->mergeCells('I12:I13');
                $sheet->setCellValue('I12', 'ADDITIONAL GSIS PREMIUM');
                $sheet->mergeCells('J12:J13');
                $sheet->setCellValue('J12', 'LBP SALARY LOAN');
                $sheet->mergeCells('K12:K13');
                $sheet->setCellValue('K12', 'NYCEA DEDUCTIONS');
                $sheet->mergeCells('L12:M12');
                $sheet->setCellValue('L12', 'NYC COOP');
                $sheet->mergeCells('N12:V12');
                $sheet->setCellValue('N12', 'GSIS');
                $sheet->mergeCells('W12:W13');
                $sheet->setCellValue('W12', 'PAG-IBIG MPL');
                $sheet->mergeCells('X12:X13');
                $sheet->setCellValue('X12', 'OTHER DEDUCTIONS PHILHEALTH DIFFERENTIAL');
                $sheet->mergeCells('Y12:AB12');
                $sheet->setCellValue('Y12', 'MANDATORY DEDUCTION');
                $sheet->mergeCells('AC12:AC13');
                $sheet->setCellValue('AC12', 'TOTAL DEDUCTION');
                $sheet->mergeCells('AD12:AD13');
                $sheet->setCellValue('AD12', 'NET AMOUNT RECEIVED');
                $sheet->mergeCells('AE12:AE13');
                $sheet->setCellValue('AE12', 'AMOUNT DUE ' . $firstHalf );
                $sheet->mergeCells('AF12:AF13');
                $sheet->setCellValue('AF12', 'AMOUNT DUE ' . $secondHalf );
                $sheet->setCellValue('L13', 'S.C/MEMBERSHIP');
                $sheet->setCellValue('M13', 'TOTAL LOANS');
                $sheet->setCellValue('N13', 'SALARY LOAN');
                $sheet->setCellValue('O13', 'POLICY LOAN');
                $sheet->setCellValue('P13', 'EAL');
                $sheet->setCellValue('Q13', 'EMERGENCY LOAN');
                $sheet->setCellValue('R13', 'MPL');
                $sheet->setCellValue('S13', 'HOUSING LOAN');
                $sheet->setCellValue('T13', 'OULI PREM');
                $sheet->setCellValue('U13', 'GFAL');
                $sheet->setCellValue('V13', 'CPL');
                $sheet->setCellValue('Y13', 'LIFE & RETIREMENT INSURANCE PREMIUMS');
                $sheet->setCellValue('Z13', 'PAG-IBIG CONTRIBUTION');
                $sheet->setCellValue('AA13', 'W/HOLDING TAX');
                $sheet->setCellValue('AB13', 'PHILHEALTH');

                // Style the totals row
                $sheet->getStyle('A' . $highestRow . ':AF' . $highestRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_DOUBLE,
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFF0F0F0'], // Light gray background
                    ],
                ]);

                // Set the row height to 50 pixels
                $sheet->getRowDimension($highestRow)->setRowHeight(30);

                // Center align the "TOTAL" text
                $sheet->getStyle('C' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F' . $highestRow . ':AF' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $highestRow . ':AF' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;

        // Add custom header
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', "Employee List");

        // Apply some basic styling
        $sheet->getStyle('A1:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:I3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:I3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);

        // Adjust row heights
        for ($i = 1; $i <= 8; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        $sheet->getStyle('A1:AF11')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_NONE,
            ],
        ]);

        $event->sheet->setShowGridlines(false);

        // Push down the headings
        $sheet->insertNewRowBefore(4, 1);
    }

    public function collection(){
        $query = User::join('payrolls', 'payrolls.user_id', 'users.id')
                ->where('users.user_role', 'emp')
                ->select('users.email', 'payrolls.*', 'users.created_at as date_employed');

        $formatDate = function($value) {
            $date = Carbon::parse($value)->format('F d, Y');
            return $date;
        };

        return $query->get()
            ->map(function ($user) use ($formatDate) {
                return [
                    'Name' => $user->name,
                    'Email' => $user->email,
                    'Employee ID' => $user->employee_number,
                    'Position' => $user->position,
                    'Department' => $user->department,
                    'Office/Division' => $user->office_division,
                    'SG/STEP' => $user->sg_step,
                    'Rate Per Month' => $user->rate_per_month,
                    'Date Employed' => $formatDate($user->date_employed),
                ];
            });
    }

    public function headings(): array{
        return [
            'NAME',
            'EMAIL',
            'EMPLOYEE ID',
            'POSITION',
            'DEPARTMENT',
            'OFFICE/DIVISION',
            'SG/STEP',
            'RATE PER MONTH',
            'DATE EMPLOYED',
        ];
    }
}
