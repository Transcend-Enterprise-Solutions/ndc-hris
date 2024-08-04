<?php

namespace App\Livewire\Admin;

use App\Exports\PayrollExport;
use App\Models\EmployeesDtr;
use App\Models\EmployeesPayroll;
use App\Models\Holiday;
use App\Models\LeaveApplication;
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
        'leave_days_withpay',
        'leave_payment',
        'gross_salary',
        'leave_days_withoutpay',
        'leave_days_withoutpay_amount',
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
    protected $savePayroll;
    public $employeePayslip;

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
                $this->employeePayslip = $query->get();
                $this->hasPayroll = true;
            } else {
                $payrolls = $this->getPayroll();
                $this->hasPayroll = false;
                $this->employeePayslip = $payrolls;
            }

        }

        // Check for holiday values
        if($payrolls != null){
            foreach ($payrolls as $payroll) {
                if ($payroll['regular_holidays_amount'] != 0 || $payroll['regular_holidays'] != 0) {
                    $this->weekdayRegularHolidays = 1;
                }
                if ($payroll['special_holidays_amount'] != 0 || $payroll['special_holidays'] != 0) {
                    $this->weekdaySpecialHolidays = 1;
                }
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
        $this->savePayroll = true;
        $this->getPayroll();
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

                // Fetch approved leaves
                $approvedLeaves = $this->getApprovedLeaves($startDate, $endDate);
                $userLeaves = $approvedLeaves->groupBy('user_id');

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
                    $absentDays = $dtrData['total_absent'];
                    
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

                    // Process approved leaves
                    $leaveDaysWithPay = 0;
                    $leavePayment = 0;
                    $leaveDaysWithoutPay = 0;
                    $leaveDaysWithoutPayAmount = 0;
                    if (isset($userLeaves[$userId])) {
                        foreach ($userLeaves[$userId] as $leave) {
                            if ($leave->remarks === 'With Pay') {
                                $leaveDaysWithPay += $leave->approved_days;
                            } else {
                                $leaveDaysWithoutPay += $leave->approved_days;
                            }
                        }
                    }

                    $leavePayment = $leaveDaysWithPay * $dailySalaryRate;
                    $leaveDaysWithoutPayAmount = $leaveDaysWithoutPay * $dailySalaryRate;
                    
                    $grossSalary = $grossSalary + $regularHolidayPay + $specialHolidayPay;

                    $grossSalaryLess = 
                        $grossSalary - $lateUndertimeHoursAmount - 
                        $lateUndertimeMinsAmount - $absentAmount -
                        $leaveDaysWithoutPayAmount;

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
                            $netAmountDue = $grossSalaryLess - $newTotalDeduction + $payrollsAllRecord->personal_economic_relief_allowance;
                        }
                    }else{
                        $netAmountDue = $grossSalaryLess;
                    }

    
                    if($this->savePayroll){
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
                                'leave_days_withpay' => $leaveDaysWithPay,
                                'leave_payment' => $leavePayment,
                                'gross_salary' => $grossSalary,
                                'leave_days_withoutpay' => $leaveDaysWithoutPay,
                                'leave_days_withoutpay_amount' => $leaveDaysWithoutPayAmount,
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
                            ]
                        );
                    }else{
                        $payrolls->push([
                            'user_id' => $user->id,
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
                            'leave_days_withpay' => $leaveDaysWithPay,
                            'leave_payment' => $leavePayment,
                            'gross_salary' => $grossSalary,
                            'leave_days_withoutpay' => $leaveDaysWithoutPay,
                            'leave_days_withoutpay_amount' => $leaveDaysWithoutPayAmount,
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
                }

                if($this->savePayroll){
                    $this->dispatch('notify', [
                        'message' => 'Payroll Saved!',
                        'type' => 'success'
                    ]);
                    $this->savePayroll = null;
                    return;
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
                'message' => 'Fetching or Saving data was unsuccessful!',
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
                        'total_absent' => 0,
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
                if($record->remarks == "Late"){
                    $payrollDTR[$employeeId]['total_late'] += $late;
                }
                if($record->remarks == "Absent"){
                    $payrollDTR[$employeeId]['total_absent']++;
                }
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
                    'total_hours' => $totalHours,
                    'remarks' => $record->remarks,
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

    private function getApprovedLeaves($startDate, $endDate){
        return LeaveApplication::where('status', 'Approved')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('approved_start_date', [$startDate, $endDate])
                    ->orWhereBetween('approved_end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('approved_start_date', '<=', $startDate)
                        ->where('approved_end_date', '>=', $endDate);
                    });
            })
            ->get();
    }

    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function exportPayroll(){
        try {
            if ($this->startDate && $this->endDate) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);

                $filters = [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ];
                
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
                
                return Excel::download(new PayrollExport($payrolls, $filters), $filename);
            } else {
                $this->dispatch('notify', [
                    'message' => 'Select start and end date!',
                    'type' => 'info'
                ]);
            }
        } catch (Exception $e) {
            // $this->dispatch('notify', [
            //     'message' => 'Error exporting payroll: ' . $e->getMessage(),
            //     'type' => 'error'
            // ]);
            throw $e;
        }
    }

    public function exportPayslip($userId){
        try {
            $user = User::where('id', $userId)->first();
            if ($user) {
                $payslip = null;
            
                if ($this->hasPayroll) {
                    // If payroll exists in the database
                    $payslip = $this->employeePayslip->where('user_id', $userId)->first();
                } else {
                    // If payroll is generated on the fly
                    $payslip = collect($this->employeePayslip)->firstWhere('user_id', $userId);
                }

                if ($payslip) {
                    $pdf = Pdf::loadView('pdf.semi-monthly-payslip', ['payslip' => (object)$payslip]);
                    $pdf->setPaper([0, 0, 396, 612], 'portrait');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, $payslip['name'] . ' Payslip.pdf');
                } else {
                    throw new Exception('Payslip not found for the user.');
                }
            }
    
            $this->dispatch('notify', [
                'message' => 'Payslip exported!',
                'type' => 'success'
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Unable to export payslip: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}
