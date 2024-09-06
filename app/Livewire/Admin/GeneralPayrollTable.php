<?php

namespace App\Livewire\Admin;

use App\Exports\GeneralPayrollExport;
use App\Exports\IndivPlantillaPayrollExport;
use App\Exports\PayrollListExport;
use App\Models\CosRegPayrolls;
use App\Models\CosSkPayrolls;
use App\Models\EmployeesDtr;
use App\Models\LeaveCredits;
use App\Models\OfficeDivisions;
use App\Models\Payrolls;
use App\Models\PayrollsLeaveCreditsDeduction;
use App\Models\PlantillaPayslip;
use App\Models\Positions;
use App\Models\SalaryGrade;
use App\Models\Signatories;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class GeneralPayrollTable extends Component
{
    use WithPagination, WithFileUploads;
    public $sortColumn = false;
    public $search;
    public $search2;
    public $allCol = false;
    public $columns = [
        'name' => true,
        'emp_code' => true,
        'office_division' => true,
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
    public $payrollColumns = [
        'name' => true,
        'emp_code' => true,
        'office_division' => true,
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
    public $payroll;
    public $employees;
    public $userId;
    public $name;
    public $employee_number;
    public $office_division;
    public $position;
    public $sg_step;
    public $sg;
    public $step;
    public $rate_per_month;
    public $personal_economic_relief_allowance = 0;
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
    public $other_deductions;
    public $total_deduction;
    public $net_amount_received;
    public $amount_due_first_half;
    public $amount_due_second_half;
    public $startDateFirstHalf;
    public $endDateFirstHalf;
    public $startDateSecondHalf;
    public $endDateSecondHalf;
    public $hasPayroll = true;
    public $employeePayslip;
    public $startMonth;
    public $endMonth;
    public $monthRange = true;
    public $addPayroll;
    public $editPayroll;
    public $deleteId;
    public $deleteMessage;
    public $salaryGrade;
    public $toDelete;
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
    public $unpayrolledEmployees;

    public $generalPayrolls;

    protected $credits_per_minute = [
        0 => 0.000, 1 => 0.002, 2 => 0.004, 3 => 0.006, 4 => 0.008, 5 => 0.010,
        6 => 0.012, 7 => 0.015, 8 => 0.017, 9 => 0.019, 10 => 0.021, 11 => 0.023,
        12 => 0.025, 13 => 0.027, 14 => 0.029, 15 => 0.031, 16 => 0.033, 17 => 0.035,
        18 => 0.037, 19 => 0.040, 20 => 0.042, 21 => 0.044, 22 => 0.046, 23 => 0.048,
        24 => 0.050, 25 => 0.052, 26 => 0.054, 27 => 0.056, 28 => 0.058, 29 => 0.060,
        30 => 0.062, 31 => 0.065, 32 => 0.067, 33 => 0.069, 34 => 0.071, 35 => 0.073,
        36 => 0.075, 37 => 0.077, 38 => 0.079, 39 => 0.081, 40 => 0.083, 41 => 0.085,
        42 => 0.087, 43 => 0.090, 44 => 0.092, 45 => 0.094, 46 => 0.096, 47 => 0.098,
        48 => 0.100, 49 => 0.102, 50 => 0.104, 51 => 0.106, 52 => 0.108, 53 => 0.110,
        54 => 0.112, 55 => 0.115, 56 => 0.117, 57 => 0.119, 58 => 0.121, 59 => 0.123,
        60 => 0.125
    ];

    protected $credits_per_minute_reversed = [
        "0.000" => 0, "0.002" => 1, "0.004" => 2, "0.006" => 3, "0.008" => 4, "0.010" => 5,
        "0.012" => 6, "0.015" => 7, "0.017" => 8, "0.019" => 9, "0.021" => 10, "0.023" => 11,
        "0.025" => 12, "0.027" => 13, "0.029" => 14, "0.031" => 15, "0.033" => 16, "0.035" => 17,
        "0.037" => 18, "0.040" => 19, "0.042" => 20, "0.044" => 21, "0.046" => 22, "0.048" => 23,
        "0.050" => 24, "0.052" => 25, "0.054" => 26, "0.056" => 27, "0.058" => 28, "0.060" => 29,
        "0.062" => 30, "0.065" => 31, "0.067" => 32, "0.069" => 33, "0.071" => 34, "0.073" => 35,
        "0.075" => 36, "0.077" => 37, "0.079" => 38, "0.081" => 39, "0.083" => 40, "0.085" => 41,
        "0.087" => 42, "0.090" => 43, "0.092" => 44, "0.094" => 45, "0.096" => 46, "0.098" => 47,
        "0.100" => 48, "0.102" => 49, "0.104" => 50, "0.106" => 51, "0.108" => 52, "0.110" => 53,
        "0.112" => 54, "0.115" => 55, "0.117" => 56, "0.119" => 57, "0.121" => 58, "0.123" => 59,
        "0.125" => 60
    ];
    public $absentLateUndertimeDeductionAmount = 0;
    

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
        
        $this->employees = User::where('user_role', '=', 'emp')->get();
        $this->salaryGrade = SalaryGrade::all();
    }

    public function render(){
        $this->GeneralPayrolls();

        $payrolls = User::when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->join('payrolls', 'payrolls.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*')
                ->paginate(5);


        if($this->userId){
            $user = User::where('id', $this->userId)->first();
            $pos = Positions::where('id', $user->position_id)->first();
            $officeDiv = OfficeDivisions::where('id', $user->office_division_id)->first();
            $this->employee_number = $user->emp_code;
            $this->position = $pos->position;
            $this->office_division = $officeDiv->office_division;
        }

        $this->total_deduction = 
                ($this->additional_gsis_premium ?: 0) +
                ($this->lbp_salary_loan ?: 0 )+
                ($this->nycea_deductions ?: 0) +
                ($this->sc_membership ?: 0) +
                ($this->total_loans ?: 0) +
                ($this->salary_loan ?: 0) +
                ($this->policy_loan ?: 0) +
                ($this->eal ?: 0) +
                ($this->emergency_loan ?: 0) +
                ($this->mpl ?: 0) +
                ($this->housing_loan ?: 0) +
                ($this->ouli_prem ?: 0) +
                ($this->gfal ?: 0) +
                ($this->cpl ?: 0) +
                ($this->pagibig_mpl ?: 0) +
                ($this->other_deduction_philheath_diff ?: 0) +
                ($this->life_retirement_insurance_premiums ?: 0) +
                ($this->pagibig_contribution ?: 0) +
                ($this->w_holding_tax ?: 0) +
                ($this->other_deductions ?: 0) + 
                ($this->philhealth ?: 0);
        
        $this->total_deduction = number_format((float)$this->total_deduction, 2, '.', '');


        if($this->rate_per_month && $this->personal_economic_relief_allowance){
            $this->gross_amount = $this->rate_per_month + $this->personal_economic_relief_allowance;
        }

        $this->getRate();

        $plantillaPayrollSignatories = User::join('signatories', 'signatories.user_id', 'users.id')
            ->join('positions', 'positions.id', 'users.position_id')
            ->where('signatories.signatory_type', 'plantilla_payroll')
            ->select('users.name', 'positions.*', 'signatories.*')
            ->get();
        $aPlantila = $plantillaPayrollSignatories->where('signatory', 'A')->first();
        $bPlantila = $plantillaPayrollSignatories->where('signatory', 'B')->first();
        $cPlantila = $plantillaPayrollSignatories->where('signatory', 'C')->first();
        $dPlantila = $plantillaPayrollSignatories->where('signatory', 'D')->first();
        $plantillaPayroll = [
            'a' => $aPlantila,
            'b' => $bPlantila,
            'c' => $cPlantila,
            'd' => $dPlantila,
        ];


        $user = Auth::user();
        $preparedBy = User::where('users.id', $user->id)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->first();
        $preparedBySignature = Signatories::where('user_id', $user->id)->first();

        $plantillaPayslipSignatories = User::join('signatories', 'signatories.user_id', 'users.id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('signatories.signatory_type', 'plantilla_payslip')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->get();
        $plantillaNotedBy = $plantillaPayslipSignatories->where('signatory', 'Noted By')->first();
        $plantillaPayslipSigns = [
            'notedBy' => $plantillaNotedBy,
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

        return view('livewire.admin.general-payroll-table', [
            'genPayrolls' => $this->generalPayrolls,
            'payrolls' => $payrolls,
            'plantillaPayslipSigns' => $plantillaPayslipSigns,
            'plantillaPayroll' => $plantillaPayroll,
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

    public function toggleAllPayrollColumn() {
        if ($this->allCol) {
            foreach (array_keys($this->payrollColumns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->payrollColumns[$col] = false;
            }
            $this->allCol = false;
        } else {
            foreach (array_keys($this->payrollColumns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->payrollColumns[$col] = true;
            }
            $this->allCol = true;
        }
    }

    public function GeneralPayrolls()
    {
        $payrolls = collect();
        
        if ($this->startMonth) {
            $startDate = Carbon::parse($this->startMonth);
            $payslip = PlantillaPayslip::whereMonth('start_date', $startDate->month)
                                    ->whereYear('start_date', $startDate->year)
                                    ->get();

            if ($payslip->isEmpty()) {
                $this->hasPayroll = false;
            }


            $carbonDate = Carbon::createFromFormat('Y-m', $this->startMonth);
            $this->startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
            $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $this->endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();
    
            $payrolls = User::when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->join('payrolls', 'payrolls.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*')
                ->get()
                ->map(function ($payroll) {
                    $net_amount_received = $payroll->gross_amount - $payroll->total_deduction;
                    $half_amount = $net_amount_received / 2;
                    
                    $amount_due_second_half = floor($half_amount);
                    $amount_due_first_half = $net_amount_received - $amount_due_second_half;
    
                    $payroll->net_amount_received = $net_amount_received;
                    $payroll->amount_due_first_half = $amount_due_first_half;
                    $payroll->amount_due_second_half = $amount_due_second_half;
    
                    return $payroll;
                });
    
            // Get DTR data including credits
            $payrollDTR = $this->getDTRForPayroll($this->startDateFirstHalf, $this->endDateSecondHalf);
    
            // Integrate DTR credits into payroll data
            $payrolls = $payrolls->map(function ($payroll) use ($payrollDTR) {
                $userId = $payroll->user_id;
    
                if (isset($payrollDTR[$userId])) {
                    $payroll->total_absent_days = $payrollDTR[$userId]['total_absent'];
                    $payroll->total_late_minutes = $payrollDTR[$userId]['total_late'];
                    $payroll->total_credits_deducted = $payrollDTR[$userId]['total_credits'];
                    $payroll->absent_late_undertime_deduction = $payrollDTR[$userId]['absent_late_undertime_deduction'];
                } else {
                    $payroll->total_absent_days = 0;
                    $payroll->total_late_minutes = 0;
                    $payroll->total_credits_deducted = 0.00;
                    $payroll->absent_late_undertime_deduction = 0;
                }
    
                return $payroll;
            });
        }
        $this->generalPayrolls = $payrolls;
    }
    
    public function getDTRForPayroll($startDate, $endDate, $employeeId = null)
    {
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
                        'total_credits' => 0,
                        'daily_records' => []
                    ];
                }
    
                $payrollDTR[$employeeId]['total_days']++;
    
                // Convert time strings to integer minutes
                $totalHours = $this->timeToMinutes($record->total_hours_rendered);
                $late = $this->timeToMinutes($record->late);
                $overtime = $this->timeToMinutes($record->overtime);
    
                $payrollDTR[$employeeId]['total_hours'] += $totalHours;
    
                if ($record->remarks == "Late/Undertime") {
                    $payrollDTR[$employeeId]['total_late'] += $late;
                
                    // Separate hours and minutes
                    $hours = floor($late / 60); // Get the number of hours
                    $minutes = $late % 60; // Get the remaining minutes
                
                    // Calculate credits based on hours and minutes
                    $hourCredits = $hours * 0.125;
                    $minuteCredits = isset($this->credits_per_minute[$minutes]) ? $this->credits_per_minute[$minutes] : 0;
                
                    // Sum the credits from hours and minutes
                    $totalCredits = $hourCredits + $minuteCredits;
                
                    // Add to the total credits for the employee
                    $payrollDTR[$employeeId]['total_credits'] += $totalCredits;
                }
                
                if($record->remarks == "Absent"){
                    $payrollDTR[$employeeId]['total_absent']++;
                    $payrollDTR[$employeeId]['total_credits'] += 1.00; // 1 day absent
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

            // Deduct credits from vl credits
            foreach ($payrollDTR as $employeeId => $data) {

                // Check if the deduction has already been applied
                $deduction = PayrollsLeaveCreditsDeduction::where('user_id', $employeeId)
                    ->whereMonth('month', Carbon::parse($startDate)->month)
                    ->whereYear('month', Carbon::parse($startDate)->year)
                    ->first();

                if ($deduction && $deduction->status == 1) {
                    $payrollDTR[$employeeId]['absent_late_undertime_deduction'] = number_format((float) $deduction->salary_deduction_amount, 2, '.', '');
                    continue;
                }

                $totalCredits = $data['total_credits'];
                $leaveCredits = LeaveCredits::where('user_id', $employeeId)->first();

                $creditsDeduction = $totalCredits;
                $negativeCredits = 0;

                if ($leaveCredits) {
                    $leaveCredits->vl_claimable_credits -= $totalCredits;

                    // Check if credits become negative then deduct the adsents and late/undertime to the salary
                    if ($leaveCredits->vl_claimable_credits < 0) {
                        $negativeCredits = abs($leaveCredits->vl_claimable_credits);
                        $creditsDeduction = $totalCredits - $negativeCredits;

                        $creditsInDays = 0;
                        $creditsInHours = 0;
                        $creditsInMinutes = 0;

                        if($negativeCredits >= 1){
                            $creditsInDays = floor($negativeCredits);
                            $negativeCredits -= $creditsInDays;
                        }

                        if($negativeCredits >= 0.125){
                            $creditsInHours = floor($negativeCredits / 0.125);
                            $negativeCredits -= $creditsInHours * 0.125;
                        }
                        
                        if ($negativeCredits < 0.125) {
                            foreach ($this->credits_per_minute_reversed as $credit => $minutes) {
                                if (abs((float) $credit - $negativeCredits) < 0.001 || (float) $credit > $negativeCredits) {
                                    $creditsInMinutes = $minutes;
                                    break;
                                }
                            }
                        }

                        $payroll = User::find($employeeId)->payrolls; 
                        $dailyRate = $payroll->rate_per_month / 22;
                        $hourlyRate = $dailyRate / 8;
                        $minutesRate = $hourlyRate / 60;

                        $this->absentLateUndertimeDeductionAmount = ($creditsInDays * $dailyRate) + ($creditsInHours * $hourlyRate) + ($creditsInMinutes * $minutesRate);

                        $leaveCredits->vl_claimable_credits = 0;
                    }

                    $leaveCredits->save();

                    // Mark the vl credits deduction as applied
                    PayrollsLeaveCreditsDeduction::create([
                        'user_id' => $employeeId,
                        'month' => $startDate,
                        'credits_deducted' => $creditsDeduction,
                        'salary_deduction_credits' => $negativeCredits,
                        'salary_deduction_amount' => $this->absentLateUndertimeDeductionAmount,
                        'status' => 1,
                    ]);
                }

                $payrollDTR[$employeeId]['absent_late_undertime_deduction'] = number_format((float) $this->absentLateUndertimeDeductionAmount, 2, '.', '');
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
    
    public function toggleAllColumn() {
        if ($this->allCol) {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'net_amount_received' || $col == 'amount_due_first_half' || $col == 'amount_due_second_half'){
                    continue;
                }
                $this->columns[$col] = false;
            }
            $this->allCol = false;
        } else {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'net_amount_received' || $col == 'amount_due_first_half' || $col == 'amount_due_second_half'){
                    continue;
                }
                $this->columns[$col] = true;
            }
            $this->allCol = true;
        }
    }
    
    public function exportExcel(){
        $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
            ->join('positions', 'positions.id', 'users.position_id')
            ->where('signatories.signatory_type', 'plantilla_payroll')
            ->select('users.name', 'positions.position', 'signatories.*');
        
        if($this->startMonth == null){
            $this->dispatch('swal', [
                'title' => 'Please select a month/start month!',
                'icon' => 'error'
            ]);
            return;
        }
        
        if($this->endMonth && $this->startMonth > $this->endMonth){
            $this->dispatch('swal', [
                'title' => 'Start month must not be ahead of the end month!',
                'icon' => 'error'
            ]);
            return;
        }

        if(!$this->endMonth){
            $this->endMonth = $this->startMonth;
        }

        $filters = [
            'search' => $this->search,
            'startMonth' => $this->startMonth,
            'endMonth' => $this->endMonth,
            'signatories' => $signatories,
        ];
    
        $startDate = Carbon::parse($this->startMonth);
        $endDate = $this->endMonth ? Carbon::parse($this->endMonth) : $startDate;
    
        $fileName = 'General Payroll ' . $startDate->format('F Y');
        if ($startDate->format('Y-m') !== $endDate->format('Y-m')) {
            $fileName .= ' to ' . $endDate->format('F Y');
        }
        $fileName .= '.xlsx';
    
        return Excel::download(new GeneralPayrollExport($filters), $fileName);
    }

    public function exportIndivPayroll($id){
        try{
            $admin = Auth::user();
            $notedBy = User::join('signatories', 'signatories.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->where('signatories.signatory_type', 'plantilla_payslip')
                ->where('signatories.signatory', 'Noted By')
                ->select('users.name', 'positions.position', 'signatories.*')
                ->first();
            $preparedBy = User::where('users.id', $admin->id)
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('signatories', 'signatories.user_id', 'users.id')
                ->select('users.name', 'positions.position', 'signatories.*')
                ->first();

            $payroll = User::where('users.id', $id)
                ->join('payrolls', 'payrolls.user_id', 'users.id')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*', 'user_data.sex', 'user_data.civil_status')
                ->first();

            if(!$this->endMonth){
                $this->endMonth = $this->startMonth;
            }
    
            $filters = [
                'payroll' => $payroll,
                'startMonth' => $this->startMonth,
                'endMonth' => $this->endMonth,
                'preparedBy' => $preparedBy,
                'notedBy' => $notedBy,
            ];

            $fileName = 'Plantilla Individual Payroll.xlsx';
            return Excel::download(new IndivPlantillaPayrollExport($filters), $fileName);
        }catch(Exception $e){
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
                        ->where('signatories.signatory_type', 'plantilla_payslip')
                        ->where('signatories.signatory', 'Noted By')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->first();
                    
                $payslip = User::where('users.id', $userId)
                    ->join('payrolls', 'payrolls.user_id', 'users.id')
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*')
                    ->get()
                    ->map(function ($p) use ($userId) {
                        $net_amount_received = $p->gross_amount - $p->total_deduction;

                        $deduction = PayrollsLeaveCreditsDeduction::where('user_id', $userId)
                            ->whereMonth('month', Carbon::parse($this->startDateFirstHalf)->month)
                            ->whereYear('month', Carbon::parse($this->endDateSecondHalf)->year)
                            ->first();

                        if ($deduction) {
                            $net_amount_received -= $deduction->salary_deduction_amount;
                        }

                        $half_amount = $net_amount_received / 2;
        
                        $amount_due_second_half = floor($half_amount);
                        $amount_due_first_half = $net_amount_received - $amount_due_second_half;

                        $p->absent_late_undertime_deduction = $deduction ? $deduction->salary_deduction_amount : 0;
                        $p->net_amount_received = $net_amount_received;
                        $p->amount_due_first_half = $amount_due_first_half;
                        $p->amount_due_second_half = $amount_due_second_half;
                        $p->others = 0;
                        $p->pbb_withholding_tax = 0;
                        $p->hdmf_contribution = 0;
                        $p->computer = 0;
                        $p->nycempc_share_capital_membership = 0;
                        $p->nycempc_loan = 0;
                        $p->nycempc_educ_loan = 0;
                        $p->nycempc_personal_loan = 0;
                        $p->nycempc_business_loan = 0;
                        $p->nycempc_dues = 0;
                        $p->coa_dis_allowance = 0;
                        $p->landbank_mobile_saver = 0;
                        $p->other_deduction_phil_adjustment = 0;

                        return $p;
                    });

                $payslip = $payslip->first(); 

                $dates = [
                    'startDateFirstHalf' => $this->startDateFirstHalf,
                    'endDateFirstHalf' => $this->endDateFirstHalf,
                    'startDateSecondHalf' => $this->startDateSecondHalf,
                    'endDateSecondHalf' => $this->endDateSecondHalf,
                ];

                $payslipFor = \Carbon\Carbon::parse($dates['startDateFirstHalf'])->format('F') . " " .
                              \Carbon\Carbon::parse($dates['startDateFirstHalf'])->format('d') .  "-" .
                              \Carbon\Carbon::parse($dates['endDateSecondHalf'])->format('d') . " " .
                              \Carbon\Carbon::parse($dates['startDateFirstHalf'])->format('Y');
                
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
                    $pdf = Pdf::loadView('pdf.monthly-payslip', [
                        'preparedBy' => $preparedBy,
                        'payslip' => $payslip,
                        'dates' => $dates,
                        'signatories' => $signatories,
                        'preparedBySignaturePath' => $preparedBySignaturePath,
                        'signatoriesSignaturePath' => $signatoriesSignaturePath,
                    ]);
                    $pdf->setPaper([0, 0, 396, 612], 'portrait');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, $payslip['name'] . ' ' . $payslipFor . ' Payslip.pdf');
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

    public function viewPayroll($userId){
        $this->payroll = true;
        $this->userId = $userId;
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->startMonth);
            $date = $carbonDate->startOfMonth()->toDateString();

            $payroll = User::where('users.id', $userId)
                ->join('payrolls', 'payrolls.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*')
                ->get()
                ->map(function ($p) {
                    $net_amount_received = $p->gross_amount - $p->total_deduction;
                    $half_amount = $net_amount_received / 2;
            
                    $amount_due_second_half = floor($half_amount);
                    $amount_due_first_half = $net_amount_received - $amount_due_second_half;
            
                    $p->net_amount_received = $net_amount_received;
                    $p->amount_due_first_half = $amount_due_first_half;
                    $p->amount_due_second_half = $amount_due_second_half;
            
                    return $p;
                });
            
            $payroll = $payroll->first(); 
          
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->emp_code;
                $this->office_division = $payroll->office_division;
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
                $this->net_amount_received = $payroll->net_amount_received;
                $this->amount_due_first_half = $payroll->amount_due_first_half;
                $this->amount_due_second_half = $payroll->amount_due_second_half;

            } else {
                $this->resetPayrollFields();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->payroll = null;
        $this->addPayroll = null;
        $this->editPayroll = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->sg_step = null;
        $this->sg = null;
        $this->step = null;
        $this->office_division = null;
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
        $this->addSignatory = null;
        $this->editSignatory = null;
        $this->addPayslipSignatory = null;
        $this->editPayslipSignatory = null;
        $this->signatoryFor = null;
        $this->signatory = null;
        $this->signatures = [];
        $this->preparedBySign = null;
        $this->deleteMessage = null;
        $this->deleteId = null;
        $this->other_deductions = null;
        
    }

    public function recordPayroll(){
        try {
            if ($this->startMonth) {
                $carbonDate = Carbon::createFromFormat('Y-m', $this->startMonth);
                $startDate= $carbonDate->startOfMonth()->toDateString();
                $endDate = $carbonDate->endOfMonth()->toDateString();

                $payrolls = User::when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->join('payrolls', 'payrolls.user_id', 'users.id')
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->select('users.name', 'users.emp_code', 'payrolls.*', 'positions.*', 'office_divisions.*')
                    ->get()
                    ->map(function ($payroll) {
                        $net_amount_received = $payroll->gross_amount - $payroll->total_deduction;
                        $half_amount = $net_amount_received / 2;
                        
                        $amount_due_second_half = floor($half_amount);
                        $amount_due_first_half = $net_amount_received - $amount_due_second_half;
        
                        $payroll->net_amount_received = $net_amount_received;
                        $payroll->amount_due_first_half = $amount_due_first_half;
                        $payroll->amount_due_second_half = $amount_due_second_half;
        
                        return $payroll;
                    });
                $payrollDTR = $this->getDTRForPayroll($startDate, $endDate);
                $payrolls = $payrolls->map(function ($payroll) use ($payrollDTR) {
                    $userId = $payroll->user_id;
        
                    if (isset($payrollDTR[$userId])) {
                        $payroll->total_absent_days = $payrollDTR[$userId]['total_absent'];
                        $payroll->total_late_minutes = $payrollDTR[$userId]['total_late'];
                        $payroll->total_credits_deducted = $payrollDTR[$userId]['total_credits'];
                        $payroll->absent_late_undertime_deduction = $payrollDTR[$userId]['absent_late_undertime_deduction'];
                    } else {
                        $payroll->total_absent_days = 0;
                        $payroll->total_late_minutes = 0;
                        $payroll->total_credits_deducted = 0.00;
                        $payroll->absent_late_undertime_deduction = 0;
                    }
        
                    return $payroll;
                });

                $admin = Auth::user();
                $preparedBy = User::where('users.id', $admin->id)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('signatories', 'signatories.user_id', 'users.id')
                    ->first();

                foreach ($payrolls as $payroll) {
                    PlantillaPayslip::create([
                            'user_id' => $payroll->user_id,
                            'sg_step' => $payroll->sg_step,
                            'rate_per_month' => $payroll->rate_per_month,
                            'personal_economic_relief_allowance' => $payroll->personal_economic_relief_allowance,
                            'gross_amount' => $payroll->gross_amount,
                            'additional_gsis_premium' => $payroll->additional_gsis_premium,
                            'lbp_salary_loan' => $payroll->lbp_salary_loan,
                            'nycea_deductions' => $payroll->nycea_deductions,
                            'sc_membership' => $payroll->sc_membership,
                            'total_loans' => $payroll->total_loans,
                            'salary_loan' => $payroll->salary_loan,
                            'policy_loan' => $payroll->policy_loan,
                            'eal' => $payroll->eal,
                            'emergency_loan' => $payroll->emergency_loan,
                            'mpl' => $payroll->mpl,
                            'housing_loan' => $payroll->housing_loan,
                            'ouli_prem' => $payroll->ouli_prem,
                            'gfal' => $payroll->gfal,
                            'cpl' => $payroll->cpl,
                            'pagibig_mpl' => $payroll->pagibig_mpl,
                            'other_deduction_philheath_diff' => $payroll->other_deduction_philheath_diff,
                            'life_retirement_insurance_premiums' => $payroll->life_retirement_insurance_premiums,
                            'pagibig_contribution' => $payroll->pagibig_contribution,
                            'w_holding_tax' => $payroll->w_holding_tax,
                            'philhealth' => $payroll->philhealth,
                            'other_deductions' => $payroll->other_deductions,
                            'total_deduction' => $payroll->total_deduction,
                            'net_amount_recieved' => $payroll->net_amount_received,
                            'first_half_amount' => $payroll->amount_due_first_half,
                            'second_half_amount' => $payroll->amount_due_second_half,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'prepared_by_name' => $preparedBy->name,
                            'prepared_by_position' => $preparedBy->position,
                        ]
                    );
                }
    
                $this->dispatch('swal', [
                    'title' => 'Payroll Saved!',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'Select date!',
                    'icon' => 'info'
                ]);
            }
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
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
    
    public function toggleEditPayroll($userId){
        $this->editPayroll = true;
        $this->userId = $userId;
        try {
            $payroll = Payrolls::where('user_id', $userId)->first();
            $sg = explode('-', $payroll->sg_step);
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->employee_number;
                $this->office_division = $payroll->office_division;
                $this->position = $payroll->position;
                $this->sg = $sg[0];
                $this->step = $sg[1];
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
                $this->other_deductions = $payroll->other_deductions;
                $this->total_deduction = $payroll->total_deduction;
            } else {
                $this->resetVariables();
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
            $user = User::where('id', $this->userId)->first();
            $sg_step = implode('-', [$this->sg, $this->step]);
            $message = null;
            $icon = null;
            $cosReg = CosRegPayrolls::where('user_id', $user->id)->first();
            $cosSk = CosSkPayrolls::where('user_id', $user->id)->first();
            if(!$cosReg && !$cosSk){
                $payrollData = [
                    'user_id' => $this->userId,
                    'sg_step' => $sg_step,
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
                        'gross_amount' => 'required|numeric',
                        'pagibig_contribution' => 'required|numeric',
                        'w_holding_tax' => 'required|numeric',
                        'philhealth' => 'required|numeric',
                        'total_deduction' => 'required|numeric',
                    ]);
    
                    $payroll->update($payrollData);
                    $message = "Payroll updated successfully!";
                    $icon = "success";
                } else {
                    $this->validate([
                        'employee_number' => 'required|max:100',
                        'office_division' => 'required|max:100',
                        'position' => 'required|max:100',
                        'sg' => 'required|numeric',
                        'step' => 'required|numeric',
                        'rate_per_month' => 'required|numeric',
                        'gross_amount' => 'required|numeric',
                        'pagibig_contribution' => 'required|numeric',
                        'w_holding_tax' => 'required|numeric',
                        'philhealth' => 'required|numeric',
                        'total_deduction' => 'required|numeric',
                    ]);
                    $payrollData['name'] = $user->name;
                    Payrolls::create($payrollData);
                    $message = "Payroll added successfully!";
                    $icon = "success";
                }
            }else{
                $message = "This employee already has a COS Regular/SK payroll!";
                $icon = "error";
            }
    
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => $icon,
            ]);
    
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleDelete($userId){
        $this->deleteMessage = "payroll";
        $this->deleteId = $userId;
    }

    public function deleteData(){
        try {
            $user = User::where('id', $this->deleteId)->first();
            if ($user) {
                $user->payrolls()->delete();
                $message = "Plantilla payroll deleted successfully!";
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

    public function exportPlantillaExcel(){
        $filters = [
            'search' => $this->search,
        ];
        $fileName = 'Plantilla Payroll List.xlsx';
        return Excel::download(new PayrollListExport($filters), $fileName);
    }
}
