<?php

namespace App\Livewire\Admin;

use App\Exports\CosPayrollListExport;
use App\Exports\IndivCosPayrollExport;
use App\Exports\PayrollExport;
use App\Models\CosRegPayrolls;
use App\Models\CosSkPayrolls;
use App\Models\CosSkPayslip;
use App\Models\CosSkSemiMonthlyPayrolls;
use App\Models\EmployeesDtr;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\OfficeDivisions;
use App\Models\Payrolls;
use App\Models\Positions;
use App\Models\SalaryGrade;
use App\Models\Signatories;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class CosSkPayrollTable  extends Component
{
    use WithPagination, WithFileUploads;
    public $sortColumn = false;
    public $startDate;
    public $endDate;
    public $hasPayroll = true;
    public $search;
    public $search2;
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
        'adjustment',
        'withholding_tax',
        'nycempc',
        'other_deductions',
        'total_deduction',
        'net_amount_due',
    ];

    public $cosColumns = [
        'name' => true,
        'employee_number' => true,
        'position' => true,
        'office_division' => true,
        'sg_step' => true,
        'rate_per_month' => true,
        'additional_premiums' => true,
        'adjustment' => true,
        'withholding_tax' => true,
        'nycempc' => true,
        'other_deductions' => true,
        'total_deduction' => true,
    ];

    public $view;
    public $userId;
    public $name;
    public $employee_number;
    public $sg_step;
    public $office_division;
    public $position;
    public $sg;
    public $step = 1;
    public $rate_per_month;
    public $daily_salary_rate;
    public $no_of_days_covered;
    public $gross_salary;
    public $absences_days;
    public $absences_amount;
    public $late_undertime_hours;
    public $late_undertime_hours_amount;
    public $late_undertime_mins;
    public $late_undertime_mins_amount;
    public $gross_salary_less;
    public $w_holding_tax;
    public $nycempc;
    public $total_deduction;
    public $net_amount_due;
    public $weekdayRegularHolidays = 0;
    public $weekdaySpecialHolidays = 0;
    protected $savePayroll;
    public $employeePayslip;

    public $addCosPayroll;
    public $editCosPayroll;
    public $addSignatory;
    public $editSignatory;
    public $addPayslipSignatory;
    public $editPayslipSignatory;
    public $signatory;
    public $empPayrolled;
    public $signatoryFor;
    public $signatures = [];
    public $preparedBySign;
    public $employees;
    public $salaryGrade;
    public $deleteId;
    public $deleteMessage;
    public $additional_premiums;
    public $adjustment;
    public $withholding_tax;
    public $other_deductions;
    public $unpayrolledEmployees;
    public $canExportPayslip = false;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function mount(){
        $this->unpayrolledEmployees = User::where('user_role', '=', 'emp')
            ->leftJoin('payrolls', 'payrolls.user_id', '=', 'users.id')
            ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', '=', 'users.id')
            ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', '=', 'users.id')
            ->whereNull('payrolls.id')
            ->whereNull('cos_sk_payrolls.id')
            ->whereNull('cos_reg_payrolls.id')
            ->select('users.*')
            ->get();

        $this->employees = User::where('users.user_role', '=', 'emp')->get();
        $this->salaryGrade = SalaryGrade::all();
    }

    public function render(){
        $users = User::paginate(10);
        $payrolls = collect();
        if ($this->startDate && $this->endDate) {
            $query = CosSkPayslip::where('cos_sk_payslip.start_date', $this->startDate)
                ->join('users', 'users.id', 'cos_sk_payslip.user_id')
                ->where('cos_sk_payslip.end_date', $this->endDate)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                });
    
            if ($query->exists()) {
                $this->hasPayroll = true;
            } else {
                $this->hasPayroll = false;
            }
            $payrolls = $this->getPayroll();

            $carbonStartDate = Carbon::parse($this->startDate);
            $carbonEndDate = Carbon::parse($this->endDate);
            $isStartDate16 = $carbonStartDate->day === 16;
            $isEndDateEndOfMonth = $carbonEndDate->isLastOfMonth();
            $this->canExportPayslip = $isStartDate16 && $isEndDateEndOfMonth;
        }


        $cosPayrolls = User::when($this->search2, function ($query) {
                    return $query->search(trim($this->search2));
                })
                ->join('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('user_data.*', 'users.name', 'users.emp_code as employee_number', 'cos_sk_payrolls.*', 'positions.*', 'office_divisions.*')
                ->paginate($this->pageSize);

        if($this->userId){
            $user = User::where('id', $this->userId)->first();
            $pos = Positions::where('id', $user->position_id)->first();
            $officeDiv = OfficeDivisions::where('id', $user->office_division_id)->first();
            $this->employee_number = $user->emp_code;
            $this->position = $pos->position;
            $this->office_division = $officeDiv->office_division;
        }

        $this->getRate();


        $cosPayrollSignatories = User::join('signatories', 'signatories.user_id', 'users.id')
            ->join('positions', 'positions.id', 'users.position_id')
            ->where('signatories.signatory_type', 'cos_payroll')
            ->select('users.name', 'positions.*', 'signatories.*')
            ->get();
        $aCos = $cosPayrollSignatories->where('signatory', 'A')->first();
        $bCos = $cosPayrollSignatories->where('signatory', 'B')->first();
        $cCos = $cosPayrollSignatories->where('signatory', 'C')->first();
        $dCos = $cosPayrollSignatories->where('signatory', 'D')->first();
        $cosPayroll = [
            'a' => $aCos,
            'b' => $bCos,
            'c' => $cCos,
            'd' => $dCos,
        ];

        $user = Auth::user();
        $preparedBy = User::where('users.id', $user->id)
            ->join('positions', 'positions.id', 'users.position_id')
            ->first();
        $preparedBySignature = Signatories::where('user_id', $user->id)->first();

        $cosPayslipSignatories = User::join('signatories', 'signatories.user_id', 'users.id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('signatories.signatory_type', 'cos_payslip')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->get();
        $cosNotedBy = $cosPayslipSignatories->where('signatory', 'Noted By')->first();
        $cosPayslipSigns = [
            'notedBy' => $cosNotedBy,
        ];

        if($this->signatures) {
            foreach ($this->signatures as $id => $signature) {
                if ($signature) {
                    $this->saveSignature($id);
                }
            }
        }

        if($this->preparedBySign){
            $this->saveSignature($user->id);
        }

        if($this->sg){
        }

        $this->total_deduction = 
            ($this->additional_premiums ?: 0) +
            ($this->adjustment ?: 0) +
            ($this->nycempc ?: 0) +
            ($this->withholding_tax ?: 0) +
            ($this->other_deductions ?: 0);

        return view('livewire.admin.cos-sk-payroll-table', [
            'users' => $users,
            'payrolls' => $payrolls,
            'cosPayrolls' => $cosPayrolls,
            'cosPayslipSigns' => $cosPayslipSigns,
            'cosPayroll' => $cosPayroll,
            'preparedBy' => $preparedBy,
            'preparedBySignature' => $preparedBySignature,
        ]);
    }

    public function getRate(){
        if ($this->sg && $this->step) {
            $salaryGrades = SalaryGrade::where('salary_grade', $this->sg);
            switch ($this->step) {
                case 1:
                    $salaryGrade = $salaryGrades->select('step1 as step')->first();
                    break;
                case 2:
                    $salaryGrade = $salaryGrades->select('step2 as step')->first();
                    break;
                case 3:
                    $salaryGrade = $salaryGrades->select('step3 as step')->first();
                    break;
                case 4:
                    $salaryGrade = $salaryGrades->select('step4 as step')->first();
                    break;
                case 5:
                    $salaryGrade = $salaryGrades->select('step5 as step')->first();
                    break;
                case 6:
                    $salaryGrade = $salaryGrades->select('step6 as step')->first();
                    break;
                case 7:
                    $salaryGrade = $salaryGrades->select('step7 as step')->first();
                    break;
                case 8:
                    $salaryGrade = $salaryGrades->select('step8 as step')->first();
                    break;
                default:
                    $salaryGrade = null;
                    break;
            }
            if ($salaryGrade) {
                $this->rate_per_month = $salaryGrade->step;
            } else {
                $this->rate_per_month = 0;
            }
        }
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

    public function getPayroll($employeeId = null, $payslip = null){
        $payrolls = collect();
        try {
            $sDate = $this->startDate;
            $eDate = $this->endDate;

            if($payslip){
                $carbonDate = Carbon::parse($this->startDate);
                $sDate = $carbonDate->startOfMonth()->toDateString();
                $eDate = $carbonDate->copy()->day(15)->toDateString();
            }

            if ($sDate && $eDate) {
                $payrollsAll = null;
                if ($employeeId) {
                    $payrollsAll =User::join('positions', 'positions.id', 'users.position_id')
                        ->join('user_data', 'user_data.user_id', 'users.id')
                        ->join('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                        ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                        ->select('users.name', 
                            'user_data.first_name',
                            'user_data.surname',
                            'user_data.middle_name',
                            'user_data.name_extension',
                            'users.emp_code', 
                            'cos_sk_payrolls.*', 
                            'positions.position', 
                            'office_divisions.office_division')
                        ->where('users.id', $employeeId)
                        ->orderBy('user_data.surname', 'ASC')
                        ->get();
                }else{
                    $payrollsAll = User::join('positions', 'positions.id', 'users.position_id')
                        ->join('user_data', 'user_data.user_id', 'users.id')
                        ->join('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                        ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                        ->select('users.name', 
                            'user_data.first_name',
                            'user_data.surname',
                            'user_data.middle_name',
                            'user_data.name_extension',
                            'users.emp_code', 
                            'cos_sk_payrolls.*', 
                            'positions.position', 
                            'office_divisions.office_division')
                        ->orderBy('user_data.surname', 'ASC')
                        ->get();
                }
                $payrollDTR = $this->getDTRForPayroll($sDate, $eDate);
                $startDate = Carbon::parse($sDate);
                $endDate = Carbon::parse($eDate);
                
                $totalWorkingDaysInMonth = Carbon::parse($startDate)->daysInMonth - 
                    Carbon::parse($startDate)->daysInMonth * 2 / 7;
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

                $totalDeductions = 0;
                $withholdingTax = 0;
                $deductionBalance = 0;
                $nycempc = 0;
                $netAmountDue = 0;

                $admin = Auth::user();
                $preparedBy = User::where('users.id', $admin->id)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('signatories', 'signatories.user_id', 'users.id')
                    ->first();

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

                    // Get the count of absences and its amount
                    $absentDays = $dtrData['total_absent'];
                    $absentAmount = $absentDays * $dailySalaryRate;

                    // Get the count of no-work no-pay days and its amount
                    $noWorkNoPayDays = $dtrData['no_work'];
                    $noWorkNoPayAmount = $noWorkNoPayDays * $dailySalaryRate;

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
                    $grossSalaryLess = $grossSalary - $lateUndertimeHoursAmount - $lateUndertimeMinsAmount - $noWorkNoPayAmount;


                    $withholdingTax = $payrollRecord->withholding_tax;
                    $nycempc = $payrollRecord->nycempc;
                    $adjustment = $payrollRecord->adjustment;
                    $otherDeductions = $payrollRecord->other_deductions;
                    $additionalPremiums = $payrollRecord->additional_premiums;


                    // Calculate remaining deductions
                    $totalDeductions = $withholdingTax + $nycempc;

                    if($grossSalaryLess < $totalDeductions) {
                        $deductionBalance = $totalDeductions - $grossSalaryLess;
                        $netAmountDue = 0;
                    } else {
                        $netAmountDue = $grossSalaryLess - $totalDeductions;
                    }

    
                    if($this->savePayroll){
                        CosSkPayslip::create([
                            'user_id' => $user->id,
                            'salary_grade' => $payrollRecord->sg_step,
                            'rate_per_month' => $payrollRecord->rate_per_month,
                            'days_covered' => $totalDays,
                            'gross_salary' => $grossSalary,
                            'absences_days' => $absentDays,
                            'absences_amount' => $absentAmount,
                            'late_undertime_hours' => $lateUndertimeHours,
                            'late_undertime_hours_amount' => $lateUndertimeHoursAmount,
                            'late_undertime_minutes' => $lateUndertimeMins,
                            'late_undertime_minutes_amount' => $lateUndertimeMinsAmount,
                            'gross_salary_less' => $grossSalaryLess,
                            'additional_premiums' => $additionalPremiums,
                            'adjustment' => $adjustment,
                            'w_holding_tax' => $withholdingTax,
                            'nycempc' => $nycempc,
                            'other_deductions' => $otherDeductions,
                            'total_deduction' => $totalDeductions,
                            'net_amount_received' => $netAmountDue,
                            'start_date' => $sDate,
                            'end_date' => $eDate,
                            'prepared_by_name' => $preparedBy->name,
                            'prepared_by_position' => $preparedBy->position,
                        ]);
                    }else{
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
                }

                if($this->savePayroll){
                    $this->dispatch('swal', [
                        'title' => 'Payroll Saved!',
                        'icon' => 'success'
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
            $this->dispatch('swal', [
                'title' => 'Fetching or Saving data was unsuccessful!',
                'icon' => 'error'
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
                        'no_work' => 0,
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

                if($record->remarks == "Late/Undertime"){
                    $payrollDTR[$employeeId]['total_late'] += $late;
                }

                if($record->remarks == "Absent" && $record->up_remarks == null){
                    $payrollDTR[$employeeId]['total_absent']++;
                }

                if($record->remarks == "Absent" && ($record->up_remarks == "Holiday" || $record->up_remarks == "Leave")){
                    $payrollDTR[$employeeId]['no_work']++;
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

    public function exportPayslip($userId){
        try {
            $user = User::where('id', $userId)->first();
            if ($user) {
                $admin = Auth::user();
                $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('signatories.signatory_type', 'cos_payslip')
                        ->where('signatories.signatory', 'Noted By')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->first();
                    
                $payslip = $this->getPayroll($userId, true)->first();
                $payslip2 = $this->getPayroll($userId)->first();

                $dates = [
                    'startDate' => $this->startDate,
                    'endDate' => $this->endDate,
                ];

                $carbonDate = Carbon::parse($this->startDate);
                $payslipFor = $carbonDate->format('F') . " 1 - 15 " . $carbonDate->format('Y');
                $payslipFor2 = $carbonDate->format('F') . " 16 - " . $carbonDate->endOfMonth()->format('d') . " " . $carbonDate->format('Y');
                $monthPaylipFor = $carbonDate->format('F') . " 1 - " . $carbonDate->endOfMonth()->format('d') . " " . $carbonDate->format('Y');


                $preparedBy = User::where('users.id', $admin->id)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('signatories', 'signatories.user_id', 'users.id')
                    ->first();

                if(!$signatories){
                    $this->dispatch('swal', [
                        'title' => 'Please set the payslip signatory for "Noted By"',
                        'icon' => 'error'
                    ]);
                    return;
                }
        
        
                // Generate temporary paths for signatures
                $preparedBySignaturePath = $preparedBy ? $this->getTemporarySignaturePath($preparedBy) : null;
                $signatoriesSignaturePath = $signatories ? $this->getTemporarySignaturePath($signatories) : null;

                if ($payslip) {
                    $pdf = Pdf::loadView('pdf.cos-semi-monthly-payslip', [
                        'preparedBy' => $preparedBy,
                        'payslip' => $payslip,
                        'payslip2' => $payslip2,
                        'payslipFor' => $payslipFor,
                        'payslipFor2' => $payslipFor2,
                        'monthPaylipFor' => $monthPaylipFor,
                        'signatories' => $signatories,
                        'preparedBySignaturePath' => $preparedBySignaturePath,
                        'signatoriesSignaturePath' => $signatoriesSignaturePath,
                    ]);
                    $pdf->setPaper('A4', 'portrait');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, $payslip['name'] . ' ' . $monthPaylipFor . ' Payslip.pdf');
                } else {
                    throw new Exception('Payslip not found for the user.');
                }
            }
    
            $this->dispatch('swal', [
                'title' => 'Payslip exported!',
                'icon' => 'success'
            ]);
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Unable to export payslip: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
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
        $leaves = LeaveApplication::where('status', 'Approved')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('approved_start_date', [$startDate, $endDate])
                        ->orWhereBetween('approved_end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('approved_start_date', '<=', $startDate)
                            ->where('approved_end_date', '>=', $endDate);
                        });
                })
                ->get();
        if($leaves){
            return $leaves;
        }
        return null;
    }

    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function exportPayroll(){
        try {
            if ($this->startDate && $this->endDate) {
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
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
                
                $filename = 'COS SK Payroll ' . $startDate->format('F') . ' '
                                       . $startDate->format('d') . '-'
                                       . $endDate->format('d') . ' '
                                       . $startDate->format('Y') . '.xlsx';
                
                $payrolls = $this->getPayroll();

                if ($payrolls->isEmpty() || !$payrolls){
                    $this->dispatch('swal', [
                        'title' => 'No exportable record/s!',
                        'icon' => 'error'
                    ]);
                    return;
                }
                
                return Excel::download(new PayrollExport($payrolls, $filters), $filename);
            } else {
                $this->dispatch('swal', [
                    'title' => 'Select start and end date!',
                    'icon' => 'error'
                ]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleEditCosPayroll($userId){
        $this->editCosPayroll = true;
        $this->userId = $userId;
        try {
            $payroll = CosSkPayrolls::where('cos_sk_payrolls.user_id', $userId)
                    ->join('users', 'users.id', 'cos_sk_payrolls.user_id')
                    ->first();
            $sg = explode('-', $payroll->sg_step);
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->employee_number;
                $this->office_division = $payroll->office_division;
                $this->position = $payroll->position;
                $this->sg = $payroll->sg_step;
                $this->rate_per_month = $payroll->rate_per_month;
            } else {
                $this->resetVariables();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddCosPayroll(){
        $this->editCosPayroll = true;
        $this->addCosPayroll = true;
    }
    
    public function saveCosPayroll(){
        try {
            $payroll = CosSkPayrolls::where('user_id', $this->userId)->first();
            $user = User::where('id', $this->userId)->first();
            $sg_step = implode('-', [$this->sg, $this->step]);
            $message = null;
            $icon = null;
            $plantilla = Payrolls::where('user_id', $user->id)->first();
            $cosReg = CosRegPayrolls::where('user_id', $user->id)->first();
            if(!$plantilla && !$cosReg){
                $payrollData = [
                    'user_id' => $this->userId,
                    'sg_step' => $this->sg,
                    'rate_per_month' => $this->rate_per_month,
                    'additional_premiums' => $this->additional_premiums,
                    'adjustment' => $this->adjustment,
                    'withholding_tax' => $this->withholding_tax,
                    'nycempc' => $this->nycempc,
                    'other_deductions' => $this->other_deductions,
                    'total_deduction' => $this->total_deduction,
                ];
        
                if ($payroll) {
                    $this->validate([
                        'employee_number' => 'required|max:100',
                        'office_division' => 'required|max:100',
                        'position' => 'required|max:100',
                        'sg' => 'required|numeric',
                        'step' => 'required|numeric',
                        'rate_per_month' => 'required|numeric',
                    ]);
    
                    $payroll->update($payrollData);
                    $message = "COS Payroll updated successfully!";
                    $icon = "success";
                } else {
                    $this->validate([
                        'employee_number' => 'required|max:100',
                        'office_division' => 'required|max:100',
                        'position' => 'required|max:100',
                        'sg' => 'required|numeric',
                        'step' => 'required|numeric',
                        'rate_per_month' => 'required|numeric',
                    ]);
                    CosSkPayrolls::create($payrollData);
                    $message = "COS Payroll added successfully!";
                    $icon = "success";
                }
            }else{
                $message = "This employee already has a Plantilla/COS Regular payroll!";
                $icon = "error";
            }
      
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => $icon
            ]);
    
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleEditSignatory($userId, $type){
        $this->editSignatory = true;
        $this->userId = $userId;
        $this->signatoryFor = $type;
        try {
            $user = User::join('signatories', 'signatories.user_id', 'users.id')
                    ->where('users.id', $userId)
                    ->where('signatories.signatory_type', $type)
                    ->first();
            if ($user) {
                $this->name = $user->name;
                $this->signatory = $user->signatory;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddSignatory($payroll, $signatory){
        $this->editSignatory= true;
        $this->addSignatory= true;
        $this->signatoryFor = $payroll;
        $this->signatory = $signatory;
    }

    public function toggleEditPayslipSignatory($userId, $type){
        $this->editPayslipSignatory = true;
        $this->userId = $userId;
        $this->signatoryFor = $type;
        try {
            $user = User::join('signatories', 'signatories.user_id', 'users.id')
                    ->where('users.id', $userId)
                    ->where('signatories.signatory_type', $this->signatoryFor)
                    ->first();
            if ($user) {
                $this->name = $user->name;
                $this->signatory = $user->signatory;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddPayslipSignatory($payslip, $type){
        $this->editPayslipSignatory= true;
        $this->addPayslipSignatory= true;
        $this->signatoryFor = $type;
        $this->signatory = $payslip;
    }

    public function saveSignatory(){
        try {
            $message = "";
            if(!$this->addSignatory){
                $signatory = Signatories::where('signatory', $this->signatory)
                    ->where('signatory_type', $this->signatoryFor)->first();
                $signatory->update([
                    'user_id' => $this->userId,
                ]);
                $message = "Payroll signatory updated successfully!";
            }else{
                $this->validate([
                    'signatory' => 'required',
                    'userId' => 'required',
                ]);

                $signatoryType = $this->signatoryFor;

                Signatories::create([
                    'user_id' => $this->userId,
                    'signatory' => $this->signatory,
                    'signatory_type' => $signatoryType,
                ]);
                $message = "Payroll signatory added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Payroll signatory update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function savePayslipSignatory(){
        try {
            $signatory = Signatories::where('user_id', $this->userId)
                        ->where('signatory_type', $this->signatoryFor)
                        ->first();
            $message = "";
            if(!$this->addPayslipSignatory){
                if($this->signatory == "X"){
                    $signatory->delete();
                }else{
                    $this->validate([
                        'signatory' => 'required',
                        'userId' => 'required',
                    ]);

                    $signatory->update([
                        'signatory' => $this->signatory,
                    ]);
                }
                $message = "Payslip signatory updated successfully!";
            }else{
                $this->validate([
                    'signatory' => 'required',
                    'userId' => 'required',
                ]);

                Signatories::create([
                    'user_id' => $this->userId,
                    'signatory' => $this->signatory,
                    'signatory_type' => $this->signatoryFor,
                ]);
                $message = "Payslip signatory added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Payslip signatory update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function saveSignature($id){
        try {
            $message = "";
            $signatory = Signatories::findOrFail($id);
            if ($this->preparedBySign){
                $originalFilename = $this->preparedBySign->getClientOriginalName();
                $uniqueFilename = time() . '_' . $originalFilename;
                $user = Auth::user();
                $currentSign = Signatories::where('user_id', $user->id)->first();
                $pathToDelete = "";
                if($currentSign){
                    $pathToDelete = str_replace('public/', '', $currentSign->signature);
                }
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
                $filePath = $this->preparedBySign->storeAs('signatures', $uniqueFilename, 'public');
                Signatories::updateOrCreate(
                    ['user_id' => $id],[
                    'signature' =>  'public/' . $filePath,
                ]);
                $message = "Signature saved successfully!";
            }else{
                if (isset($this->signatures[$id])) {
                    $file = $this->signatures[$id];
                    $currentSign = Signatories::where('id', $id)->first();
                    $pathToDelete = "";
                    if($currentSign){
                        $pathToDelete = str_replace('public/', '', $currentSign->signature);
                    }
                    if (Storage::disk('public')->exists($pathToDelete)) {
                        Storage::disk('public')->delete($pathToDelete);
                    }

                    $originalFilename = $file->getClientOriginalName();
                    $uniqueFilename = time() . '_' . $originalFilename;
                    $filePath = $file->storeAs('signatures', $uniqueFilename, 'public');
                    $signatory->signature = 'public/' . $filePath;
                    $signatory->save();
                    
                    unset($this->signatures[$id]);
                    $message = "Signature saved successfully!";
                }
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => "success",
            ]);
        } catch (Exception $e) {
           throw $e;
        }
    }

    public function exportCosExcel(){
        $filters = [
            'search' => $this->search2,
            'type' => 'SK',
        ];
        $fileName = 'COS SK Payroll List.xlsx';
        return Excel::download(new CosPayrollListExport($filters), $fileName);
    }

    public function toggleCosDelete($userId){
        $this->deleteMessage = "payroll";
        $this->deleteId = $userId;
    }

    public function viewPayroll($id){
        try{
            $this->view = true;
            $payrolls = $this->getPayroll($id);
            $payroll = $payrolls[0];
            $this->hasPayroll = false;
            $this->employeePayslip = $payrolls;
            $this->userId = $payroll['user_id'];
            $this->name = $payroll['name'];
            $this->employee_number = $payroll['employee_number'];
            $this->sg_step = $payroll['salary_grade'];
            $this->office_division = $payroll['office_division'];
            $this->position = $payroll['position'];
            $this->daily_salary_rate = $payroll['daily_salary_rate'];
            $this->no_of_days_covered = $payroll['no_of_days_covered'];
            $this->gross_salary = $payroll['gross_salary'];
            $this->absences_days = $payroll['absences_days'];
            $this->absences_amount = $payroll['absences_amount'];
            $this->late_undertime_hours = $payroll['late_undertime_hours'];
            $this->late_undertime_hours_amount = $payroll['late_undertime_hours_amount'];
            $this->late_undertime_mins = $payroll['late_undertime_mins'];
            $this->late_undertime_mins_amount = $payroll['late_undertime_mins_amount'];
            $this->gross_salary_less = $payroll['gross_salary_less'];
            $this->w_holding_tax = $payroll['withholding_tax'];
            $this->nycempc = $payroll['nycempc'];
            $this->total_deduction = $payroll['total_deductions'];
            $this->net_amount_due = $payroll['net_amount_due'];
        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportIndivPayroll($id){
        try{
            $admin = Auth::user();
            $notedBy = User::join('signatories', 'signatories.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->where('signatories.signatory_type', 'cos_payslip')
                ->where('signatories.signatory', 'Noted By')
                ->select('users.name', 'positions.position', 'signatories.*')
                ->first();
            $preparedBy = User::where('users.id', $admin->id)
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('signatories', 'signatories.user_id', 'users.id')
                ->select('users.name', 'positions.position', 'signatories.*')
                ->first();

            $payroll = $this->getPayroll($id);

            $filters = [
                'payroll' => $payroll,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'preparedBy' => $preparedBy,
                'notedBy' => $notedBy,
            ];

            $fileName = 'COS SK Individual Payroll.xlsx';
            return Excel::download(new IndivCosPayrollExport($filters), $fileName);
        }catch(Exception $e){
            throw $e;
        }
    }

    private function getTemporarySignaturePath($signatory){
        if ($signatory->signature) {
            $path = str_replace('public/', '', $signatory->signature);
            $originalPath = Storage::disk('public')->get($path);
            $filename = str_replace('public/signatures/', '', $signatory->signature);
            $tempPath = public_path('temp/' . $filename);
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            file_put_contents($tempPath, $originalPath);
            
            return $tempPath;
        }
        return null;
    }

    public function deleteData(){
        try {
            $user = User::where('id', $this->deleteId)->first();
            if ($user) {
                $user->cosSkPayrolls()->delete();
                $message = "COS payroll deleted successfully!";

                $this->resetVariables();
                $this->dispatch('swal', [
                    'title' => $message,
                    'icon' => 'success'
                ]);            
            }
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Payroll deletion was unsuccessful!",
                'icon' => 'error'
            ]);
            $this->resetVariables();
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->addCosPayroll = null;
        $this->editCosPayroll = null;
        $this->name = null;
        $this->employee_number = null;
        $this->office_division = null;
        $this->position = null;
        $this->sg = null;
        $this->rate_per_month = null;
        $this->deleteId = null;
        $this->deleteMessage = null;
        $this->addSignatory = null;
        $this->editSignatory = null;
        $this->addPayslipSignatory = null;
        $this->editPayslipSignatory = null;
        $this->signatoryFor = null;
        $this->signatory = null;
        $this->signatures = [];
        $this->preparedBySign = null;
        $this->view = null;
    }
}
