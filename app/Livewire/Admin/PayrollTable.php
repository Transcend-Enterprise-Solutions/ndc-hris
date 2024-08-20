<?php

namespace App\Livewire\Admin;

use App\Exports\CosPayrollListExport;
use App\Exports\PayrollExport;
use App\Models\CosPayrolls;
use App\Models\CosRegSemiMonthlyPayrolls;
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

class PayrollTable extends Component
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
        'withholding_tax',
        'nycempc',
        'total_deductions',
        'net_amount_due',
    ];

    public $cosColumns = [
        'name' => true,
        'employee_number' => true,
        'position' => true,
        'office_division' => true,
        'sg_step' => true,
        'rate_per_month' => true,
    ];

    public $userId;
    public $name;
    public $employee_number;
    public $office_division;
    public $position;
    public $sg;
    public $step;
    public $rate_per_month;
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

    public function mount(){
        $this->employees = User::where('user_role', '=', 'emp')->get();
        $this->salaryGrade = SalaryGrade::all();
    }

    public function render(){
        $users = User::paginate(10);
        $payrolls = collect();
        if ($this->startDate && $this->endDate) {
            $query = CosRegSemiMonthlyPayrolls::where('start_date', $this->startDate)
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


        $cosPayrolls = User::when($this->search2, function ($query) {
                    return $query->search(trim($this->search2));
                })
                ->join('cos_payrolls', 'cos_payrolls.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.name', 'users.emp_code as employee_number', 'cos_payrolls.*', 'positions.*', 'office_divisions.*')
                ->paginate(5);

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

        return view('livewire.admin.payroll-table', [
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

    public function getPayroll(){
        $payrolls = collect();
        try {
            if ($this->startDate && $this->endDate) {
                $payrollsAll = User::join('positions', 'positions.id', 'users.position_id')
                            ->join('cos_payrolls', 'cos_payrolls.user_id', 'users.id')
                            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                            ->select('users.name', 
                                'users.emp_code', 
                                'cos_payrolls.*', 
                                'positions.position', 
                                'office_divisions.office_division')
                            ->get();

                $payrollDTR = $this->getDTRForPayroll($this->startDate, $this->endDate);
                $startDate = Carbon::parse($this->startDate);
                $endDate = Carbon::parse($this->endDate);
                
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

                foreach ($payrollsAll as $payrollRecord) {
                    $userId = $payrollRecord->user_id;
                    $user = User::where('users.id', $userId)->first();
        
                    $dtrData = $payrollDTR[$userId] ?? null;
    
                    if (!$dtrData) {
                        continue;
                    }

                    $dailySalaryRate = $payrollRecord->rate_per_month / $totalWorkingDaysInMonth;

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


                    $withholdingTax = $payrollRecord->w_holding_tax;
                    $nycempc = $payrollRecord->nycempc;

                    // Calculate remaining deductions
                    $totalDeductions = $withholdingTax + $nycempc;

                    if($grossSalaryLess < $totalDeductions) {
                        $deductionBalance = $totalDeductions - $grossSalaryLess;
                        $netAmountDue = 0;
                    } else {
                        $netAmountDue = $grossSalaryLess - $totalDeductions;
                    }

    
                    if($this->savePayroll){
                        CosRegSemiMonthlyPayrolls::create([
                                'user_id' => $userId,
                                'name' => $payrollRecord->name,
                                'employee_number' => $payrollRecord->emp_code,
                                'office_division' => $payrollRecord->office_division,
                                'position' => $payrollRecord->position,
                                'salary_grade' => $payrollRecord->sg_step,
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
                                'total_deductions' => $totalDeductions,
                                'net_amount_due' => $netAmountDue,
                                'start_date' => $this->startDate,
                                'end_date' => $this->endDate,
                            ]
                        );
                    }else{
                        $payrolls->push([
                            'user_id' => $user->id,
                            'name' => $payrollRecord->name,
                            'employee_number' => $payrollRecord->emp_code,
                            'position' => $payrollRecord->position,
                            'salary_grade' => $payrollRecord->sg_step,
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
                            'total_deductions' => $totalDeductions,
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

                if($record->remarks == "Late/Undertime"){
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
                $signatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                    ->where('signatory_type', 'payroll')
                    ->get();

                $filters = [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'signatories' => $signatories,
                ];
                
                $filename = 'Payroll ' . $startDate->format('F') . ' '
                                       . $startDate->format('d') . '-'
                                       . $endDate->format('d') . ' '
                                       . $startDate->format('Y') . '.xlsx';
                
                if ($this->hasPayroll) {
                    $payrolls = CosRegSemiMonthlyPayrolls::where('start_date', $this->startDate)
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

    public function toggleEditCosPayroll($userId){
        $this->editCosPayroll = true;
        $this->userId = $userId;
        try {
            $payroll = CosPayrolls::where('user_id', $userId)->first();
            $sg = explode('-', $payroll->sg_step);
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->employee_number;
                $this->office_division = $payroll->office_division;
                $this->position = $payroll->position;
                $this->sg = $sg[0];
                $this->step = $sg[1];
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
            $payroll = CosPayrolls::where('user_id', $this->userId)->first();
            $user = User::where('id', $this->userId)->first();
            $sg_step = implode('-', [$this->sg, $this->step]);
            $message = null;
            $icon = null;
            $plantilla = Payrolls::where('user_id', $user->id)->first();
            if(!$plantilla){
                $payrollData = [
                    'user_id' => $this->userId,
                    'sg_step' => $sg_step,
                    'rate_per_month' => $this->rate_per_month,
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
                    CosPayrolls::create($payrollData);
                    $message = "COS Payroll added successfully!";
                    $icon = "success";
                }
            }else{
                $message = "This employee already has a Plantilla payroll!";
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
        ];
        $fileName = 'COS Payroll List.xlsx';
        return Excel::download(new CosPayrollListExport($filters), $fileName);
    }

    public function toggleCosDelete($userId){
        $this->deleteMessage = "payroll";
        $this->deleteId = $userId;
    }

    public function deleteData(){
        try {
            $user = User::where('id', $this->deleteId)->first();
            if ($user) {
                $user->cosPayrolls()->delete();
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
        $this->step = null;
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
    }
}
