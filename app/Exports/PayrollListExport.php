<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PayrollListExport implements FromCollection, WithEvents
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
                ->join('payrolls', 'payrolls.user_id', 'users.id')
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
                'pera' => $formatCurrency($payroll->personal_economic_relief_allowance),
                'gross_amount' => $formatCurrency($payroll->gross_amount),
                'additional_gsis_premium' => $formatCurrency($payroll->additional_gsis_premium),
                'lbp_salary_loan' => $formatCurrency($payroll->lbp_salary_loan),
                'nycea_deductions' => $formatCurrency($payroll->nycea_deductions),
                'sc_membership' => $formatCurrency($payroll->sc_membership),
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
                for ($col = 'F'; $col <= 'Z'; $col++) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                }
                $sheet->getColumnDimension('AA')->setWidth(20);
                $sheet->getColumnDimension('AB')->setWidth(20);
                $sheet->getColumnDimension('AC')->setWidth(20);


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
                $sheet->setCellValue('H4', 'PERA');
                $sheet->setCellValue('I4', 'GROSS AMOUNT');
                $sheet->setCellValue('J4', 'ADDITIONAL GSIS PREMIUM');
                $sheet->setCellValue('K4', 'LBP SALARY LOAN');
                $sheet->setCellValue('L4', 'NYCEA DEDUCTIONS');
                $sheet->setCellValue('M4', 'SC MEMBERSHIP');
                $sheet->setCellValue('N4', 'SALARY LOAN');
                $sheet->setCellValue('O4', 'POLICY LOAN');
                $sheet->setCellValue('P4', 'EAL');
                $sheet->setCellValue('Q4', 'EMERGENCY LOAN');
                $sheet->setCellValue('R4', 'MPL');
                $sheet->setCellValue('S4', 'HOUSING LOAN');
                $sheet->setCellValue('T4', 'OULI PREM');
                $sheet->setCellValue('U4', 'GFAL');
                $sheet->setCellValue('V4', 'CPL');
                $sheet->setCellValue('W4', 'PAG-IBIG MPL');
                $sheet->setCellValue('X4', 'OTHER DEDUCTION PHILHEALTH DIFF');
                $sheet->setCellValue('Y4', 'LIFE RETIREMENT INSURANCE PREMIUMS');
                $sheet->setCellValue('Z4', 'PAG-IBIG CONTRIBUTION');
                $sheet->setCellValue('AA4', 'WITHHOLDING TAX');
                $sheet->setCellValue('AB4', 'PHILHEALTH');
                $sheet->setCellValue('AC4', 'TOTAL DEDUCTION');

                // Apply word wrap
                $sheet->getStyle('A4:AC4')->getAlignment()->setWrapText(true);

                // Column Header
                $sheet->getStyle('A1:AC3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4:AC4')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);

                // Rows
                $sheet->getStyle('A4:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C4:AC' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    private function addCustomHeader(BeforeSheet $event){
        $sheet = $event->sheet;

        // Add custom header
        $sheet->mergeCells('A1:AC1');
        $sheet->setCellValue('A1', "");

        $sheet->mergeCells('A2:AC2');
        $sheet->setCellValue('A2', "NATIONAL YOUTH COMMISSION");
        $sheet->mergeCells('A3:AC3');
        $sheet->setCellValue('A3', "Plantilla Payroll List");

        // Apply some basic styling
        $sheet->getStyle('A1:AC3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:AC3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:AC3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('A4:AC4')->getFont()->setBold(true);
        $sheet->getStyle('2:2')->getFont()->setSize(16);

        $sheet->getStyle('A4:AC4')->applyFromArray([
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
