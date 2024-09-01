<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\AttendanceExport;
use App\Models\EmployeesDtr;
use Livewire\Component;
use App\Models\User;
use App\Models\DocRequest;
use App\Models\Rating;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeReportExport;
use App\Exports\DocRequestExport;
use App\Exports\AverageOverallRatingsExport;

class HeadCount extends Component
{
    public $date;
    public $month;
    public $docRequestMonth;
    public $ratingsMonth;

    public function mount()
    {
        $this->ratingsMonth = Carbon::now()->format('Y-m');
        $this->docRequestMonth = Carbon::now()->format('Y-m');
        $this->month = Carbon::now()->format('Y-m');
    }


    public function render()
    {
        $totalEmployees = User::where('user_role', 'emp')->count();

        // New employees this month
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

        // Department counts
        $departmentCounts = User::where('user_role', 'emp')
            ->join('payrolls', 'payrolls.user_id', 'users.id')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->selectRaw('office_divisions.office_division, count(*) as count')
            ->groupBy('office_division')
            ->get();

        // Daily attendance
        $dailyAttendance = $this->date ? EmployeesDtr::where('date', $this->date)
                            ->where(function ($query) {
                                $query->where('remarks', 'Present')
                                      ->orWhere('remarks', 'Late');
                            })
                            ->count() : 0;

        // Document requests count
        $docRequestsCount = DocRequest::when($this->docRequestMonth, function ($query) {
            $date = Carbon::createFromFormat('Y-m', $this->docRequestMonth);
            return $query->whereYear('date_requested', $date->year)
                         ->whereMonth('date_requested', $date->month);
        }, function ($query) {
            return $query->whereYear('date_requested', Carbon::now()->year)
                         ->whereMonth('date_requested', Carbon::now()->month);
        })->count();

        // Average overall rating per month
        $averageOverallRatings = Rating::selectRaw(
            'YEAR(created_at) as year, MONTH(created_at) as month, AVG(overall) as avg_overall'
        )
        ->when($this->ratingsMonth, function ($query) {
            $date = Carbon::createFromFormat('Y-m', $this->ratingsMonth);
            return $query->whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month);
        }, function ($query) {
            return $query->whereYear('created_at', Carbon::now()->year)
                         ->whereMonth('created_at', Carbon::now()->month);
        })
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        // Calculate the average overall rating
        $averageOverall = $averageOverallRatings->avg('avg_overall');

        return view('livewire.admin.reports.head-count', [
            'totalEmployees' => $totalEmployees,
            'newEmployeesThisMonth' => $newEmployeesThisMonth,
            'departmentCounts' => $departmentCounts,
            'dailyAttendance' => $dailyAttendance,
            'docRequestsCount' => $docRequestsCount,
            'averageOverallRatings' => $averageOverallRatings,
            'averageOverall' => $averageOverall,
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
    public function exportAverageOverallRatings()
    {
        try {
            $month = $this->ratingsMonth ? Carbon::createFromFormat('Y-m', $this->ratingsMonth)->format('Y-m') : now()->format('Y-m');
            $filters = [
                'month' => $month,
            ];
            $filename = $month . ' AverageOverallRatings.xlsx';
            return Excel::download(new AverageOverallRatingsExport($filters), $filename);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
