<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\UserData;
use Livewire\WithPagination;

class EmployeeTable extends Component
{
    use WithPagination;

    public $selectedUser;
    public $selectedUserData;
    public $full_address;

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
        $this->full_address = $this->selectedUserData->p_house_street . ' ' . 
                              $this->selectedUserData->permanent_selectedBarangay . ' ' . 
                              $this->selectedUserData->permanent_selectedCity . ', ' . 
                              $this->selectedUserData->permanent_selectedProvince . ', ' . 
                              $this->selectedUserData->permanent_selectedRegion;
    }

    public function closeUserProfile()
    {
        $this->selectedUser = null;
        $this->selectedUserData = null;
        $this->full_address = null;
    }
}
