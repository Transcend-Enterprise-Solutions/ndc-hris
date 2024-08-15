<?php

namespace App\Livewire\Admin;

use App\Exports\GeneralPayrollExport;
use App\Exports\PayrollListExport;
use App\Models\Admin;
use App\Models\CosPayrolls;
use App\Models\EmployeesPayroll;
use App\Models\GeneralPayroll;
use App\Models\OfficeDivisions;
use App\Models\Payrolls;
use App\Models\Positions;
use App\Models\SalaryGrade;
use App\Models\Signatories;
use App\Models\User;
use Exception;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
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
        'office_division' => false,
        'position' => false,
        'sg_step' => false,
        'rate_per_month' => false,
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
    public $monthRange = false;
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

    public $generalPayrolls;

    public function mount(){
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

    public function GeneralPayrolls(){
        $payrolls = collect();
    
        if ($this->startMonth) {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->startMonth);
            $this->startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
            $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
            $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
            $this->endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

            $generalPayrollQuery = GeneralPayroll::where('date', $this->startDateFirstHalf)
                                ->join('payrolls', 'general_payroll.user_id', '=', 'payrolls.user_id')
                                ->select('payrolls.*', 
                                    'general_payroll.net_amount_received as total_amount_due', 
                                    'general_payroll.amount_due_first_half as net_amount_due_first_half', 
                                    'general_payroll.gross_salary_less', 
                                    'general_payroll.late_absences', 
                                    'general_payroll.others', 
                                    'general_payroll.total_earnings', 
                                    'general_payroll.amount_due_second_half as net_amount_due_second_half')
                                ->when($this->search2, function ($query) {
                                    return $query->search(trim($this->search2));
                                });
            if($this->endMonth){
                $carbonEndMonth = Carbon::createFromFormat('Y-m', $this->endMonth);
                $endDateEndMonth = $carbonEndMonth->endOfMonth()->toDateString();
                $generalPayrollQuery->orWhereBetween('date', [$this->startDateFirstHalf, $endDateEndMonth]);
            }

            if($generalPayrollQuery->exists()){
                $payrolls = $generalPayrollQuery->paginate(10);
                $this->hasPayroll = true;
            }else{
                $payrolls = $this->getGenPayroll()->paginate(10);
                $this->employeePayslip = $this->getGenPayroll()->get();
                $this->hasPayroll = false;
            }
        }

        if($this->monthRange == false){
            $this->endMonth = null;
        }

        $this->generalPayrolls = $payrolls;
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

    public function getGenPayroll(){
        try{
            $payrollAggregates = DB::table('employees_payroll')
            ->select('user_id')
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as net_amount_due_first_half", [$this->startDateFirstHalf, $this->endDateFirstHalf])
            ->selectRaw("SUM(CASE 
                            WHEN start_date >= ? AND end_date <= ? 
                            THEN net_amount_due 
                            ELSE 0 
                        END) as net_amount_due_second_half", [$this->startDateSecondHalf, $this->endDateSecondHalf])
            ->selectRaw("SUM(net_amount_due) as total_amount_due")
            ->where('start_date', $this->startDateFirstHalf)
            ->orWhere('end_date', $this->endDateSecondHalf)
            ->groupBy('user_id');

            // Join the aggregate results with the general_payroll table
            $payrolls = Payrolls::when($this->search, function ($query) {
                                return $query->search(trim($this->search));
                            })
                            ->joinSub($payrollAggregates, 'payroll_aggregates', function ($join) {
                                $join->on('payrolls.user_id', '=', 'payroll_aggregates.user_id');
                            })
                            ->select('payrolls.*', 
                                    'payroll_aggregates.net_amount_due_first_half', 
                                    'payroll_aggregates.net_amount_due_second_half', 
                                    'payroll_aggregates.total_amount_due');
            return $payrolls;
        }catch(Exception $e){
            throw $e;
        }
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
    
    public function exportExcel()
    {
        $signatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
            ->where('signatories.signatory_type', 'plantilla_payroll')
            ->get();
        
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

    public function viewPayroll($userId){
        $this->payroll = true;
        $this->userId = $userId;
        try {
            $carbonDate = Carbon::createFromFormat('Y-m', $this->startMonth);
            $date = $carbonDate->startOfMonth()->toDateString();

            $payroll = Payrolls::where('user_id', $userId)->first();
            $generalPayroll = GeneralPayroll::where('user_id', $userId)
                                ->where('date', $date)
                                ->first();
                                
            if ($payroll) {
                $this->name = $payroll->name;
                $this->employee_number = $payroll->employee_number;
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
                if($generalPayroll){
                    $this->net_amount_received = $generalPayroll->net_amount_received;
                    $this->amount_due_first_half = $generalPayroll->amount_due_first_half;
                    $this->amount_due_second_half = $generalPayroll->amount_due_second_half;
                }else{
                    $thisPayroll = EmployeesPayroll::where('user_id', $userId)
                                ->selectRaw("SUM(CASE
                                                WHEN start_date >= ? AND end_date <= ?
                                                THEN net_amount_due
                                                ELSE 0
                                            END) as net_amount_due_first_half", [$this->startDateFirstHalf, $this->endDateFirstHalf])
                                ->selectRaw("SUM(CASE
                                                WHEN start_date >= ? AND end_date <= ?
                                                THEN net_amount_due
                                                ELSE 0
                                            END) as net_amount_due_second_half", [$this->startDateSecondHalf, $this->endDateSecondHalf])
                                ->selectRaw("SUM(net_amount_due) as total_amount_due")
                                ->where(function ($query) {
                                    $query->where('start_date', '>=', $this->startDateFirstHalf)
                                        ->where('end_date', '<=', $this->endDateSecondHalf);
                                })
                                ->groupBy('user_id')
                                ->first();
                    $this->net_amount_received = $thisPayroll->total_amount_due;
                    $this->amount_due_first_half = $thisPayroll->net_amount_due_first_half;
                    $this->amount_due_second_half = $thisPayroll->net_amount_due_second_half;
                }
            } else {
                // If no payroll exists, you might want to reset all fields
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
        
    }

    public function recordPayroll(){
        try {
            if ($this->date) {
                $carbonDate = Carbon::createFromFormat('Y-m', $this->date);
                $startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
                $endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
                $startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
                $endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

                $payrollAggregates = DB::table('employees_payroll')
                    ->select('user_id')
                    ->selectRaw("SUM(CASE 
                                    WHEN start_date >= ? AND end_date <= ? 
                                    THEN net_amount_due 
                                    ELSE 0 
                                END) as net_amount_due_first_half", [$startDateFirstHalf, $endDateFirstHalf])
                    ->selectRaw("SUM(CASE 
                                    WHEN start_date >= ? AND end_date <= ? 
                                    THEN net_amount_due 
                                    ELSE 0 
                                END) as net_amount_due_second_half", [$startDateSecondHalf, $endDateSecondHalf])
                    ->selectRaw("SUM(net_amount_due) as total_amount_due")
                    ->selectRaw("SUM(gross_salary_less) as gross_salary_less")
                    ->selectRaw("SUM(leave_days_withoutpay_amount) as leave_days_withoutpay_amount")
                    ->selectRaw("SUM(absences_amount + late_undertime_hours_amount + late_undertime_mins_amount) as late_absences")
                    ->groupBy('user_id');
        
                // Join the aggregate results with the general_payroll table
                $payrolls = Payrolls::joinSub($payrollAggregates, 'payroll_aggregates', function ($join) {
                                    $join->on('payrolls.user_id', '=', 'payroll_aggregates.user_id');
                                })
                                ->select('payrolls.*', 
                                        'payroll_aggregates.net_amount_due_first_half',
                                        'payroll_aggregates.net_amount_due_second_half',
                                        'payroll_aggregates.total_amount_due',
                                        'payroll_aggregates.gross_salary_less',
                                        'payroll_aggregates.leave_days_withoutpay_amount',
                                        'payroll_aggregates.late_absences')
                                ->get();
    
                foreach ($payrolls as $payroll) {
                    $userId = $payroll->user_id;
                    $lateAbsences = $payroll->late_absences;
                    $grossSalaryLess = $payroll->rate_per_month - $lateAbsences;
                    $totalEarnings = $payroll->rate_per_month - $lateAbsences + $payroll->personal_economic_relief_allowance;
                        
                    GeneralPayroll::create([
                            'user_id' => $userId,
                            'net_amount_received' => $payroll->total_amount_due,
                            'amount_due_first_half' => $payroll->net_amount_due_first_half,
                            'amount_due_second_half' => $payroll->net_amount_due_second_half,
                            'gross_salary_less' => $grossSalaryLess,
                            'late_absences' =>$lateAbsences,
                            'leave_without_pay' =>$payroll->leave_days_withoutpay_amount,
                            'others' => 0,
                            'total_earnings' => $totalEarnings,
                            'date' => $startDateFirstHalf,
                        ]
                    );
                }
    
                $this->dispatch('swal', [
                    'title' => 'General Payroll Saved!',
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

    public function exportPayslip($userId){
        try {
            $user = User::where('id', $userId)->first();
            if ($user) {
                $preparedBy = Auth::user();
                $signatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                    ->where('signatories.signatory_type', 'plantilla_payslip')
                    ->where('signatories.signatory', 'Noted By')
                    ->first();
                    
                $payslip = null;
                if ($this->hasPayroll) {
                        $payslip = GeneralPayroll::where('general_payroll.user_id', $userId)
                                ->where('date', $this->startDateFirstHalf)
                                ->join('payrolls', 'general_payroll.user_id', '=', 'payrolls.user_id')
                                ->select('payrolls.*', 
                                    'general_payroll.net_amount_received as total_amount_due', 
                                    'general_payroll.amount_due_first_half as net_amount_due_first_half',
                                    'general_payroll.gross_salary_less', 
                                    'general_payroll.late_absences', 
                                    'general_payroll.leave_without_pay', 
                                    'general_payroll.others', 
                                    'general_payroll.total_earnings',  
                                    'general_payroll.amount_due_second_half as net_amount_due_second_half')
                                ->first();
                } else {
                    $payslip = $this->employeePayslip->where('user_id', $userId)->first();
                }

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

                $pb = Admin::where('admin.user_id', $preparedBy->id)
                    ->join('payrolls', 'payrolls.id', 'admin.payroll_id')
                    ->join('signatories', 'signatories.user_id', 'admin.user_id')
                    ->first();
        
        
                // Generate temporary paths for signatures
                $preparedBySignaturePath = $this->getTemporarySignaturePath($pb);
                $signatoriesSignaturePath = $this->getTemporarySignaturePath($signatories);
                
                if ($payslip) {
                    $pdf = Pdf::loadView('pdf.monthly-payslip', [
                        'preparedBy' => $pb,
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
            $cos = CosPayrolls::where('user_id', $user->id)->first();
            if(!$cos){
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
                $message = "This employee already has a COS payroll!";
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
