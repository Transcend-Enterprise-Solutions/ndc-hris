<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Models\CosPayrolls;
use App\Models\CosRegPayrolls;
use App\Models\OfficeDivisions;
use App\Models\Payrolls;
use App\Models\PayrollSignatories;
use App\Models\Positions;
use App\Models\SalaryGrade;
use App\Models\Signatories;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagementTable extends Component
{
    use WithPagination;
    public $addRole;
    public $editRole;
    public $employees;
    public $positions;
    public $office_divisions;
    public $userId;
    public $name;
    public $employee_number;
    public $position;
    public $user_role;
    public $admin_email;
    public $office_division;
    public $password;
    public $cpassword;
    public $search;
    public $search2;
    public $search3;
    public $deleteId;
    public $deleteMessage;
    public $add;
    public $data;
    public $settings;
    public $settingsId;
    public $settings_data;
    public $settingsData = [['value' => '']];
    public $salaryGrades;
    public $editingId = null;
    public $isEditing = false;
    public $editedData = [];
    public $showSGModal = false;
    public $salaryGradeData = [
        'salary_grade' => '',
        'step1' => '', 'step2' => '', 'step3' => '', 'step4' => '',
        'step5' => '', 'step6' => '', 'step7' => '', 'step8' => '',
    ];
    public $addPosition;
    public $editPosition;

    public function mount(){
        $this->employees = User::where('user_role', '=', 'emp')->get();
        $this->positions = Positions::all();
        $this->office_divisions = OfficeDivisions::all();
        $this->salaryGrades = SalaryGrade::orderBy('salary_grade')->get();
    }

    public function render(){
        $admins = Admin::join('users', 'users.id', 'admin.user_id')
                ->leftJoin('payrolls', 'payrolls.user_id', 'admin.payroll_id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'admin.payroll_id')
                ->where('users.user_role', '!=', 'emp')
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->select(
                    'admin.*',
                    'users.user_role',
                    DB::raw('COALESCE(payrolls.name, cos_reg_payrolls.name) as name'),
                    DB::raw('COALESCE(payrolls.employee_number, cos_reg_payrolls.employee_number) as employee_number'),
                    DB::raw('COALESCE(payrolls.office_division, cos_reg_payrolls.office_division) as office_division'),
                    DB::raw('COALESCE(payrolls.position, cos_reg_payrolls.position) as position'),
                    DB::raw('CASE WHEN payrolls.user_id IS NOT NULL THEN "Plantilla" ELSE "COS" END as employee_type')
                )
                ->paginate(5);

        $officeDivisions = OfficeDivisions::all();
        $positions = Positions::all();

        $empPos = User::where('user_role', 'emp')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->select('users.*', 'positions.position', 'office_divisions.office_division')
                ->when($this->search3, function ($query) {
                    return $query->search3(trim($this->search3));
                })
                ->paginate(5);

        return view('livewire.admin.role-management-table',[
            'admins' => $admins,
            'officeDivisions' => $officeDivisions,
            'positions' => $positions,
            'empPos' => $empPos,
        ]);
    }

    public function toggleAddSettings($data)
    {
        $this->data = $data;
        $this->settings = true;
        $this->add = true;
        $this->settingsData = [['value' => '']];
    }

    public function addNewSetting()
    {
        $this->settingsData[] = ['value' => ''];
    }

    public function removeSetting($index)
    {
        unset($this->settingsData[$index]);
        $this->settingsData = array_values($this->settingsData);
    }

    public function toggleDeleteSettings($id, $data){ 
        $this->deleteId = $id;
        $this->data = $data;
        $this->deleteMessage = $data;
    }

    public function toggleEditSettings($id, $data){
        $this->settings = true;  
        $this->settingsId = $id;
        $this->data = $data;
        if($data == "office/division"){
            $officeDivisions = OfficeDivisions::where('id', $this->settingsId)->first();
            $this->settings_data = $officeDivisions->office_division;
        }else if($data == "position"){
            $positions = Positions::where('id', $this->settingsId)->first();
            $this->settings_data = $positions->position;
        }
    }

    public function saveSettings(){
        $this->validate([
            'settingsData.*.value' => 'required|string|max:255',
        ]);
        try {
            $message = null;
            foreach ($this->settingsData as $setting) {
                if ($this->data == "office/division") {
                    OfficeDivisions::create([
                        'office_division' => $setting['value'],
                    ]);
                    $message = "Office/Division(s) added successfully!";
                } else if ($this->data == "position") {
                    Positions::create([
                        'position' => $setting['value'],
                    ]);
                    $message = "Position(s) added successfully!";
                }
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        } catch(Exception $e) {
            throw $e;
        }
    }

    public function toggleEditRole($userId){
        $this->editRole = true;
        $this->userId = $userId;
        try {
            $admin = Admin::where('admin.user_id', $userId)
                ->join('users', 'users.id', 'admin.user_id')
                ->join('payrolls', 'payrolls.user_id', 'admin.payroll_id')
                ->select(
                    'admin.*', 
                    'payrolls.name', 
                    'payrolls.employee_number', 
                    'payrolls.office_division', 
                    'payrolls.position', 
                    'users.user_role',
                    'users.email')
                ->first();
            if ($admin) {
                $this->name = $admin->name;
                $this->user_role = $admin->user_role;
                $this->admin_email = $admin->email;
                $this->office_division = $admin->office_division;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddRole(){
        $this->editRole = true;
        $this->addRole = true;
    }

    public function toggleEditPosition($userId){
        $this->editPosition = true;
        $this->userId = $userId;
        try {
            $empPos = User::where('users.id', $userId)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->select('users.*', 'positions.position', 'office_divisions.office_division')
                    ->when($this->search3, function ($query) {
                        return $query->search(trim($this->search3));
                    })
                    ->first();
            if ($empPos) {
                $this->userId = $empPos->id;
                $this->name = $empPos->name;
                $this->position = $empPos->position;
                $this->office_division = $empPos->office_division;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function saveRole(){
        try {
            $user = User::where('id', $this->userId)->first();
            if($user){
                if($this->addRole){
                    $this->validate([
                        'user_role' => 'required',
                        'office_division' => 'required',
                        'admin_email' => 'required|email|unique:users,email',
                        'password' => 'required|min:8',
                        'cpassword' => 'required|same:password',
                    ]);

                    if (!$this->isPasswordComplex($this->password)) {
                        $this->addError('password', 'The password must contain at least one uppercase letter, one number, and one special character.');
                        return;
                    }

                    $payrollId = null;
                    $payrolls = Payrolls::where('user_id', $user->id)->first();
                    $cosPayrolls = CosRegPayrolls::where('user_id', $user->id)->first();

                    if($payrolls){
                        $payrollId = $payrolls->user_id;
                    }else if($cosPayrolls){
                        $payrollId = $cosPayrolls->user_id;
                    }else{
                        $this->dispatch('swal', [
                            'title' => "This employee don't have a payroll yet!",
                            'icon' => 'error'
                        ]);
                        return;
                    }

                    $admin = User::create([
                        'name' => $user->name,
                        'email' => $this->admin_email,
                        'password' => $this->password,
                        'user_role' => $this->user_role,
                    ]);
                    Admin::create([
                        'user_id' => $admin->id,
                        'payroll_id' => $payrollId,
                        'office_division' => $this->office_division,
                    ]);
                }else{
                    $admin = Admin::where('user_id', $user->id)
                    ->first();

                    if($this->user_role == "emp"){
                        $admin->delete();
                        $user->delete();
                        $this->resetVariables();
                        $this->dispatch('swal', [
                            'title' => "Account role updated successfully!",
                            'icon' => 'success'
                        ]);
                        return;
                    }

                    $this->validate([
                        'user_role' => 'required',
                        'office_division' => 'required',
                        'admin_email' => 'required|email',
                    ]);

                    $admin = Admin::where('user_id', $user->id)
                            ->first();
                    $admin->update([
                        'office_division' => $this->office_division,
                    ]);
                    $user->update([
                        'email' => $this->admin_email,
                        'user_role' => $this->user_role,
                    ]);
                }
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => "Account role updated successfully!",
                'icon' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Account role update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function toggleDelete($userId, $message){
        $this->deleteMessage = $message;
        $this->deleteId = $userId;
    }

    public function deleteData(){
        try {
            $message = null;

            if($this->data){
                if($this->data == "office/division"){
                    $officeDivisions = OfficeDivisions::where('id', $this->deleteId)->first();
                    $officeDivisions->delete();
                    $message = "Office/Division deleted successfully!";
                }else if($this->data == "position"){
                    $positions = Positions::where('id', $this->deleteId)->first();
                    $positions->delete();
                    $message = "Position deleted successfully!";
                }else if($this->data == "salary grade"){
                    $sg = SalaryGrade::where('id', $this->deleteId)->first();
                    $sg->delete();
                    $message = "Salary grade deleted successfully!";
                }
            }else{
                $user = User::where('id', $this->deleteId)->first();
                if ($user) {
                    switch($this->deleteMessage){
                        case "role":
                            $user->delete();
                            $user->admin()->delete();
                            $message = "Role deleted successfully!";
                            break;
                        case "payroll signatory":
                            $user->signatories()->where('signatory_type', 'payroll')->delete();
                            $message = "Payroll signatory deleted successfully!";
                            break;
                        case "payslip signatory":
                            $user->signatories()->where('signatory_type', 'payslip')->delete();
                            $message = "Payslip signatory deleted successfully!";
                            break;
                        default:
                            break;
                    }             
                }
            }

            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Deletion of " . $this->deleteMessage . "was unsuccessful!",
                'icon' => 'error'
            ]);
            $this->resetVariables();
            throw $e;
        }
    }

    public function editSG($id){
        $this->isEditing = true;
        $this->editingId = $id;
        $salaryGrade = $this->salaryGrades->firstWhere('id', $id);
        
        $this->salaryGradeData = [
            'salary_grade' => $salaryGrade->salary_grade,
            'step1' => $salaryGrade->step1,
            'step2' => $salaryGrade->step2,
            'step3' => $salaryGrade->step3,
            'step4' => $salaryGrade->step4,
            'step5' => $salaryGrade->step5,
            'step6' => $salaryGrade->step6,
            'step7' => $salaryGrade->step7,
            'step8' => $salaryGrade->step8,
        ];
        
        $this->showSGModal = true;
    }

    public function openSGModal(){
        $this->showSGModal = true;
    }

    public function saveSalaryGrade(){
        try{
            $message = null;
            $this->validate([
                'salaryGradeData.salary_grade' => 'required|integer',
                'salaryGradeData.step1' => 'required|numeric',
                'salaryGradeData.step2' => 'required|numeric',
                'salaryGradeData.step3' => 'required|numeric',
                'salaryGradeData.step4' => 'required|numeric',
                'salaryGradeData.step5' => 'required|numeric',
                'salaryGradeData.step6' => 'required|numeric',
                'salaryGradeData.step7' => 'required|numeric',
                'salaryGradeData.step8' => 'required|numeric',
            ]);
            if ($this->isEditing) {
                SalaryGrade::find($this->editingId)->update($this->salaryGradeData);
                $message = "Salary grade updated successfully!";
            } else {
                SalaryGrade::create($this->salaryGradeData);
                $message = "Salary grade added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toggleDeleteSG($id, $data){
        $this->deleteId = $id;
        $this->data = $data;
        $this->deleteMessage = $data;
    }


    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->editRole = null;
        $this->addRole = null;
        $this->admin_email = null;
        $this->password = null;
        $this->cpassword = null;
        $this->office_division = null;
        $this->deleteId = null;
        $this->deleteMessage = null;
        $this->settings = null;
        $this->settingsId = null;
        $this->add = null;
        $this->settings_data = null;
        $this->settingsData = [['value' => '']];
        $this->data = null;
        $this->showSGModal = null;
        $this->editingId = null;
        $this->salaryGradeData = [
            'salary_grade' => '',
            'step1' => '', 'step2' => '', 'step3' => '', 'step4' => '',
            'step5' => '', 'step6' => '', 'step7' => '', 'step8' => '',
        ];
    }

    private function isPasswordComplex($password){
        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsNumber = preg_match('/\d/', $password);
        $containsSpecialChar = preg_match('/[^A-Za-z0-9]/', $password); // Changed regex to include special characters
        return $containsUppercase && $containsNumber && $containsSpecialChar;
    }
}
