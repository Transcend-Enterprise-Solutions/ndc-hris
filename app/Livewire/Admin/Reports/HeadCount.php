<?php

namespace App\Livewire\Admin\Reports;

use App\Models\EmployeesDtr;
use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeReportExport;

class HeadCount extends Component
{
    public $date;

    public function render(){
        $totalEmployees = User::where('user_role', 'emp')->count();
        $newEmployeesThisMonth = User::where('user_role', 'emp')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        $departmentCounts = User::where('user_role', 'emp')
            ->join('payrolls', 'payrolls.user_id', 'users.id')
            ->selectRaw('payrolls.department, count(*) as count')
            ->groupBy('department')
            ->get();

        $dailyAttendance = null;
        if ($this->date) {
            $dailyAttendance = EmployeesDtr::where('date', $this->date)
                            ->where(function ($query) {
                                $query->where('remarks', 'Present')
                                        ->orWhere('remarks', 'Late');
                            })
                            ->count();
        }else{
            $dailyAttendance = 0;
        }

        return view('livewire.admin.reports.head-count',[
            'totalEmployees' => $totalEmployees,
            'newEmployeesThisMonth' => $newEmployeesThisMonth,
            'departmentCounts' => $departmentCounts,
            'dailyAttendance' => $dailyAttendance,
        ]);
    }

    public function exportTotalEmployee(){
        try{
            $filters = null;
            return Excel::download(new EmployeeReportExport($filters), 'EmployeesList.xlsx');
        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportTotalEmployeeThisMonth(){
        try{

        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportTotalEmployeeInDepartment(){
        try{

        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportTotalEmployeeDaily(){
        try{

        }catch(Exception $e){
            throw $e;
        }
    }
}
