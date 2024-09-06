<?php

namespace App\Livewire\User;

use App\Models\CosRegPayrolls;
use App\Models\CosRegPayslip;
use App\Models\CosSkPayrolls;
use App\Models\CosSkPayslip;
use App\Models\Payrolls;
use App\Models\PayrollsLeaveCreditsDeduction;
use App\Models\PlantillaPayslip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PayslipTable extends Component
{
    use WithPagination;

    public $date;
    public $startDate;
    public $endDate;
    public $monthsEnd;
    public $range;
    protected $payrolls = [];
    public $type;
    public $search;
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
    public $payslip;
    public $payslipDate;
    public $month;

    public function render(){
        $payslips = collect();
        $cosPayslips = collect();
        $user = Auth::user(); 
        $plantilla = Payrolls::where('user_id', $user->id)->first();
        $cosReg = CosRegPayrolls::where('user_id', $user->id)->first();
        $cosSk = CosSkPayrolls::where('user_id', $user->id)->first();

        if($plantilla){
            $this->type = "plantilla";
            $payslips = PlantillaPayslip::where('user_id', $user->id)
                ->when($this->date, function ($query) {
                    $query->whereMonth('start_date', Carbon::parse($this->date)->month)
                        ->whereYear('start_date', Carbon::parse($this->date)->year);
                })
                ->orderBy('start_date', 'DESC')
                ->paginate(10);
        }elseif($cosReg){
            $this->type = "cos-reg";
            $cosPayslips = $this->getCosRegPayslips();
        }elseif($cosSk){
            $this->type = "cos-sk";
            $cosPayslips = $this->getCosSkPayslips();
        }

        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $cosPayslipsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $cosPayslips->forPage($currentPage, $perPage),
            $cosPayslips->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );


        return view('livewire.user.payslip-table', [
            'payslips' => $payslips,
            'cosPayslips' => $cosPayslipsPaginated,
        ]);
    }

    public function getCosRegPayslips(){
        $user = Auth::user(); 
        return CosRegPayslip::where('user_id', $user->id)
            ->when($this->date, function ($query) {
                $query->whereMonth('start_date', Carbon::parse($this->date)->month)
                    ->whereYear('start_date', Carbon::parse($this->date)->year);
            })
            ->orderBy('start_date', 'DESC')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->start_date)->format('Y-m');
            })
            ->map(function ($group) {
                $firstHalf = $group->where('start_date', '<=', Carbon::parse($group->first()->start_date)->startOfMonth()->addDays(14));
                $secondHalf = $group->where('start_date', '>', Carbon::parse($group->first()->start_date)->startOfMonth()->addDays(14));
                
                return [
                    'month_year' => Carbon::parse($group->first()->start_date)->format('F Y'),
                    'first_half_amount' => number_format((float) $firstHalf->sum('gross_salary_less'), 2, '.', ','),
                    'second_half_amount' => number_format((float) $secondHalf->sum('gross_salary_less'), 2, '.', ','),
                    'net_amount_received' => number_format((float) $group->sum('net_amount_received'), 2, '.', ','),
                    'start_date' => $group->first()->start_date,
                ];
            })
            ->values();
    }

    public function getCosSkPayslips(){
        $user = Auth::user(); 
        return CosSkPayslip::where('user_id', $user->id)
            ->when($this->date, function ($query) {
                $query->whereMonth('start_date', Carbon::parse($this->date)->month)
                    ->whereYear('start_date', Carbon::parse($this->date)->year);
            })
            ->orderBy('start_date', 'DESC')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->start_date)->format('Y-m');
            })
            ->map(function ($group) {
                $firstHalf = $group->where('start_date', '<=', Carbon::parse($group->first()->start_date)->startOfMonth()->addDays(14));
                $secondHalf = $group->where('start_date', '>', Carbon::parse($group->first()->start_date)->startOfMonth()->addDays(14));
                
                return [
                    'month_year' => Carbon::parse($group->first()->start_date)->format('F Y'),
                    'first_half_amount' => number_format((float) $firstHalf->sum('gross_salary_less'), 2, '.', ','),
                    'second_half_amount' => number_format((float) $secondHalf->sum('gross_salary_less'), 2, '.', ','),
                    'net_amount_received' => number_format((float) $group->sum('net_amount_received'), 2, '.', ','),
                    'start_date' => $group->first()->start_date,
                ];
            })
            ->values();
    }

    public function paginationView(){
        return 'vendor.livewire.tailwind';
    }

    public function viewPlantillaPayslip($month){
        $this->payslip = true;
        $this->month = $month;
        try {
            $this->payslipDate = Carbon::parse($month)->format('F') . ", " . Carbon::parse($month)->format('Y');
            $user = Auth::user();
            $payslip = PlantillaPayslip::where('user_id', $user->id)
                        ->whereMonth('start_date', Carbon::parse($month)->month)
                        ->whereYear('start_date', Carbon::parse($month)->year)
                        ->first();
          
            if ($payslip) {
                $this->name = $payslip->name;
                $this->employee_number = $payslip->emp_code;
                $this->office_division = $payslip->office_division;
                $this->position = $payslip->position;
                $this->sg_step = $payslip->sg_step;
                $this->rate_per_month = $payslip->rate_per_month;
                $this->personal_economic_relief_allowance = $payslip->personal_economic_relief_allowance;
                $this->gross_amount = $payslip->gross_amount;
                $this->additional_gsis_premium = $payslip->additional_gsis_premium;
                $this->lbp_salary_loan = $payslip->lbp_salary_loan;
                $this->nycea_deductions = $payslip->nycea_deductions;
                $this->sc_membership = $payslip->sc_membership;
                $this->total_loans = $payslip->total_loans;
                $this->salary_loan = $payslip->salary_loan;
                $this->policy_loan = $payslip->policy_loan;
                $this->eal = $payslip->eal;
                $this->emergency_loan = $payslip->emergency_loan;
                $this->mpl = $payslip->mpl;
                $this->housing_loan = $payslip->housing_loan;
                $this->ouli_prem = $payslip->ouli_prem;
                $this->gfal = $payslip->gfal;
                $this->cpl = $payslip->cpl;
                $this->pagibig_mpl = $payslip->pagibig_mpl;
                $this->other_deduction_philheath_diff = $payslip->other_deduction_philheath_diff;
                $this->life_retirement_insurance_premiums = $payslip->life_retirement_insurance_premiums;
                $this->pagibig_contribution = $payslip->pagibig_contribution;
                $this->w_holding_tax = $payslip->w_holding_tax;
                $this->philhealth = $payslip->philhealth;
                $this->total_deduction = $payslip->total_deduction;
                $this->other_deductions = $payslip->other_deductions;
                $this->net_amount_received = $payslip->net_amount_received;
                $this->amount_due_first_half = $payslip->first_half_amount;
                $this->amount_due_second_half = $payslip->second_half_amount;

            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportPlantillaPayslip($month){
        try{
            $userId = Auth::user()->id;
            if ($month) {
                $carbonDate = Carbon::parse($month);
                $this->startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
                $this->endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
                $this->startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
                $this->endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

                $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('signatories.signatory_type', 'plantilla_payslip')
                        ->where('signatories.signatory', 'Noted By')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->first();

                $payslip = User::where('user_id', $userId)
                    ->join('plantilla_payslip', 'plantilla_payslip.user_id', 'users.id')
                    ->whereMonth('plantilla_payslip.start_date', Carbon::parse($month)->month)
                    ->whereYear('plantilla_payslip.start_date', Carbon::parse($month)->year)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->select('users.name', 'users.emp_code', 'plantilla_payslip.*', 'positions.*', 'office_divisions.*')
                    ->get()
                    ->map(function ($p) use ($userId) {

                        $deduction = PayrollsLeaveCreditsDeduction::where('user_id', $userId)
                            ->whereMonth('month', Carbon::parse($this->startDateFirstHalf)->month)
                            ->whereYear('month', Carbon::parse($this->endDateSecondHalf)->year)
                            ->first();

                        $p->absent_late_undertime_deduction = $deduction ? $deduction->salary_deduction_amount : 0;
                        $p->amount_due_first_half = $p->first_half_amount;
                        $p->amount_due_second_half = $p->second_half_amount;
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

                $payslipFor = Carbon::parse($dates['startDateFirstHalf'])->format('F') . " " .
                              Carbon::parse($dates['startDateFirstHalf'])->format('d') .  "-" .
                              Carbon::parse($dates['endDateSecondHalf'])->format('d') . " " .
                              Carbon::parse($dates['startDateFirstHalf'])->format('Y');
                
                $preparedBy = User::where('user_id', $userId)
                            ->join('plantilla_payslip', 'plantilla_payslip.user_id', 'users.id')
                            ->whereMonth('plantilla_payslip.start_date', Carbon::parse($month)->month)
                            ->whereYear('plantilla_payslip.start_date', Carbon::parse($month)->year)
                            ->select('plantilla_payslip.prepared_by_name as name', 'plantilla_payslip.prepared_by_position as position')
                            ->first();
        
        
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
        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportCosPayslip($month){
        try {
            $user = Auth::user();
            if ($user) {
                $carbonDate = Carbon::parse($month);
                $startDateFirstHalf = $carbonDate->startOfMonth()->toDateString();
                $endDateFirstHalf = $carbonDate->copy()->day(15)->toDateString();
                $startDateSecondHalf = $carbonDate->copy()->day(16)->toDateString();
                $endDateSecondHalf = $carbonDate->endOfMonth()->toDateString();

                $signatories = User::join('signatories', 'signatories.user_id', 'users.id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('signatories.signatory_type', 'cos_payslip')
                        ->where('signatories.signatory', 'Noted By')
                        ->select('users.name', 'positions.*', 'signatories.*')
                        ->first();

                $payslip = null;
                $payslip = null;
                if($this->type === "cos-reg"){
                    $payslip = CosRegPayslip::where('start_date', $startDateFirstHalf)
                                ->where('end_date', $endDateFirstHalf)
                                ->join('users', 'users.id', 'cos_reg_payslip.user_id')
                                ->join('positions', 'positions.id', 'users.position_id')
                                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                                ->select(
                                    'users.name',
                                    'users.emp_code as employee_number',
                                    'positions.*',
                                    'office_divisions.*',
                                    'cos_reg_payslip.days_covered as no_of_days_covered',
                                    'cos_reg_payslip.w_holding_tax as withholding_tax',
                                    'cos_reg_payslip.net_amount_received as net_amount_due',
                                    'cos_reg_payslip.total_deduction as total_deductions',
                                    'cos_reg_payslip.*',
                                )
                                ->first();
                    $payslip2 = CosRegPayslip::where('start_date', $startDateSecondHalf)
                                ->where('end_date', $endDateSecondHalf)
                                ->join('users', 'users.id', 'cos_reg_payslip.user_id')
                                ->join('positions', 'positions.id', 'users.position_id')
                                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                                ->select(
                                    'users.name',
                                    'users.emp_code as employee_number',
                                    'positions.*',
                                    'office_divisions.*',
                                    'cos_reg_payslip.days_covered as no_of_days_covered',
                                    'cos_reg_payslip.w_holding_tax as withholding_tax',
                                    'cos_reg_payslip.net_amount_received as net_amount_due',
                                    'cos_reg_payslip.total_deduction as total_deductions',
                                    'cos_reg_payslip.*',
                                )
                                ->first();
                }elseif($this->type === "cos-sk"){
                    $payslip = CosSkPayslip::where('start_date', $startDateFirstHalf)
                        ->where('end_date', $endDateFirstHalf)
                        ->join('users', 'users.id', 'cos_sk_payslip.user_id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                        ->select(
                            'users.name',
                            'users.emp_code as employee_number',
                            'positions.*',
                            'office_divisions.*',
                            'cos_sk_payslip.days_covered as no_of_days_covered',
                            'cos_sk_payslip.w_holding_tax as withholding_tax',
                            'cos_sk_payslip.net_amount_received as net_amount_due',
                            'cos_sk_payslip.total_deduction as total_deductions',
                            'cos_sk_payslip.*',
                        )
                        ->first();
                    $payslip2 = CosSkPayslip::where('start_date', $startDateSecondHalf)
                        ->where('end_date', $endDateSecondHalf)
                        ->join('users', 'users.id', 'cos_sk_payslip.user_id')
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                        ->select(
                            'users.name',
                            'users.emp_code as employee_number',
                            'positions.*',
                            'office_divisions.*',
                            'cos_sk_payslip.days_covered as no_of_days_covered',
                            'cos_sk_payslip.w_holding_tax as withholding_tax',
                            'cos_sk_payslip.net_amount_received as net_amount_due',
                            'cos_sk_payslip.total_deduction as total_deductions',
                            'cos_sk_payslip.*',
                        )
                        ->first();
                }



                $dates = [
                    'startDate' => $this->startDate,
                    'endDate' => $this->endDate,
                ];

                $payslipFor = $carbonDate->format('F') . " 1 - 15 " . $carbonDate->format('Y');
                $payslipFor2 = $carbonDate->format('F') . " 16 - " . $carbonDate->endOfMonth()->format('d') . " " . $carbonDate->format('Y');
                $monthPaylipFor = $carbonDate->format('F') . " 1 - " . $carbonDate->endOfMonth()->format('d') . " " . $carbonDate->format('Y');


                $preparedBy = User::where('user_id', $user->id)
                    ->join('plantilla_payslip', 'plantilla_payslip.user_id', 'users.id')
                    ->whereMonth('plantilla_payslip.start_date', Carbon::parse($month)->month)
                    ->whereYear('plantilla_payslip.start_date', Carbon::parse($month)->year)
                    ->select('plantilla_payslip.prepared_by_name as name', 'plantilla_payslip.prepared_by_position as position')
                    ->first();
        
        
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

    public function resetVariables(){
        $this->resetValidation();
        $this->payslip = null;
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
        $this->other_deductions = null;
        $this->payslipDate = null;
    }
}
