<?php

namespace App\Livewire\Admin;

use App\Models\EmployeesPayroll;
use App\Models\User;
use Livewire\Component;

class PayrollManagementTable extends Component
{
    public $sortColumn = false;

    public $columns = [
        'employee_number' => true,
        'position' => true,
        'sg/step' => true,
        'rate_per_month' => true,
        'personal_economic_relief_allowance' => true,
        'gross_amount' => true,
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
        'net_amount_received' => false,
        'amount_due_first_half' => false,
        'amount_due_second_half' => false,
    ];

    public function render(){
        $users = User::paginate(10);
        $payrolls = EmployeesPayroll::all();
        
        return view('livewire.admin.payroll-management-table', [
            'users' => $users,
            'payrolls' => $payrolls,
        ]);
    }

    public function toggleDropdown()
    {
        $this->sortColumn = !$this->sortColumn;
    }

}
