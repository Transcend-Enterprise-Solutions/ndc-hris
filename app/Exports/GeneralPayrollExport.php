<?php

namespace App\Exports;

use App\Models\Payrolls;
use App\Models\GeneralPayroll;
use Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GeneralPayrollExport implements FromCollection, WithEvents
{
    use Exportable;

    protected $filters;
    protected $startDateFirstHalf;
    protected $endDateFirstHalf;
    protected $startDateSecondHalf;
    protected $endDateSecondHalf;
    protected $rowNumber = 0;

    public function __construct($filters)
    {
        $this->filters = $filters;
        $this->setDates();
    }

    public function collection(){
        $formatCurrency = function($value) {
            if($value == 0 || $value == null){
                return "-";
            }
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $query = Payrolls::query();

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('employee_number', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('sg_step', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('position', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }
        
        if (!empty($this->filters['date'])) {
            $generalPayrollQuery = GeneralPayroll::where('date', $this->startDateFirstHalf);
            if($generalPayrollQuery->exists()){
                $query->join('general_payroll', 'payrolls.user_id', '=', 'general_payroll.user_id')
                        ->where('date', $this->startDateFirstHalf);
            }else{
                $query = $this->getGenPayroll();
            }

        }

        $data = $query->get();
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
                $this->rowNumber,
                'employee_number' => $payroll->employee_number,
                'name' => $payroll->name,
                'position' => $payroll->position,
                'sg_step' => $payroll->sg_step,
                'rate_per_month' => $formatCurrency($payroll->rate_per_month),
                'personal_economic_relief_allowance' => $formatCurrency($payroll->personal_economic_relief_allowance),
                'gross_amount' => $formatCurrency($payroll->gross_amount),
                'additional_gsis_premium' => $formatCurrency($payroll->additional_gsis_premium),
                'lbp_salary_loan' => $formatCurrency($payroll->lbp_salary_loan),
                'nycea_deductions' => $formatCurrency($payroll->nycea_deductions),
                'sc_membership' => $formatCurrency($payroll->sc_membership),
                'total_loans' => $formatCurrency($payroll->total_loans),
                'salary_loan' => $formatCurrency($payroll->salary_loan),
                'policy_loan' => $formatCurrency($payroll->policy_loan),
                'eal' => $formatCurrency($payroll->eal),
                'emergency_loan' => $formatCurrency($payroll->emergency_loan),
                'mpl' => $formatCurrency($payroll->mpl),
                'housing_loan' => $formatCurrency($payroll->housing_loan),
                'ouli_prem' => $formatCurrency($payroll->ouli_prem),
                'gfal' => $formatCurrency($payroll->gfal),
                'cpl' => $formatCurrency($payroll->cpl),
                'pagibig_mpl' => $formatCurrency($payroll->pagibig_mpl),
                'other_deduction_philheath_diff' => $formatCurrency($payroll->other_deduction_philheath_diff),
                'life_retirement_insurance_premiums' => $formatCurrency($payroll->life_retirement_insurance_premiums),
                'pagibig_contribution' => $formatCurrency($payroll->pagibig_contribution),
                'w_holding_tax' => $formatCurrency($payroll->w_holding_tax),
                'philhealth' => $formatCurrency($payroll->philhealth),
                'total_deduction' => $formatCurrency($payroll->total_deduction),
                'net_amount_received' => $formatCurrency($payroll->net_amount_received),
                'amount_due_first_half' => $formatCurrency($payroll->amount_due_first_half),
                'amount_due_second_half' => $formatCurrency($payroll->amount_due_second_half),
            ];
        });

        // Add the totals row
        $formattedData->push([
            '', // Serial No.
            '', // Employee No.
            'SUB-TOTAL', // Name
            '', // Position
            '', // SG/Step
            $formatCurrency($totals['rate_per_month']),
            $formatCurrency($totals['personal_economic_relief_allowance']),
            $formatCurrency($totals['gross_amount']),
            $formatCurrency($totals['additional_gsis_premium']),
            $formatCurrency($totals['lbp_salary_loan']),
            $formatCurrency($totals['nycea_deductions']),
            $formatCurrency($totals['sc_membership']),
            $formatCurrency($totals['total_loans']),
            $formatCurrency($totals['salary_loan']),
            $formatCurrency($totals['policy_loan']),
            $formatCurrency($totals['eal']),
            $formatCurrency($totals['emergency_loan']),
            $formatCurrency($totals['mpl']),
            $formatCurrency($totals['housing_loan']),
            $formatCurrency($totals['ouli_prem']),
            $formatCurrency($totals['gfal']),
            $formatCurrency($totals['cpl']),
            $formatCurrency($totals['pagibig_mpl']),
            $formatCurrency($totals['other_deduction_philheath_diff']),
            $formatCurrency($totals['life_retirement_insurance_premiums']),
            $formatCurrency($totals['pagibig_contribution']),
            $formatCurrency($totals['w_holding_tax']),
            $formatCurrency($totals['philhealth']),
            $formatCurrency($totals['total_deduction']),
            $formatCurrency($totals['net_amount_received']),
            $formatCurrency($totals['amount_due_first_half']),
            $formatCurrency($totals['amount_due_second_half']),
        ]);

        return $formattedData;
    }

    private function setDates(){
        if (!empty($this->filters['date'])) {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->filters['date']);

            $this->startDateFirstHalf = $carbonDate->copy()->startOfMonth()->toDateString();
            $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $this->endDateSecondHalf = $carbonDate->copy()->endOfMonth()->toDateString();
        }
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
        $payrollMonth = Carbon::parse($this->filters['date'])->format('F');

        $payrollDays = Carbon::parse($this->startDateFirstHalf)->format('d') . '-' .Carbon::parse($this->endDateSecondHalf)->format('d');
        
        // Add custom header
        $sheet->mergeCells('A1:AF1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', "Payroll No.: _____________________");
        
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', "Sheet _________of __________Sheets");

        $sheet->mergeCells('AC2:AD2');
        $sheet->setCellValue('AC2', "PAYROLL WORKSHEET");
        $sheet->setCellValue('AE2', "MONTH");
        $sheet->setCellValue('AF2', "DAYS");

        $sheet->mergeCells('AC3:AD3');
        $sheet->setCellValue('AC3', $payrollMonth);
        $sheet->setCellValue('AE3', $payrollMonth);
        $sheet->setCellValue('AF3', $payrollDays);
        
        $sheet->mergeCells('A4:AF4');
        $sheet->setCellValue('A4', "GENERAL PAYROLL");
        // $sheet->setCellValue('A3', strtoupper($payrollMonth) . " " . $this->startDateSecondHalf . "-" . $this->endDateSecondHalf . " GENERAL PAYROLL");
        
        $sheet->mergeCells('A5:AF5');
        $sheet->setCellValue('A5', "NATIONAL YOUTH COMMISSION");
        
        $sheet->mergeCells('A6:AF6');
        $sheet->setCellValue('A6', "FOR THE MONTH OF " . strtoupper($payrollMonth));

        $sheet->mergeCells('A7:AF7');
        $sheet->setCellValue('A7', "");
        
        $sheet->mergeCells('A8:B8');
        $sheet->setCellValue('A8', "Entity Name : ");

        $sheet->mergeCells('C8:j8');
        $sheet->setCellValue('C8', " NATIONAL YOUTH COMMISSION ");
        
        $sheet->mergeCells('A9:B9');
        $sheet->setCellValue('A9', "Fund Cluster : ");

        $sheet->mergeCells('C9:E9');
        $sheet->setCellValue('C9', " 01101101 ");
        
        $sheet->mergeCells('A10:AF10');
        $sheet->setCellValue('A10', "WE ACKNOWLEDGED RECEIPT OF THE SUM SHOWN OPPOSITE OUR NAMES AS FULL COMPENSATION FOR OUR SERVICE RENDERED FOR THE PERIOD STATED:");

        $sheet->mergeCells('A11:AF11');
        $sheet->setCellValue('A11', "");
        

        // Apply some basic styling
        $sheet->getStyle('A4:AF6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:AF')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('AC2:AF2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('AC3:AF3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle('A5')->getFont()->setBold(true);
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $sheet->getStyle('A12:AF12')->getFont()->setBold(false);
        $sheet->getStyle('A1:AF11')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);

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

        $sheet->getStyle('C8:J8')->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);

        $sheet->getStyle('C9:E9')->applyFromArray([
            'font' => [
                'underline' => true,
            ],
        ]);

        $event->sheet->setShowGridlines(false);

        // Push down the headings
        $sheet->insertNewRowBefore(12, 1);
    }

    public function getGenPayroll(){
        try{
            $payrollAggregates = DB::table('employees_payroll')
            ->select('user_id')
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as amount_due_first_half", [$this->startDateFirstHalf, $this->endDateFirstHalf])
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as amount_due_second_half", [$this->startDateSecondHalf, $this->endDateSecondHalf])
            ->selectRaw("SUM(net_amount_due) as net_amount_received")
            ->where('start_date', $this->startDateFirstHalf)
            ->orWhere('end_date', $this->endDateSecondHalf)
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
        }catch(Exception $e){
            throw $e;
        }
    }
    
}
