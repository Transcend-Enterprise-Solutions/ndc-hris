<?php

namespace App\Livewire\Admin;

use App\Exports\ServiceRecordExport;
use App\Models\User;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRecordTable extends Component
{
    use WithPagination;

    public $search;
    public $recordId;
    public $thisRecord;
    public $serviceRecord;

    public function render()
    {
        $users = User::join('positions', 'positions.id', 'users.position_id')
            ->join('user_data', 'user_data.user_id', 'users.id')
            ->where('positions.position', '!=', 'Super Admin')
            ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
            ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
            ->where('users.user_role', 'emp')
            ->where('users.active_status', '!=', 4)
            ->select('users.*', 'user_data.appointment')
            ->withCount(['workExperience as total_months_gov_service' => function ($query) {
                $query->where('gov_service', 1)
                    ->select(DB::raw('SUM(
                        CASE
                            WHEN toPresent = "Present" THEN TIMESTAMPDIFF(MONTH, start_date, CURDATE())
                            WHEN end_date IS NOT NULL THEN TIMESTAMPDIFF(MONTH, start_date, end_date)
                            ELSE 0
                        END
                    )'));
            }])
            ->when($this->search, function ($query) {
                return $query->search(trim($this->search));
            })
            ->paginate(10);

        foreach ($users as $user) {
            $totalMonths = $user->total_months_gov_service;
            $years = floor($totalMonths / 12);
            $months = $totalMonths % 12;
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
        $this->serviceRecord = WorkExperience::where('user_id', $id)
                ->orderBy('start_date', 'DESC')
                ->get();
    }

    public function exportRecord($id){
        try{
            $user = User::findOrFail($id);
            $record = WorkExperience::where('user_id', $id)
                    ->orderBy('start_date', 'DESC')
                    ->get();
            if($record){
                $filters = [
                    'user' => $user,
                    'record' => $record,
                ];

                $exporter = new ServiceRecordExport($filters);
                $result = $exporter->export();

                return response()->streamDownload(function () use ($result) {
                    echo $result['content'];
                }, $result['filename']);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->recordId = null;
        $this->thisRecord = null;
        $this->serviceRecord = null;
    }
}
