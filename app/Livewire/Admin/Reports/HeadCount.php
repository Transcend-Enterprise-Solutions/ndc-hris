<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\AttendanceExport;
use App\Models\EmployeesDtr;
use Livewire\Component;
use App\Models\User;
use App\Models\DocRequest;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeReportExport;
use App\Exports\DocRequestExport;

class HeadCount extends Component
{
    public $date;
    public $month;

    public function render()
    {
        $totalEmployees = User::where('user_role', 'emp')->count();

        $newEmployeesThisMonth = User::where('user_role', 'emp');
        if ($this->month) {
            $date = Carbon::createFromFormat('Y-m', $this->month);
            $newEmployeesThisMonth = User::where('user_role', 'emp')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        } else {
            $newEmployeesThisMonth = User::where('user_role', 'emp')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();
        }

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
        } else {
            $dailyAttendance = 0;
        }

        $docRequestsCount = DocRequest::when($this->month, function ($query) {
            $date = Carbon::createFromFormat('Y-m', $this->month);
            return $query->whereYear('date_requested', $date->year)
                         ->whereMonth('date_requested', $date->month);
        }, function ($query) {
            return $query->whereYear('date_requested', Carbon::now()->year)
                         ->whereMonth('date_requested', Carbon::now()->month);
        })->count();

        return view('livewire.admin.reports.head-count', [
            'totalEmployees' => $totalEmployees,
            'newEmployeesThisMonth' => $newEmployeesThisMonth,
            'departmentCounts' => $departmentCounts,
            'dailyAttendance' => $dailyAttendance,
            'docRequestsCount' => $docRequestsCount,
        ]);
    }

    public function exportTotalEmployee()
    {
        try {
            $filters = null;
            return Excel::download(new EmployeeReportExport($filters), 'EmployeesList.xlsx');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportTotalEmployeeThisMonth()
    {
        try {
            $month = $this->month ? Carbon::createFromFormat('Y-m', $this->month)->format('Y-m') : now()->format('Y-m');
            $filters = [
                'month' => $month,
            ];
            $filename = $month . ' EmployeesList.xlsx';
            return Excel::download(new EmployeeReportExport($filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportTotalEmployeeInDepartment($department)
    {
        try {
            $filters = [
                'department' => $department,
            ];
            $filename = $department . ' EmployeesList.xlsx';
            return Excel::download(new EmployeeReportExport($filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportTotalEmployeeDaily()
    {
        try {
            $filters = [
                'date' => $this->date,
            ];
            $filename = $this->date . ' EmployeesList.xlsx';
            return Excel::download(new AttendanceExport($filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportDocRequests()
    {
        try {
            $month = $this->month ? Carbon::createFromFormat('Y-m', $this->month)->format('Y-m') : now()->format('Y-m');
            $filters = [
                'month' => $month,
            ];
            $filename = $month . ' DocRequestsList.xlsx';
            return Excel::download(new DocRequestExport($filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
