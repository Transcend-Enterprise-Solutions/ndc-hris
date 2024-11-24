<?php

namespace App\Livewire\Admin\PayrollComponent;

use App\Exports\PayrollExport;
use App\Models\CosRegPayslip;
use App\Models\EmployeesDtr;
use App\Models\Holiday;
use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class CosRegRecordedPayroll extends Component
{
    public $pageSize = 30; 
    public $pageSizes = [10, 20, 30, 50, 100]; 
    public $recordMonth;
    public $weekdayRegularHolidays = 0;
    public $weekdaySpecialHolidays = 0;
    public $delete;
    public $start_Date;
    public $end_Date;


    public function render()
    {
        $releasedPayrolls = CosRegPayslip::select('start_date', 'end_date')
                    ->when($this->recordMonth, function ($query) {
                        $query->whereMonth('start_date', Carbon::parse($this->recordMonth))
                        ->whereYear('start_date', Carbon::parse($this->recordMonth));
                    })
                    ->groupBy('start_date', 'end_date')
                    ->paginate($this->pageSize);

        return view('livewire.admin.payroll-component.cos-reg-recorded-payroll',[
            'releasedPayrolls' => $releasedPayrolls,
        ]);
    }

    public function exportExcel($startMonth, $endMonth){
        try {
            $startDate = Carbon::parse($startMonth);
            $endDate = Carbon::parse($endMonth);
            $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->where('signatories.signatory_type', 'cos_payroll')
                ->select('users.name', 'positions.position', 'office_divisions.office_division', 'signatories.*');

            $filters = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'signatories' => $signatories,
            ];
            
            $filename = 'COS Regular Payroll ' . $startDate->format('F') . ' '
                                    . $startDate->format('d') . '-'
                                    . $endDate->format('d') . ' '
                                    . $startDate->format('Y') . '.xlsx';
            
            $payrolls = $this->getPayroll($startDate, $endDate);

            if ($payrolls->isEmpty() || !$payrolls){
                $this->dispatch('swal', [
                    'title' => 'No exportable record/s!',
                    'icon' => 'error'
                ]);
                return;
            }
            
            return Excel::download(new PayrollExport($payrolls, $filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPayroll($startDate, $endDate){
        $payrolls = collect();
        try {
            $sDate = $startDate;
            $eDate = $endDate;

            if ($sDate && $eDate) {
                $payrollsAll = null;
                $payrollsAll = User::join('positions', 'positions.id', 'users.position_id')
                    ->join('user_data', 'user_data.user_id', 'users.id')
                    ->join('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->select('users.name',
                        'user_data.first_name',
                        'user_data.surname',
                        'user_data.middle_name',
                        'user_data.name_extension', 
                        'users.emp_code', 
                        'cos_reg_payrolls.*', 
                        'positions.position', 
                        'office_divisions.office_division')
                    ->orderBy('user_data.surname', 'ASC')
                    ->get();

                $payrollDTR = $this->getDTRForPayroll($sDate, $eDate);
                $startDate = Carbon::parse($sDate);
                $endDate = Carbon::parse($eDate);
                
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - Carbon::parse($startDate)->daysInMonth * 2 / 7;
                $totalWorkingDaysInMonth = round($totalWorkingDaysInMonth);

                // Fetch holidays between start and end date
                $holidays = $this->getHolidays($startDate, $endDate);

                // Fetch approved leaves
                // $userLeaves = null;
                // $approvedLeaves = $this->getApprovedLeaves($startDate, $endDate);
                // if($approvedLeaves){
                //     $userLeaves = $approvedLeaves->groupBy('user_id');
                // }

                // Calculate total working days (Monday to Friday, excluding holidays)
                $totalDays = 0;
                $currentDate = $startDate->copy();
                $this->weekdayRegularHolidays = 0;
                $this->weekdaySpecialHolidays = 0;
         
                // while ($currentDate <= $endDate) {
                //     if ($currentDate->isWeekday()) {
                //         $dateString = $currentDate->format('Y-m-d');
                //         if (!$holidays->has($dateString)) {
                //             $totalDays++;
                //         } else {
                //             // Check the type of holiday
                //             $holidayType = $holidays->get($dateString);
                //             if ($holidayType === 'Special') {
                //                 $totalDays++;
                //                 $this->weekdaySpecialHolidays++;
                //             } else {
                //                 $this->weekdayRegularHolidays++;
                //             }
                //         }
                //     }
                //     $currentDate->addDay();
                // }

                $totalDeductions = 0;
                $withholdingTax = 0;
                $deductionBalance = 0;
                $nycempc = 0;
                $netAmountDue = 0;

                foreach ($payrollsAll as $payrollRecord) {
                    $userId = $payrollRecord->user_id;
                    $user = User::where('users.id', $userId)
                            ->join('user_data', 'user_data.user_id', 'users.id')
                            ->select('user_data.sex', 'user_data.civil_status', 'users.id')
                            ->first();

        
                    $dtrData = $payrollDTR[$userId] ?? null;
    
                    if (!$dtrData) {
                        continue;
                    }

                    $ratePerMonth = ($payrollRecord->rate_per_month * 0.20) + $payrollRecord->rate_per_month;

                    $dailySalaryRate = $ratePerMonth / 22;

                    //Get total number of covered days
                    $totalDays = $dtrData['total_days'];

                    // Get the count of absences and its amount
                    $absentDays = $dtrData['total_absent'];
                    $absentAmount = $absentDays * $dailySalaryRate;

                    $totalHoursRendered = $dtrData['total_hours'] / 60;
                    $totalDaysRendered = $totalHoursRendered / 8; 
                    
       
                    $grossSalary = $dailySalaryRate * $totalDays;
                    


                    $lateUndertimeHours = floor($dtrData['total_late'] / 60);
                    $lateUndertimeMins = $dtrData['total_late'] % 60;
                    $lateUndertimeHoursAmount = $lateUndertimeHours * ($dailySalaryRate / 8);
                    $lateUndertimeMinsAmount = $lateUndertimeMins * ($dailySalaryRate / 480);

                    $hourlyRate = $dailySalaryRate / 8;
                    $totalHoursRendered = 0;

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
                                $regularHolidayCount++;
                            } elseif ($holidayType === 'Special') {
                                $specialHolidayCount++;
                            }
                        }

                        if ($record) {
                            $totalHoursRendered += $record['total_hours'];
                        }

                        $currentDate->addDay();
                    }

                    // Deducted Salary
                    $grossSalaryLess = $grossSalary - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount - $absentAmount;


                    $withholdingTax = $payrollRecord->withholding_tax;
                    $nycempc = $payrollRecord->nycempc;
                    $adjustment = $payrollRecord->adjustment;
                    $otherDeductions = $payrollRecord->other_deductions;
                    $additionalPremiums = $payrollRecord->additional_premiums;

                    // Calculate remaining deductions
                    $totalDeductions = $withholdingTax + $nycempc + $adjustment + $otherDeductions + $additionalPremiums;

                    if($grossSalaryLess < $totalDeductions) {
                        $deductionBalance = $totalDeductions - $grossSalaryLess;
                        $netAmountDue = 0;
                    } else {
                        $netAmountDue = $grossSalaryLess - $totalDeductions;
                    }

                    $payrolls->push([
                        'user_id' => $user->id,
                        'name' => $payrollRecord->surname . ", " . $payrollRecord->first_name . " " . $payrollRecord->middle_name ?: ''  . " " . $payrollRecord->name_extension ?: '',
                        'sex' => $user->sex,
                        'civil_status' => $user->civil_status,
                        'employee_number' => $payrollRecord->emp_code,
                        'position' => $payrollRecord->position,
                        'office_division' => $payrollRecord->office_division,
                        'salary_grade' => $payrollRecord->sg_step,
                        'daily_salary_rate' => $dailySalaryRate,
                        'rate_per_month' => $payrollRecord->rate_per_month,
                        'additional_premiums' => $payrollRecord->additional_premiums,
                        'no_of_days_covered' => $totalDays,
                        'gross_salary' => $grossSalary,
                        'absences_days' => $absentDays,
                        'absences_amount' => $absentAmount,
                        'late_undertime_hours' => $lateUndertimeHours,
                        'late_undertime_hours_amount' => $lateUndertimeHoursAmount,
                        'late_undertime_mins' => $lateUndertimeMins,
                        'late_undertime_mins_amount' => $lateUndertimeMinsAmount,
                        'gross_salary_less' => $grossSalaryLess,
                        'adjustment' => $adjustment,
                        'withholding_tax' => $withholdingTax,
                        'nycempc' => $nycempc,
                        'other_deductions' => $otherDeductions,
                        'total_deductions' => $totalDeductions,
                        'net_amount_due' => $netAmountDue,
                        'start_date' => $sDate,
                        'end_date' => $eDate,
                    ]);
                }

                return $payrolls;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getDTRForPayroll($startDate, $endDate){
        try {
            $query = EmployeesDtr::whereBetween('date', [$startDate, $endDate]);
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

                
                // Convert time strings to integer minutes
                $totalHours = $this->timeToMinutes($record->total_hours_rendered);
                $late = $this->timeToMinutes($record->late);
                $overtime = $this->timeToMinutes($record->overtime);

                $payrollDTR[$employeeId]['total_hours'] += $totalHours;

                if($record->remarks == "Late/Undertime"){
                    $payrollDTR[$employeeId]['total_late'] += $late;
                }
                
                if($record->remarks == "Absent" && $record->up_remarks == null){
                    $payrollDTR[$employeeId]['total_absent']++;
                }

                // Get No. of Days Covered
                if($record->remarks == "Present" || ($record->remarks == "Absent" && $record->up_remarks != "Holiday" && $record->up_remarks != null) || $record->remarks == "Incomplete" || $record->remarks == "Late/Undertime"){
                    $payrollDTR[$employeeId]['total_days']++;
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
            $dateString = $holiday->holiday_date->format('Y-m-d');
            return [$dateString => $holiday->type];
        });
    }

    public function toggleDelete($startDate, $endDate){
        $this->delete = true;
        $this->start_Date = $startDate;
        $this->end_Date = $endDate;
    }

    public function deletePayroll(){
        try{
            $payslips = CosRegPayslip::where('start_date', Carbon::parse($this->start_Date))
                                    ->where('end_date', Carbon::parse($this->end_Date))
                                    ->get();
            if($payslips){
                $payslips->each->delete();

                $this->resetVariables();
                $this->dispatch('swal', [
                    'title' => "Payroll deleted successfully",
                    'icon' => 'success'
                ]);
            }else{
                $this->resetVariables();
                $this->dispatch('swal', [
                    'title' => "Payroll deletion was unsuccessful",
                    'icon' => 'error'
                ]);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->delete = null;
    }
}
