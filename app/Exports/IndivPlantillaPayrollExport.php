<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class IndivPlantillaPayrollExport implements WithEvents, WithDrawings
{
    use Exportable;

    protected $filters;
    protected $rowNumber = 0;
    protected $totalPayroll;
    protected $months;
    protected $currentRow = 1;
    protected $payroll;
    protected $dateRange;

    public function drawings(){
        return [];
    }

    public function __construct($filters){
        $this->filters = $filters;
        $this->payroll = $filters['payroll'];
        $this->getMonthsRange();
    }

    private function getMonthsRange(){
        $start = Carbon::parse($this->filters['startMonth']);
        $end = Carbon::parse($this->filters['endMonth']);
        $months = [];

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        $start = Carbon::parse($this->filters['startMonth']);
        $end = Carbon::parse($this->filters['endMonth']);
    
        $startYear = $start->format('Y');
        $startMonth = $start->format('F');
        $endYear = $end->format('Y');
        $endMonth = $end->format('F');

        
        $this->months = $months;
        
        $date = "";
        if($startMonth == $endMonth && $startYear == $endYear){
            $date = $startMonth . ", " . $startYear;
        }else if($startYear == $endYear){
            $numberOfMonths = count($this->months);
            if($numberOfMonths == 2){
                $date = $startMonth . ' and ' . $endMonth . ", " . $startYear;
            }else{
                $date = $startMonth . ' to ' . $endMonth . ", " . $startYear;
            }
        }

        $this->dateRange = $date;
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(8);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);

                $this->formatAllMonths($sheet);
            },
        ];
    }

    private function formatAllMonths($sheet){
        $this->Header($this->payroll, $sheet);
        $this->DataRows($this->payroll, $sheet);
        $this->Footer($this->payroll, $sheet);
    }

    private function Header($payroll, $sheet){
        $prefix = "";
        if($payroll->sex == "Male"){
            $prefix = "Mr.";
        }else if($payroll->sex == "Female"){
            if($payroll->civil_status == "Single" || $payroll->civil_status == "Widowed" || $payroll->civil_status == "Separated"){
                $prefix = "Ms.";
            }else{
                $prefix = "Mrs.";
            }
        }
    
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $this->currentRow ++;
        $headerRowStart = $this->currentRow;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Computation of FIRST SALARY OF");
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getBorders()->getAllBorders()->applyFromArray([
            'bottom' => ['borderStyle' => Border::BORDER_NONE],
        ]);
    
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $prefix . " " . $payroll->name);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getBorders()->getAllBorders()->applyFromArray([
            'bottom' => ['borderStyle' => Border::BORDER_NONE],
        ]);
    
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $payroll->position);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getBorders()->getAllBorders()->applyFromArray([
            'bottom' => ['borderStyle' => Border::BORDER_NONE],
        ]);
    
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "for the period of " . $this->dateRange);
    
        $headerRowEnd = $this->currentRow;
        $sheet->getStyle("A:F")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getBorders()->getAllBorders()->applyFromArray([
            'allBorders' => ['borderStyle' => Border::BORDER_NONE],
        ]);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getFont()->setBold(true);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        for ($i = 2; $i <= 5; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }

    private function DataRows($payroll, $sheet){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return 0.00;
            }
            return number_format((float)$value, 2, '.', ',');
        };

        $half = $payroll->rate_per_month / 2;
        $perDay = $payroll->rate_per_month / 22;
        $perHour = $perDay / 8;
        $perMin = $perHour / 60;
        $peraPerDay = $payroll->personal_economic_relief_allowance / 22;

        $this->currentRow += 3;
        $bodyRowStart = $this->currentRow;
        $sheet->setCellValue("A{$this->currentRow}", "Gross Salary");
        $sheet->setCellValue("B{$this->currentRow}", "(one month)");
        $sheet->setCellValue("C{$this->currentRow}", "SG " . $payroll->sg_step);
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll->rate_per_month));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Half of Monthly Salary");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($half));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Rate per day");
        $sheet->setCellValue("B{$this->currentRow}", $formatCurrency($payroll->rate_per_month) . "/22 days)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perDay));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Rate per hour");
        $sheet->setCellValue("B{$this->currentRow}", $formatCurrency($perDay) . "/8 hours)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perHour));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Rate per minute");
        $sheet->setCellValue("B{$this->currentRow}", $formatCurrency($perHour) . "/60 minutes)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perMin));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "PERA");
        $sheet->setCellValue("B{$this->currentRow}", "(one month)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll->personal_economic_relief_allowance));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "PERA per day");
        $sheet->setCellValue("B{$this->currentRow}", $formatCurrency($payroll->personal_economic_relief_allowance) . "/22 days)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($peraPerDay));

        $this->currentRow += 2;

        $gross = null;
        foreach ($this->months as $month) {
            $pay = $this->getPerMonthPayroll($month, $sheet, $payroll);
            $gross = $gross + $pay;
        }

        $this->currentRow += 2;
        $sheet->setCellValue("A{$this->currentRow}", "GROSS");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($gross));
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getRowDimension($this->currentRow)->setRowHeight(30);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

        // $sheet->getStyle("F{$this->currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        $bodyRowEnd = $this->currentRow;
        $sheet->getStyle("A{$bodyRowStart}:A{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("B{$bodyRowStart}:B{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("F{$bodyRowStart}:F{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E{$bodyRowStart}:E{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Start of all deductions
        $totalDeductions = null;

        // GSIS
        $this->currentRow += 2;
        $sheet->setCellValue("B{$this->currentRow}", "GSIS Premiums:");
        $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow += 2;
        foreach ($this->months as $month) {
            $gsis = $this->getGsisDeductions($month, $sheet, $payroll);
            $totalDeductions = $totalDeductions + $gsis;
        }

        // Philhealth
        $this->currentRow += 2;
        $sheet->setCellValue("B{$this->currentRow}", "PHILHEALTH Premiums:");
        $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $totalPhilhealth = $payroll->philhealth * count($this->months);
        $sheet->mergeCells("C{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("B{$this->currentRow}", $this->dateRange);
        $sheet->setCellValue("C{$this->currentRow}", $formatCurrency($payroll->philhealth) . " x " . count($this->months) . " MONTH/S");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($totalPhilhealth));
        $sheet->getStyle("E{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $totalDeductions = $totalDeductions + $totalPhilhealth;

        // Pagibig
        $this->currentRow += 2;
        $sheet->setCellValue("B{$this->currentRow}", "PAG IBIG Premiums:");
        $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $totalPagibig = $payroll->philhealth * count($this->months);
        $sheet->mergeCells("C{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("B{$this->currentRow}", $this->dateRange);
        $sheet->setCellValue("C{$this->currentRow}", $formatCurrency($payroll->pagibig_contribution) . " x " . count($this->months) . " MONTH/S");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($totalPagibig));
        $sheet->getStyle("E{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $totalDeductions = $totalDeductions + $totalPagibig;

        // Withholding Tax
        $this->currentRow += 2;
        $sheet->setCellValue("B{$this->currentRow}", "WITHHOLDING TAX:");
        $sheet->getStyle("B{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $totalTax = $payroll->w_holding_tax * count($this->months);
        $sheet->mergeCells("C{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("B{$this->currentRow}", $this->dateRange);
        $sheet->setCellValue("C{$this->currentRow}", $formatCurrency($payroll->w_holding_tax) . " x " . count($this->months) . " MONTH/S");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($totalTax));
        $sheet->getStyle("E{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $totalDeductions = $totalDeductions + $totalTax;

        // Total Deduction
        $this->currentRow += 2;
        $sheet->setCellValue("B{$this->currentRow}", "Total Deductions:");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($totalDeductions));
        $sheet->getStyle("F{$this->currentRow}")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("F{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Net
        $this->currentRow ++;
        $net = $gross - $totalDeductions;
        $sheet->setCellValue("B{$this->currentRow}", "NET");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($net));
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle("F{$this->currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("F{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($this->currentRow)->setRowHeight(30);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

    }

    private function getPerMonthPayroll($month, $sheet, $payroll){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return 0.00;
            }
            return number_format((float)$value, 2, '.', ',');
        };

        $carbonDate = Carbon::parse($month);
        $payrollYear = $carbonDate->format('Y');
        $payrollMonth = $carbonDate->format('F');
        $startDate = $carbonDate->copy()->startOfMonth()->format('d');
        $endDate = $carbonDate->copy()->endOfMonth()->format('d');

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Salary (" . $payrollMonth . " " . $startDate . "-" . $endDate . ", " . $payrollYear . ")");
        $sheet->setCellValue("C{$this->currentRow}", $formatCurrency($payroll->rate_per_month));
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($payroll->personal_economic_relief_allowance));
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll->gross_amount));
        $sheet->getStyle("F{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;

        return $payroll->gross_amount;
    }

    private function getGsisDeductions($month, $sheet, $payroll){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return 0.00;
            }
            return number_format((float)$value, 2, '.', ',');
        };

        $carbonDate = Carbon::parse($month);
        $payrollYear = $carbonDate->format('Y');
        $payrollMonth = $carbonDate->format('F');
        $startDate = $carbonDate->copy()->startOfMonth()->format('d');
        $endDate = $carbonDate->copy()->endOfMonth()->format('d');
        $totalDaysInMonth = $carbonDate->daysInMonth;

        $gsisPercentageAmt = $payroll->rate_per_month * 0.09;
        $gsisPercentageAmtPerDay =  $gsisPercentageAmt / $totalDaysInMonth;
        $totalGSIS = $gsisPercentageAmtPerDay * $totalDaysInMonth;

        $this->currentRow ++;
        $rowStart = $this->currentRow;
        $sheet->mergeCells("A{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $formatCurrency($payroll->rate_per_month) . " x 9%");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($gsisPercentageAmt));

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $formatCurrency($gsisPercentageAmt) . "/" . $totalDaysInMonth . " calendar days");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($gsisPercentageAmtPerDay));

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:D{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $formatCurrency($gsisPercentageAmtPerDay) . " x " . $totalDaysInMonth . " calendar days (" . $payrollMonth . " " . $startDate . "-" . $endDate . ", " . $payrollYear . ")");
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($totalGSIS));
        $sheet->getStyle("E{$this->currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("E{$this->currentRow}")->getFont()->setBold(true);

        $rowEnd = $this->currentRow;
        $sheet->getStyle("A{$rowStart}:A{$rowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E{$rowStart}:E{$rowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $this->currentRow ++;

        return $totalGSIS;
    }

    private function Footer($payroll, $sheet){
        $imageOptions = [
            'height' => 50,
            'width' => 100
        ];
        $worksheet = $sheet->getDelegate();

        $prefix = "";
        if($payroll->sex == "Male"){
            $prefix = "Mr.";
        }else if($payroll->sex == "Female"){
            if($payroll->civil_status == "Single" || $payroll->civil_status == "Widowed" || $payroll->civil_status == "Separated"){
                $prefix = "Ms.";
            }else{
                $prefix = "Mrs.";
            }
        }

        $this->currentRow += 2;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "This is to certify that " . $prefix . " " . $payroll['name'] . " was not included in the payroll");
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "for the period of " . $this->dateRange);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->currentRow += 4;
        $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "          Prepared By:");
        $sheet->setCellValue("D{$this->currentRow}", "Noted By:");

        // Prepared By Signature
        // $this->currentRow += 2;
        // $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        // $signatureA = $this->getTemporarySignaturePath($this->filters['preparedBy']);
        // if ($signatureA) {
        //     $drawingA = new Drawing();
        //     $drawingA->setName('Prepared By');
        //     $drawingA->setDescription('Prepared By');
        //     $drawingA->setPath($signatureA);
        //     $drawingA->setHeight($imageOptions['height']);
        //     $drawingA->setWidth($imageOptions['width']);
        //     $drawingA->setCoordinates("A{$this->currentRow}");
        //     $drawingA->setOffsetX(100);
        //     $drawingA->setWorksheet($worksheet);
        // }
        // $sheet->getRowDimension($this->currentRow)->setRowHeight($imageOptions['height'] / 2);

        // Noted By Signature
        // $sheet->mergeCells("D{$this->currentRow}:F{$this->currentRow}");
        // $signatureB = $this->getTemporarySignaturePath($this->filters['notedBy']);
        // if ($signatureB) {
        //     $drawingB = new Drawing();
        //     $drawingB->setName('Noted By');
        //     $drawingB->setDescription('Noted By');
        //     $drawingB->setPath($signatureB);
        //     $drawingB->setHeight($imageOptions['height']);
        //     $drawingB->setWidth($imageOptions['width']);
        //     $drawingB->setCoordinates("D{$this->currentRow}");
        //     $drawingB->setOffsetX(100);
        //     $drawingB->setWorksheet($worksheet);
        // }
        // $sheet->getRowDimension($this->currentRow)->setRowHeight($imageOptions['height'] / 2);

        $this->currentRow += 3;
        $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "          " . $this->filters['preparedBy']->name ?? 'XXXXXXXXXX');
        $sheet->mergeCells("D{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("D{$this->currentRow}", $this->filters['notedBy']->name ?? 'XXXXXXXXXX');
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow++;
        $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "          " . $this->filters['preparedBy']->position ?? 'XXXXXXXXXX');
        $sheet->mergeCells("D{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("D{$this->currentRow}", $this->filters['notedBy']->position ?? 'XXXXXXXXXX');

        $this->currentRow = $this->currentRow + 5;
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

