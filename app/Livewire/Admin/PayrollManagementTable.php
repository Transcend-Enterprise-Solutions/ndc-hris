<?php

namespace App\Livewire\Admin;

use App\Exports\PayrollListExport;
use App\Models\GeneralPayroll;
use App\Models\Payrolls;
use App\Models\User;
use Exception;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class PayrollManagementTable extends Component
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

    public function mount(){
        $this->employees = User::all();
    }

    public function render(){
        $payrolls = Payrolls::when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->paginate(10);
        
        return view('livewire.admin.payroll-management-table', [
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
        $fileName = 'Payroll List.xlsx';
        return Excel::download(new PayrollListExport($filters), $fileName);
    }

    public function toggleEditPayroll($userId){
        $this->editPayroll = true;
        $this->userId = $userId;
        try {
            $payroll = GeneralPayroll::where('user_id', $userId)->first();
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
            } else {
                $this->resetPayrollFields();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddPayroll(){
        $this->editPayroll = true;
        $this->addPayroll = true;
    }

    public function savePayroll(){
        try {
            $payroll = Payrolls::where('user_id', $this->userId)->first();
    
            $payrollData = [
                'user_id' => $this->userId,
                'employee_number' => $this->employee_number,
                'position' => $this->position,
                'sg_step' => $this->sg_step,
                'rate_per_month' => $this->rate_per_month,
                'personal_economic_relief_allowance' => $this->personal_economic_relief_allowance,
                'gross_amount' => $this->gross_amount,
                'additional_gsis_premium' => $this->additional_gsis_premium,
                'lbp_salary_loan' => $this->lbp_salary_loan,
                'nycea_deductions' => $this->nycea_deductions,
                'sc_membership' => $this->sc_membership,
                'total_loans' => $this->total_loans,
                'salary_loan' => $this->salary_loan,
                'policy_loan' => $this->policy_loan,
                'eal' => $this->eal,
                'emergency_loan' => $this->emergency_loan,
                'mpl' => $this->mpl,
                'housing_loan' => $this->housing_loan,
                'ouli_prem' => $this->ouli_prem,
                'gfal' => $this->gfal,
                'cpl' => $this->cpl,
                'pagibig_mpl' => $this->pagibig_mpl,
                'other_deduction_philheath_diff' => $this->other_deduction_philheath_diff,
                'life_retirement_insurance_premiums' => $this->life_retirement_insurance_premiums,
                'pagibig_contribution' => $this->pagibig_contribution,
                'w_holding_tax' => $this->w_holding_tax,
                'philhealth' => $this->philhealth,
                'total_deduction' => $this->total_deduction,
            ];
    
            if ($payroll) {
                $this->validate([
                    'employee_number' => 'required|max:100',
                    'position' => 'required|max:100',
                    'sg_step' => 'required|max:100',
                    'rate_per_month' => 'required|numeric',
                    'gross_amount' => 'required|numeric',
                    'pagibig_contribution' => 'required|numeric',
                    'w_holding_tax' => 'required|numeric',
                    'philhealth' => 'required|numeric',
                    'total_deduction' => 'required|numeric',
                ]);

                $payroll->update($payrollData);
                $message = "Payroll updated successfully!";
            } else {
                $this->validate([
                    'employee_number' => 'required|max:100',
                    'position' => 'required|max:100',
                    'sg_step' => 'required|max:100',
                    'rate_per_month' => 'required|numeric',
                    'gross_amount' => 'required|numeric',
                    'pagibig_contribution' => 'required|numeric',
                    'w_holding_tax' => 'required|numeric',
                    'philhealth' => 'required|numeric',
                    'total_deduction' => 'required|numeric',
                ]);
                $user = User::where('id', $this->userId)->first();
                $payrollData['name'] = $user->name;
                Payrolls::create($payrollData);
                $message = "Payroll added successfully!";
            }
    
            $this->resetVariables();
            $this->editPayroll = null;
            $this->addPayroll = null;
            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Payroll update was unsuccessful!",
                'type' => 'error'
            ]);
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
    }
}
