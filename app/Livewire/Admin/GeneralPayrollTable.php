<?php

namespace App\Livewire\Admin;

use App\Exports\GeneralPayrollExport;
use App\Models\EmployeesPayroll;
use App\Models\GeneralPayroll;
use App\Models\Payrolls;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class GeneralPayrollTable extends Component
{
    public $sortColumn = false;
    public $search;
    public $allCol = false;
    public $columns = [
        'name' => true,
        'employee_number' => true,
        'position' => true,
        'sg_step' => true,
        'rate_per_month' => true,
        'personal_economic_relief_allowance' => true,
        'gross_amount' => true,
        'additional_gsis_premium' => true,
        'lbp_salary_loan' => true,
        'nycea_deductions' => true,
        'sc_membership' => true,
        'total_loans' => true,
        'salary_loan' => true,
        'policy_loan' => true,
        'eal' => true,
        'emergency_loan' => true,
        'mpl' => true,
        'housing_loan' => true,
        'ouli_prem' => true,
        'gfal' => true,
        'cpl' => true,
        'pagibig_mpl' => true,
        'other_deduction_philheath_diff' => true,
        'life_retirement_insurance_premiums' => true,
        'pagibig_contribution' => true,
        'w_holding_tax' => true,
        'philhealth' => true,
        'total_deduction' => true,
        'net_amount_received' => true,
        'amount_due_first_half' => true,
        'amount_due_second_half' => true,
    ];
    public $payroll;
    public $employees;
    public $userId;
    public $name;
    public $employee_number;
    public $position;
    public $sg_step;
    public $rate_per_month;
    public $personal_economic_relief_allowance;
    public $gross_amount;
    public $additional_gsis_premium;
    public $lbp_salary_loan;
    public $nycea_deductions;
    public $sc_membership;
    public $total_loans;
    public $salary_loan;
    public $policy_loan;
    public $eal;
    public $emergency_loan;
    public $mpl;
    public $housing_loan;
    public $ouli_prem;
    public $gfal;
    public $cpl;
    public $pagibig_mpl;
    public $other_deduction_philheath_diff;
    public $life_retirement_insurance_premiums;
    public $pagibig_contribution;
    public $w_holding_tax;
    public $philhealth;
    public $total_deduction;
    public $net_amount_received;
    public $amount_due_first_half;
    public $amount_due_second_half;
    public $date;
    public $startDateFirstHalf;
    public $endDateFirstHalf;
    public $startDateSecondHalf;
    public $endDateSecondHalf;
    public $hasPayroll = true;


    public function mount(){
        $this->employees = User::all();
    }

    public function render(){
        $payrolls = collect();
    
        if ($this->date) {
            // Create a Carbon instance from the month input
            $carbonDate = Carbon::createFromFormat('Y-m', $this->date);
    
            // Set the date ranges for the first and second halves of the month
            $this->startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
            $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $this->endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

            $generalPayrollQuery = GeneralPayroll::where('date', $this->startDateFirstHalf);
            if($generalPayrollQuery->exists()){
                $payrolls = $generalPayrollQuery
                    ->join('payrolls', 'general_payroll.user_id', '=', 'payrolls.user_id')
                    ->select('payrolls.*', 
                        'general_payroll.net_amount_received as total_amount_due', 
                        'general_payroll.amount_due_first_half as net_amount_due_first_half', 
                        'general_payroll.amount_due_second_half as net_amount_due_second_half')
                    ->when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->paginate(10);
                $this->hasPayroll = true;
            }else{
                $payrolls = $this->getGenPayroll();
                $this->hasPayroll = false;
            }
        }
    
        return view('livewire.admin.general-payroll-table', [
            'payrolls' => $payrolls,
        ]);
    }

    public function getGenPayroll(){
        try{
            $payrollAggregates = DB::table('employees_payroll')
            ->select('user_id')
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as net_amount_due_first_half", [$this->startDateFirstHalf, $this->endDateFirstHalf])
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as net_amount_due_second_half", [$this->startDateSecondHalf, $this->endDateSecondHalf])
            ->selectRaw("SUM(net_amount_due) as total_amount_due")
            ->where('start_date', $this->startDateFirstHalf)
            ->orWhere('end_date', $this->endDateSecondHalf)
            ->groupBy('user_id');

            // Join the aggregate results with the general_payroll table
            $payrolls = Payrolls::when($this->search, function ($query) {
                                return $query->search(trim($this->search));
                            })
                            ->joinSub($payrollAggregates, 'payroll_aggregates', function ($join) {
                                $join->on('payrolls.user_id', '=', 'payroll_aggregates.user_id');
                            })
                            ->select('payrolls.*', 
                                    'payroll_aggregates.net_amount_due_first_half', 
                                    'payroll_aggregates.net_amount_due_second_half', 
                                    'payroll_aggregates.total_amount_due')
                            ->paginate(10);
            return $payrolls;
        }catch(Exception $e){
            throw $e;
        }
    }
    
    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function toggleAllColumn() {
        if ($this->allCol) {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->columns[$col] = false;
            }
            $this->allCol = false;
        } else {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->columns[$col] = true;
            }
            $this->allCol = true;
        }
    }
    
    public function exportExcel(){
        $filters = [
            'search' => $this->search,
            'date' => $this->date,
        ];
        $fileName = 'General Payroll.xlsx';
        return Excel::download(new GeneralPayrollExport($filters), $fileName);
    }

    public function viewPayroll($userId){
        $this->payroll = true;
        $this->userId = $userId;
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->date);
            $date = $carbonDate->startOfMonth()->toDateString();

            $payroll = Payrolls::where('user_id', $userId)->first();
            $generalPayroll = GeneralPayroll::where('user_id', $userId)
                                ->where('date', $date)
                                ->first();
                                
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->employee_number;
                $this->position = $payroll->position;
                $this->sg_step = $payroll->sg_step;
                $this->rate_per_month = $payroll->rate_per_month;
                $this->personal_economic_relief_allowance = $payroll->personal_economic_relief_allowance;
                $this->gross_amount = $payroll->gross_amount;
                $this->additional_gsis_premium = $payroll->additional_gsis_premium;
                $this->lbp_salary_loan = $payroll->lbp_salary_loan;
                $this->nycea_deductions = $payroll->nycea_deductions;
                $this->sc_membership = $payroll->sc_membership;
                $this->total_loans = $payroll->total_loans;
                $this->salary_loan = $payroll->salary_loan;
                $this->policy_loan = $payroll->policy_loan;
                $this->eal = $payroll->eal;
                $this->emergency_loan = $payroll->emergency_loan;
                $this->mpl = $payroll->mpl;
                $this->housing_loan = $payroll->housing_loan;
                $this->ouli_prem = $payroll->ouli_prem;
                $this->gfal = $payroll->gfal;
                $this->cpl = $payroll->cpl;
                $this->pagibig_mpl = $payroll->pagibig_mpl;
                $this->other_deduction_philheath_diff = $payroll->other_deduction_philheath_diff;
                $this->life_retirement_insurance_premiums = $payroll->life_retirement_insurance_premiums;
                $this->pagibig_contribution = $payroll->pagibig_contribution;
                $this->w_holding_tax = $payroll->w_holding_tax;
                $this->philhealth = $payroll->philhealth;
                $this->total_deduction = $payroll->total_deduction;
                if($generalPayroll){
                    $this->net_amount_received = $generalPayroll->net_amount_received;
                    $this->amount_due_first_half = $generalPayroll->amount_due_first_half;
                    $this->amount_due_second_half = $generalPayroll->amount_due_second_half;
                }else{
                    $thisPayroll = EmployeesPayroll::where('user_id', $userId)
                                ->selectRaw("SUM(CASE
                                                WHEN start_date >= ? AND end_date <= ?
                                                THEN net_amount_due
                                                ELSE 0
                                            END) as net_amount_due_first_half", [$this->startDateFirstHalf, $this->endDateFirstHalf])
                                ->selectRaw("SUM(CASE
                                                WHEN start_date >= ? AND end_date <= ?
                                                THEN net_amount_due
                                                ELSE 0
                                            END) as net_amount_due_second_half", [$this->startDateSecondHalf, $this->endDateSecondHalf])
                                ->selectRaw("SUM(net_amount_due) as total_amount_due")
                                ->where(function ($query) {
                                    $query->where('start_date', '>=', $this->startDateFirstHalf)
                                        ->where('end_date', '<=', $this->endDateSecondHalf);
                                })
                                ->groupBy('user_id')
                                ->first();
                    $this->net_amount_received = $thisPayroll->total_amount_due;
                    $this->amount_due_first_half = $thisPayroll->net_amount_due_first_half;
                    $this->amount_due_second_half = $thisPayroll->net_amount_due_second_half;
                }
            } else {
                // If no payroll exists, you might want to reset all fields
                $this->resetPayrollFields();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->payroll = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->sg_step = null;
        $this->rate_per_month = null;
        $this->personal_economic_relief_allowance = null;
        $this->gross_amount = null;
        $this->additional_gsis_premium = null;
        $this->lbp_salary_loan = null;
        $this->nycea_deductions = null;
        $this->sc_membership = null;
        $this->total_loans = null;
        $this->salary_loan = null;
        $this->policy_loan = null;
        $this->eal = null;
        $this->emergency_loan = null;
        $this->mpl = null;
        $this->housing_loan = null;
        $this->ouli_prem = null;
        $this->gfal = null;
        $this->cpl = null;
        $this->pagibig_mpl = null;
        $this->other_deduction_philheath_diff = null;
        $this->life_retirement_insurance_premiums = null;
        $this->pagibig_contribution = null;
        $this->w_holding_tax = null;
        $this->philhealth = null;
        $this->total_deduction = null;
        // $this->net_amount_received = null;
        // $this->amount_due_first_half = null;
        // $this->amount_due_second_half = null;
        
    }

    public function recordPayroll(){
        try {
            if ($this->date) {
                $carbonDate = Carbon::createFromFormat('Y-m', $this->date);
                $startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
                $endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
                $startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
                $endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

                $payrollAggregates = DB::table('employees_payroll')
                    ->select('user_id')
                    ->selectRaw("SUM(CASE 
                                    WHEN start_date >= ? AND end_date <= ? 
                                    THEN net_amount_due 
                                    ELSE 0 
                                END) as net_amount_due_first_half", [$startDateFirstHalf, $endDateFirstHalf])
                    ->selectRaw("SUM(CASE 
                                    WHEN start_date >= ? AND end_date <= ? 
                                    THEN net_amount_due 
                                    ELSE 0 
                                END) as net_amount_due_second_half", [$startDateSecondHalf, $endDateSecondHalf])
                    ->selectRaw("SUM(net_amount_due) as total_amount_due")
                    ->groupBy('user_id');
        
                // Join the aggregate results with the general_payroll table
                $payrolls = Payrolls::joinSub($payrollAggregates, 'payroll_aggregates', function ($join) {
                                    $join->on('payrolls.user_id', '=', 'payroll_aggregates.user_id');
                                })
                                ->select('payrolls.*', 
                                        'payroll_aggregates.net_amount_due_first_half', 
                                        'payroll_aggregates.net_amount_due_second_half', 
                                        'payroll_aggregates.total_amount_due')
                                ->get();
    
                foreach ($payrolls as $payroll) {
                    $userId = $payroll->user_id;
                        
                    GeneralPayroll::create([
                            'user_id' => $userId,
                            'net_amount_received' => $payroll->total_amount_due,
                            'amount_due_first_half' => $payroll->net_amount_due_first_half,
                            'amount_due_second_half' => $payroll->net_amount_due_second_half,
                            'date' => $startDateFirstHalf,
                        ]
                    );
                }
    
                $this->dispatch('notify', [
                    'message' => 'General Payroll Saved!',
                    'type' => 'success'
                ]);
            }else{
                $this->dispatch('notify', [
                    'message' => 'Select date!',
                    'type' => 'info'
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
            throw $e;
        }
    }
}
