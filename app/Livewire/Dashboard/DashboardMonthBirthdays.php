<?php

namespace App\Livewire\Dashboard;

use App\Models\UserData;
use Carbon\Carbon;
use Livewire\Component;

class DashboardMonthBirthdays extends Component
{
    public function render()
    {
        $birthdayEmployees = UserData::whereMonth('date_of_birth', now()->month)
                ->orderByRaw('DAY(date_of_birth) ASC') 
                ->get();

        if($birthdayEmployees){
            foreach($birthdayEmployees as $emp){
                $emp->age = Carbon::parse($emp->date_of_birth)->age;
            }
        }

        return view('livewire.dashboard.dashboard-month-birthdays', [
            'birthdayEmployees' => $birthdayEmployees,
        ]);
    }
}
