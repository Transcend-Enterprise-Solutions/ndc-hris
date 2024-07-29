<?php

namespace App\Exports;

use App\Models\Payrolls;
use App\Models\GeneralPayroll;
use Exception;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GeneralPayrollExport implements FromCollection, WithHeadings
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



        return $query->get()->map(function ($payroll) use ($formatCurrency) {
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

    private function setDates(){
        if (!empty($this->filters['date'])) {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->filters['date']);

            $this->startDateFirstHalf = $carbonDate->copy()->startOfMonth()->toDateString();
            $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $this->endDateSecondHalf = $carbonDate->copy()->endOfMonth()->toDateString();
        }
    }

    public function headings(): array
    {
        $firstHalf = $this->startDateFirstHalf && $this->endDateFirstHalf
            ? Carbon::parse($this->startDateFirstHalf)->format('F d') . '-' . Carbon::parse($this->endDateFirstHalf)->format('d, Y')
            : 'Date range not set';

        $secondHalf = $this->startDateSecondHalf && $this->endDateSecondHalf
            ? Carbon::parse($this->startDateSecondHalf)->format('F d') . '-' . Carbon::parse($this->endDateSecondHalf)->format('d, Y')
            : 'Date range not set';

        return [
            'SERIAL NO.',
            'EMPLOYEE NUMBER',
            'NAME',
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
            'AMOUNT DUE (' . $firstHalf . ')',
            'AMOUNT DUE (' . $secondHalf . ')',
        ];
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
