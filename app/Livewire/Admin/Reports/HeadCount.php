<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;

class HeadCount extends Component
{
    public function render()
    {
        $totalEmployees = User::where('user_role', 'emp')->count();
        $newEmployeesThisMonth = User::where('user_role', 'emp')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $departmentCounts = User::where('user_role', 'emp')
            ->join('payrolls', 'payrolls.user_id', 'users.id')
            ->selectRaw('payrolls.department, count(*) as count')
            ->groupBy('department')
            ->get();

        return view('livewire.admin.reports.head-count',[
           'totalEmployees' => $totalEmployees,
            'newEmployeesThisMonth' => $newEmployeesThisMonth,
            'departmentCounts' => $departmentCounts,
        ]);
    }
}
