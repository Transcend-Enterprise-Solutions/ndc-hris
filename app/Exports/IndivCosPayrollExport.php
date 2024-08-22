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

class IndivCosPayrollExport implements WithEvents, WithDrawings
{
    use Exportable;

    protected $filters;
    protected $currentRow = 1;
    protected $payrolls;

    public function drawings(){
        return [];
    }

    public function __construct($filters){
        $this->filters = $filters;
        $this->payrolls = $filters['payroll'];
    }

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(13);
                $sheet->getColumnDimension('C')->setWidth(10);
                $sheet->getColumnDimension('D')->setWidth(10);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);

                $this->formatAllMonths($sheet);
            },
        ];
    }

    private function formatAllMonths($sheet){
        foreach ($this->payrolls as $payroll) {
            $this->Header($sheet, $payroll);
            $this->DataRows($sheet, $payroll);
            $this->Footer($sheet, $payroll);
        }
    }

    private function Header($sheet, $payroll){
        $carbonStartDate = Carbon::parse($this->filters['startDate']);
        $carbonEndDate = Carbon::parse($this->filters['endDate']);
    
        $payrollYear = $carbonStartDate->format('Y');
        $payrollMonth = $carbonStartDate->format('F');
        $startDate = $carbonStartDate->format('d');
        $endDate = $carbonEndDate->format('d');
    
        $payrollDays = $startDate . "-" . $endDate;
    
        $prefix = "";
        if($payroll['sex'] == "Male"){
            $prefix = "Mr.";
        }else if($payroll['sex'] == "Female"){
            if($payroll->civil_status == "Single" || $payroll['civil_status'] == "Widowed" || $payroll['civil_status'] == "Separated"){
                $prefix = "Ms.";
            }else{
                $prefix = "Mrs.";
            }
        }
    
        $this->currentRow += 4;
        $headerRowStart = $this->currentRow;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Computation FIRST SALARY OF " . $prefix . " " . $payroll['name']);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getBorders()->getAllBorders()->applyFromArray([
            'bottom' => ['borderStyle' => Border::BORDER_NONE],
        ]);
    
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "as " . $payroll['position'] . " under MOA ");
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getBorders()->getAllBorders()->applyFromArray([
            'bottom' => ['borderStyle' => Border::BORDER_NONE],
        ]);
    
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "for the period of " . $payrollMonth . " " . $payrollDays . ", " . $payrollYear);
    
        $headerRowEnd = $this->currentRow;
        $sheet->getStyle("A:F")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getBorders()->getAllBorders()->applyFromArray([
            'allBorders' => ['borderStyle' => Border::BORDER_NONE],
        ]);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getFont()->setBold(true);
        $sheet->getStyle("A{$headerRowStart}:F{$headerRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
        for ($i = 5; $i <= 7; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }

    private function DataRows($sheet, $payroll){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return 0.00;
            }
            return number_format((float)$value, 2, '.', ',');
        };
        $carbonStartDate = Carbon::parse($this->filters['startDate']);
        $carbonEndDate = Carbon::parse($this->filters['endDate']);
        $payrollYear = $carbonStartDate->format('Y');
        $payrollMonth = $carbonStartDate->format('F');
        $startDate = $carbonStartDate->format('d');
        $endDate = $carbonEndDate->format('d');
        $payrollDays = $startDate . "-" . $endDate;
        $absents = $payroll['absences_days'];
        $lates = $payroll['late_undertime_hours'] . ":" . $payroll['late_undertime_mins'];
        $absentLatesAmount = $payroll['absences_amount'] + $payroll['late_undertime_hours_amount'] + $payroll['late_undertime_mins_amount'];

        $half = $payroll['rate_per_month'] / 2;
        $perDay = $payroll['daily_salary_rate'];
        $perHour = $perDay / 8;
        $perMin = $perHour / 60;
        $monthSalary = $payroll['rate_per_month'] + $payroll['additional_premiums'];

        $this->currentRow += 3;
        $bodyRowStart = $this->currentRow;
        $sheet->setCellValue("A{$this->currentRow}", "SG-" . $payroll['salary_grade'] . " (Inclusive 20% Premium)");
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Legend of Salary:");
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Salary");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll['rate_per_month']));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Additional 20% premiums:");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll['additional_premiums']));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Monthly Salary");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($monthSalary));
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("F{$this->currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "1/2 Month Salary (" . $formatCurrency($half) . "/2)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($half));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Per Day (" . $formatCurrency($perDay) . "/22)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perDay));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Per Hour (" . $formatCurrency($perHour) . "/8)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perHour));

        $this->currentRow ++;
        $sheet->setCellValue("A{$this->currentRow}", "Per Minute (" . $formatCurrency($perMin) . "/60)");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($perMin));

        $this->currentRow += 3;
        $sheet->setCellValue("A{$this->currentRow}", "Salary (" . $payrollMonth . " " . $payrollDays . ", " . $payrollYear . ")");
        $sheet->setCellValue("B{$this->currentRow}", $payroll['no_of_days_covered'] . " days x " . $formatCurrency($perDay));
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($payroll['gross_salary']));
        $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:B{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Absent (" . $absents . ") & Lates/Undertime (" . $lates . "):");
        $sheet->setCellValue("C{$this->currentRow}", $formatCurrency($absentLatesAmount));
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($payroll['gross_salary_less']));
        $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->setCellValue("E{$this->currentRow}", $formatCurrency($payroll['net_amount_due']));
        $sheet->getStyle("E{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("E{$this->currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        
        $this->currentRow += 2;
        $sheet->setCellValue("A{$this->currentRow}", "Total");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll['net_amount_due']));
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);
        
        $this->currentRow += 2;
        $sheet->setCellValue("A{$this->currentRow}", "NET");
        $sheet->setCellValue("F{$this->currentRow}", $formatCurrency($payroll['net_amount_due']));
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);
        $sheet->getStyle("F{$this->currentRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);


        $bodyRowEnd = $this->currentRow;
        $sheet->getStyle("A{$bodyRowStart}:A{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("B{$bodyRowStart}:B{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("F{$bodyRowStart}:F{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("E{$bodyRowStart}:E{$bodyRowEnd}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }

    private function Footer($sheet, $payroll){
        $imageOptions = [
            'height' => 50,
            'width' => 100
        ];
        $worksheet = $sheet->getDelegate();
        $carbonStartDate = Carbon::parse($this->filters['startDate']);
        $carbonEndDate = Carbon::parse($this->filters['endDate']);

        $payrollYear = $carbonStartDate->format('Y');
        $payrollMonth = $carbonStartDate->format('F');
        $startDate = $carbonStartDate->format('d');
        $endDate = $carbonEndDate->format('d');

        $payrollDays = $startDate . "-" . $endDate;

        $prefix = "";
        if($payroll['sex'] == "Male"){
            $prefix = "Mr.";
        }else if($payroll['sex'] == "Female"){
            if($payroll['civil_status'] == "Single" || $payroll['civil_status'] == "Widowed" || $payroll['civil_status'] == "Separated"){
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
        $sheet->setCellValue("A{$this->currentRow}", "for the period of " . $payrollMonth . " " . $payrollDays . ", " . $payrollYear);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $this->currentRow += 4;
        $sheet->setCellValue("A{$this->currentRow}", "Prepared By:");
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
        //     $drawingA->setCoordinates("C{$this->currentRow}");
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
        //     $drawingB->setCoordinates("C{$this->currentRow}");
        //     $drawingB->setOffsetX(100);
        //     $drawingB->setWorksheet($worksheet);
        // }
        // $sheet->getRowDimension($this->currentRow)->setRowHeight($imageOptions['height'] / 2);

        $this->currentRow += 3;
        $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $this->filters['preparedBy']->name);
        $sheet->mergeCells("D{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("D{$this->currentRow}", $this->filters['notedBy']->name);
        $sheet->getStyle("A{$this->currentRow}:F{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow++;
        $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", $this->filters['preparedBy']->position);
        $sheet->mergeCells("D{$this->currentRow}:F{$this->currentRow}");
        $sheet->setCellValue("D{$this->currentRow}", $this->filters['notedBy']->position);

        $this->currentRow = $this->currentRow + 5;
    }

    private function getTemporarySignaturePath($signatory){
        if ($signatory && isset($signatory->signature)) {
            $path = str_replace('public/', '', $signatory->signature);
            $originalPath = Storage::disk('public')->get($path);
            $filename = str_replace('public/signatures/', '', $signatory->signature);
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

