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
    public $docRequestMonth;

    public function render()
    {
        $totalEmployees = User::where('user_role', 'emp')->count();

        $newEmployeesThisMonth = User::where('user_role', 'emp');
        if ($this->month) {
            $date = Carbon::createFromFormat('Y-m', $this->month);
            $newEmployeesThisMonth = $newEmployeesThisMonth
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        } else {
            $newEmployeesThisMonth = $newEmployeesThisMonth
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->count();
        }

        $departmentCounts = User::where('user_role', 'emp')
            ->join('payrolls', 'payrolls.user_id', 'users.id')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->selectRaw('office_divisions.office_division, count(*) as count')
            ->groupBy('office_division')
            ->get();

        $dailyAttendance = $this->date ? EmployeesDtr::where('date', $this->date)
                            ->where(function ($query) {
                                $query->where('remarks', 'Present')
                                        ->orWhere('remarks', 'Late');
                            })
                            ->count() : 0;

        $docRequestsCount = DocRequest::when($this->docRequestMonth, function ($query) {
            $date = Carbon::createFromFormat('Y-m', $this->docRequestMonth);
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
            $month = $this->docRequestMonth ? Carbon::createFromFormat('Y-m', $this->docRequestMonth)->format('Y-m') : now()->format('Y-m');
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
