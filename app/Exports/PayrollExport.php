<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;

class PayrollExport implements FromCollection, WithEvents
{
    use Exportable;
    protected $payrolls;
    protected $rowNumber = 0;
    protected $filters;
    protected $payrollPeriod;

    public function __construct($payrolls, $filters){
        $this->payrolls = $payrolls;
        $this->filters = $filters;
        $this->setDates();
    }

    private function setDates(){
        if (!empty($this->filters['startDate']) && !empty($this->filters['endDate'])) {
            $startDate = $this->filters['startDate'];
            $endDate = $this->filters['endDate'];

            $this->payrollPeriod = $startDate->format('F') . ' '
                                    . $startDate->format('d') . '-'
                                    . $endDate->format('d') . ', '
                                    . $startDate->format('Y');
        }
    }

    public function collection(){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $zeroCheck = function($value) {
            if ($value == 0 || $value == null) {
                return "-";
            }
            return $value;
        };

        $data = $this->payrolls;
        $totals = $data->reduce(function ($carry, $item) {
            $numericColumns = [
                'daily_salary_rate', 'no_of_days_covered', 'gross_salary', 'absences_days',
                'absences_amount', 'late_undertime_hours', 'late_undertime_hours_amount', 'late_undertime_mins', 'late_undertime_mins_amount',
                'gross_salary_less', 'withholding_tax', 'nycempc', 'total_deductions', 'net_amount_due'
            ];

            foreach ($numericColumns as $column) {
                $carry[$column] = ($carry[$column] ?? 0) + $item[$column];
            }

            return $carry;
        }, []);

        $formattedData = $data->map(function ($payroll, $index) use ($formatCurrency, $zeroCheck) {
            $this->rowNumber++;
            return [
                $this->rowNumber,
                'name' => $payroll['name'],
                'position' => $payroll['position'],
                'employee_number' => ('D-' . substr($payroll['employee_number'], 1)),
                'salary_grade' => $payroll['salary_grade'],
                'daily_salary_rate' => $formatCurrency($payroll['daily_salary_rate']),
                'no_of_days_covered' => $zeroCheck($payroll['no_of_days_covered']),
                'gross_salary' => $formatCurrency($payroll['gross_salary']),
                'absences_days' => $zeroCheck($payroll['absences_days']),
                'absences_amount' => $formatCurrency($payroll['absences_amount']),
                'late_undertime_hours' => $zeroCheck($payroll['late_undertime_hours']),
                'late_undertime_hours_amount' => $formatCurrency($payroll['late_undertime_hours_amount']),
                'late_undertime_mins' => $zeroCheck($payroll['late_undertime_mins']),
                'late_undertime_mins_amount' => $formatCurrency($payroll['late_undertime_mins_amount']),
                'vacant' => '',
                'gross_salary_less' => $formatCurrency($payroll['gross_salary_less']),
                'withholding_tax' => $formatCurrency($payroll['withholding_tax']),
                'nycempc' => $formatCurrency($payroll['nycempc']),
                'vacant2' => '',
                'total_deductions' => $formatCurrency($payroll['total_deductions']),
                'net_amount_due' => $payroll['net_amount_due'] == 0 ? '₱ 0.00' : $formatCurrency($payroll['net_amount_due']),
            ];
        });

        // Add the totals row
        $formattedData->push([
            '', // Serial No.
            'TOTAL', // Name
            '', // Position
            '', // Employee No.
            '', // Salary Grade
            $formatCurrency($totals['daily_salary_rate']),
            '',
            $formatCurrency($totals['gross_salary']),
            $zeroCheck($totals['absences_days']),
            $formatCurrency($totals['absences_amount']),
            $zeroCheck($totals['late_undertime_hours']),
            $formatCurrency($totals['late_undertime_hours_amount']),
            $zeroCheck($totals['late_undertime_mins']),
            $formatCurrency($totals['late_undertime_mins_amount']),
            '', // Vacant
            $formatCurrency($totals['gross_salary_less']),
            $formatCurrency($totals['withholding_tax']),
            $formatCurrency($totals['nycempc']),
            '', // Vacant2
            $formatCurrency($totals['total_deductions']),
            $formatCurrency($totals['net_amount_due']),
        ]);

        return $formattedData;
    }

    public function registerEvents(): array{
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->addCustomHeader($event);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                
                // Apply borders to the data table
                $sheet->getStyle('A8:U' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'], // Black color
                        ],
                    ],
                ]);

                // Apply word wrap
                $sheet->getStyle('A8:U9')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A9:U9')->getAlignment()->setWrapText(true);
                $sheet->getStyle('A10:U10')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A8:U10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Rows
                $sheet->getStyle('A11:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B11:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C11:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F11:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('G11:G' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H11:H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('I11:I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J11:J' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('K11:K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('L11:L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('M11:M' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('N11:U' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('1:1')->getFont()->setSize(16);
                $sheet->getStyle('2:2')->getFont()->setSize(14);

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(12);
                $sheet->getColumnDimension('E')->setWidth(8);
                $sheet->getColumnDimension('F')->setWidth(13);
                $sheet->getColumnDimension('G')->setWidth(12);
                $sheet->getColumnDimension('H')->setWidth(13);
                $sheet->getColumnDimension('I')->setWidth(8);
                $sheet->getColumnDimension('J')->setWidth(13);
                $sheet->getColumnDimension('K')->setWidth(8);
                $sheet->getColumnDimension('L')->setWidth(13);
                $sheet->getColumnDimension('M')->setWidth(8);
                $sheet->getColumnDimension('N')->setWidth(13);
                $sheet->getColumnDimension('O')->setWidth(0);
                $sheet->getColumnDimension('P')->setWidth(13);
                $sheet->getColumnDimension('Q')->setWidth(13);
                $sheet->getColumnDimension('R')->setWidth(13);
                $sheet->getColumnDimension('S')->setWidth(0);
                $sheet->getColumnDimension('T')->setWidth(13);
                $sheet->getColumnDimension('U')->setWidth(20);

                // Set row height for row 12
                $sheet->getRowDimension(10)->setRowHeight(30); 

                // Merge cells A12 and A13
                $sheet->mergeCells('A8:A10');
                $sheet->setCellValue('A8', 'NO.');
                $sheet->mergeCells('B8:B10');
                $sheet->setCellValue('B8', 'NAME');
                $sheet->mergeCells('C8:C10');
                $sheet->setCellValue('C8', 'POSITION');
                $sheet->mergeCells('D8:D10');
                $sheet->setCellValue('D8', 'ID NUMBER');
                $sheet->mergeCells('E8:E10');
                $sheet->setCellValue('E8', 'SALARY GRADE');
                $sheet->mergeCells('F8:F10');
                $sheet->setCellValue('F8', 'DAILY SALARY RATE');
                $sheet->mergeCells('G8:G10');
                $sheet->setCellValue('G8', 'NO. OF DAYS COVERED');
                $sheet->mergeCells('H8:H10');
                $sheet->setCellValue('H8', 'GROSS SALARY');
                $sheet->mergeCells('I8:N8');
                $sheet->setCellValue('I8', 'DEDUCTIONS');
                $sheet->mergeCells('I9:J9');
                $sheet->setCellValue('I9', 'ABSENCES');
                $sheet->mergeCells('K9:N9');
                $sheet->setCellValue('K9', 'LATES & UNDERTIME');
                $sheet->mergeCells('O8:O10');
                $sheet->setCellValue('O8', 'ADJUSTMENT');
                $sheet->mergeCells('P8:P10');
                $sheet->setCellValue('P8', 'GROSS SALARY LESS (Absences/Lates/Undertime');
                $sheet->mergeCells('Q8:Q10');
                $sheet->setCellValue('Q8', 'WITHHOLDING TAX');
                $sheet->mergeCells('R8:R10');
                $sheet->setCellValue('R8', 'NYCEMPC');
                $sheet->mergeCells('S8:S10');
                $sheet->setCellValue('S8', 'AOM #: 2024-009-101 (2023)');
                $sheet->mergeCells('T8:T10');
                $sheet->setCellValue('T8', 'TOTAL DEDUCTIONS');
                $sheet->mergeCells('U8:U10');
                $sheet->setCellValue('U8', 'NET AMOUNT DUE');
                $sheet->mergeCells('AD12:AD13');
                $sheet->setCellValue('I10', 'Days');
                $sheet->setCellValue('J10', 'Amount');
                $sheet->setCellValue('K10', 'Hours');
                $sheet->setCellValue('L10', 'Amount');
                $sheet->setCellValue('M10', 'Mins.');
                $sheet->setCellValue('N10', 'Amount');


                // Style the totals row
                $sheet->getStyle('A' . $highestRow . ':U' . $highestRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
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
                $sheet->getStyle('B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('M' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('L' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F' . $highestRow . ':H' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('N' . $highestRow . ':U' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('A' . $highestRow . ':U' . $highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

                $sheet->getStyle("U8:U{$highestRow}")->getFont()->setBold(true);
                // Add the footer
                $totalNetAmount = $this->payrolls->sum('net_amount_due');
                $this->addFooter($sheet, $highestRow + 2, $totalNetAmount);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;
        $payrollFor = $this->payrollPeriod;
        
        // Add custom header
        $sheet->mergeCells('A1:U1');
        $sheet->setCellValue('A1', "PAYROLL");
        
        $sheet->mergeCells('A2:U2');
        $sheet->setCellValue('A2', "For the Period of " . strtoupper($payrollFor));

        $sheet->mergeCells('A3:C3');
        $sheet->setCellValue('A3', "Entity Name : NATIONAL YOUTH COMMISSION");
        
        $sheet->mergeCells('A4:C4');
        $sheet->setCellValue('A4', "Fund Cluster : ___________________________");

        $sheet->mergeCells('N3:R3');
        $sheet->setCellValue('N3', "Payroll No.: _____________________");
        
        $sheet->mergeCells('N4:R4');
        $sheet->setCellValue('N4', "Sheet _________of __________Sheets");

        $sheet->mergeCells('A5:U5');
        
        $sheet->mergeCells('A6:U6');
        $sheet->setCellValue('A6', "We acknowledge receipt of cash shown opposite our name as");

        $sheet->mergeCells('A7:U7');
        $sheet->setCellValue('A7', "for the period: " . $payrollFor . " (MOA/CONTRACTUAL EMPLOYEES)");
        

        // Apply some basic styling
        $sheet->getStyle('A1:U2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:U')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A7:U7')->getFont()->setBold(true);
        $sheet->getStyle('A8:U8')->getFont()->setBold(false);
        $sheet->getStyle('A9:U9')->getFont()->setBold(false);
        $sheet->getStyle('A10:U10')->getFont()->setBold(false);
        $sheet->getStyle('A1:U7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);

        // Adjust row heights
        for ($i = 1; $i <= 8; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        $sheet->getStyle('A1:U7')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_NONE,
            ],
        ]);

        $sheet->getStyle('C2:F2')->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);

        $sheet->getStyle('C4:F4')->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);

        $event->sheet->setShowGridlines(false);
    }

    private function addFooter($sheet, $startRow, $totalNetAmount){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return 'PHP ' . number_format((float)$value, 2, '.', ',');
        };
        $formattedAmount = number_format($totalNetAmount, 2, '.', '');
        $date = now()->format("m/d/y");

        $imageOptions = [
            'height' => 50,
            'width' => 100
        ];
        $worksheet = $sheet->getDelegate();
        $signatories = $this->filters['signatories']->get()->groupBy('signatory');
        $getSignatoryInfo = function($key) use ($signatories) {
            $signatory = $signatories->get($key, collect())->first();
            return [
                'name' => $signatory['name'] ?? 'XXXXXXXXXX',
                'position' => $signatory['position'] ?? 'XXXXXXXXXX',
                'signature' => $signatory['signature'] ?? null
            ];
        };
        
        $signatoryA = $getSignatoryInfo('A');
        $signatoryB = $getSignatoryInfo('B');
        $signatoryC = $getSignatoryInfo('C');
        $signatoryD = $getSignatoryInfo('D');
        
        $aName = $signatoryA['name'];
        $aPosition = $signatoryA['position'];
        $bName = $signatoryB['name'];
        $bPosition = $signatoryB['position'];
        $cName = $signatoryC['name'];
        $cPosition = $signatoryC['position'];
        $dName = $signatoryD['name'];
        $dPosition = $signatoryD['position'];

        // Add footer content
        $sheet->setCellValue("A{$startRow}", "A.");
        $sheet->mergeCells("B{$startRow}:C{$startRow}");
        $sheet->setCellValue("B{$startRow}", "CERTIFIED: Services duly rendered as stated.");
        $sheet->setCellValue("I{$startRow}", "C.");
        $sheet->mergeCells("J{$startRow}:U{$startRow}");
        $amountInWords = $this->numberToWords($formattedAmount);
        $sheet->setCellValue("J{$startRow}", "APPROVED FOR PAYMENT: " .strtoupper($amountInWords) . " PESOS ONLY");
        $sheet->getStyle("A{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("I{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("J{$startRow}:L{$startRow}");
        $sheet->setCellValue("J{$startRow}", $formatCurrency($totalNetAmount));
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

         // Signature A
         $startRow++;
         $sheet->mergeCells("B{$startRow}:E{$startRow}");
         $signatureA = $this->getTemporarySignaturePath($signatoryA);
         if ($signatureA) {
             $drawingA = new Drawing();
             $drawingA->setName('Signature A');
             $drawingA->setDescription('Signature A');
             $drawingA->setPath($signatureA);
             $drawingA->setHeight($imageOptions['height']);
             $drawingA->setWidth($imageOptions['width']);
             $drawingA->setCoordinates("C{$startRow}");
             $drawingA->setWorksheet($worksheet);
         }
 
         // Signature C
         $sheet->mergeCells("K{$startRow}:P{$startRow}");
         $signatureC = $this->getTemporarySignaturePath($signatoryC);
         if ($signatureC) {
             $drawingC = new Drawing();
             $drawingC->setName('Signature C');
             $drawingC->setDescription('Signature C');
             $drawingC->setPath($signatureC);
             $drawingC->setHeight($imageOptions['height']);
             $drawingC->setWidth($imageOptions['width']);
             $drawingC->setCoordinates("M{$startRow}");
             $drawingC->setWorksheet($worksheet);
         }
         $sheet->getRowDimension($startRow)->setRowHeight($imageOptions['height'] - 2);

        $startRow ++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $aName);
        $sheet->mergeCells("F{$startRow}:G{$startRow}");
        $sheet->setCellValue("F{$startRow}", $date);

        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $cName);
        // $sheet->mergeCells("S{$startRow}:T{$startRow}");
        // $sheet->setCellValue("S{$startRow}", "06/19/24");
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle("S{$startRow}:T{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$startRow}:G{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$startRow}")->getFont()->setBold(true);
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $aPosition);
        $sheet->mergeCells("F{$startRow}:G{$startRow}");
        $sheet->setCellValue("F{$startRow}", "Date");

        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $cPosition);
        // $sheet->mergeCells("S{$startRow}:T{$startRow}");
        // $sheet->setCellValue("S{$startRow}", "Date");
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $startRow++;
        $sheet->setCellValue("A{$startRow}", "B.");
        $sheet->mergeCells("B{$startRow}:H{$startRow}");
        $sheet->setCellValue("B{$startRow}", "CERTIFIED: Supporting documents complete and proper; and cash available in the amount of");
        $sheet->setCellValue("I{$startRow}", "D.");
        $sheet->mergeCells("J{$startRow}:U{$startRow}");
        $sheet->setCellValue("J{$startRow}", "CERTIFIED: Each employee whose name");
        $sheet->getStyle("A{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("I{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $startRow++;
        $sheet->mergeCells("B{$startRow}:C{$startRow}");
        $sheet->setCellValue("B{$startRow}", $formatCurrency($totalNetAmount));
        $sheet->mergeCells("J{$startRow}:U{$startRow}");
        $sheet->setCellValue("J{$startRow}", "appears above has been paid the amount indicated");
        $sheet->getStyle("B{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("J{$startRow}:U{$startRow}");
        $sheet->setCellValue("J{$startRow}", "opposite on his/her name.");

        $startRow++;
        // Signature B
        $sheet->mergeCells("B{$startRow}:E{$startRow}");
        $signatureB = $this->getTemporarySignaturePath($signatoryB);
        if ($signatureB) {
            $drawingB = new Drawing();
            $drawingB->setName('Signature A');
            $drawingB->setDescription('Signature A');
            $drawingB->setPath($signatureB);
            $drawingB->setHeight($imageOptions['height']);
            $drawingB->setWidth($imageOptions['width']);
            $drawingB->setCoordinates("C{$startRow}");
            $drawingB->setWorksheet($worksheet);
        }

        // Signature D
        $sheet->mergeCells("K{$startRow}:P{$startRow}");
        $signatureD = $this->getTemporarySignaturePath($signatoryD);
        if ($signatureD) {
            $drawingD = new Drawing();
            $drawingD->setName('Signature D');
            $drawingD->setDescription('Signature D');
            $drawingD->setPath($signatureD);
            $drawingD->setHeight($imageOptions['height']);
            $drawingD->setWidth($imageOptions['width']);
            $drawingD->setCoordinates("M{$startRow}");
            $drawingD->setWorksheet($worksheet);
        }
        $sheet->getRowDimension($startRow)->setRowHeight($imageOptions['height'] / 2);

        $startRow ++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $bName);
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $dName);
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}")->getFont()->setBold(true);
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $bPosition);
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $dPosition);
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}:U{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("I8:I{$startRow}")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("U1:U{$startRow}")->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);

        $startRow++;
        // Apply white background color to all cells inside the populated table
        $sheet->getStyle("A1:U{$startRow}")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color(Color::COLOR_WHITE));
        // Apply white background color to all cells outside the populated table
        $sheet->getStyle("A{$startRow}:U200")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('A9A9A9'));
        $sheet->getStyle("V1:AD200")->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new Color('A9A9A9'));
        
       
    }

    private function numberToWords($number){
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );
    
        if (!is_numeric($number)) {
            return false;
        }
    
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            trigger_error(
                'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
    
        if ($number < 0) {
            return $negative . $this->numberToWords(abs($number));
        }
    
        $string = $fraction = null;
    
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
    
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->numberToWords($remainder);
                }
                break;
        }
    
        if (null !== $fraction && is_numeric($fraction)) {
            $fraction = substr($fraction . '00', 0, 2);  // Ensure two decimal places
            $string .= " and {$fraction}/100";
        }
    
        return $string;
    }

   
    private function getTemporarySignaturePath($signatory){
        if ($signatory && isset($signatory['signature'])) {
            $path = str_replace('public/', '', $signatory['signature']);
            $originalPath = Storage::disk('public')->get($path);
            $filename = str_replace('public/signatures/', '', $signatory['signature']);
            $tempPath = public_path('temp/' . $filename);
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            file_put_contents($tempPath, $originalPath);
           
            return $tempPath;
        }
        return null;
    }
}
