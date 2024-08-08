<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Models\Payrolls;
use App\Models\PayrollSignatories;
use App\Models\Signatories;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagementTable extends Component
{
    use WithPagination;
    public $addRole;
    public $editRole;
    public $addSignatory;
    public $editSignatory;
    public $addPayslipSignatory;
    public $editPayslipSignatory;
    public $signatory;
    public $employees;
    public $userId;
    public $name;
    public $employee_number;
    public $position;
    public $user_role;
    public $admin_email;
    public $department;
    public $password;
    public $cpassword;
    public $search;
    public $search2;
    public $search3;
    public $deleteId;
    public $deleteMessage;

    public function mount(){
        $this->employees = User::where('user_role', '=', 'emp')->get();
    }

    public function render(){
        $admins = Admin::join('users', 'users.id', 'admin.user_id')
                    ->join('payrolls', 'payrolls.id', 'admin.payroll_id')
                    ->where('users.user_role', '!=', 'emp')
                    ->when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
                    ->select(
                        'admin.*', 
                        'payrolls.name', 
                        'payrolls.employee_number', 
                        'payrolls.office_division', 
                        'payrolls.position', 
                        'users.user_role')
                    ->paginate(5);

        $payrollSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                    ->where('signatory_type', 'payroll')
                    ->when($this->search2, function ($query) {
                        return $query->search2(trim($this->search2));
                    })
                    ->orderBy('signatory', 'ASC')
                    ->paginate(5);

        $payslipSignatories = Payrolls::join('signatories', 'signatories.user_id', 'payrolls.user_id')
                    ->where('signatory_type', 'payslip')
                    ->when($this->search3, function ($query) {
                        return $query->search3(trim($this->search3));
                    })
                    ->orderBy('signatory', 'ASC')
                    ->paginate(5);

        $a = $payrollSignatories->where('signatory', 'A')->first();
        $b = $payrollSignatories->where('signatory', 'B')->first();
        $c = $payrollSignatories->where('signatory', 'C')->first();
        $d = $payrollSignatories->where('signatory', 'D')->first();

        $notedBy = $payslipSignatories->where('signatory', 'Noted By')->first();

        $signs = [
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'd' => $d,
        ];

        $payslipSigns = [
            'notedBy' => $notedBy,
        ];

        return view('livewire.admin.role-management-table',[
            'admins' => $admins,
            'payrollSignatories' => $payrollSignatories,
            'payslipSignatories' => $payslipSignatories,
            'signs' => $signs,
            'payslipSigns' => $payslipSigns,
        ]);
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
                $this->department = $admin->department;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddRole(){
        $this->editRole = true;
        $this->addRole = true;
    }

    public function toggleEditSignatory($userId){
        $this->editSignatory = true;
        $this->userId = $userId;
        try {
            $user = User::join('signatories', 'signatories.user_id', 'users.id')
                    ->where('users.id', $userId)
                    ->where('signatories.signatory_type', 'payroll')
                    ->first();
            if ($user) {
                $this->name = $user->name;
                $this->signatory = $user->signatory;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddSignatory(){
        $this->editSignatory= true;
        $this->addSignatory= true;
    }

    public function toggleEditPayslipSignatory($userId){
        $this->editPayslipSignatory = true;
        $this->userId = $userId;
        try {
            $user = User::join('signatories', 'signatories.user_id', 'users.id')
                    ->where('users.id', $userId)
                    ->where('signatories.signatory_type', 'payslip')
                    ->first();
            if ($user) {
                $this->name = $user->name;
                $this->signatory = $user->signatory;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddPayslipSignatory(){
        $this->editPayslipSignatory= true;
        $this->addPayslipSignatory= true;
    }

    public function saveRole(){
        try {
            $user = User::where('id', $this->userId)->first();
            if($user){
                if($this->addRole){
                    $this->validate([
                        'user_role' => 'required',
                        'department' => 'required',
                        'admin_email' => 'required|email|unique:users,email',
                        'password' => 'required|min:8',
                        'cpassword' => 'required|same:password',
                    ]);

                    if (!$this->isPasswordComplex($this->password)) {
                        $this->addError('password', 'The password must contain at least one uppercase letter, one number, and one special character.');
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
                        'payroll_id' => $user->id,
                        'department' => $this->department,
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
                        'department' => 'required',
                        'admin_email' => 'required|email',
                    ]);

                    $admin = Admin::where('user_id', $user->id)
                            ->first();
                    $admin->update([
                        'department' => $this->department,
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

    public function saveSignatory(){
        try {
            $signatory = Signatories::where('user_id', $this->userId)
                        ->where('signatory_type', 'payroll')
                        ->first();
            $message = "";
            if($signatory){
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
                $message = "Payroll signatory updated successfully!";
            }else{
                $this->validate([
                    'signatory' => 'required',
                    'userId' => 'required',
                ]);

                Signatories::create([
                    'user_id' => $this->userId,
                    'signatory' => $this->signatory,
                    'signatory_icon' => 'payroll',
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
                        ->where('signatory_type', 'payslip')
                        ->first();
            $message = "";
            if($signatory){
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
                    'signatory_icon' => 'payslip',
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

    public function toggleDelete($userId, $message){
        $this->deleteMessage = $message;
        $this->deleteId = $userId;
    }

    public function deleteData(){
        try {
            $user = User::where('id', $this->deleteId)->first();
            if ($user) {
                $message = "";
                switch($this->deleteMessage){
                    case "role":
                        $user->delete();
                        $user->admin()->delete();
                        $this->resetVariables();
                        $message = "Role deleted successfully!";
                        break;
                    case "payroll signatory":
                        $user->signatories()->where('signatory_type', 'payroll')->delete();
                        $this->resetVariables();
                        $message = "Payroll signatory deleted successfully!";
                        break;
                    case "payslip signatory":
                        $user->signatories()->where('signatory_type', 'payslip')->delete();
                        $this->resetVariables();
                        $message = "Payslip signatory deleted successfully!";
                        break;
                    default:
                        break;
                } 
                $this->dispatch('swal', [
                    'title' => $message,
                    'icon' => 'success'
                ]);            
            }
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Deletion of " . $this->deleteMessage . "was unsuccessful!",
                'icon' => 'error'
            ]);
            $this->resetVariables();
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->editRole = null;
        $this->addRole = null;
        $this->editSignatory= null;
        $this->addSignatory= null;
        $this->editPayslipSignatory= null;
        $this->addPayslipSignatory= null;
        $this->signatory = null;
        $this->admin_email = null;
        $this->password = null;
        $this->cpassword = null;
        $this->department = null;
        $this->deleteId = null;
        $this->deleteMessage = null;
    }

    private function isPasswordComplex($password){
        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsNumber = preg_match('/\d/', $password);
        $containsSpecialChar = preg_match('/[^A-Za-z0-9]/', $password); // Changed regex to include special characters
        return $containsUppercase && $containsNumber && $containsSpecialChar;
    }
}
