<?php

namespace App\Livewire\Admin;

use App\Exports\CosPayrollListExport;
use App\Exports\PayrollListExport;
use App\Models\Admin;
use App\Models\CosPayrolls;
use App\Models\CosRegPayrolls;
use App\Models\GeneralPayroll;
use App\Models\Payrolls;
use App\Models\SalaryGrade;
use App\Models\Signatories;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PayrollManagementTable extends Component
{
    use WithPagination, WithFileUploads;
    public $sortColumn = false;
    public $search;
    public $search2;
    public $allCol = false;
    public $columns = [
        'name' => true,
        'employee_number' => true,
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

    public $cosColumns = [
        'name' => true,
        'employee_number' => true,
        'position' => true,
        'office_division' => true,
        'sg_step' => true,
        'rate_per_month' => true,
    ];

    public $addPayroll;
    public $editPayroll;
    public $employees;
    public $userId;
    public $name;
    public $employee_number;
    public $office_division;
    public $position;
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
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function mount(){
        $this->employees = User::where('user_role', '=', 'emp')->get();
        $this->empPayrolled = User::where('user_role', '=', 'emp')
                            ->join('payrolls', 'payrolls.user_id', 'users.id')->get();
        $this->salaryGrade = SalaryGrade::all();
    }

    public function render(){
        $payrolls = Payrolls::when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->paginate(5);

        $cosPayrolls = CosRegPayrolls::when($this->search2, function ($query) {
                        return $query->search(trim($this->search2));
                    })
                    ->paginate($this->pageSize);
        
        if($this->userId){
            $user = User::where('id', $this->userId)->first();
            $this->employee_number = $user->emp_code;
        }

        
        if($this->rate_per_month && $this->personal_economic_relief_allowance){
            $this->gross_amount = $this->rate_per_month + $this->personal_economic_relief_allowance;
        }

        $this->getRate();

        $plantillaPayrollSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
            ->where('signatories.signatory_type', 'plantilla_payroll')->get();
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

        $cosPayrollSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
            ->where('signatories.signatory_type', 'cos_payroll')->get();
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
        $payrollId = Admin::where('user_id', $user->id)->select('payroll_id')->first();
        $preparedBy = Payrolls::where('payrolls.id', $payrollId->payroll_id)->first();
        $preparedBySignature = Signatories::where('user_id', $user->id)->first();

        $plantillaPayslipSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                            ->where('signatories.signatory_type', 'plantilla_payslip')->get();
        $plantillaNotedBy = $plantillaPayslipSignatories->where('signatory', 'Noted By')->first();
        $plantillaPayslipSigns = [
            'notedBy' => $plantillaNotedBy,
        ];

        $cosPayslipSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                            ->where('signatories.signatory_type', 'cos_payslip')->get();
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

        return view('livewire.admin.payroll-management-table', [
            'payrolls' => $payrolls,
            'cosPayrolls' => $cosPayrolls,
            'plantillaPayslipSigns' => $plantillaPayslipSigns,
            'cosPayslipSigns' => $cosPayslipSigns,
            'plantillaPayroll' => $plantillaPayroll,
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
    
    public function updatedSignatures($value, $key){
        $this->validateOnly($key, [
            "signatures.$key" => 'image|max:1024', // 1MB Max
        ]);
    }

    public function toggleDropdown(){
        $this->sortColumn = !$this->sortColumn;
    }

    public function toggleAllColumn() {
        if ($this->allCol) {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->columns[$col] = false;
            }
            $this->allCol = false;
        } else {
            foreach (array_keys($this->columns) as $col) {
                if($col == 'name' || $col == 'employee_number' || $col == 'position' || $col == 'sg_step' || $col == 'rate_per_month'){
                    continue;
                }
                $this->columns[$col] = true;
            }
            $this->allCol = true;
        }
    }

    public function exportExcel(){
        $filters = [
            'search' => $this->search,
        ];
        $fileName = 'Plantilla Payroll List.xlsx';
        return Excel::download(new PayrollListExport($filters), $fileName);
    }

    public function exportCosExcel(){
        $filters = [
            'search' => $this->search2,
        ];
        $fileName = 'COS Payroll List.xlsx';
        return Excel::download(new CosPayrollListExport($filters), $fileName);
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
            $cos = CosRegPayrolls::where('user_id', $user->id)->first();
            if(!$cos){
                $payrollData = [
                    'user_id' => $this->userId,
                    'employee_number' => $this->employee_number,
                    'office_division' => $this->office_division,
                    'position' => $this->position,
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

    public function toggleEditCosPayroll($userId){
        $this->editCosPayroll = true;
        $this->userId = $userId;
        try {
            $payroll = CosRegPayrolls::where('user_id', $userId)->first();
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
            $payroll = CosRegPayrolls::where('user_id', $this->userId)->first();
            $user = User::where('id', $this->userId)->first();
            $sg_step = implode('-', [$this->sg, $this->step]);
            $message = null;
            $icon = null;
            $plantilla = Payrolls::where('user_id', $user->id)->first();
            if(!$plantilla){
                $payrollData = [
                    'user_id' => $this->userId,
                    'employee_number' => $this->employee_number,
                    'office_division' => $this->office_division,
                    'position' => $this->position,
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
                    $payrollData['name'] = $user->name;
                    CosRegPayrolls::create($payrollData);
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

    public function toggleDelete($userId){
        $this->deleteMessage = "payroll";
        $this->deleteId = $userId;
        $this->toDelete = 'plantilla';
    }

    public function toggleCosDelete($userId){
        $this->deleteMessage = "payroll";
        $this->deleteId = $userId;
        $this->toDelete = 'cos';
    }

    public function deleteData(){
        try {
            $user = User::where('id', $this->deleteId)->first();
            if ($user) {
                if($this->toDelete === 'plantilla'){
                    $user->payrolls()->delete();
                    $message = "Plantilla payroll deleted successfully!";
                }else{
                    $user->cosPayrolls()->delete();
                    $message = "COS payroll deleted successfully!";
                }
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

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->addPayroll = null;
        $this->editPayroll = null;
        $this->addCosPayroll = null;
        $this->editCosPayroll = null;
        $this->name = null;
        $this->employee_number = null;
        $this->office_division = null;
        $this->position = null;
        $this->sg = null;
        $this->step = null;
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
        $this->deleteId = null;
        $this->deleteMessage = null;
        $this->toDelete = null;
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
