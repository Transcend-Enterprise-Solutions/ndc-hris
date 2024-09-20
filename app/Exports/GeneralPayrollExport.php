<?php

namespace App\Exports;

use App\Models\Payrolls;
use App\Models\PayrollsLeaveCreditsDeduction;
use App\Models\User;
use Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GeneralPayrollExport
{
    use Exportable;

    protected $filters;
    protected $rowNumber = 0;
    protected $totalPayroll;
    protected $months;
    protected $currentRow = 1;
    protected $workingSheetCount = 1;
    protected $chunksCount;

    public function __construct($filters){
        $this->filters = $filters;
        $this->months = $this->getMonthsRange();
    }

    private function getMonthsRange(){
        $start = Carbon::parse($this->filters['startMonth']);
        $end = Carbon::parse($this->filters['endMonth']);
        $months = [];

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        return $months;
    }

    public function export(){
        try {
            $spreadsheet = IOFactory::load(storage_path('app/templates/plantilla_payroll_template.xlsx'));
            $sheet = $spreadsheet->getSheetByName(worksheetName: 'General Payroll NYC');
           
            $this->formatAllMonths($sheet);

            $sheet = $spreadsheet->getSheetByName(worksheetName: 'Working Sheet');

            $this->workingSheet($sheet);

            $writer = new Xlsx($spreadsheet);
            $filename = "General Payroll.xlsx";
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
            $writer->save($tempFile);
            $fileContent = file_get_contents($tempFile);
            unlink($tempFile);
            return [
                'content' => $fileContent,
                'filename' => $filename
            ];

        } catch (Exception $e) {
            throw $e;
        }
    }

    // public function registerEvents(): array{
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $sheet = $event->sheet;
    //             // Set column widths
    //             $sheet->getColumnDimension('A')->setWidth(4);
    //             $sheet->getColumnDimension('B')->setWidth(12);
    //             $sheet->getColumnDimension('C')->setWidth(30);
    //             $sheet->getColumnDimension('D')->setWidth(20);
    //             $sheet->getColumnDimension('E')->setWidth(8);
    //             $sheet->getColumnDimension('F')->setWidth(15);
    //             $sheet->getColumnDimension('G')->setWidth(13);
    //             $sheet->getColumnDimension('H')->setWidth(15);
    //             for ($col = 'I'; $col <= 'Z'; $col++) {
    //                 $sheet->getColumnDimension($col)->setWidth(13);
    //             }
    //             $sheet->getColumnDimension('AA')->setWidth(13);
    //             $sheet->getColumnDimension('AB')->setWidth(13);
    //             for ($col = 'AC'; $col <= 'AF'; $col++) {
    //                 $sheet->getColumnDimension($col)->setWidth(15);
    //             }
    //             $sheet->getStyle("A:AF")->applyFromArray([
    //                 'font' => [
    //                     'bold' => true,
    //                     'name' => 'Cambria',
    //                 ],
    //             ]);

    //             $this->formatAllMonths($sheet);
    //         },
    //     ];
    // }


    private function formatAllMonths($sheet, $isWorkingSheet = null){
        if(!$isWorkingSheet){
            $sheet->getColumnDimension('A')->setWidth(4);
            $sheet->getColumnDimension('B')->setWidth(12);
            $sheet->getColumnDimension('C')->setWidth(30);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(8);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(13);
            $sheet->getColumnDimension('H')->setWidth(15);
            for ($col = 'I'; $col <= 'Z'; $col++) {
                $sheet->getColumnDimension($col)->setWidth(13);
            }
            $sheet->getColumnDimension('AA')->setWidth(13);
            $sheet->getColumnDimension('AB')->setWidth(13);
            for ($col = 'AC'; $col <= 'AF'; $col++) {
                $sheet->getColumnDimension($col)->setWidth(15);
            }
            $sheet->getStyle("A:AF")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'name' => 'Cambria',
                ],
            ]);
        }

        foreach ($this->months as $month) {
            $data = $this->getPayrollData($month);
            $chunks = array_chunk($data->toArray(), 20);
            $totalChunks = count($chunks);
            $this->chunksCount = $totalChunks;
            
            $grandTotal = [
                'rate_per_month' => 0, 
                'personal_economic_relief_allowance' => 0, 
                'gross_amount' => 0,
                'additional_gsis_premium' => 0, 
                'lbp_salary_loan' => 0, 
                'nycea_deductions' => 0,
                'sc_membership' => 0, 
                'nycempc_total' => 0, 
                'nycempc_mpl' => 0, 
                'nycempc_educ_loan' => 0, 
                'nycempc_pi' => 0, 
                'nycempc_business_loan' => 0, 
                'salary_loan' => 0, 
                'policy_loan' => 0, 
                'eal' => 0,
                'emergency_loan' => 0, 
                'mpl' => 0, 
                'housing_loan' => 0, 
                'ouli_prem' => 0, 
                'gfal' => 0, 
                'cpl' => 0,
                'pagibig_mpl' => 0, 
                'lwop' => 0,
                'gsis_rlip' => 0, 
                'gsis_gs' => 0, 
                'gsis_ecip' => 0, 
                'pagibig_contribution' => 0,
                'pagibig_gs' => 0,
                'pagibig_calamity_loan' => 0,
                'w_holding_tax' => 0, 
                'philhealth' => 0, 
                'philhealth_es' => 0, 
                'total_deduction' => 0, 
                'net_amount_received' => 0,
                'amount_due_first_half' => 0, 
                'amount_due_second_half' => 0,
            ];

            if(!$isWorkingSheet){
                foreach ($chunks as $index => $chunk) {
                    $this->Header($month, $sheet);
                    $this->TableHeader($month, $sheet);
                    $subtotal = $this->DataRows($chunk, $sheet, $index === $totalChunks - 1);
                    
                    // Add subtotal to grand total
                    foreach ($grandTotal as $key => $value) {
                        $value += $subtotal[$key];
                        $grandTotal[$key] = $value;
                    }
    
                    if ($index === $totalChunks - 1) {
                        // Add grand total row
                        $this->addGrandTotalRow($sheet, $grandTotal);
                        $this->Footer($sheet, $grandTotal);
                    } else {
                        $this->addPageBreak($sheet);
                    }
                }
            }else{
                $this->currentRow = 6;
                foreach ($chunks as $index => $chunk) {
                    $subtotal = $this->workingSheetDataRows($chunk, $sheet, $index === $totalChunks - 1);
                    foreach ($grandTotal as $key => $value) {
                        $value += $subtotal[$key];
                        $grandTotal[$key] = $value;
                    }
                    if ($index === $totalChunks - 1) {
                        $this->addWorkingSheetGrandTotalRow($sheet, $grandTotal);
                    }
                }
            }
        }
    }

    private function addGrandTotalRow($sheet, $grandTotal){
        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", "");
        $sheet->setCellValue("B{$this->currentRow}", "");
        $sheet->setCellValue("C{$this->currentRow}", "TOTAL");
        $sheet->setCellValue("F{$this->currentRow}", $this->formatCurrency($grandTotal['rate_per_month']));
        $sheet->setCellValue("G{$this->currentRow}", $this->formatCurrency($grandTotal['personal_economic_relief_allowance']));
        $sheet->setCellValue("H{$this->currentRow}", $this->formatCurrency($grandTotal['gross_amount']));
        $sheet->setCellValue("I{$this->currentRow}", $this->formatCurrency($grandTotal['additional_gsis_premium']));
        $sheet->setCellValue("J{$this->currentRow}", $this->formatCurrency($grandTotal['lbp_salary_loan']));
        $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($grandTotal['nycea_deductions']));
        $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($grandTotal['sc_membership']));
        $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_total']));
        $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($grandTotal['salary_loan']));
        $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($grandTotal['policy_loan']));
        $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($grandTotal['eal']));
        $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($grandTotal['emergency_loan']));
        $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($grandTotal['mpl']));
        $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($grandTotal['housing_loan']));
        $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($grandTotal['ouli_prem']));
        $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($grandTotal['gfal']));
        $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($grandTotal['cpl']));
        $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_mpl']));
        $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($grandTotal['lwop']));
        $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($grandTotal['gsis_rlip']));
        $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_contribution']));
        $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($grandTotal['w_holding_tax']));
        $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($grandTotal['philhealth']));
        $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($grandTotal['total_deduction']));
        $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($grandTotal['net_amount_received']));
        $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($grandTotal['amount_due_first_half']));
        $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($grandTotal['amount_due_second_half']));

        $sheet->getRowDimension($this->currentRow )->setRowHeight(30);
        $sheet->getStyle("A{$this->currentRow}:C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }

    private function addPageBreak($sheet){
        $sheet->setBreak("A{$this->currentRow}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
        $this->currentRow += 2; // Add some space after the page break
    }

    private function formatCurrency($value) {
        if($value == 0 || $value == null){
            return "-";
        }
        return number_format((double)$value, 2, '.', ',');
    }

    private function Header($month, $sheet){
        $carbonDate = Carbon::parse($month);
        $payrollMonth = $carbonDate->format('F');
        $startDateFirstHalf = $carbonDate->copy()->startOfMonth()->toDateString();
        $endDateSecondHalf = $carbonDate->copy()->endOfMonth()->toDateString();

        $payrollDays = Carbon::parse($startDateFirstHalf)->format('d') . '-' .Carbon::parse($endDateSecondHalf)->format('d');

        $headerRowStart = $this->currentRow;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "");

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:J{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Payroll No.: _____________________");
        $sheet->mergeCells("AC{$this->currentRow}:AD{$this->currentRow}");
        $sheet->setCellValue("AC{$this->currentRow}", "PAYROLL WORKSHEET");
        $sheet->setCellValue("AE{$this->currentRow}", "MONTH");
        $sheet->setCellValue("AF{$this->currentRow}", "DAYS");
        $workSheetRow = $this->currentRow;

        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:J{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Sheet _________of __________Sheets");
        $sheet->mergeCells("AC{$this->currentRow}:AD{$this->currentRow}");
        $sheet->setCellValue("AC{$this->currentRow}", $payrollMonth);
        $sheet->setCellValue("AE{$this->currentRow}", $payrollMonth);
        $sheet->setCellValue("AF{$this->currentRow}", $payrollDays);
        $workSheetDataRow = $this->currentRow;
        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "GENERAL PAYROLL");
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "NATIONAL YOUTH COMMISSION");
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);
        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "FOR THE MONTH OF " . strtoupper($payrollMonth));
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "");
        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:B{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Entity Name : ");
        $sheet->mergeCells("C{$this->currentRow}:j{$this->currentRow}");
        $sheet->setCellValue("C{$this->currentRow}", " NATIONAL YOUTH COMMISSION ");
        $sheet->getStyle("C{$this->currentRow}:J{$this->currentRow}")->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);
        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:B{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "Fund Cluster : ");
        $sheet->mergeCells("C{$this->currentRow}:E{$this->currentRow}");
        $sheet->setCellValue("C{$this->currentRow}", " 01101101 ");
        $sheet->getStyle("C{$this->currentRow}:E{$this->currentRow}")->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);
        
        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "WE ACKNOWLEDGED RECEIPT OF THE SUM SHOWN OPPOSITE OUR NAMES AS FULL COMPENSATION FOR OUR SERVICE RENDERED FOR THE PERIOD STATED:");

        $this->currentRow ++;
        $sheet->mergeCells("A{$this->currentRow}:AF{$this->currentRow}");
        $sheet->setCellValue("A{$this->currentRow}", "");
        
        $headerRowEnd = $this->currentRow;
        
        $sheet->getStyle("A:AF")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$headerRowStart}:AF{$headerRowEnd}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
         
        $this->currentRow ++;
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getFont()->setBold(false);

        // Adjust row heights
        for ($i = 1; $i <= 8; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }

        $sheet->getStyle("A{$headerRowStart}:AF{$headerRowEnd}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_NONE,
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        $sheet->getStyle("AC{$workSheetRow}:AF{$workSheetRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("AC{$workSheetDataRow}:AF{$workSheetDataRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->setShowGridlines(false);

        // Push down the headings
        $sheet->insertNewRowBefore($this->currentRow, 1);
    }

    private function TableHeader($month, $sheet){
        $carbonDate = Carbon::parse($month);
        $startDateFirstHalf = $carbonDate->copy()->startOfMonth()->toDateString();
        $endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
        $startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
        $endDateSecondHalf = $carbonDate->copy()->endOfMonth()->toDateString();

        $firstHalf = $startDateFirstHalf && $endDateFirstHalf
        ? Carbon::parse($startDateFirstHalf)->format('F d') . '-' . Carbon::parse($endDateFirstHalf)->format('d, Y')
        : 'Date range not set';

        $secondHalf = $startDateSecondHalf && $endDateSecondHalf
            ? Carbon::parse($startDateSecondHalf)->format('F d') . '-' . Carbon::parse($endDateSecondHalf)->format('d, Y')
            : 'Date range not set';

        $firstRowOfTable = $this->currentRow;
     
        $sheet->getStyle('A:AF')->getAlignment()->setWrapText(true);
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("A{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("B{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("D{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("E{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("H{$this->currentRow}")->getAlignment()->setTextRotation(90);
        $sheet->getStyle("W{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("X{$this->currentRow}")->getAlignment()->setTextRotation(90);
        $sheet->getStyle("AC{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("AD{$this->currentRow}")->getAlignment()->setTextRotation(90); 
   
        // Table header height
        $this->currentRow ++;
        $headerRow = $this->currentRow;
  
        // Set vertical text rotation for a specific cell or range
        $sheet->getStyle("M{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("N{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("O{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("P{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("Q{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("R{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("S{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("T{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("U{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("V{$this->currentRow}")->getAlignment()->setTextRotation(90);  
        $sheet->getStyle("Y{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("Z{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("AA{$this->currentRow}")->getAlignment()->setTextRotation(90); 
        $sheet->getStyle("AB{$this->currentRow}")->getAlignment()->setTextRotation(90); 

        // Merge cells top row and bottom row of the table header
        $sheet->mergeCells("A{$firstRowOfTable}:A{$this->currentRow}");
        $sheet->setCellValue("A{$firstRowOfTable}", "SERIAL NO.");
        $sheet->mergeCells("B{$firstRowOfTable}:B{$this->currentRow}");
        $sheet->setCellValue("B{$firstRowOfTable}", "EMPLOYEE NO.");
        $sheet->mergeCells("C{$firstRowOfTable}:C{$this->currentRow}");
        $sheet->setCellValue("C{$firstRowOfTable}", "NAME");
        $sheet->mergeCells("D{$firstRowOfTable}:D{$this->currentRow}");
        $sheet->setCellValue("D{$firstRowOfTable}", "POSITION");
        $sheet->mergeCells("E{$firstRowOfTable}:E{$this->currentRow}");
        $sheet->setCellValue("E{$firstRowOfTable}", "SG/STEP");
        $sheet->mergeCells("F{$firstRowOfTable}:F{$this->currentRow}");
        $sheet->setCellValue("F{$firstRowOfTable}", "Rate Per Month (per NBC 594) dtd. August 12, 2024");
        $sheet->mergeCells("G{$firstRowOfTable}:G{$this->currentRow}");
        $sheet->setCellValue("G{$firstRowOfTable}", "Personal Economic Relief Allowance");
        $sheet->mergeCells("H{$firstRowOfTable}:H{$this->currentRow}");
        $sheet->setCellValue("H{$firstRowOfTable}", "GROSS AMT.");
        $sheet->mergeCells("I{$firstRowOfTable}:I{$this->currentRow}");
        $sheet->setCellValue("I{$firstRowOfTable}", "ADDITIONAL GSIS PREMIUM");
        $sheet->mergeCells("J{$firstRowOfTable}:J{$this->currentRow}");
        $sheet->setCellValue("J{$firstRowOfTable}", "LBP SALARY LOAN");
        $sheet->mergeCells("K{$firstRowOfTable}:K{$this->currentRow}");
        $sheet->setCellValue("K{$firstRowOfTable}", "NYCEA DEDUCTIONS");
        $sheet->mergeCells("L{$firstRowOfTable}:M{$firstRowOfTable}");
        $sheet->setCellValue("L{$firstRowOfTable}", "NYC COOP");
        $sheet->mergeCells("N{$firstRowOfTable}:V{$firstRowOfTable}");
        $sheet->setCellValue("N{$firstRowOfTable}", "GSIS");
        $sheet->mergeCells("W{$firstRowOfTable}:W{$this->currentRow}");
        $sheet->setCellValue("W{$firstRowOfTable}", "PAG-IBIG MPL");
        $sheet->mergeCells("X{$firstRowOfTable}:X{$this->currentRow}");
        $sheet->setCellValue("X{$firstRowOfTable}", "LWOP");
        $sheet->mergeCells("Y{$firstRowOfTable}:AB{$firstRowOfTable}");
        $sheet->setCellValue("Y{$firstRowOfTable}", "MANDATORY DEDUCTION");
        $sheet->mergeCells("AC{$firstRowOfTable}:AC{$this->currentRow}");
        $sheet->setCellValue("AC{$firstRowOfTable}", "TOTAL DEDUCTION");
        $sheet->mergeCells("AD{$firstRowOfTable}:AD{$this->currentRow}");
        $sheet->setCellValue("AD{$firstRowOfTable}", "NET AMOUNT RECEIVED");
        $sheet->mergeCells("AE{$firstRowOfTable}:AE{$this->currentRow}");
        $sheet->setCellValue("AE{$firstRowOfTable}", "AMOUNT DUE " . $firstHalf );
        $sheet->mergeCells("AF{$firstRowOfTable}:AF{$this->currentRow}");
        $sheet->setCellValue("AF{$firstRowOfTable}", "AMOUNT DUE " . $secondHalf );
        $sheet->setCellValue("L{$this->currentRow}", "S.C/MEMBERSHIP");
        $sheet->setCellValue("M{$this->currentRow}", "TOTAL LOANS");
        $sheet->setCellValue("N{$this->currentRow}", "SALARY LOAN");
        $sheet->setCellValue("O{$this->currentRow}", "POLICY LOAN");
        $sheet->setCellValue("P{$this->currentRow}", "EAL");
        $sheet->setCellValue("Q{$this->currentRow}", "EMERGENCY LOAN");
        $sheet->setCellValue("R{$this->currentRow}", "MPL");
        $sheet->setCellValue("S{$this->currentRow}", "HOUSING LOAN");
        $sheet->setCellValue("T{$this->currentRow}", "OULI PREM");
        $sheet->setCellValue("U{$this->currentRow}", "GFAL");
        $sheet->setCellValue("V{$this->currentRow}", "CPL");
        $sheet->setCellValue("Y{$this->currentRow}", "LIFE & RETIREMENT INSURANCE PREMIUMS");
        $sheet->setCellValue("Z{$this->currentRow}", "PAG-IBIG CONTRIBUTION");
        $sheet->setCellValue("AA{$this->currentRow}", "W/HOLDING TAX");
        $sheet->setCellValue("AB{$this->currentRow}", "PHILHEALTH");

        $this->currentRow = $sheet->getHighestRow();

        // Apply borders to the data table
        $sheet->getStyle("A{$firstRowOfTable}:AF{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(80); 
        $sheet->getStyle("A{$firstRowOfTable}:B" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$firstRowOfTable}:C" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$firstRowOfTable}:AF" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$firstRowOfTable}:AF{$this->currentRow}")->getFont()->setBold(true);
    }

    private function getPayrollData($month){
        $carbonDate = Carbon::parse($month);
        $startDateFirstHalf = $carbonDate->copy()->startOfMonth()->toDateString();
        $endDateSecondHalf = $carbonDate->copy()->endOfMonth()->toDateString();
    
        $query = User::join('payrolls', 'payrolls.user_id', 'users.id')
            ->join('positions', 'positions.id', 'users.position_id')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*');

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('users.name', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('users.emp_code', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('payrolls.sg_step', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('positions.position', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('office_divisions.office_division', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }

        $data = $query->get()->map(function ($payroll) use ($month){

            // Check if the user has a salary deduction
            $deduction = PayrollsLeaveCreditsDeduction::where('user_id', $payroll->user_id)
                        ->whereMonth('month', Carbon::parse($month)->month)
                        ->whereYear('month', Carbon::parse($month)->year)
                        ->first();
            $salaryDeduction = $deduction ? $deduction->salary_deduction_amount : 0;
            $net_amount_received = $payroll->gross_amount - $payroll->total_deduction - $salaryDeduction;

            // For Tenths ------------------------------------------------------------- //
            $amount_due_second_half = floor($net_amount_received / 2 / 10) * 10;
            $amount_due_first_half = $net_amount_received - $amount_due_second_half;

            // For Ones ------------------------------------------------------------- //
            // $half_amount = $net_amount_received / 2;
            // $amount_due_second_half = floor($half_amount);
            // $amount_due_first_half = $net_amount_received - $amount_due_second_half;

            $payroll->net_amount_received = $net_amount_received;
            $payroll->amount_due_first_half = $amount_due_first_half;
            $payroll->amount_due_second_half = $amount_due_second_half;
        
            return $payroll;
        });

        $totals = $data->reduce(function ($carry, $item) {
            $numericColumns = [
                'rate_per_month', 'personal_economic_relief_allowance', 'gross_amount',
                'additional_gsis_premium', 'lbp_salary_loan', 'nycea_deductions',
                'sc_membership', 'nycempc_total', 'salary_loan', 'policy_loan', 'eal',
                'emergency_loan', 'mpl', 'housing_loan', 'ouli_prem', 'gfal', 'cpl',
                'pagibig_mpl', 'lwop',
                'gsis_rlip', 'pagibig_contribution',
                'w_holding_tax', 'philhealth', 'total_deduction', 'net_amount_received',
                'amount_due_first_half', 'amount_due_second_half'
            ];
    
            foreach ($numericColumns as $column) {
                $carry[$column] = ($carry[$column] ?? 0) + $item->$column;
            }
    
            return $carry;
        }, []);
    
        $formattedData = $data->map(function ($payroll, $index) {
            $this->rowNumber++;
            return [
                0 => $this->rowNumber,
                1 => $payroll->emp_code,
                2 => $payroll->name,
                3 => $payroll->position,
                4 => $payroll->sg_step,
                5 => $payroll->rate_per_month,
                6 => $payroll->personal_economic_relief_allowance,
                7 => $payroll->gross_amount,
                8 => $payroll->additional_gsis_premium,
                9 => $payroll->lbp_salary_loan,
                10 => $payroll->nycea_deductions,
                11 => $payroll->sc_membership,
                12 => $payroll->nycempc_total,
                13 => $payroll->salary_loan,
                14 => $payroll->policy_loan,
                15 => $payroll->eal,
                16 => $payroll->emergency_loan,
                17 => $payroll->mpl,
                18 => $payroll->housing_loan,
                19 => $payroll->ouli_prem,
                20 => $payroll->gfal,
                21 => $payroll->cpl,
                22 => $payroll->pagibig_mpl,
                23 => $payroll->lwop,
                24 => $payroll->gsis_rlip,
                25 => $payroll->pagibig_contribution,
                26 => $payroll->w_holding_tax,
                27 => $payroll->philhealth,
                28 => $payroll->total_deduction,
                29 => $payroll->net_amount_received,
                30 => $payroll->amount_due_first_half,
                31 => $payroll->amount_due_second_half,
                32 => $payroll->pagibig_gs,
                33 => $payroll->pagibig_calamity_loan,
                34 => $payroll->philhealth_es,
                35 => $payroll->gsis_gs,
                36 => $payroll->gsis_ecip,
                37 => $payroll->nycempc_mpl,
                38 => $payroll->nycempc_educ_loan,
                39 => $payroll->nycempc_pi,
                40 => $payroll->nycempc_business_loan,
            ];
        });
    
        $this->totalPayroll = $totals['net_amount_received'];
        $this->rowNumber = 0;
        return $formattedData;
    }

    private function DataRows($data, $sheet, $isLastChunk){
        $subtotal = [
            'rate_per_month' => 0, 
            'personal_economic_relief_allowance' => 0, 
            'gross_amount' => 0,
            'additional_gsis_premium' => 0, 
            'lbp_salary_loan' => 0, 
            'nycea_deductions' => 0,
            'sc_membership' => 0, 
            'nycempc_total' => 0, 
            'nycempc_mpl' => 0, 
            'nycempc_educ_loan' => 0, 
            'nycempc_pi' => 0, 
            'nycempc_business_loan' => 0, 
            'salary_loan' => 0, 
            'policy_loan' => 0, 
            'eal' => 0,
            'emergency_loan' => 0, 
            'mpl' => 0, 
            'housing_loan' => 0, 
            'ouli_prem' => 0, 
            'gfal' => 0, 
            'cpl' => 0,
            'pagibig_mpl' => 0, 
            'lwop' => 0,
            'gsis_rlip' => 0, 
            'gsis_gs' => 0, 
            'gsis_ecip' => 0, 
            'pagibig_contribution' => 0,
            'pagibig_gs' => 0,
            'pagibig_calamity_loan' => 0,
            'w_holding_tax' => 0, 
            'philhealth' => 0, 
            'philhealth_es' => 0, 
            'total_deduction' => 0, 
            'net_amount_received' => 0,
            'amount_due_first_half' => 0, 
            'amount_due_second_half' => 0,
        ];

        $totalRows = count($data);
        foreach ($data as $index => $row) {
            $this->currentRow++;
            $sheet->setCellValue("A{$this->currentRow}", $row[0]);
            $sheet->setCellValue("B{$this->currentRow}", $row[1]);
            $sheet->setCellValue("C{$this->currentRow}", $row[2]);
            $sheet->setCellValue("D{$this->currentRow}", $row[3]);
            $sheet->setCellValue("E{$this->currentRow}", $row[4]);
            $sheet->setCellValue("F{$this->currentRow}", $this->formatCurrency($row[5]));
            $sheet->setCellValue("G{$this->currentRow}", $this->formatCurrency($row[6]));
            $sheet->setCellValue("H{$this->currentRow}", $this->formatCurrency($row[7]));
            $sheet->setCellValue("I{$this->currentRow}", $this->formatCurrency($row[8]));
            $sheet->setCellValue("J{$this->currentRow}", $this->formatCurrency($row[9]));
            $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($row[10]));
            $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($row[11]));
            $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($row[12]));
            $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($row[13]));
            $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($row[14]));
            $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($row[15]));
            $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($row[16]));
            $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($row[17]));
            $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($row[18]));
            $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($row[19]));
            $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($row[20]));
            $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($row[21]));
            $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($row[22]));
            $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($row[23]));
            $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($row[24]));
            $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($row[25]));
            $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($row[26]));
            $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($row[27]));
            $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($row[28]));
            $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($row[29]));
            $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($row[30]));
            $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($row[31]));

            $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);
            $sheet->getStyle("A{$this->currentRow}:B{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$this->currentRow}:C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("D{$this->currentRow}:E{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $subtotal['rate_per_month'] += (float)$row[5];
            $subtotal['personal_economic_relief_allowance'] += (float)$row[6];
            $subtotal['gross_amount'] += (float)$row[7];
            $subtotal['additional_gsis_premium'] += (float)$row[8]; 
            $subtotal['lbp_salary_loan'] += (float)$row[9]; 
            $subtotal['nycea_deductions'] += (float)$row[10];
            $subtotal['sc_membership'] += (float)$row[11]; 
            $subtotal['nycempc_total'] += (float)$row[12]; 
            $subtotal['salary_loan'] += (float)$row[13]; 
            $subtotal['policy_loan'] += (float)$row[14]; 
            $subtotal['eal'] += (float)$row[15];
            $subtotal['emergency_loan'] += (float)$row[16]; 
            $subtotal['mpl'] += (float)$row[17]; 
            $subtotal['housing_loan'] += (float)$row[18]; 
            $subtotal['ouli_prem'] += (float)$row[19]; 
            $subtotal['gfal'] += (float)$row[20]; 
            $subtotal['cpl'] += (float)$row[21];
            $subtotal['pagibig_mpl'] += (float)$row[22]; 
            $subtotal['lwop'] += (float)$row[23];
            $subtotal['gsis_rlip'] += (float)$row[24]; 
            $subtotal['pagibig_contribution'] += (float)$row[25];
            $subtotal['w_holding_tax'] += (float)$row[26]; 
            $subtotal['philhealth'] += (float)$row[27]; 
            $subtotal['total_deduction'] += (float)$row[28]; 
            $subtotal['net_amount_received'] += (float)$row[29];
            $subtotal['amount_due_first_half'] += (float)$row[30]; 
            $subtotal['amount_due_second_half'] += (float)$row[31];
        }

        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", "");
        $sheet->setCellValue("B{$this->currentRow}", "");
        $sheet->setCellValue("C{$this->currentRow}", "SUB-TOTAL");
        $sheet->setCellValue("F{$this->currentRow}", $this->formatCurrency($subtotal['rate_per_month']));
        $sheet->setCellValue("G{$this->currentRow}", $this->formatCurrency($subtotal['personal_economic_relief_allowance']));
        $sheet->setCellValue("H{$this->currentRow}", $this->formatCurrency($subtotal['gross_amount']));
        $sheet->setCellValue("I{$this->currentRow}", $this->formatCurrency($subtotal['additional_gsis_premium']));
        $sheet->setCellValue("J{$this->currentRow}", $this->formatCurrency($subtotal['lbp_salary_loan']));
        $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($subtotal['nycea_deductions']));
        $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($subtotal['sc_membership']));
        $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_total']));
        $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($subtotal['salary_loan']));
        $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($subtotal['policy_loan']));
        $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($subtotal['eal']));
        $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($subtotal['emergency_loan']));
        $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($subtotal['mpl']));
        $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($subtotal['housing_loan']));
        $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($subtotal['ouli_prem']));
        $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($subtotal['gfal']));
        $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($subtotal['cpl']));
        $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_mpl']));
        $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($subtotal['lwop']));
        $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($subtotal['gsis_rlip']));
        $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_contribution']));
        $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($subtotal['w_holding_tax']));
        $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($subtotal['philhealth']));
        $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($subtotal['total_deduction']));
        $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($subtotal['net_amount_received']));
        $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($subtotal['amount_due_first_half']));
        $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($subtotal['amount_due_second_half']));

        $sheet->getRowDimension($this->currentRow )->setRowHeight(30);
        $sheet->getStyle("A{$this->currentRow}:C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
        $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return $subtotal;
    }

    private function Footer($sheet, $grandTotal){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return 'PHP ' . number_format((float)$value, 2, '.', ',');
        };
        $formattedAmount = number_format($grandTotal['net_amount_received'], 2, '.', '');
        $startRow = $this->currentRow + 1;
        $imageOptions = [
            'height' => 50,
            'width' => 100
        ];

        // $worksheet = $sheet->getDelegate();

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
        
        $date = now()->format("m/d/y");

        $iBorderLeftStart = $startRow;

        $sheet->setCellValue("A{$startRow}", "A.");
        $sheet->mergeCells("B{$startRow}:C{$startRow}");
        $sheet->setCellValue("B{$startRow}", "CERTIFIED: Services duly rendered as stated.");
        $sheet->setCellValue("I{$startRow}", "C.");
        $sheet->mergeCells("J{$startRow}:AF{$startRow}");
        $amountInWords = $this->numberToWords($formattedAmount);
        $sheet->setCellValue("J{$startRow}", "APPROVED FOR PAYMENT: " .strtoupper($amountInWords) . " PESOS ONLY");
        $sheet->getStyle("A{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("I{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$startRow}:AF{$startRow}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("J{$startRow}:L{$startRow}");
        $sheet->setCellValue("J{$startRow}", $formatCurrency($this->totalPayroll));
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);

        // Signature A
        // $startRow++;
        // $sheet->mergeCells("A{$startRow}:E{$startRow}");
        // $signatureA = $this->getTemporarySignaturePath($signatoryA);
        // if ($signatureA) {
        //     $drawingA = new Drawing();
        //     $drawingA->setName('Signature A');
        //     $drawingA->setDescription('Signature A');
        //     $drawingA->setPath($signatureA);
        //     $drawingA->setHeight($imageOptions['height']);
        //     $drawingA->setWidth($imageOptions['width']);
        //     $drawingA->setCoordinates("C{$startRow}");
        //     $drawingA->setOffsetX(100);
        //     $drawingA->setWorksheet($worksheet);
        // }

        // Signature C
        // $signatureC = $this->getTemporarySignaturePath($signatoryC);
        // if ($signatureC) {
        //     $drawingC = new Drawing();
        //     $drawingC->setName('Signature C');
        //     $drawingC->setDescription('Signature C');
        //     $drawingC->setPath($signatureC);
        //     $drawingC->setHeight($imageOptions['height']);
        //     $drawingC->setWidth($imageOptions['width']);
        //     $drawingC->setCoordinates("M{$startRow}");
        //     $drawingC->setOffsetX(50);
        //     $drawingC->setWorksheet($worksheet);
        // }
        // $sheet->getRowDimension($startRow)->setRowHeight($imageOptions['height'] - 2);

        $startRow ++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $aName);
        $sheet->mergeCells("F{$startRow}:G{$startRow}");
        $sheet->setCellValue("F{$startRow}", $date);

        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $cName);
        $sheet->getStyle("A{$startRow}:AF{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
        $sheet->getStyle("A{$startRow}:AF{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}:AF{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $startRow++;
        $sheet->setCellValue("A{$startRow}", "B.");
        $sheet->mergeCells("B{$startRow}:H{$startRow}");
        $sheet->setCellValue("B{$startRow}", "CERTIFIED: Supporting documents complete and proper; and cash available in the amount of");
        $sheet->setCellValue("I{$startRow}", "D.");
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", "");
        $sheet->setCellValue("AD{$startRow}", "E.");
        $sheet->getStyle("A{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("I{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("AD{$startRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $eBorderStart = $startRow;

        $startRow++;
        $sheet->mergeCells("B{$startRow}:C{$startRow}");
        $sheet->setCellValue("B{$startRow}", $formatCurrency($this->totalPayroll));
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", "");
        $sheet->getStyle("B{$startRow}")->getFont()->setBold(true);

        $startRow++;
        // Signature B
        // $sheet->mergeCells("A{$startRow}:E{$startRow}");
        // $signatureB = $this->getTemporarySignaturePath($signatoryB);
        // if ($signatureB) {
        //     $drawingB = new Drawing();
        //     $drawingB->setName('Signature A');
        //     $drawingB->setDescription('Signature A');
        //     $drawingB->setPath($signatureB);
        //     $drawingB->setHeight($imageOptions['height']);
        //     $drawingB->setWidth($imageOptions['width']);
        //     $drawingB->setCoordinates("C{$startRow}");
        //     $drawingB->setOffsetX(100);
        //     $drawingB->setWorksheet($worksheet);
        // }

        // Signature D
        // $signatureD = $this->getTemporarySignaturePath($signatoryD);
        // if ($signatureD) {
        //     $drawingD = new Drawing();
        //     $drawingD->setName('Signature D');
        //     $drawingD->setDescription('Signature D');
        //     $drawingD->setPath($signatureD);
        //     $drawingD->setHeight($imageOptions['height']);
        //     $drawingD->setWidth($imageOptions['width']);
        //     $drawingD->setCoordinates("M{$startRow}");
        //     $drawingD->setOffsetX(50);
        //     $drawingD->setWorksheet($worksheet);
        // }
        // $sheet->getRowDimension($startRow)->setRowHeight($imageOptions['height'] / 2);

        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", "");
        $sheet->mergeCells("AD{$startRow}:AF{$startRow}");
        $sheet->setCellValue("AD{$startRow}", "ORS/BURS No.:______________________");
        $sheet->getStyle("AD{$startRow}")->getFont()->setBold(true);


        $startRow++;
        $sheet->mergeCells("AD{$startRow}:AF{$startRow}");
        $sheet->setCellValue("AD{$startRow}", "Date:______________________");
        $sheet->getStyle("AD{$startRow}")->getFont()->setBold(true);


        $startRow ++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $bName);
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $dName);
        $sheet->mergeCells("F{$startRow}:G{$startRow}");
        $sheet->setCellValue("F{$startRow}", $date);
        $sheet->getStyle("F{$startRow}:G{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle("A{$startRow}:Q{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells("AD{$startRow}:AF{$startRow}");
        $sheet->setCellValue("AD{$startRow}", "JEV No.:______________________");
        $sheet->getStyle("A{$startRow}")->getFont()->setBold(true);
        $sheet->getStyle("J{$startRow}")->getFont()->setBold(true);
        $sheet->getStyle("AD{$startRow}")->getFont()->setBold(true);

        $startRow++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $sheet->setCellValue("A{$startRow}", $bPosition);
        $sheet->mergeCells("J{$startRow}:Q{$startRow}");
        $sheet->setCellValue("J{$startRow}", $dPosition);
        $sheet->mergeCells("AD{$startRow}:AF{$startRow}");
        $sheet->setCellValue("AD{$startRow}", "Date.:______________________");
        $sheet->mergeCells("F{$startRow}:G{$startRow}");
        $sheet->setCellValue("F{$startRow}", "Date");
        $sheet->getStyle("AD{$startRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$startRow}:Q{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$startRow}:AF{$startRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("I{$iBorderLeftStart}:I{$startRow}")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("U{$iBorderLeftStart}:AF{$startRow}")->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN); 
        $sheet->getStyle("AD{$eBorderStart}:AD{$startRow}")->getBorders()->getLeft()->setBorderStyle(Border::BORDER_THIN);

        $this->currentRow = $startRow + 5;
    }

    private function getGenPayroll($startDate, $endDate)
    {
        try {
            $payrollAggregates = DB::table('employees_payroll')
                ->select('user_id')
                ->selectRaw("SUM(CASE 
                                WHEN start_date >= ? AND end_date <= ? 
                                THEN net_amount_due 
                                ELSE 0 
                            END) as amount_due_first_half", [$startDate, Carbon::parse($startDate)->day(15)->toDateString()])
                ->selectRaw("SUM(CASE 
                                WHEN start_date >= ? AND end_date <= ? 
                                THEN net_amount_due 
                                ELSE 0 
                            END) as amount_due_second_half", [Carbon::parse($startDate)->day(16)->toDateString(), $endDate])
                ->selectRaw("SUM(net_amount_due) as net_amount_received")
                ->where('start_date', '>=', $startDate)
                ->where('end_date', '<=', $endDate)
                ->groupBy('user_id');
    
            // Join the aggregate results with the general_payroll table
            $payrolls = Payrolls::when($this->filters['search'], function ($query) {
                                    return $query->search(trim($this->filters['search']));
                                })
                                ->joinSub($payrollAggregates, 'payroll_aggregates', function ($join) {
                                    $join->on('payrolls.user_id', '=', 'payroll_aggregates.user_id');
                                })
                                ->select('payrolls.*', 
                                        'payroll_aggregates.amount_due_first_half', 
                                        'payroll_aggregates.amount_due_second_half', 
                                        'payroll_aggregates.net_amount_received');
            return $payrolls;
        } catch(Exception $e) {
            throw $e;
        }
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

    private function workingSheet($sheet){
        $this->formatAllMonths($sheet, true);
    }
    
    private function workingSheetDataRows($data, $sheet, $isLastChunk){
        $subtotal = [
            'rate_per_month' => 0, 
            'personal_economic_relief_allowance' => 0, 
            'gross_amount' => 0,
            'additional_gsis_premium' => 0, 
            'lbp_salary_loan' => 0, 
            'nycea_deductions' => 0,
            'sc_membership' => 0, 
            'nycempc_total' => 0, 
            'nycempc_mpl' => 0, 
            'nycempc_educ_loan' => 0, 
            'nycempc_pi' => 0, 
            'nycempc_business_loan' => 0, 
            'salary_loan' => 0, 
            'policy_loan' => 0, 
            'eal' => 0,
            'emergency_loan' => 0, 
            'mpl' => 0, 
            'housing_loan' => 0, 
            'ouli_prem' => 0, 
            'gfal' => 0, 
            'cpl' => 0,
            'pagibig_mpl' => 0, 
            'lwop' => 0,
            'gsis_rlip' => 0, 
            'gsis_gs' => 0, 
            'gsis_ecip' => 0, 
            'pagibig_contribution' => 0,
            'pagibig_gs' => 0,
            'pagibig_calamity_loan' => 0,
            'w_holding_tax' => 0, 
            'philhealth' => 0, 
            'philhealth_es' => 0, 
            'total_deduction' => 0, 
            'net_amount_received' => 0,
            'amount_due_first_half' => 0, 
            'amount_due_second_half' => 0,
        ];

        $totalRows = count($data);
        foreach ($data as $index => $row) {
            $this->currentRow++;
            $sheet->setCellValue("A{$this->currentRow}", $row[0]);
            $sheet->setCellValue("B{$this->currentRow}", $row[2]);
            $sheet->setCellValue("C{$this->currentRow}", $row[3]);
            $sheet->setCellValue("D{$this->currentRow}", $this->formatCurrency($row[5]));
            $sheet->setCellValue("E{$this->currentRow}", $this->formatCurrency($row[6]));
            $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($row[7]));
            $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($row[26]));
            $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($row[25]));
            $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($row[32]));
            $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($row[22]));
            $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($row[33]));
            $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($row[27]));
            $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($row[34]));
            $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($row[24]));
            $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($row[35]));
            $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($row[36]));
            $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($row[13]));
            $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($row[14]));
            $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($row[15]));
            $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($row[16]));
            $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($row[17]));
            $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($row[18]));
            $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($row[19]));
            $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($row[20]));
            $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($row[21]));
            $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($row[11]));
            $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($row[37]));
            $sheet->setCellValue("AG{$this->currentRow}", $this->formatCurrency($row[38]));
            $sheet->setCellValue("AH{$this->currentRow}", $this->formatCurrency($row[39]));
            $sheet->setCellValue("AI{$this->currentRow}", $this->formatCurrency($row[40]));
            $sheet->setCellValue("AJ{$this->currentRow}", $this->formatCurrency($row[12]));
            $sheet->setCellValue("AK{$this->currentRow}", $this->formatCurrency($row[9]));
            $sheet->setCellValue("AL{$this->currentRow}", $this->formatCurrency($row[10]));
            $sheet->setCellValue("AM{$this->currentRow}", $this->formatCurrency($row[23]));
            $sheet->setCellValue("AN{$this->currentRow}", '');
            $sheet->setCellValue("AO{$this->currentRow}", '');
            $sheet->setCellValue("AP{$this->currentRow}", $this->formatCurrency($row[28]));
            $sheet->setCellValue("AQ{$this->currentRow}", $this->formatCurrency($row[29]));
            $sheet->setCellValue("AR{$this->currentRow}", $this->formatCurrency($row[30]));
            $sheet->setCellValue("AS{$this->currentRow}", $this->formatCurrency($row[31]));

            $difference = $this->formatCurrency($row[30] - $row[31]);
            $quotient = $this->formatCurrency($row[29] / 2);

            $sheet->setCellValue("AT{$this->currentRow}", $difference);
            $sheet->setCellValue("AU{$this->currentRow}", $quotient);

            $sheet->getStyle("A{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
            $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $subtotal['rate_per_month'] += (float)$row[5];
            $subtotal['personal_economic_relief_allowance'] += (float)$row[6];
            $subtotal['gross_amount'] += (float)$row[7];
            $subtotal['additional_gsis_premium'] += (float)$row[8]; 
            $subtotal['lbp_salary_loan'] += (float)$row[9]; 
            $subtotal['nycea_deductions'] += (float)$row[10];
            $subtotal['sc_membership'] += (float)$row[11]; 
            $subtotal['nycempc_total'] += (float)$row[12]; 
            $subtotal['salary_loan'] += (float)$row[13]; 
            $subtotal['policy_loan'] += (float)$row[14]; 
            $subtotal['eal'] += (float)$row[15];
            $subtotal['emergency_loan'] += (float)$row[16]; 
            $subtotal['mpl'] += (float)$row[17]; 
            $subtotal['housing_loan'] += (float)$row[18]; 
            $subtotal['ouli_prem'] += (float)$row[19]; 
            $subtotal['gfal'] += (float)$row[20]; 
            $subtotal['cpl'] += (float)$row[21];
            $subtotal['pagibig_mpl'] += (float)$row[22]; 
            $subtotal['lwop'] += (float)$row[23];
            $subtotal['gsis_rlip'] += (float)$row[24]; 
            $subtotal['pagibig_contribution'] += (float)$row[25];
            $subtotal['w_holding_tax'] += (float)$row[26]; 
            $subtotal['philhealth'] += (float)$row[27]; 
            $subtotal['total_deduction'] += (float)$row[28]; 
            $subtotal['net_amount_received'] += (float)$row[29];
            $subtotal['amount_due_first_half'] += (float)$row[30]; 
            $subtotal['amount_due_second_half'] += (float)$row[31];

            $subtotal['pagibig_gs'] += (float)$row[32];
            $subtotal['pagibig_calamity_loan'] += (float)$row[33];
            $subtotal['philhealth_es'] += (float)$row[34];
            $subtotal['gsis_gs'] += (float)$row[35];
            $subtotal['gsis_ecip'] += (float)$row[36];
            $subtotal['nycempc_mpl'] += (float)$row[37];
            $subtotal['nycempc_educ_loan'] += (float)$row[38];
            $subtotal['nycempc_pi'] += (float)$row[39];
            $subtotal['nycempc_business_loan'] += (float)$row[40];

            $this->workingSheetCount++;
        }

        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", "");
        $sheet->setCellValue("B{$this->currentRow}", "SUB-TOTAL");
        $sheet->setCellValue("C{$this->currentRow}", "");
        $sheet->setCellValue("D{$this->currentRow}", $this->formatCurrency($subtotal['rate_per_month']));
        $sheet->setCellValue("E{$this->currentRow}", "");
        $sheet->setCellValue("F{$this->currentRow}", "");
        $sheet->setCellValue("G{$this->currentRow}", "");
        $sheet->setCellValue("H{$this->currentRow}", "");
        $sheet->setCellValue("I{$this->currentRow}", "");
        $sheet->setCellValue("J{$this->currentRow}", "");
        $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($subtotal['gross_amount']));
        $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($subtotal['w_holding_tax']));
        $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_contribution']));
        $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_gs']));
        $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_mpl']));
        $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($subtotal['pagibig_calamity_loan']));
        $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($subtotal['philhealth']));
        $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($subtotal['philhealth_es']));
        $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($subtotal['gsis_rlip']));
        $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($subtotal['gsis_gs']));
        $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($subtotal['gsis_ecip']));
        // $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($subtotal['additional_gsis_premium']));
        $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($subtotal['salary_loan']));
        $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($subtotal['policy_loan']));
        $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($subtotal['eal']));
        $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($subtotal['emergency_loan']));
        $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($subtotal['mpl']));
        $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($subtotal['housing_loan']));
        $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($subtotal['ouli_prem']));
        $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($subtotal['gfal']));
        $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($subtotal['cpl']));
        $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($subtotal['sc_membership']));
        $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_mpl']));
        $sheet->setCellValue("AG{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_educ_loan']));
        $sheet->setCellValue("AH{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_pi']));
        $sheet->setCellValue("AI{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_business_loan']));
        $sheet->setCellValue("AJ{$this->currentRow}", $this->formatCurrency($subtotal['nycempc_total']));
        $sheet->setCellValue("AK{$this->currentRow}", $this->formatCurrency($subtotal['lbp_salary_loan']));
        $sheet->setCellValue("AL{$this->currentRow}", $this->formatCurrency($subtotal['nycea_deductions']));
        $sheet->setCellValue("AM{$this->currentRow}", $this->formatCurrency($subtotal['lwop']));
        $sheet->setCellValue("AN{$this->currentRow}", "");
        $sheet->setCellValue("AO{$this->currentRow}", "");
        $sheet->setCellValue("AP{$this->currentRow}", $this->formatCurrency($subtotal['total_deduction']));
        $sheet->setCellValue("AQ{$this->currentRow}", $this->formatCurrency($subtotal['net_amount_received']));
        $sheet->setCellValue("AR{$this->currentRow}", $this->formatCurrency($subtotal['amount_due_first_half']));
        $sheet->setCellValue("AS{$this->currentRow}", $this->formatCurrency($subtotal['amount_due_second_half']));

        $difference = $this->formatCurrency($subtotal['amount_due_first_half'] - $subtotal['amount_due_second_half']);
        $quotient = $this->formatCurrency($subtotal['net_amount_received'] / 2);

        $sheet->setCellValue("AT{$this->currentRow}", $difference);
        $sheet->setCellValue("AU{$this->currentRow}", $quotient);

        $sheet->getRowDimension($this->currentRow )->setRowHeight(20);
        $sheet->getStyle("A{$this->currentRow}:C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
        $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        $sheet->getStyle("A{$this->currentRow}:AU{$this->currentRow}")->getFont()->setBold(true);

        return $subtotal;
    }

    private function addWorkingSheetGrandTotalRow($sheet, $grandTotal){
        $this->currentRow++;
        $sheet->setCellValue("A{$this->currentRow}", "");
        $sheet->setCellValue("B{$this->currentRow}", "GRAND TOTAL");
        $sheet->setCellValue("C{$this->currentRow}", "");
        $sheet->setCellValue("D{$this->currentRow}", $this->formatCurrency($grandTotal['rate_per_month']));
        $sheet->setCellValue("E{$this->currentRow}", "");
        $sheet->setCellValue("F{$this->currentRow}", "");
        $sheet->setCellValue("G{$this->currentRow}", "");
        $sheet->setCellValue("H{$this->currentRow}", "");
        $sheet->setCellValue("I{$this->currentRow}", "");
        $sheet->setCellValue("J{$this->currentRow}", "");
        $sheet->setCellValue("K{$this->currentRow}", $this->formatCurrency($grandTotal['gross_amount']));
        $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($grandTotal['w_holding_tax']));
        $sheet->setCellValue("M{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_contribution']));
        $sheet->setCellValue("N{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_gs']));
        $sheet->setCellValue("O{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_mpl']));
        $sheet->setCellValue("P{$this->currentRow}", $this->formatCurrency($grandTotal['pagibig_calamity_loan']));
        $sheet->setCellValue("Q{$this->currentRow}", $this->formatCurrency($grandTotal['philhealth']));
        $sheet->setCellValue("R{$this->currentRow}", $this->formatCurrency($grandTotal['philhealth_es']));
        $sheet->setCellValue("S{$this->currentRow}", $this->formatCurrency($grandTotal['gsis_rlip']));
        $sheet->setCellValue("T{$this->currentRow}", $this->formatCurrency($grandTotal['gsis_gs']));
        $sheet->setCellValue("U{$this->currentRow}", $this->formatCurrency($grandTotal['gsis_ecip']));
        // $sheet->setCellValue("L{$this->currentRow}", $this->formatCurrency($grandTotal['additional_gsis_premium']));
        $sheet->setCellValue("V{$this->currentRow}", $this->formatCurrency($grandTotal['salary_loan']));
        $sheet->setCellValue("W{$this->currentRow}", $this->formatCurrency($grandTotal['policy_loan']));
        $sheet->setCellValue("X{$this->currentRow}", $this->formatCurrency($grandTotal['eal']));
        $sheet->setCellValue("Y{$this->currentRow}", $this->formatCurrency($grandTotal['emergency_loan']));
        $sheet->setCellValue("Z{$this->currentRow}", $this->formatCurrency($grandTotal['mpl']));
        $sheet->setCellValue("AA{$this->currentRow}", $this->formatCurrency($grandTotal['housing_loan']));
        $sheet->setCellValue("AB{$this->currentRow}", $this->formatCurrency($grandTotal['ouli_prem']));
        $sheet->setCellValue("AC{$this->currentRow}", $this->formatCurrency($grandTotal['gfal']));
        $sheet->setCellValue("AD{$this->currentRow}", $this->formatCurrency($grandTotal['cpl']));
        $sheet->setCellValue("AE{$this->currentRow}", $this->formatCurrency($grandTotal['sc_membership']));
        $sheet->setCellValue("AF{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_mpl']));
        $sheet->setCellValue("AG{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_educ_loan']));
        $sheet->setCellValue("AH{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_pi']));
        $sheet->setCellValue("AI{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_business_loan']));
        $sheet->setCellValue("AJ{$this->currentRow}", $this->formatCurrency($grandTotal['nycempc_total']));
        $sheet->setCellValue("AK{$this->currentRow}", $this->formatCurrency($grandTotal['lbp_salary_loan']));
        $sheet->setCellValue("AL{$this->currentRow}", $this->formatCurrency($grandTotal['nycea_deductions']));
        $sheet->setCellValue("AM{$this->currentRow}", $this->formatCurrency($grandTotal['lwop']));
        $sheet->setCellValue("AN{$this->currentRow}", "");
        $sheet->setCellValue("AO{$this->currentRow}", "");
        $sheet->setCellValue("AP{$this->currentRow}", $this->formatCurrency($grandTotal['total_deduction']));
        $sheet->setCellValue("AQ{$this->currentRow}", $this->formatCurrency($grandTotal['net_amount_received']));
        $sheet->setCellValue("AR{$this->currentRow}", $this->formatCurrency($grandTotal['amount_due_first_half']));
        $sheet->setCellValue("AS{$this->currentRow}", $this->formatCurrency($grandTotal['amount_due_second_half']));

        $difference = $this->formatCurrency($grandTotal['amount_due_first_half'] - $grandTotal['amount_due_second_half']);
        $quotient = $this->formatCurrency($grandTotal['net_amount_received'] / 2);

        $sheet->setCellValue("AT{$this->currentRow}", $difference);
        $sheet->setCellValue("AU{$this->currentRow}", $quotient);

        $sheet->getRowDimension($this->currentRow )->setRowHeight(30);
        $sheet->getStyle("A{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
        $sheet->getStyle("A{$this->currentRow}:AS{$this->currentRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
        $sheet->getStyle("A{$this->currentRow}:AU{$this->currentRow}")->getFont()->setBold(true);

        $this->currentRow += 2;

    }
}

