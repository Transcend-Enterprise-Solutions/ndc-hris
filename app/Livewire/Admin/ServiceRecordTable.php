<?php

namespace App\Livewire\Admin;

use App\Exports\ServiceRecordExport;
use App\Models\ServiceRecords;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    public $userId2;
    public $name;
    public $name2;
    public $tableData = [];
    public $headers = [
        'From', 'To', 
        'Designation', 
        'Status', 'Salary/Annum', 
        'Station/Place of Assignment', 
        'Branch', 'L/V Abs W/O Pay', 
        'Remarks'];
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function mount()
    {
        $this->tableData = [
            array_fill(0, 9, '')
        ];
    }

    public function render()
    {
        $users = User::join('user_data', 'user_data.user_id', 'users.id')
            ->where('users.user_role', 'emp')
            ->where('users.active_status', '!=', 4)
            ->select('users.*', 'user_data.appointment', 'user_data.surname', 'user_data.first_name', 'user_data.middle_name', 'user_data.name_extension')
            ->withCount(['serviceRecords as total_months_gov_service' => function ($query) {
                $query->select(DB::raw('SUM(
                    CASE
                        WHEN `toPresent` = "Present" THEN TIMESTAMPDIFF(MONTH, `from`, CURDATE())
                        WHEN `to` IS NOT NULL THEN TIMESTAMPDIFF(MONTH, `from`, `to`)
                        ELSE 0
                    END
                )'));
            }])
            ->when($this->search, function ($query) {
                return $query->search6(trim($this->search));
            })
            ->orderBy('user_data.surname')
            ->paginate($this->pageSize);
    
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

    public function toggleViewRecord($id)
    {
        $this->recordId = $id;
        $user = UserData::where('user_id', $id)->first();
        $this->name = $user->surname . ', ' . $user->first_name . ($user->middle_name ? ' ' . $user->middle_name  : '' ) . ($user->name_extension ? ' ' . $user->name_extension : '');

        $this->tableData = ServiceRecords::where('user_id', $id)
            ->orderByRaw("CASE WHEN toPresent = 'Present' THEN 0 ELSE 1 END")
            ->orderBy('from') 
            ->get()
            ->map(function ($record) {
                return [
                    Carbon::parse($record->from)->format('m/d/Y'),
                    $record->to ? Carbon::parse($record->to)->format('m/d/Y'): $record->toPresent,
                    $record->designation,
                    $record->status,
                    $record->salary_annum,
                    $record->station_place_of_assignment,
                    $record->branch,
                    $record->lv_abs_wo_pay,
                    $record->remarks,
                ];
            })
            ->toArray();
    }
    

    public function exportRecord($id = null){
        try{
            if(!$id){
                $id = $this->recordId;
            }

           
            $user = User::findOrFail($id);
            $record = ServiceRecords::where('user_id', $id)
                    ->orderByRaw("CASE WHEN toPresent = 'Present' THEN 0 ELSE 1 END")
                    ->orderBy('from', 'desc') 
                    ->get();
            
            if($user && $record){
                $filters = [
                    'user' => $user,
                    'record' => $record,
                ];

                $exporter = new ServiceRecordExport($filters);
                $result = $exporter->export();
                return response()->streamDownload(function () use ($result) {
                    echo $result['content'];
                }, $result['filename']);
            }else{
                $this->dispatch('swal', [
                    'title' => 'No service record found for this user.',
                    'icon' => 'error'
                ]);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function addRow()
    {
        $this->tableData[] = array_fill(0, 9, '');
    }

    public function saveRecords()
    {
        if (empty($this->tableData) || collect($this->tableData)->every(fn($row) => !array_filter($row))) {
            $this->dispatch('swal', [
                'title' => 'No valid service records to save.',
                'icon' => 'error'
            ]);
            return;
        }

       
        foreach ($this->tableData as $row) {
            if (!array_filter($row)) {
                continue;
            }

            $existingRecord = ServiceRecords::where('user_id', $this->recordId)
                ->where('from', Carbon::parse($row[0])->format('Y-m-d'))
                ->first();

            if ($existingRecord) {
                $existingRecord->update([
                    'from' => $row[0] ? Carbon::parse($row[0])->format('Y-m-d'): null,
                    'to' => $row[1] != 'Present' ? Carbon::parse($row[1])->format('Y-m-d') : null,
                    'toPresent' => $row[1] == 'Present' ? $row[1] : null,
                    'designation' => $row[2] ?: '--do--',
                    'status' => $row[3] ?: '--do--',
                    'salary_annum' => $row[4] ?: '--do--',
                    'station_place_of_assignment' => $row[5] ?: '--do--',
                    'branch' => $row[6] ?: '--do--',
                    'lv_abs_wo_pay' => $row[7] ?: '--do--',
                    'remarks' => $row[8] ?: '--do--',
                ]);
            } else {
                ServiceRecords::create([
                    'user_id' => $this->recordId,
                    'from' => $row[0] ? Carbon::parse($row[0])->format('Y-m-d'): null,
                    'to' => ($row[1] != 'Present' && strtotime($row[1])) ? Carbon::parse($row[1])->format('Y-m-d') : null,
                    'toPresent' => $row[1] == 'Present' ? $row[1] : null,
                    'designation' => $row[2] ?: '--do--',
                    'status' => $row[3] ?: '--do--',
                    'salary_annum' => $row[4] ?: '--do--',
                    'station_place_of_assignment' => $row[5] ?: '--do--',
                    'branch' => $row[6] ?: '--do--',
                    'lv_abs_wo_pay' => $row[7] ?: '--do--',
                    'remarks' => $row[8] ?: '--do--',
                ]);
            }
        }
    
        $this->resetVariables();
        $this->dispatch('swal', [
            'title' => 'Service record saved successfully',
            'icon' => 'success'
        ]);
    }
    
    public function closeWorkExpSheet(){
        $this->showServiceRecord = null;
        $this->pdfContent = null;
        $this->employeeName = null;
    }

    public function toggleEditSig(){
        $this->editSig = true;
        $signatory1 = Signatories::where('signatory_type', 'service_record_1')->first();
        $signatory2 = Signatories::where('signatory_type', 'service_record_2')->first();
        if($signatory1){
            $employee = User::findOrFail($signatory1->user_id);
            $this->name = $employee->name;
            $this->userId = $employee->id;
        }
        if($signatory2){
            $employee = User::findOrFail($signatory2->user_id);
            $this->name2 = $employee->name;
            $this->userId2 = $employee->id;
        }
    }

    public function saveSignatory(){
        $signatory1 = Signatories::where('signatory_type', 'service_record_1')->first();
        if($signatory1){
            $signatory1->update([
                'user_id' => $this->userId,
            ]);
        }else{
            $this->validate([
                'userId' => 'required',
            ]);

            Signatories::create([
                'user_id' => $this->userId,
                'signatory_type' => 'service_record_1',
            ]);
        }

        $signatory2 = Signatories::where('signatory_type', 'service_record_2')->first();
        if($signatory2 && $this->userId2){
            $signatory2->update([
                'user_id' => $this->userId,
            ]);
        }elseif($this->userId2){
            $this->validate([
                'userId2' => 'required',
            ]);
            Signatories::create([
                'user_id' => $this->userId2,
                'signatory_type' => 'service_record_2',
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
        $this->name = null;
        $this->name2 = null;
        $this->userId = null;
    }
}
