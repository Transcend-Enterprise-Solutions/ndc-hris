<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\EmployeesDtr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardDtr extends Component
{
    public $attendanceData;
    public $overtimeData;
    public $lateData;
    public $startDate;
    public $endDate;
    public $totalPresent;
    public $totalAbsent;
    public $totalLate;
    public $avgOvertime;

    public function mount()
    {
        $this->startDate = Carbon::now()->subDays(30)->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->loadData();
    }

    public function updatedStartDate()
    {
        $this->loadData();
        $this->dispatch('dataUpdated');
    }

    public function updatedEndDate()
    {
        $this->loadData();
        $this->dispatch('dataUpdated');
    }

    private function loadData()
    {
        $this->attendanceData = EmployeesDtr::select(
            DB::raw('DATE(date) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw("SUM(CASE WHEN remarks = 'Present' THEN 1 ELSE 0 END) as present_count"),
            DB::raw("SUM(CASE WHEN remarks = 'Absent' THEN 1 ELSE 0 END) as absent_count"),
            DB::raw("SUM(CASE WHEN remarks = 'Late' THEN 1 ELSE 0 END) as late_count")
        )
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->overtimeData = EmployeesDtr::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(overtime))) as total_overtime')
        )
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->lateData = EmployeesDtr::select(
            DB::raw('DATE(date) as date'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(late))) as total_late')
        )
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->calculateSummary();
    }

    private function calculateSummary()
    {
        $this->totalPresent = $this->attendanceData->sum('present_count');
        $this->totalAbsent = $this->attendanceData->sum('absent_count');
        $this->totalLate = $this->attendanceData->sum('late_count');

        $totalOvertimeSeconds = $this->overtimeData->sum(function ($day) {
            list($hours, $minutes, $seconds) = explode(':', $day->total_overtime);
            return ($hours * 3600) + ($minutes * 60) + $seconds;
        });

        $avgOvertimeSeconds = $this->overtimeData->count() > 0 ? $totalOvertimeSeconds / $this->overtimeData->count() : 0;
        $this->avgOvertime = sprintf('%02d:%02d', floor($avgOvertimeSeconds / 3600), floor(($avgOvertimeSeconds % 3600) / 60));
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-dtr');
    }
}
