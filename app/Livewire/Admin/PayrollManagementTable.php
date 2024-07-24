<?php

namespace App\Livewire\Admin;

use App\Exports\PayrollExport;
use App\Models\EmployeesDtr;
use App\Models\EmployeesPayroll;
use App\Models\GeneralPayroll;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class PayrollManagementTable extends Component
{
    public $sortColumn = false;
    public $startDate;
    public $endDate;
    public $payrollDTR;
    public $generalPayroll;
    public $hasPayroll;
    protected $payrolls = [];
    public $search;
    public $allCol = true;
    public $columns = [
        'name',
        'employee_number',
        'position',
        'salary_grade',
        'daily_salary_rate',
        'no_of_days_covered',
        'gross_salary',
        'absences_days',
        'absences_amount',
        'late_undertime_hours',
        'late_undertime_hours_amount',
        'late_undertime_mins',
        'late_undertime_mins_amount',
        'gross_salary_less',
        'withholding_tax',
        'nycempc',
        'total_deductions',
        'net_amount_due',
    ];

    public function render(){
        $users = User::paginate(10);

        if ($this->startDate && $this->endDate) {
            $query = EmployeesPayroll::where('start_date', $this->startDate)
                ->where('end_date', $this->endDate)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                });
    
            if ($query->exists()) {
                $this->payrolls = $query->paginate(10);
                $this->hasPayroll = true;
            } else {
                $this->payrolls = $this->getPayroll();
                $this->hasPayroll = false;
            }
        }

        return view('livewire.admin.payroll-management-table', [
            'users' => $users,
            'payrolls' => $this->payrolls,
        ]);
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

    public function recordPayroll(){
        try {
            if ($this->startDate && $this->endDate) {
                $this->generalPayroll = GeneralPayroll::all();
                $this->payrollDTR = $this->getDTRForPayroll($this->startDate, $this->endDate);
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
                // Calculate total working days in the month
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - 
                    Carbon::parse($startDate)->daysInMonth * 2 / 7; // Subtracting weekends
                $totalWorkingDaysInMonth = round($totalWorkingDaysInMonth);
    
                // Calculate working days in the covered period
                $totalDays = $startDate->diffInDaysFiltered(function(Carbon $date) {
                    return !$date->isWeekend();
                }, $endDate) + 1; // Include both start and end dates
    
                foreach ($this->generalPayroll as $generalPayrollRecord) {
                    $userId = $generalPayrollRecord->user_id;
                    $user = User::where('id', $userId)->first();
                    $dtrData = $this->payrollDTR[$userId] ?? null;
    
                    if (!$dtrData) {
                        continue; // Skip if no DTR data for this user
                    }
    
                    $presentDays = count($dtrData['daily_records']);
                    $absentDays = $totalDays - $presentDays;
    
                    // New calculations
                    $dailySalaryRate = $generalPayrollRecord->rate_per_month / $totalWorkingDaysInMonth;
                    $grossSalary = $dailySalaryRate * $totalDays;
                    
                    $deductionPercentage = $totalDays / $totalWorkingDaysInMonth;
                    $totalDeductions = $generalPayrollRecord->total_deduction * $deductionPercentage;
                    $withholdingTax = $generalPayrollRecord->w_holding_tax * $deductionPercentage;
    
                    $absentAmount = $absentDays * $dailySalaryRate;
    
                    $lateUndertimeHours = floor($dtrData['total_late'] / 60);
                    $lateUndertimeMins = $dtrData['total_late'] % 60;
    
                    $lateUndertimeHoursAmount = $lateUndertimeHours * ($dailySalaryRate / 8); // Assuming 8-hour workday
                    $lateUndertimeMinsAmount = $lateUndertimeMins * ($dailySalaryRate / 480); // 480 minutes in a workday
    
                    $grossSalaryLess = $grossSalary - $absentAmount - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount;

                    // Deduct withholding tax
                    $grossSalaryLessTax = $grossSalaryLess - $withholdingTax;

                    // Deduct NYCEMPC
                    $grossSalaryLessNYCEMPC = $grossSalaryLessTax - ($generalPayrollRecord->nycea_deductions * $deductionPercentage);

                    // Ensure net amount is not negative
                    $netAmountDue = max(0, $grossSalaryLessTax - $grossSalaryLessNYCEMPC);

                    $net_amount_due = 0;

                    if ($grossSalaryLessNYCEMPC < $totalDeductions) {
                        $totalDeductions = $grossSalaryLessNYCEMPC;
                        $net_amount_due = $grossSalaryLessNYCEMPC - $totalDeductions;
                    }

                    $net_amount_due = $grossSalaryLessNYCEMPC - $totalDeductions;
                        
                    EmployeesPayroll::create([
                            'user_id' => $userId,
                            'name' => $user->name,
                            'employee_number' => $generalPayrollRecord->employee_number,
                            'position' => $generalPayrollRecord->position,
                            'salary_grade' => $generalPayrollRecord->sg_step,
                            'daily_salary_rate' => $dailySalaryRate,
                            'no_of_days_covered' => $totalDays,
                            'gross_salary' => $grossSalary,
                            'absences_days' => $absentDays,
                            'absences_amount' => $absentAmount,
                            'late_undertime_hours' => $lateUndertimeHours,
                            'late_undertime_hours_amount' => $lateUndertimeHoursAmount,
                            'late_undertime_mins' => $lateUndertimeMins,
                            'late_undertime_mins_amount' => $lateUndertimeMinsAmount,
                            'gross_salary_less' => $grossSalaryLess,
                            'withholding_tax' => $withholdingTax,
                            'nycempc' => $generalPayrollRecord->nycea_deductions * $deductionPercentage,
                            'total_deductions' => $totalDeductions,
                            'net_amount_due' => $net_amount_due,
                            'start_date' => $this->startDate,
                            'end_date' => $this->endDate,
                        ]
                    );
                }
    
                $this->dispatch('notify', [
                    'message' => 'Payroll Saved!',
                    'type' => 'success'
                ]);
            }else{
                $this->dispatch('notify', [
                    'message' => 'Select start and end date!',
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

    public function getPayroll(){
        $payrolls = collect();
        try {
            if ($this->startDate && $this->endDate) {
                $generalPayroll = GeneralPayroll::all();
                $payrollDTR = $this->getDTRForPayroll($this->startDate, $this->endDate);
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - 
                    Carbon::parse($startDate)->daysInMonth * 2 / 7;
                $totalWorkingDaysInMonth = round($totalWorkingDaysInMonth);

                $totalDays = $startDate->diffInDaysFiltered(function(Carbon $date) {
                    return !$date->isWeekend();
                }, $endDate) + 1;

                foreach ($generalPayroll as $generalPayrollRecord) {
                    $userId = $generalPayrollRecord->user_id;
                    $user = User::where('id', $userId)->first();
                    $dtrData = $payrollDTR[$userId] ?? null;

                    if (!$dtrData) {
                        continue;
                    }

                    $presentDays = count($dtrData['daily_records']);
                    $absentDays = $totalDays - $presentDays;

                    $dailySalaryRate = $generalPayrollRecord->rate_per_month / $totalWorkingDaysInMonth;
                    $grossSalary = $dailySalaryRate * $totalDays;
                    
                    $deductionPercentage = $totalDays / $totalWorkingDaysInMonth;
                    $totalDeductions = $generalPayrollRecord->total_deduction * $deductionPercentage;
                    $withholdingTax = $generalPayrollRecord->w_holding_tax * $deductionPercentage;

                    $absentAmount = $absentDays * $dailySalaryRate;

                    $lateUndertimeHours = floor($dtrData['total_late'] / 60);
                    $lateUndertimeMins = $dtrData['total_late'] % 60;

                    $lateUndertimeHoursAmount = $lateUndertimeHours * ($dailySalaryRate / 8);
                    $lateUndertimeMinsAmount = $lateUndertimeMins * ($dailySalaryRate / 480);

                    $grossSalaryLess = $grossSalary - $absentAmount - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount;

                    // Deduct withholding tax
                    $afterTax = $grossSalaryLess - $withholdingTax;

                    // Deduct NYCEMPC
                    $nycempc = $generalPayrollRecord->nycea_deductions * $deductionPercentage;
                    $afterNYCEMPC = $afterTax - $nycempc;

                    // Calculate remaining deductions
                    $remainingDeductions = $totalDeductions - ($withholdingTax + $nycempc);

                    // Ensure net amount is not negative
                    $netAmountDue = max(0, $afterNYCEMPC - $remainingDeductions);

                    // Adjust total deductions if necessary
                    $adjustedTotalDeductions = $totalDeductions;
                    if ($afterNYCEMPC < $remainingDeductions) {
                        $adjustedTotalDeductions = $afterNYCEMPC + $withholdingTax + $nycempc;
                    }

                    $payrolls->push([
                        'name' => $user->name,
                        'employee_number' => $generalPayrollRecord->employee_number,
                        'position' => $generalPayrollRecord->position,
                        'salary_grade' => $generalPayrollRecord->sg_step,
                        'daily_salary_rate' => $dailySalaryRate,
                        'no_of_days_covered' => $totalDays,
                        'gross_salary' => $grossSalary,
                        'absences_days' => $absentDays,
                        'absences_amount' => $absentAmount,
                        'late_undertime_hours' => $lateUndertimeHours,
                        'late_undertime_hours_amount' => $lateUndertimeHoursAmount,
                        'late_undertime_mins' => $lateUndertimeMins,
                        'late_undertime_mins_amount' => $lateUndertimeMinsAmount,
                        'gross_salary_less' => $grossSalaryLess,
                        'withholding_tax' => $withholdingTax,
                        'nycempc' => $nycempc,
                        'total_deductions' => $adjustedTotalDeductions,
                        'net_amount_due' => $netAmountDue,
                        'start_date' => $this->startDate,
                        'end_date' => $this->endDate,
                    ]);
                }

                // Apply search filter
                if ($this->search) {
                    $payrolls = $payrolls->filter(function ($payroll) {
                        return Str::contains(strtolower($payroll['name']), strtolower($this->search)) ||
                            Str::contains(strtolower($payroll['employee_number']), strtolower($this->search));
                    });
                }

                $page = request()->get('page', 1); // Get the current page from the request
                $perPage = 10; // Number of items per page

                $items = $payrolls->forPage($page, $perPage);

                $paginator = new LengthAwarePaginator(
                    $items,
                    $payrolls->count(),
                    $perPage,
                    $page,
                    ['path' => request()->url(), 'query' => request()->query()]
                );

                return $paginator;
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
            return new LengthAwarePaginator([], 0, 10);
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
            // ...
        }
    }

    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function exportPayroll(){
        $filters = [
            'search' => $this->search,
        ];
        $filename = 'Payroll_' . $this->startDate->format('F') . ' ' . $this->startDate->format('d') . '-' . $this->endDate->format('d') . ' ' . $this->startDate->format('Y') . '.xlsx';
        return Excel::download(new PayrollExport($this->payrolls), $filename);
    }
}
