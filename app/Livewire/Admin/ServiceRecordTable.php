<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceRecordTable extends Component
{
    use WithPagination;

    public $filters = [
        'name' => true,
        'date_of_birth' => true,
        'place_of_birth' => true,
        'sex' => true,
        'citizenship' => true,
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
        'permanent_selectedProvince' => false,
        'permanent_selectedCity' => false,
        'permanent_selectedBarangay' => false,
        'p_house_street' => false,
        'permanent_selectedZipcode' => false,
        'residential_selectedProvince' => false,
        'residential_selectedCity' => false,
        'residential_selectedBarangay' => false,
        'r_house_street' => false,
        'residential_selectedZipcode' => false,
        'active_status' => true,
        'appointment' => true,
        'date_hired' => true,
        'years_in_gov_service' => true,
        // 'tel_number' => false,
        // 'mobile_number' => false,
        // 'email' => false,
    ];

    public function render()
    {
        $users = User::where('user_role', 'emp')->paginate(10);

        return view('livewire.admin.service-record-table',[
            'users' => $users,
        ]);
    }
}
