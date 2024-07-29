<?php

namespace App\Livewire\Admin;

use App\Exports\PayrollExport;
use App\Models\EmployeesDtr;
use App\Models\EmployeesPayroll;
use App\Models\GeneralPayroll;
use App\Models\Holiday;
use App\Models\Payrolls;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollTable extends Component
{
    public $sortColumn = false;
    public $startDate;
    public $endDate;
    public $hasPayroll = true;
    public $search;
    public $allCol = true;
    public $columns = [
        'name',
        'employee_number',
        'position',
        'salary_grade',
        'daily_salary_rate',
        'no_of_days_covered',
        'regular_holidays',
        'regular_holidays_amount',
        'special_holidays',
        'special_holidays_amount',
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
    public $weekdayRegularHolidays = 0;
    public $weekdaySpecialHolidays = 0;

    public function render(){
        $users = User::paginate(10);
        $payrolls = [];
        if ($this->startDate && $this->endDate) {
            $query = EmployeesPayroll::where('start_date', $this->startDate)
                ->where('end_date', $this->endDate)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                });
    
            if ($query->exists()) {
                $payrolls = $query->paginate(10);
                $this->hasPayroll = true;
            } else {
                $payrolls = $this->getPayroll();
                $this->hasPayroll = false;
            }
        }

        // Check for holiday values
        foreach ($payrolls as $payroll) {
            if ($payroll['regular_holidays_amount'] != 0 || $payroll['regular_holidays'] != 0) {
                $this->weekdayRegularHolidays = 1;
            }
            if ($payroll['special_holidays_amount'] != 0 || $payroll['special_holidays'] != 0) {
                $this->weekdaySpecialHolidays = 1;
            }
        }

        return view('livewire.admin.payroll-table', [
            'users' => $users,
            'payrolls' => $payrolls,
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
                $payrollsAll = Payrolls::all();
                $payrollDTR = $this->getDTRForPayroll($this->startDate, $this->endDate);
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - 
                    Carbon::parse($startDate)->daysInMonth * 2 / 7;
                $totalWorkingDaysInMonth = round($totalWorkingDaysInMonth);
    
                // Fetch holidays between start and end date
                $holidays = $this->getHolidays($startDate, $endDate);

                // Calculate total working days (Monday to Friday, excluding holidays)
                $totalDays = 0;
                $currentDate = $startDate->copy();
                $this->weekdayRegularHolidays = 0;
                $this->weekdaySpecialHolidays = 0;

                while ($currentDate <= $endDate) {
                    if ($currentDate->isWeekday()) {
                        $dateString = $currentDate->format('Y-m-d');
                        if (!$holidays->has($dateString)) {
                            $totalDays++;
                        } else {
                            // Check the type of holiday
                            $holidayType = $holidays->get($dateString);
                            if ($holidayType === 'Special') {
                                $totalDays++;
                                $this->weekdaySpecialHolidays++;
                            } else {
                                $this->weekdayRegularHolidays++;
                            }
                        }
                    }
                    $currentDate->addDay();
                }
    
                // Check if the start date is from 16th to end of month
                $isSecondSemiMonthlyPay = $startDate->day >= 16 || $endDate->isLastOfMonth();
    
                // Get the first day of the month
                $firstDayOfMonth = $startDate->copy()->startOfMonth();

                $totalDeductions = 0;
                $withholdingTax = 0;
                $newTotalDeduction = 0;
                $deductionBalance = 0;
                $nycempc = 0;
                $netAmountDue = 0;
                $specialHolidayRate = 0.3;
                $regularHolidayRate = 2;
    
                foreach ($payrollsAll as $payrollsAllRecord) {
                    $userId = $payrollsAllRecord->user_id;
                    $user = User::where('id', $userId)->first();
                    $dtrData = $payrollDTR[$userId] ?? null;
    
                    if (!$dtrData) {
                        continue;
                    }

                    $presentDays = count($dtrData['daily_records']);
                    $absentDays = $totalDays - $presentDays;
                    
                    $totalHoursRendered = $dtrData['total_hours'] / 60;
                    $totalDaysRendered = $totalHoursRendered / 8; 
                    
                    $dailySalaryRate = $payrollsAllRecord->rate_per_month / $totalWorkingDaysInMonth;
                    $grossSalary = $dailySalaryRate * $totalDays;
                    
                    $absentAmount = $absentDays * $dailySalaryRate;

                    $lateUndertimeHours = floor($dtrData['total_late'] / 60);
                    $lateUndertimeMins = $dtrData['total_late'] % 60;
                    $lateUndertimeHoursAmount = $lateUndertimeHours * ($dailySalaryRate / 8);
                    $lateUndertimeMinsAmount = $lateUndertimeMins * ($dailySalaryRate / 480);

                    $hourlyRate = $dailySalaryRate / 8;
                    $totalHoursRendered = 0;
                    $regularHolidayPay = 0;
                    $specialHolidayPay = 0;
                    $regularHolidayCount = 0;
                    $specialHolidayCount = 0;

                    // Iterate through all days in the pay period
                    $currentDate = $startDate->copy();
                    while ($currentDate <= $endDate) {
                        $dateString = $currentDate->format('Y-m-d');
                        $holidayType = $holidays->get($dateString);
                        $record = $dtrData['daily_records'][$dateString] ?? null;

                        if ($currentDate->isWeekday()) {
                            if ($holidayType === 'Regular') {
                                if ($record && $record['total_hours'] > 0) {
                                    // Employee worked on a regular holiday
                                    $regularHolidayPay += ($record['total_hours'] / 60) * $hourlyRate * $regularHolidayRate;
                                } else {
                                    // Employee didn't work but it's a paid holiday
                                    $regularHolidayPay += $dailySalaryRate;
                                }
                                $regularHolidayCount++;
                            } elseif ($holidayType === 'Special') {
                                if ($record && $record['total_hours'] > 0) {
                                    // Employee worked on a special holiday
                                    $specialHolidayPay += ($record['total_hours'] / 60) * $hourlyRate * $specialHolidayRate;
                                }
                                // If employee didn't work on a special holiday, no additional pay
                                $specialHolidayCount++;
                            }
                        }

                        if ($record) {
                            $totalHoursRendered += $record['total_hours'];
                        }

                        $currentDate->addDay();
                    }

                    
                    // Gross salary holiday added
                    $grossSalary = $grossSalary + $regularHolidayPay + $specialHolidayPay;
                    // Gross Salary less
                    $grossSalaryLess = $grossSalary - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount - $absentAmount;
                    
                    // Calculate withholding tax based on rendered days
                    if ($isSecondSemiMonthlyPay) {
                        $withholdingTax = $payrollsAllRecord->w_holding_tax;
                        // $withholdingTax = $payrollsAllRecord->w_holding_tax * ($totalDaysRendered / $totalWorkingDaysInMonth);
                        // dd( $payrollsAllRecord->w_holding_tax . " * " . "(" . $totalDaysRendered . "/" . $totalWorkingDaysInMonth . ") = " . $withholdingTax);
                        
                        $totalDeductions = $payrollsAllRecord->total_deduction;
                        $nycempc = $payrollsAllRecord->nycea_deductions;
    
                        // Calculate remaining deductions
                        $newTotalDeduction = $totalDeductions + $nycempc;
    
                        if($grossSalaryLess < $newTotalDeduction) {
                            $deductionBalance = $newTotalDeduction - $grossSalaryLess;
                            $netAmountDue = 0;
                        } else {
                            $netAmountDue = $grossSalaryLess - $newTotalDeduction;
                        }
                    }else{
                        $netAmountDue = $grossSalaryLess;
                    }
                        
                    EmployeesPayroll::create([
                            'user_id' => $userId,
                            'name' => $user->name,
                            'employee_number' => $payrollsAllRecord->employee_number,
                            'position' => $payrollsAllRecord->position,
                            'salary_grade' => $payrollsAllRecord->sg_step,
                            'daily_salary_rate' => $dailySalaryRate,
                            'no_of_days_covered' => $totalDays,
                            'regular_holidays' => $regularHolidayCount,
                            'regular_holidays_amount' => $regularHolidayPay,
                            'special_holidays' => $specialHolidayCount,
                            'special_holidays_amount' => $specialHolidayPay,
                            'gross_salary' => $grossSalary,
                            'absences_days' => $absentDays,
                            'absences_amount' => $absentAmount,
                            'late_undertime_hours' => $lateUndertimeHours,
                            'late_undertime_hours_amount' => $lateUndertimeHoursAmount,
                            'late_undertime_mins' => $lateUndertimeMins,
                            'late_undertime_mins_amount' => $lateUndertimeMinsAmount,
                            'gross_salary_less' => $grossSalaryLess,
                            'withholding_tax' => $withholdingTax,
                            'nycempc' => $payrollsAllRecord->nycea_deductions,
                            'total_deductions' => $totalDeductions,
                            'net_amount_due' => $netAmountDue,
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
                $payrollsAll = Payrolls::all();
                $payrollDTR = $this->getDTRForPayroll($this->startDate, $this->endDate);
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - 
                    Carbon::parse($startDate)->daysInMonth * 2 / 7;
                $totalWorkingDaysInMonth = round($totalWorkingDaysInMonth);
    
                // Fetch holidays between start and end date
                $holidays = $this->getHolidays($startDate, $endDate);

                // Calculate total working days (Monday to Friday, excluding holidays)
                $totalDays = 0;
                $currentDate = $startDate->copy();
                $this->weekdayRegularHolidays = 0;
                $this->weekdaySpecialHolidays = 0;

                while ($currentDate <= $endDate) {
                    if ($currentDate->isWeekday()) {
                        $dateString = $currentDate->format('Y-m-d');
                        if (!$holidays->has($dateString)) {
                            $totalDays++;
                        } else {
                            // Check the type of holiday
                            $holidayType = $holidays->get($dateString);
                            if ($holidayType === 'Special') {
                                $totalDays++;
                                $this->weekdaySpecialHolidays++;
                            } else {
                                $this->weekdayRegularHolidays++;
                            }
                        }
                    }
                    $currentDate->addDay();
                }
    
                // Check if the start date is from 16th to end of month
                $isSecondSemiMonthlyPay = $startDate->day >= 16 || $endDate->isLastOfMonth();
    
                // Get the first day of the month
                $firstDayOfMonth = $startDate->copy()->startOfMonth();

                $totalDeductions = 0;
                $withholdingTax = 0;
                $newTotalDeduction = 0;
                $deductionBalance = 0;
                $nycempc = 0;
                $netAmountDue = 0;
                $specialHolidayRate = 0.3;
                $regularHolidayRate = 2;
    
                foreach ($payrollsAll as $payrollsAllRecord) {
                    $userId = $payrollsAllRecord->user_id;
                    $user = User::where('id', $userId)->first();
                    $dtrData = $payrollDTR[$userId] ?? null;
    
                    if (!$dtrData) {
                        continue;
                    }

                    $presentDays = count($dtrData['daily_records']);
                    $absentDays = $totalDays - $presentDays;
                    
                    $totalHoursRendered = $dtrData['total_hours'] / 60;
                    $totalDaysRendered = $totalHoursRendered / 8; 
                    
                    $dailySalaryRate = $payrollsAllRecord->rate_per_month / $totalWorkingDaysInMonth;
                    $grossSalary = $dailySalaryRate * $totalDays;
                    
                    $absentAmount = $absentDays * $dailySalaryRate;

                    $lateUndertimeHours = floor($dtrData['total_late'] / 60);
                    $lateUndertimeMins = $dtrData['total_late'] % 60;
                    $lateUndertimeHoursAmount = $lateUndertimeHours * ($dailySalaryRate / 8);
                    $lateUndertimeMinsAmount = $lateUndertimeMins * ($dailySalaryRate / 480);

                    $hourlyRate = $dailySalaryRate / 8;
                    $totalHoursRendered = 0;
                    $regularHolidayPay = 0;
                    $specialHolidayPay = 0;
                    $regularHolidayCount = 0;
                    $specialHolidayCount = 0;

                    // Iterate through all days in the pay period
                    $currentDate = $startDate->copy();
                    while ($currentDate <= $endDate) {
                        $dateString = $currentDate->format('Y-m-d');
                        $holidayType = $holidays->get($dateString);
                        $record = $dtrData['daily_records'][$dateString] ?? null;

                        if ($currentDate->isWeekday()) {
                            if ($holidayType === 'Regular') {
                                if ($record && $record['total_hours'] > 0) {
                                    // Employee worked on a regular holiday
                                    $regularHolidayPay += ($record['total_hours'] / 60) * $hourlyRate * $regularHolidayRate;
                                } else {
                                    // Employee didn't work but it's a paid holiday
                                    $regularHolidayPay += $dailySalaryRate;
                                }
                                $regularHolidayCount++;
                            } elseif ($holidayType === 'Special') {
                                if ($record && $record['total_hours'] > 0) {
                                    // Employee worked on a special holiday
                                    $specialHolidayPay += ($record['total_hours'] / 60) * $hourlyRate * $specialHolidayRate;
                                }
                                // If employee didn't work on a special holiday, no additional pay
                                $specialHolidayCount++;
                            }
                        }

                        if ($record) {
                            $totalHoursRendered += $record['total_hours'];
                        }

                        $currentDate->addDay();
                    }

                    
                    // Gross salary holiday added
                    $grossSalary = $grossSalary + $regularHolidayPay + $specialHolidayPay;
                    // Gross Salary less
                    $grossSalaryLess = $grossSalary - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount - $absentAmount;
                    
                    // Calculate withholding tax based on rendered days
                    if ($isSecondSemiMonthlyPay) {
                        $withholdingTax = $payrollsAllRecord->w_holding_tax;
                        // $withholdingTax = $payrollsAllRecord->w_holding_tax * ($totalDaysRendered / $totalWorkingDaysInMonth);
                        // dd( $payrollsAllRecord->w_holding_tax . " * " . "(" . $totalDaysRendered . "/" . $totalWorkingDaysInMonth . ") = " . $withholdingTax);
                        
                        $totalDeductions = $payrollsAllRecord->total_deduction;
                        $nycempc = $payrollsAllRecord->nycea_deductions;
    
                        // Calculate remaining deductions
                        $newTotalDeduction = $totalDeductions + $nycempc;
    
                        if($grossSalaryLess < $newTotalDeduction) {
                            $deductionBalance = $newTotalDeduction - $grossSalaryLess;
                            $netAmountDue = 0;
                        } else {
                            $netAmountDue = $grossSalaryLess - $newTotalDeduction;
                        }
                    }else{
                        $netAmountDue = $grossSalaryLess;
                    }
    
                    $payrolls->push([
                        'name' => $user->name,
                        'employee_number' => $payrollsAllRecord->employee_number,
                        'position' => $payrollsAllRecord->position,
                        'salary_grade' => $payrollsAllRecord->sg_step,
                        'daily_salary_rate' => $dailySalaryRate,
                        'no_of_days_covered' => $totalDays,
                        'regular_holidays' => $regularHolidayCount,
                        'regular_holidays_amount' => $regularHolidayPay,
                        'special_holidays' => $specialHolidayCount,
                        'special_holidays_amount' => $specialHolidayPay,
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
                        'total_deductions' => $newTotalDeduction,
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
    
                return $payrolls;
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
            return new LengthAwarePaginator([], 0, 1);
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
                
                // Convert time strings to integer minutes
                $totalHours = $this->timeToMinutes($record->total_hours_rendered);
                $late = $this->timeToMinutes($record->late);
                $overtime = $this->timeToMinutes($record->overtime);

                $payrollDTR[$employeeId]['total_hours'] += $totalHours;
                $payrollDTR[$employeeId]['total_late'] += $late;
                $payrollDTR[$employeeId]['total_overtime'] += $overtime;

                $payrollDTR[$employeeId]['daily_records'][$date] = [
                    'day_of_week' => $record->day_of_week,
                    'location' => $record->location,
                    'morning_in' => $record->morning_in,
                    'morning_out' => $record->morning_out,
                    'afternoon_in' => $record->afternoon_in,
                    'afternoon_out' => $record->afternoon_out,
                    'late' => $late,
                    'overtime' => $overtime,
                    'total_hours' => $totalHours
                ];
            }
            return $payrollDTR;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function timeToMinutes($timeString){
        if (empty($timeString)) {
            return 0;
        }
        list($hours, $minutes) = explode(':', $timeString);
        return (int)$hours * 60 + (int)$minutes;
    }

    private function getHolidays($startDate, $endDate)
    {
        $holidays = Holiday::whereBetween('holiday_date', [$startDate, $endDate])->get();
        return $holidays->mapWithKeys(function ($holiday) {
            // Convert the date to a string in 'Y-m-d' format
            $dateString = $holiday->holiday_date->format('Y-m-d');
            return [$dateString => $holiday->type];
        });
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
        try {
            if ($this->startDate && $this->endDate) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
                $filename = 'Payroll ' . $startDate->format('F') . ' '
                                       . $startDate->format('d') . '-'
                                       . $endDate->format('d') . ' '
                                       . $startDate->format('Y') . '.xlsx';
                
                if ($this->hasPayroll) {
                    $payrolls = EmployeesPayroll::where('start_date', $this->startDate)
                        ->where('end_date', $this->endDate)
                        ->when($this->search, function ($query) {
                            return $query->search(trim($this->search));
                        })
                        ->select([
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
                            'net_amount_due'
                        ])
                        ->get();
                } else {
                    $payrolls = $this->getPayroll();
                }
                
                return Excel::download(new PayrollExport($payrolls), $filename);
            } else {
                $this->dispatch('notify', [
                    'message' => 'Select start and end date!',
                    'type' => 'info'
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Error exporting payroll: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

}
