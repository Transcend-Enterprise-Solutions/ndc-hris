<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\LeaveApplication;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeaveApplicationsExport;
use App\Exports\VacationLeaveExport;
use App\Exports\SickLeaveExport;

class LeaveAvailment extends Component
{
    public $statuses = ['Pending', 'Approved', 'Approved by HR', 'Approved by Supervisor', 'Disapproved'];
    public $statusesForVL = ['Pending', 'Approved', 'Approved by HR', 'Approved by Supervisor', 'Disapproved'];
    public $statusesForSL = ['Pending', 'Approved', 'Approved by HR', 'Approved by Supervisor', 'Disapproved'];

    public function exportToExcel()
    {
        return Excel::download(new LeaveApplicationsExport($this->statuses), 'TotalLeaveApplications.xlsx');
    }
    
    public function exportVacationLeaveToExcel()
    {
        return Excel::download(new VacationLeaveExport($this->statusesForVL), 'VacationLeaveApplications.xlsx');
    }

    public function exportSickLeaveToExcel()
    {
        return Excel::download(new SickLeaveExport($this->statusesForSL), 'SickLeaveApplications.xlsx');
    }

    public function render()
    {
        $leaveCount = LeaveApplication::when($this->statuses, function ($query) {
            $query->whereIn('status', $this->statuses);
        })->count();

        $vacationLeaveCount = LeaveApplication::where('type_of_leave', 'LIKE', '%Vacation Leave%')
            ->when($this->statusesForVL, function ($query) {
                $query->whereIn('status', $this->statusesForVL);
            })->count();

        $sickLeaveCount = LeaveApplication::where('type_of_leave', 'LIKE', '%Sick Leave%')
            ->when($this->statusesForSL, function ($query) {
                $query->whereIn('status', $this->statusesForSL);
            })->count();

        return view('livewire.admin.reports.leave-availment', [
            'leaveCount' => $leaveCount,
            'vacationLeaveCount' => $vacationLeaveCount,
            'sickLeaveCount' => $sickLeaveCount,
        ]);
    }
}
