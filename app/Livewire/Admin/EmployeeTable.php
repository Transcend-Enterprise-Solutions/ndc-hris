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
    // public $selectedUser;
    public $selectedUserData;
    public $p_full_address;
    public $r_full_address;
    public $childrenNames;
    public $childrenBirthDates;

    public $filters = [
        'name' => true,
        'date_of_birth' => false,
        'place_of_birth' => false,
        'sex' => false,
        'citizenship' => false,
        'civil_status' => false,
        'height' => false,
        'weight' => false,
        'blood_type' => false,
        'gsis' => false,
        'pagibig' => false,
        'philhealth' => false,
        'sss' => false,
        'tin' => false,
        'agency_employee_no' => false,
        // 'permanent_selectedProvince' => false,
        // 'permanent_selectedCity' => false,
        // 'permanent_selectedBarangay' => false,
        // 'p_house_street' => false,
        // 'permanent_selectedZipcode' => false,
        // 'residential_selectedProvince' => false,
        // 'residential_selectedCity' => false,
        // 'residential_selectedBarangay' => false,
        // 'r_house_street' => false,
        // 'residential_selectedZipcode' => false,
        // 'tel_number' => false,
        // 'mobile_number' => false,
        // 'email' => false,
    ];

    public $selectedUser = null;
    public $dropdownOpen = false;

    protected $listeners = [
        'exportUsers'
    ];

    public function toggleDropdown()
    {
        $this->dropdownOpen = !$this->dropdownOpen;
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.id')
            ->when($this->filters['name'], function ($query) {
                $query->addSelect('users.name');
            })
            ->when($this->filters['date_of_birth'], function ($query) {
                $query->addSelect('user_data.date_of_birth');
            })
            ->when($this->filters['place_of_birth'], function ($query) {
            $query->addSelect('user_data.place_of_birth');
            })
            ->when($this->filters['sex'], function ($query) {
            $query->addSelect('user_data.sex');
            })
            ->when($this->filters['civil_status'], function ($query) {
            $query->addSelect('user_data.civil_status');
            })
            ->when($this->filters['citizenship'], function ($query) {
            $query->addSelect('user_data.citizenship');
            })
            ->when($this->filters['height'], function ($query) {
            $query->addSelect('user_data.height');
            })
            ->when($this->filters['weight'], function ($query) {
            $query->addSelect('user_data.weight');
            })
            ->when($this->filters['blood_type'], function ($query) {
            $query->addSelect('user_data.blood_type');
            })
            ->when($this->filters['gsis'], function ($query) {
            $query->addSelect('user_data.gsis');
            })
            ->when($this->filters['pagibig'], function ($query) {
            $query->addSelect('user_data.pagibig');
            })
            ->when($this->filters['philhealth'], function ($query) {
            $query->addSelect('user_data.philhealth');
            })
            ->when($this->filters['sss'], function ($query) {
            $query->addSelect('user_data.sss');
            })
            ->when($this->filters['tin'], function ($query) {
            $query->addSelect('user_data.tin');
            })
            ->when($this->filters['agency_employee_no'], function ($query) {
            $query->addSelect('user_data.agency_employee_no');
            })
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
                              $this->selectedUserData->permanent_selectedZipcode;
        $this->r_full_address = $this->selectedUserData->r_house_street . ' ' . 
                              $this->selectedUserData->residential_selectedBarangay . ' ' . 
                              $this->selectedUserData->residential_selectedCity . ', ' . 
                              $this->selectedUserData->residential_selectedProvince . ', ' . 
                              $this->selectedUserData->residential_selectedZipcode;
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

    public function exportUsers($filters)
    {
        $this->filters = $filters;
        return Excel::download(new EmployeesExport($this->filters), 'EmployeesList.xlsx');
    }
}
