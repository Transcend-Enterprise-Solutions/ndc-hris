<?php

namespace App\Exports;

use App\Models\GeneralPayroll;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class GeneralPayrollExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection(){
        $formatCurrency = function($value) {
            return '₱ ' . number_format((float)$value, 2, '.', ',');
        };

        $query = GeneralPayroll::query();

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('employee_number', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('sg_step', 'LIKE', '%' . $this->filters['search'] . '%')
                ->orWhere('position', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }

        return $query->get()->map(function ($payroll) use ($formatCurrency) {
            return [
                'name' => $payroll->name,
                'employee_number' => $payroll->employee_number,
                'position' => $payroll->position,
                'sg_step' => $payroll->sg_step,
                'rate_per_month' => $formatCurrency($payroll->rate_per_month),
                'personal_economic_relief_allowance' => $formatCurrency($payroll->personal_economic_relief_allowance),
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
                'net_amount_received' => $formatCurrency($payroll->net_amount_received),
                'amount_due_first_half' => $formatCurrency($payroll->amount_due_first_half),
                'amount_due_second_half' => $formatCurrency($payroll->amount_due_second_half),
            ];
        });
    }


    public function headings(): array
    {
        return [
            'NAME',
            'EMPLOYEE NUMBER',
            'POSITION',
            'SG-STEP',
            'RATE PER MONTH',
            'PERSONAL ECONOMIC RELIEF ALLOWANCE',
            'GROSS AMOUNT',
            'ADDITIONAL GSIS PREMIUM',
            'LBP SALARY LOAN',
            'NYCEA DEDUCTIONS',
            'SC MEMBERSHIP',
            'SALARY LOAN',
            'POLICY LOAN',
            'EAL',
            'EMERGENCY LOAN',
            'MPL',
            'HOUSING LOAN',
            'OULI PREM',
            'GFAL',
            'CPL',
            'PAG-IBIG MPL',
            'OTHER DEDUCTION PHILHEALTH DIFF',
            'LIFE RETIREMENT INSURANCE PREMIUMS',
            'PAG-IBIG CONTRIBUTION',
            'WITHHOLDING TAX',
            'PHILHEALTH',
            'TOTAL DEDUCTION',
            'NET AMOUNT RECEIVED',
            'AMOUNT DUE FIRST HALF',
            'AMOUNT DUE SECOND HALF',
        ];
    }
    
}
