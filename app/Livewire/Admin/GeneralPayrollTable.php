<?php

namespace App\Livewire\Admin;

use App\Models\EmployeesDtr;
use App\Models\GeneralPayroll;
use App\Models\User;
use Exception;
use Livewire\Component;

class GeneralPayrollTable extends Component
{
    public $sortColumn = false;

    public $allCol = true;
    public $columns = [
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

    public function render(){
        $users = User::paginate(10);
        $payrolls = GeneralPayroll::all();
        
        return view('livewire.admin.general-payroll-table', [
            'users' => $users,
            'payrolls' => $payrolls,
        ]);
    }

    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function toggleAllColumn() {
        if ($this->allCol) {
            foreach (array_keys($this->columns) as $col) {
                $this->columns[$col] = false;
            }
            $this->allCol = false;
        } else {
            foreach (array_keys($this->columns) as $col) {
                $this->columns[$col] = true;
            }
            $this->allCol = true;
        }
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
