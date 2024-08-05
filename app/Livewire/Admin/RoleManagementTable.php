<?php

namespace App\Livewire\Admin;

use App\Models\Payrolls;
use App\Models\PayrollSignatories;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class RoleManagementTable extends Component
{
    use WithPagination;
    public $addRole;
    public $editRole;
    public $employees;
    public $userId;
    public $name;
    public $employee_number;
    public $position;
    public $user_role;
    public $search;
    public $search2;

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

        $sigantories = Payrolls::join('payroll_signatories', 'payroll_signatories.user_id', 'payrolls.user_id')
                    ->when($this->search2, function ($query) {
                        return $query->search2(trim($this->search2));
                    })
                    ->paginate(5);

        return view('livewire.admin.role-management-table',[
            'users' => $users,
            'payrollSignatories' => $sigantories,
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
                $this->employee_number = $user->employee_number;
                $this->position = $user->position;
                $this->user_role = $user->user_role;
            } else {
                $this->resetPayrollFields();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddRole(){
        $this->editRole = true;
        $this->addRole = true;
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

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->editRole = null;
        $this->addRole = null;
    }
}
