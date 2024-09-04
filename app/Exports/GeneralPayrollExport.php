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

class GeneralPayrollExport implements WithEvents, WithDrawings
{
    use Exportable;

    protected $filters;
    protected $rowNumber = 0;
    protected $totalPayroll;
    protected $months;
    protected $currentRow = 1;

    public function drawings(){
        return [];
    }

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

    public function registerEvents(): array{
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(4);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(8);
                for ($col = 'F'; $col <= 'Z'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
                for ($col = 'AA'; $col <= 'AF'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
                $sheet->getStyle("A:AF")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'name' => 'Cambria',
                    ],
                ]);

                $this->formatAllMonths($sheet);
            },
        ];
    }

    private function formatAllMonths($sheet){
        foreach ($this->months as $month) {
            $data = $this->getPayrollData($month);
            $this->Header($month, $sheet);
            $this->TableHeader($month, $sheet);
            $this->DataRows($data, $sheet);
            $this->Footer($sheet);
        }
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
        $sheet->setCellValue("F{$firstRowOfTable}", "Rate Per Month (per NBC 591) dtd. January 2023");
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
        $sheet->setCellValue("X{$firstRowOfTable}", "OTHER DEDUCTIONS PHILHEALTH DIFFERENTIAL");
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
        $sheet->getRowDimension($headerRow)->setRowHeight(70); 
        $sheet->getStyle("A{$firstRowOfTable}:B" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C{$firstRowOfTable}:C" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("D{$firstRowOfTable}:AF" . $this->currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$firstRowOfTable}:AF{$this->currentRow}")->getFont()->setBold(true);
    }

    private function getPayrollData($month){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return number_format((float)$value, 2, '.', ',');
        };
    
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

            // For Tenths ------------------------------------------------------------- //
            // $net_amount_received = $payroll->gross_amount - $payroll->total_deduction;
            // $amount_due_second_half = floor($net_amount_received / 2 / 10) * 10;
            // $amount_due_first_half = $net_amount_received - $amount_due_second_half;
        
            // $payroll->net_amount_received = $net_amount_received;
            // $payroll->amount_due_first_half = $amount_due_first_half;
            // $payroll->amount_due_second_half = $amount_due_second_half;

            // Check if the user has a salary deduction
            $deduction = PayrollsLeaveCreditsDeduction::where('user_id', $payroll->user_id)
                ->whereMonth('month', Carbon::parse($month)->month)
                ->whereYear('month', Carbon::parse($month)->year)
                ->first();

            $salaryDeduction = $deduction ? $deduction->salary_deduction_amount : 0;
            $net_amount_received = $payroll->gross_amount - $payroll->total_deduction - $salaryDeduction;
            $half_amount = $net_amount_received / 2;
        
            $amount_due_second_half = floor($half_amount);
            $amount_due_first_half = $net_amount_received - $amount_due_second_half;

            $payroll->net_amount_received = $net_amount_received;
            $payroll->amount_due_first_half = $amount_due_first_half;
            $payroll->amount_due_second_half = $amount_due_second_half;
        
            return $payroll;
        });

        $totals = $data->reduce(function ($carry, $item) {
            $numericColumns = [
                'rate_per_month', 'personal_economic_relief_allowance', 'gross_amount',
                'additional_gsis_premium', 'lbp_salary_loan', 'nycea_deductions',
                'sc_membership', 'total_loans', 'salary_loan', 'policy_loan', 'eal',
                'emergency_loan', 'mpl', 'housing_loan', 'ouli_prem', 'gfal', 'cpl',
                'pagibig_mpl', 'other_deduction_philheath_diff',
                'life_retirement_insurance_premiums', 'pagibig_contribution',
                'w_holding_tax', 'philhealth', 'total_deduction', 'net_amount_received',
                'amount_due_first_half', 'amount_due_second_half'
            ];
    
            foreach ($numericColumns as $column) {
                $carry[$column] = ($carry[$column] ?? 0) + $item->$column;
            }
    
            return $carry;
        }, []);
    
        $formattedData = $data->map(function ($payroll, $index) use ($formatCurrency) {
            $this->rowNumber++;
            return [
                0 => $this->rowNumber,
                1 => $payroll->emp_code,
                2 => $payroll->name,
                3 => $payroll->position,
                4 => $payroll->sg_step,
                5 => $formatCurrency($payroll->rate_per_month),
                6 => $formatCurrency($payroll->personal_economic_relief_allowance),
                7 => $formatCurrency($payroll->gross_amount),
                8 => $formatCurrency($payroll->additional_gsis_premium),
                9 => $formatCurrency($payroll->lbp_salary_loan),
                10 => $formatCurrency($payroll->nycea_deductions),
                11 => $formatCurrency($payroll->sc_membership),
                12 => $formatCurrency($payroll->total_loans),
                13 => $formatCurrency($payroll->salary_loan),
                14 => $formatCurrency($payroll->policy_loan),
                15 => $formatCurrency($payroll->eal),
                16 => $formatCurrency($payroll->emergency_loan),
                17 => $formatCurrency($payroll->mpl),
                18 => $formatCurrency($payroll->housing_loan),
                19 => $formatCurrency($payroll->ouli_prem),
                20 => $formatCurrency($payroll->gfal),
                21 => $formatCurrency($payroll->cpl),
                22 => $formatCurrency($payroll->pagibig_mpl),
                23 => $formatCurrency($payroll->other_deduction_philheath_diff),
                24 => $formatCurrency($payroll->life_retirement_insurance_premiums),
                25 => $formatCurrency($payroll->pagibig_contribution),
                26 => $formatCurrency($payroll->w_holding_tax),
                27 => $formatCurrency($payroll->philhealth),
                28 => $formatCurrency($payroll->total_deduction),
                29 => $formatCurrency($payroll->net_amount_received),
                30 => $formatCurrency($payroll->amount_due_first_half),
                31 => $formatCurrency($payroll->amount_due_second_half),
            ];
        });
    
        $formattedData->push([
            0 => '',
            1 => '',
            2 => 'SUB-TOTAL',
            3 => '',
            4 => '',
            5 => $formatCurrency($totals['rate_per_month']),
            6 => $formatCurrency($totals['personal_economic_relief_allowance']),
            7 => $formatCurrency($totals['gross_amount']),
            8 => $formatCurrency($totals['additional_gsis_premium']),
            9 => $formatCurrency($totals['lbp_salary_loan']),
            10 => $formatCurrency($totals['nycea_deductions']),
            11 => $formatCurrency($totals['sc_membership']),
            12 => $formatCurrency($totals['total_loans']),
            13 => $formatCurrency($totals['salary_loan']),
            14 => $formatCurrency($totals['policy_loan']),
            15 => $formatCurrency($totals['eal']),
            16 => $formatCurrency($totals['emergency_loan']),
            17 => $formatCurrency($totals['mpl']),
            18 => $formatCurrency($totals['housing_loan']),
            19 => $formatCurrency($totals['ouli_prem']),
            20 => $formatCurrency($totals['gfal']),
            21 => $formatCurrency($totals['cpl']),
            22 => $formatCurrency($totals['pagibig_mpl']),
            23 => $formatCurrency($totals['other_deduction_philheath_diff']),
            24 => $formatCurrency($totals['life_retirement_insurance_premiums']),
            25 => $formatCurrency($totals['pagibig_contribution']),
            26 => $formatCurrency($totals['w_holding_tax']),
            27 => $formatCurrency($totals['philhealth']),
            28 => $formatCurrency($totals['total_deduction']),
            29 => $formatCurrency($totals['net_amount_received']),
            30 => $formatCurrency($totals['amount_due_first_half']),
            31 => $formatCurrency($totals['amount_due_second_half']),
        ]);
    
        $this->totalPayroll = $totals['net_amount_received'];
        $this->rowNumber = 0;
        return $formattedData;
    }

    private function DataRows($data, $sheet){
        $totalRows = count($data);
        foreach ($data as $index => $row) {
            $this->currentRow++;
            $sheet->setCellValue("A{$this->currentRow}", $row[0]);
            $sheet->setCellValue("B{$this->currentRow}", $row[1]);
            $sheet->setCellValue("C{$this->currentRow}", $row[2]);
            $sheet->setCellValue("D{$this->currentRow}", $row[3]);
            $sheet->setCellValue("E{$this->currentRow}", $row[4]);
            $sheet->setCellValue("F{$this->currentRow}", $row[5]);
            $sheet->setCellValue("G{$this->currentRow}", $row[6]);
            $sheet->setCellValue("H{$this->currentRow}", $row[7]);
            $sheet->setCellValue("I{$this->currentRow}", $row[8]);
            $sheet->setCellValue("J{$this->currentRow}", $row[9]);
            $sheet->setCellValue("K{$this->currentRow}", $row[10]);
            $sheet->setCellValue("L{$this->currentRow}", $row[11]);
            $sheet->setCellValue("M{$this->currentRow}", $row[12]);
            $sheet->setCellValue("N{$this->currentRow}", $row[13]);
            $sheet->setCellValue("O{$this->currentRow}", $row[14]);
            $sheet->setCellValue("P{$this->currentRow}", $row[15]);
            $sheet->setCellValue("Q{$this->currentRow}", $row[16]);
            $sheet->setCellValue("R{$this->currentRow}", $row[17]);
            $sheet->setCellValue("S{$this->currentRow}", $row[18]);
            $sheet->setCellValue("T{$this->currentRow}", $row[19]);
            $sheet->setCellValue("U{$this->currentRow}", $row[20]);
            $sheet->setCellValue("V{$this->currentRow}", $row[21]);
            $sheet->setCellValue("W{$this->currentRow}", $row[22]);
            $sheet->setCellValue("X{$this->currentRow}", $row[23]);
            $sheet->setCellValue("Y{$this->currentRow}", $row[24]);
            $sheet->setCellValue("Z{$this->currentRow}", $row[25]);
            $sheet->setCellValue("AA{$this->currentRow}", $row[26]);
            $sheet->setCellValue("AB{$this->currentRow}", $row[27]);
            $sheet->setCellValue("AC{$this->currentRow}", $row[28]);
            $sheet->setCellValue("AD{$this->currentRow}", $row[29]);
            $sheet->setCellValue("AE{$this->currentRow}", $row[30]);
            $sheet->setCellValue("AF{$this->currentRow}", $row[31]);

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
            $sheet->getStyle("D{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            if ($index === $totalRows - 1) {
                $sheet->getRowDimension($this->currentRow )->setRowHeight(30);
                $sheet->getStyle("C{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);
                $sheet->getStyle("A{$this->currentRow}:AF{$this->currentRow}")->getFont()->setBold(true);
            }
        }

        $this->currentRow += 2;
    }

    private function Footer($sheet){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return 'PHP ' . number_format((float)$value, 2, '.', ',');
        };
        $formattedAmount = number_format($this->totalPayroll, 2, '.', '');
        $startRow = $this->currentRow;
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
        $startRow++;
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $signatureA = $this->getTemporarySignaturePath($signatoryA);
        if ($signatureA) {
            $drawingA = new Drawing();
            $drawingA->setName('Signature A');
            $drawingA->setDescription('Signature A');
            $drawingA->setPath($signatureA);
            $drawingA->setHeight($imageOptions['height']);
            $drawingA->setWidth($imageOptions['width']);
            $drawingA->setCoordinates("C{$startRow}");
            $drawingA->setOffsetX(100);
            $drawingA->setWorksheet($worksheet);
        }

        // Signature C
        $signatureC = $this->getTemporarySignaturePath($signatoryC);
        if ($signatureC) {
            $drawingC = new Drawing();
            $drawingC->setName('Signature C');
            $drawingC->setDescription('Signature C');
            $drawingC->setPath($signatureC);
            $drawingC->setHeight($imageOptions['height']);
            $drawingC->setWidth($imageOptions['width']);
            $drawingC->setCoordinates("M{$startRow}");
            $drawingC->setOffsetX(50);
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
        $sheet->mergeCells("A{$startRow}:E{$startRow}");
        $signatureB = $this->getTemporarySignaturePath($signatoryB);
        if ($signatureB) {
            $drawingB = new Drawing();
            $drawingB->setName('Signature A');
            $drawingB->setDescription('Signature A');
            $drawingB->setPath($signatureB);
            $drawingB->setHeight($imageOptions['height']);
            $drawingB->setWidth($imageOptions['width']);
            $drawingB->setCoordinates("C{$startRow}");
            $drawingB->setOffsetX(100);
            $drawingB->setWorksheet($worksheet);
        }

        // Signature D
        $signatureD = $this->getTemporarySignaturePath($signatoryD);
        if ($signatureD) {
            $drawingD = new Drawing();
            $drawingD->setName('Signature D');
            $drawingD->setDescription('Signature D');
            $drawingD->setPath($signatureD);
            $drawingD->setHeight($imageOptions['height']);
            $drawingD->setWidth($imageOptions['width']);
            $drawingD->setCoordinates("M{$startRow}");
            $drawingD->setOffsetX(50);
            $drawingD->setWorksheet($worksheet);
        }
        $sheet->getRowDimension($startRow)->setRowHeight($imageOptions['height'] / 2);

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

        $sheet->getStyle("U1:AF{$startRow}")->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN); 
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
    
}

