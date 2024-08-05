<?php

namespace App\Livewire\Admin;

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
    public $search;
    public $search2;
    public $search3;

    public function mount(){
        $this->employees = User::all();
    }

    public function render(){
        $users = Payrolls::join('users', 'users.id', 'payrolls.user_id')
                    ->where('users.user_role', '!=', 'emp')
                    ->when($this->search, function ($query) {
                        return $query->search(trim($this->search));
                    })
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
            'users' => $users,
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
            $user = User::join('payrolls', 'payrolls.user_id', 'users.id')
                    ->where('users.id', $userId)
                    ->first();
            if ($user) {
                $this->name = $user->name;
                $this->user_role = $user->user_role;
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
                $user->update([
                    'user_role' => $this->user_role,
                ]);
            }
            $this->resetVariables();
            $this->dispatch('notify', [
                'message' => "Account role updated successfully!",
                'type' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Account role update was unsuccessful!",
                'type' => 'error'
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
                    $signatory->update([
                        'signatory' => $this->signatory,
                    ]);
                }
                $message = "Payroll signatory updated successfully!";
            }else{
                Signatories::create([
                    'user_id' => $this->userId,
                    'signatory' => $this->signatory,
                    'signatory_type' => 'payroll',
                ]);
                $message = "Payroll signatory added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Payroll signatory update was unsuccessful!",
                'type' => 'error'
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
                    $signatory->update([
                        'signatory' => $this->signatory,
                    ]);
                }
                $message = "Payslip signatory updated successfully!";
            }else{
                Signatories::create([
                    'user_id' => $this->userId,
                    'signatory' => $this->signatory,
                    'signatory_type' => 'payslip',
                ]);
                $message = "Payslip signatory added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'message' => "Payslip signatory update was unsuccessful!",
                'type' => 'error'
            ]);
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
    }
}
