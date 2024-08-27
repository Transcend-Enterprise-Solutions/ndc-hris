<?php

namespace App\Livewire\Admin\Reports;

use App\Exports\AttendanceExport;
use App\Exports\PerOfficeDivisionExport;
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
    public $status = [
        'active' => true,
        'inactive' => true,
        'resigned' => true,
        'retired' => true,
    ];
    public $allStat;


    public function mount(){
        $this->date = now();
    }

    public function render()
    {
        $totalEmployees = User::where('user_role', 'emp')->count();

        $newEmployeesThisMonth = User::where('user_role', 'emp')->join('user_data', 'user_data.user_id', 'users.id');
        if ($this->month) {
            $date = Carbon::createFromFormat('Y-m', $this->month);
            $newEmployeesThisMonth = $newEmployeesThisMonth
                ->whereYear('user_data.date_hired', $date->year)
                ->whereMonth('user_data.date_hired', $date->month)
                ->count();
        } else {
            $newEmployeesThisMonth = $newEmployeesThisMonth
                ->whereYear('user_data.date_hired', Carbon::now()->year)
                ->whereMonth('user_data.date_hired', Carbon::now()->month)
                ->count();
        }

        $officeDivisionCounts = User::where('user_role', 'emp')
            ->join('positions', 'positions.id', 'users.position_id')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->leftJoin('payrolls', 'payrolls.user_id', 'users.id')
            ->when($this->status, function ($query) {
                return $query->where(function ($subQuery) {
                    if ($this->status['active']) {
                        $subQuery->orWhere('active_status', 1);
                    }
                    if ($this->status['inactive']) {
                        $subQuery->orWhere('active_status', 0);
                    }
                    if ($this->status['resigned']) {
                        $subQuery->orWhere('active_status', 2);
                    }
                    if ($this->status['retired']) {
                        $subQuery->orWhere('active_status', 3);
                    }
                });
            })
            ->selectRaw('office_divisions.office_division, count(*) as count')
            ->groupBy('office_division')
            ->get();

        $dailyAttendance = EmployeesDtr::where('date', $this->date)
                            ->where(function ($query) {
                                $query->where('remarks', 'Present')
                                        ->orWhere('remarks', 'Late/Undertime');
                            })
                            ->count();

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
            'officeDivisionCounts' => $officeDivisionCounts,
            'dailyAttendance' => $dailyAttendance,
            'docRequestsCount' => $docRequestsCount,
        ]);
    }

    public function exportTotalEmployee(){
        try {
            $employees = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('payrolls', 'payrolls.user_id', 'users.id')
                ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                ->where('users.active_status', '!=', 4)
                ->select(
                    'users.name', 
                    'users.email', 
                    'users.emp_code', 
                    'users.active_status', 
                    'positions.position', 
                    'user_data.appointment', 
                    'user_data.date_hired', 
                    'office_divisions.office_division',
                    'payrolls.sg_step as plantilla_sg_step',
                    'payrolls.rate_per_month as plantilla_rate',
                    'cos_sk_payrolls.sg_step as cos_sk_sg_step',
                    'cos_sk_payrolls.rate_per_month as cos_sk_rate',
                    'cos_reg_payrolls.sg_step as cos_reg_sg_step',
                    'cos_reg_payrolls.rate_per_month as cos_reg_rate', 
                );
            $filters = [
                'employees' => $employees,
            ];
            return Excel::download(new EmployeeReportExport($filters), 'EmployeesList.xlsx');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportTotalEmployeeThisMonth()
    {
        try {
            $month = $this->month ? Carbon::createFromFormat('Y-m', $this->month)->format('Y-m') : now()->format('Y-m');
            $thisYear = null;
            $thisMonth = null;
            if(!$this->month){
                $thisYear = Carbon::now()->year;
                $thisMonth = Carbon::now()->month;
            }else{
                $date = Carbon::createFromFormat('Y-m', $this->month);
                $thisYear = $date->year;
                $thisMonth = $date->month;
            }
            $employees = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('payrolls', 'payrolls.user_id', 'users.id')
                ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                ->whereYear('user_data.date_hired', $thisYear)
                ->whereMonth('user_data.date_hired', $thisMonth)
                ->where('users.active_status', '!=', 4)
                ->select(
                    'users.name', 
                    'users.email', 
                    'users.emp_code', 
                    'users.active_status', 
                    'positions.position', 
                    'user_data.appointment', 
                    'user_data.date_hired', 
                    'office_divisions.office_division',
                    'payrolls.sg_step as plantilla_sg_step',
                    'payrolls.rate_per_month as plantilla_rate',
                    'cos_sk_payrolls.sg_step as cos_sk_sg_step',
                    'cos_sk_payrolls.rate_per_month as cos_sk_rate',
                    'cos_reg_payrolls.sg_step as cos_reg_sg_step',
                    'cos_reg_payrolls.rate_per_month as cos_reg_rate', 
                );
            $filters = [
                'employees' => $employees,
                'month' => $month,
            ];
            $filename = $month . ' EmployeesList.xlsx';
            return Excel::download(new EmployeeReportExport($filters), $filename);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportTotalEmployeeInOfficeDivision($division)
    {
        try {
            $organizations = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('payrolls', 'payrolls.user_id', 'users.id')
                ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                ->where('users.active_status', '!=', 4)
                ->select(
                    'users.name', 
                    'users.email', 
                    'users.emp_code', 
                    'users.active_status', 
                    'positions.position', 
                    'user_data.appointment', 
                    'user_data.date_hired', 
                    'office_divisions.office_division',
                    'payrolls.sg_step as plantilla_sg_step',
                    'payrolls.rate_per_month as plantilla_rate',
                    'cos_sk_payrolls.sg_step as cos_sk_sg_step',
                    'cos_sk_payrolls.rate_per_month as cos_sk_rate',
                    'cos_reg_payrolls.sg_step as cos_reg_sg_step',
                    'cos_reg_payrolls.rate_per_month as cos_reg_rate',
                )
                ->where('office_divisions.office_division', $division)
                ->when($this->status, function ($query) {
                    return $query->where(function ($subQuery) {
                        if ($this->status['active']) {
                            $subQuery->orWhere('active_status', 1);
                        }
                        if ($this->status['inactive']) {
                            $subQuery->orWhere('active_status', 0);
                        }
                        if ($this->status['resigned']) {
                            $subQuery->orWhere('active_status', 2);
                        }
                        if ($this->status['retired']) {
                            $subQuery->orWhere('active_status', 3);
                        }
                    });
                });

            $selectedStatuses = $this->allStat ? ['All'] : array_keys(array_filter($this->status));
            $statusLabels = [
                'active' => 'Active',
                'inactive' => 'Inactive',
                'resigned' => 'Resigned',
                'retired' => 'Retired',
                'promoted' => 'Promoted'
            ];
    
            $filters = [
                'organizations' => $organizations,
                'office_division' => $division,
                'statuses' => $selectedStatuses == ['All'] ? ['All'] : array_map(function($status) use ($statusLabels) {
                    return $statusLabels[$status];
                }, $selectedStatuses)
            ];
            $filename = $division . ' EmployeesList.xlsx';
            return Excel::download(new PerOfficeDivisionExport($filters), $filename);
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
