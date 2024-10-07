<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceRecordTable extends Component
{
    use WithPagination;

    public $search;
    public $recordId;
    public $thisRecord;

    public function render()
    {
        $users = User::join('positions', 'positions.id', 'users.position_id')
            ->where('positions.position', '!=', 'Super Admin')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
            ->where('users.user_role', 'emp')
            ->where('users.active_status', '!=', 4)
            ->select('users.*')
            ->addSelect(DB::raw('(
                SELECT SUM(
                    CASE
                        WHEN work_experience.toPresent = "Present" THEN DATEDIFF(CURDATE(), work_experience.start_date)
                        WHEN work_experience.end_date IS NOT NULL THEN DATEDIFF(work_experience.end_date, work_experience.start_date)
                        ELSE 0
                    END
                )
                FROM work_experience
                WHERE work_experience.user_id = users.id AND work_experience.gov_service = 1
            ) as total_days_gov_service'))
            ->when($this->search, function ($query) {
                return $query->search(trim($this->search));
            })
            ->paginate(10);

        foreach ($users as $user) {
            $totalDays = $user->total_days_gov_service;
            $years = floor($totalDays / 365.25);
            $months = floor(($totalDays % 365.25) / 30.44);
            $user->formatted_gov_service = $this->formatService($years, $months);
        }

        return view('livewire.admin.service-record-table', [
            'users' => $users,
        ]);
    }

    private function formatService($years, $months)
    {
        $result = [];
        if ($years > 0) {
            $result[] = $years . ' ' . ($years == 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            $result[] = $months . ' ' . ($months == 1 ? 'month' : 'months');
        }
        return empty($result) ? '0 months' : implode(' ', $result);
    }


    public function toggleViewRecord($id){
        $this->recordId = $id;
        $user = User::where('users.id', $id)
            ->join('positions', 'positions.id', 'users.position_id')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
            ->select('users.*')
            ->addSelect(DB::raw('(
                SELECT SUM(
                    CASE
                        WHEN work_experience.toPresent = "Present" THEN DATEDIFF(CURDATE(), work_experience.start_date)
                        WHEN work_experience.end_date IS NOT NULL THEN DATEDIFF(work_experience.end_date, work_experience.start_date)
                        ELSE 0
                    END
                )
                FROM work_experience
                WHERE work_experience.user_id = users.id AND work_experience.gov_service = 1
            ) as total_days_gov_service'))
            ->first();
        $totalDays = $user->total_days_gov_service;
        $years = floor($totalDays / 365.25);
        $months = floor(($totalDays % 365.25) / 30.44);
        $user->formatted_gov_service = $this->formatService($years, $months);

        $this->thisRecord = $user;
    }

    public function exportRecord(){

    }

    public function resetVariables(){
        $this->resetValidation();
        $this->recordId = null;
        $this->thisRecord = null;
    }
}
