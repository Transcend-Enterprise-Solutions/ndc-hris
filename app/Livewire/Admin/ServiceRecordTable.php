<?php

namespace App\Livewire\Admin;

use App\Exports\ServiceRecordExport;
use App\Models\Signatories;
use App\Models\User;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ESignature;
use App\Models\UserData;
use Illuminate\Support\Facades\Storage;

class ServiceRecordTable extends Component
{
    use WithPagination;

    public $search;
    public $recordId;
    public $thisRecord;
    public $serviceRecord;
    public $showServiceRecord = true;
    public $employeeName;
    public $pdfContent;
    public $editSig;
    public $userId;
    public $name;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

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
            ->paginate($this->pageSize);
        
        // $this->showPDF(userId: 79);

        foreach ($users as $user) {
            $totalMonths = $user->total_months_gov_service;
            $years = floor($totalMonths / 12);
            $months = $totalMonths % 12;
            $user->formatted_gov_service = $this->formatService($years, $months);
        }

        $employees = User::where('user_role', 'emp')
                    ->select('name', 'id')
                    ->get();

        return view('livewire.admin.service-record-table', [
            'users' => $users,
            'employees' => $employees,
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

    public function exportRecord($id = null){
        try{
            if(!$id){
                $id = $this->recordId;
            }
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

    // public function showPDF($userId){
    //     $this->showServiceRecord = true;
    //     $eSignature = ESignature::where('user_id', $userId)->first();
    //     $signatureImagePath = null;
    //     if ($eSignature && $eSignature->file_path) {
    //         $signatureImagePath = Storage::disk('public')->path($eSignature->file_path);
    //     }


    //     $this->employeeName = User::where('id', $userId)->first()->name;
    //     $userData = UserData::where('user_id', 79)->first();

    //     $sigXPos = 110;
    //     $sigYPos = -50;
    //     $sigSize = 100;

    //     $record = WorkExperience::where('user_id', 79)
    //             ->orderBy('start_date', 'DESC')
    //             ->get();

    //     $pdf = PDF::loadView('pdf.service-record', [
    //         'myWorkExperiences' => $record,
    //         'signatureImagePath' => null,
    //         'userData' => $userData,
    //         'sigXPos' => $sigXPos,
    //         'sigYPos' => $sigYPos,
    //         'sigSize' => $sigSize,
    //     ]);

    //     $this->pdfContent = base64_encode($pdf->output());
    // }

    
    public function closeWorkExpSheet(){
        $this->showServiceRecord = null;
        $this->pdfContent = null;
        $this->employeeName = null;
    }

    public function toggleEditSig(){
        $this->editSig = true;
        $signatory = Signatories::where('signatory_type', 'service_record')->first();
        if($signatory){
            $employee = User::findOrFail($signatory->user_id);
            $this->name = $employee->name;
            $this->userId = $employee->id;
        }
    }

    public function saveSignatory(){
        $signatory = Signatories::where('signatory_type', 'service_record')->first();
        if($signatory){
            $signatory->update([
                'user_id' => $this->userId,
            ]);
        }else{
            $this->validate([
                'userId' => 'required',
            ]);

            Signatories::create([
                'user_id' => $this->userId,
                'signatory_type' => 'service_record',
            ]);
        }

        $this->resetVariables();
        $this->dispatch('swal', [
            'title' => 'Signatory saved successfully',
            'icon' => 'success'
        ]);
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->recordId = null;
        $this->thisRecord = null;
        $this->serviceRecord = null;
        $this->editSig = null;
    }
}
