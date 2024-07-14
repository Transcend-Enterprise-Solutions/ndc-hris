<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\UserData;
use Livewire\WithPagination;
use App\Models\EmployeesChildren;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;

class EmployeeTable extends Component
{
    use WithPagination;

    public $employeesChildren;
    public $selectedUser;
    public $selectedUserData;
    public $p_full_address;
    public $r_full_address;
    public $childrenNames;
    public $childrenBirthDates;

    public function render()
    {
        $users = User::join('user_data', 'users.id', '=', 'user_data.user_id')
                        ->select(
                            'users.id',
                            'users.name', 
                            'user_data.sex', 
                            'user_data.citizenship', 
                            'user_data.civil_status', 
                            'user_data.mobile_number', 
                            'user_data.p_house_street', 
                            'user_data.permanent_selectedBarangay', 
                            'user_data.permanent_selectedCity', 
                            'user_data.permanent_selectedProvince', 
                            'user_data.permanent_selectedRegion'
                        )
                        ->paginate(10);

        return view('livewire.admin.employee-table', [
            'users' => $users
        ]);
    }

    public function showUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->selectedUserData = UserData::where('user_id', $userId)->first();
        $this->employeesChildren = EmployeesChildren::where('user_id', $userId)->get();

        $this->childrenNames = $this->employeesChildren->pluck('childs_name')->implode(', ');
        $this->childrenBirthDates = $this->employeesChildren->pluck('childs_birth_date')->implode(', ');

        $this->p_full_address = $this->selectedUserData->p_house_street . ' ' . 
                              $this->selectedUserData->permanent_selectedBarangay . ' ' . 
                              $this->selectedUserData->permanent_selectedCity . ', ' . 
                              $this->selectedUserData->permanent_selectedProvince . ', ' . 
                              $this->selectedUserData->permanent_selectedRegion;
        $this->r_full_address = $this->selectedUserData->r_house_street . ' ' . 
                              $this->selectedUserData->residential_selectedBarangay . ' ' . 
                              $this->selectedUserData->residential_selectedCity . ', ' . 
                              $this->selectedUserData->residential_selectedProvince . ', ' . 
                              $this->selectedUserData->residential_selectedRegion;
    }

    public function closeUserProfile()
    {
        $this->selectedUser = null;
        $this->selectedUserData = null;
        $this->p_full_address = null;
        $this->r_full_address = null;
        $this->employeesChildren = null;
        $this->childrenNames = null;
        $this->childrenBirthDates = null;
    }

    public function exportUsers()
    {
        return Excel::download(new EmployeesExport, 'EmployeesList.xlsx');
    }
}
