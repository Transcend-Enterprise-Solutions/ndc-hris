<?php

namespace App\Livewire\Admin;

use App\Exports\GeneralPayrollExport;
use App\Models\EmployeesDtr;
use App\Models\GeneralPayroll;
use App\Models\User;
use Exception;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

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
        'personal_economic_relief_allowance' => false,
        'gross_amount' => false,
        'additional_gsis_premium' => false,
        'lbp_salary_loan' => false,
        'nycea_deductions' => false,
        'sc_membership' => false,
        'total_loans' => false,
        'salary_loan' => false,
        'policy_loan' => false,
        'eal' => false,
        'emergency_loan' => false,
        'mpl' => false,
        'housing_loan' => false,
        'ouli_prem' => false,
        'gfal' => false,
        'cpl' => false,
        'pagibig_mpl' => false,
        'other_deduction_philheath_diff' => false,
        'life_retirement_insurance_premiums' => false,
        'pagibig_contribution' => false,
        'w_holding_tax' => false,
        'philhealth' => false,
        'total_deduction' => false,
        // 'net_amount_received' => false,
        // 'amount_due_first_half' => false,
        // 'amount_due_second_half' => false,
    ];
    public $addPayroll;
    public $editPayroll;
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
    // public $net_amount_received;
    // public $amount_due_first_half;
    // public $amount_due_second_half;

    public function mount(){
        $this->employees = User::all();
    }

    public function render(){
        $payrolls = GeneralPayroll::when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->paginate(10);
        
        return view('livewire.admin.general-payroll-table', [
            'payrolls' => $payrolls,
        ]);
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
        ];
        $fileName = 'General Payroll.xlsx';
        return Excel::download(new GeneralPayrollExport($filters), $fileName);
    }

    public function toggleEditPayroll($userId){
        $this->editPayroll = true;
        $this->userId = $userId;
        try{
            $payroll = GeneralPayroll::where('user_id', $userId)->first();
            if($payroll){
                $this->name = $payroll->name;
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toggleAddPayroll(){
        $this->editPayroll = true;
        $this->addPayroll = true;
    }

    public function savePayroll(){
        try{
            $payroll = GeneralPayroll::where('user_id', $this->userId)->first();
            if($payroll){
                $payroll->update([
                    'user_id' => $this->userId,
                    'employee_number' => $this->employee_number,
                ]);
            }else{
                $user = User::where('id', $this->userId)->first();
                GeneralPayroll::create([
                    'user_id' => $this->userId,
                    'name' => $user->name,
                ]);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->addPayroll = null;
        $this->editPayroll = null;
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

    public function getDTRForPayroll($startDate, $endDate, $employeeId = null){
        try {
            $query = EmployeesDtr::whereBetween('date', [$startDate, $endDate]);

            if ($employeeId) {
                $query->where('user_id', $employeeId);
            }

            $dtrRecords = $query->orderBy('date')->get();

            $payrollDTR = [];

            foreach ($dtrRecords as $record) {
                $employeeId = $record->user_id;
                $date = $record->date;

                if (!isset($payrollDTR[$employeeId])) {
                    $payrollDTR[$employeeId] = [
                        'total_days' => 0,
                        'total_hours' => 0,
                        'total_late' => 0,
                        'total_overtime' => 0,
                        'daily_records' => []
                    ];
                }

                $payrollDTR[$employeeId]['total_days']++;
                $payrollDTR[$employeeId]['total_hours'] += $record->total_hours_endered;
                $payrollDTR[$employeeId]['total_late'] += $record->late;
                $payrollDTR[$employeeId]['total_overtime'] += $record->overtime;

                $payrollDTR[$employeeId]['daily_records'][$date] = [
                    'day_of_week' => $record->day_of_week,
                    'location' => $record->location,
                    'morning_in' => $record->morning_in,
                    'morning_out' => $record->morning_out,
                    'afternoon_in' => $record->afternoon_in,
                    'afternoon_out' => $record->afternoon_out,
                    'late' => $record->late,
                    'overtime' => $record->overtime,
                    'total_hours' => $record->total_hours_endered
                ];
            }

            return $payrollDTR;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function processPayroll($startDate, $endDate){
        $payrollData = $this->getDTRForPayroll($startDate, $endDate);

        foreach ($payrollData as $employeeId => $dtrData) {
            // Retrieve employee's base salary and other relevant information
            $employee = User::find($employeeId);
            $baseSalary = $employee->base_salary; // Assuming you have this field

            // Calculate salary based on DTR data
            $totalHours = $dtrData['total_hours'];
            $totalLate = $dtrData['total_late'];
            $totalOvertime = $dtrData['total_overtime'];

            // Perform salary calculations here
            // For example:
            $salary = ($baseSalary / 160) * $totalHours; // Assuming 160 hours per month
            $lateDeductions = $totalLate * ($baseSalary / 160 / 60); // Deduct per minute of late
            $overtimePay = $totalOvertime * (($baseSalary / 160) * 1.25); // 1.25x pay for overtime

            $grossPay = $salary + $overtimePay - $lateDeductions;

            // Calculate deductions (taxes, benefits, etc.)
            // ...

            // Calculate net pay
            // ...

            // Store payroll results
        }
    }
}
