<?php

namespace App\Livewire\Admin;

use App\Exports\AdminRolesExport;
use App\Exports\PerOfficeDivisionExport;
use App\Exports\PerUnitExport;
use App\Imports\SalaryGradeImport;
use App\Models\CosRegPayrolls;
use App\Models\CosSkPayrolls;
use App\Models\OfficeDivisions;
use App\Models\OfficeDivisionUnits;
use App\Models\Payrolls;
use App\Models\Positions;
use App\Models\SalaryGrade;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalaryGradeExport;
use Livewire\WithFileUploads;

class RoleManagementTable extends Component
{
    use WithPagination, WithFileUploads;
    public $addRole;
    public $editRole;
    public $employees;
    public $roleEmployees;
    public $positionsByUnit;
    public $positions;
    public $officeDivisions;
    public $unit;
    public $unitName;
    public $divsUnits;
    public $userId;
    public $name;
    public $employee_number;
    public $position;
    public $user_role;
    public $admin_email;
    public $office_division;
    public $password;
    public $cpassword;
    public $search;
    public $search2;
    public $search3;
    public $search4;
    public $deleteId;
    public $deleteMessage;
    public $add;
    public $data;
    public $settings;
    public $settingsId;
    public $settings_data;
    public $settingsData = [['value' => '']];
    public $units = [['value' => '']];
    public $salaryGrades;
    public $editingId = null;
    public $isEditing = false;
    public $editedData = [];
    public $showSGModal = false;
    public $salaryGradeData = [
        'salary_grade' => '',
        'step1' => '', 'step2' => '', 'step3' => '', 'step4' => '',
        'step5' => '', 'step6' => '', 'step7' => '', 'step8' => '',
    ];
    public $addPosition;
    public $editPosition;
    public $dropdownForStatus;
    public $allStat = true;
    public $activeStatus;
    public $positionId;
    public $officeDivisionId;
    public $unitId;
    public $file;
    public $divId;

    public $status = [
        'active' => true,
        'inactive' => true,
        'resigned' => true,
        'retired' => true,
    ];

    public function mount(){
        $this->employees = User::where('user_role', '=', 'emp')->get();

        $this->roleEmployees = User::where('user_role', '=', 'emp')
            ->whereDoesntHave('adminAccount')
            ->get();

        $this->salaryGrades = SalaryGrade::orderBy('salary_grade')->get();
        $this->positions = Positions::where('position', '!=', 'Super Admin')->get();
    }

    public function render(){
        if($this->office_division){
            $this->divsUnits = OfficeDivisionUnits::where('office_division_id' , $this->office_division)->get();
        }
        if($this->officeDivisionId){
            $this->divsUnits = OfficeDivisionUnits::where('office_division_id' , $this->officeDivisionId)->get();
        }


        $admins = User::join('positions', 'positions.id', 'users.position_id')
                ->where('positions.position', '!=', 'Super Admin')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                ->where('users.user_role', '!=', 'emp')
                ->where('users.active_status', '!=', 4)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->select(
                    'users.id',
                    'users.name',
                    'users.user_role',
                    'users.emp_code',
                    'positions.position',
                    'office_divisions.office_division',
                    'office_division_units.unit'
                )
                ->paginate(10);
                
            $empPos = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->where('users.active_status', '!=', 4)
                ->select(
                    'users.id', 
                    'users.name', 
                    'users.emp_code', 
                    'users.active_status', 
                    'positions.position', 
                    'user_data.appointment',
                    'office_divisions.office_division',
                    'office_division_units.unit',
                    DB::raw('
                        CASE 
                            WHEN cos_reg_payrolls.id IS NOT NULL THEN "REG"
                            WHEN cos_sk_payrolls.id IS NOT NULL THEN "SK"
                            ELSE ""
                        END as appointment_type'
                    )
                )
                ->when($this->search3, function ($query) {
                    return $query->search(trim($this->search3));
                })
                ->paginate(10);
            

        $organizations = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                ->where('users.active_status', '!=', 4)
                ->select(
                    'users.name', 
                    'users.emp_code', 
                    'users.active_status', 
                    'positions.position', 
                    'user_data.appointment', 
                    'office_divisions.office_division',
                    DB::raw('
                        CASE 
                            WHEN cos_reg_payrolls.id IS NOT NULL THEN "REG"
                            WHEN cos_sk_payrolls.id IS NOT NULL THEN "SK"
                            ELSE ""
                        END as appointment_type'
                    )
                )
                ->when($this->search2, function ($query) {
                    return $query->search(trim($this->search2));
                })
                ->when(!$this->allStat, function ($query) {
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
                ->get()
                ->groupBy('office_division');

        $this->officeDivisions = OfficeDivisions::with(['officeDivisionUnits', 'positions' => function($query) {
            $query->where('position', '!=', 'Super Admin')->whereNull('unit_id');
            }])
            ->when($this->search4, function ($query) {
                return $query->search(trim($this->search4));
            })
            ->get();
        
        $this->positionsByUnit = OfficeDivisionUnits::with(['positions' => function($query) {
            $query->where('position', '!=', 'Super Admin')->whereNotNull('unit_id');
            }])->get();


        if($this->file){
            $this->importFromExcel();
        }
                

        return view('livewire.admin.role-management-table',[
            'organizations' => $organizations,
            'admins' => $admins,
            'empPos' => $empPos,
        ]);
    }

    public function toggleAllStats() {
        if ($this->allStat) {
            $this->allStat = null;
            foreach (array_keys($this->status) as $stat) {
                $this->status[$stat] = false;
            }
            $this->allStat = false;
        } else {
            $this->allStat = true;
            foreach (array_keys($this->status) as $stat) {
                $this->status[$stat] = true;
            }
            $this->allStat = true;
        }
    }

    public function exportRoles(){
        try{
            $admins = User::join('positions', 'positions.id', 'users.position_id')
                ->where('positions.position', '!=', 'Super Admin')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                ->where('users.user_role', '!=', 'emp')
                ->where('users.active_status', '!=', 4)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->select(
                    'users.id',
                    'users.name',
                    'users.user_role',
                    'users.emp_code',
                    'positions.position',
                    'office_divisions.office_division',
                    'office_division_units.unit'
                );

            $filters = [
                'admins' => $admins,
            ];
            return Excel::download(new AdminRolesExport($filters), 'Admin_Roles_List.xlsx');
            
        }catch(Exception $e){
            throw $e;
        }
    }

    public function exportEmployees($division)
    {
        try {
            $organizations = User::where('user_role', 'emp')
                ->join('user_data', 'user_data.user_id', 'users.id')
                ->join('positions', 'positions.id', 'users.position_id')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
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
                    'office_division_units.unit',
                    'payrolls.sg_step as plantilla_sg_step',
                    'payrolls.rate_per_month as plantilla_rate',
                    'cos_sk_payrolls.sg_step as cos_sk_sg_step',
                    'cos_sk_payrolls.rate_per_month as cos_sk_rate',
                    'cos_reg_payrolls.sg_step as cos_reg_sg_step',
                    'cos_reg_payrolls.rate_per_month as cos_reg_rate',
                )
                ->where('office_divisions.office_division', $division)
                ->when($this->search2, function ($query) {
                    return $query->search(trim($this->search2));
                })
                ->when(!$this->allStat, function ($query) {
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
            return Excel::download(new PerOfficeDivisionExport($filters), $division . '_EmployeesList.xlsx');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exportEmployeesPerUnit($unitId = null, $divId){
        try{
            $users = User::where('users.office_division_id', $divId)
                        ->join('positions', 'positions.id', 'users.position_id')
                        ->where('positions.position', '!=', 'Super Admin')
                        ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                        ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                        ->join('user_data', 'user_data.user_id', 'users.id')
                        ->leftJoin('payrolls', 'payrolls.user_id', 'users.id')
                        ->leftJoin('cos_sk_payrolls', 'cos_sk_payrolls.user_id', 'users.id')
                        ->leftJoin('cos_reg_payrolls', 'cos_reg_payrolls.user_id', 'users.id')
                        ->select(
                            'users.name', 
                            'users.email', 
                            'users.emp_code', 
                            'users.active_status', 
                            'positions.position', 
                            'user_data.appointment', 
                            'user_data.date_hired', 
                            'office_divisions.office_division',
                            'office_division_units.unit',
                            'payrolls.sg_step as plantilla_sg_step',
                            'payrolls.rate_per_month as plantilla_rate',
                            'cos_sk_payrolls.sg_step as cos_sk_sg_step',
                            'cos_sk_payrolls.rate_per_month as cos_sk_rate',
                            'cos_reg_payrolls.sg_step as cos_reg_sg_step',
                            'cos_reg_payrolls.rate_per_month as cos_reg_rate',
                        );

            if ($unitId === null) {
                $users->whereNull('users.unit_id');
            } else {
                $users->where('users.unit_id', $unitId);
            }

            $unit = OfficeDivisionUnits::where('id', $unitId)->first();
            $officeDivison = OfficeDivisions::where('id', $divId)->first();
            if($users){
                $filters = [
                    'users' => $users,
                    'unit' => $unit ? $unit->unit : '',
                    'office_division' => $officeDivison->office_division,
                ];
                $filename = $officeDivison->office_division . "-" . ($unit ? $unit->unit : '') . " EmployeesList.xlsx";
                return Excel::download(new PerUnitExport($filters), $filename);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toggleAddSettings($data)
    {
        // $this->officeDivisionId = $divisionId;
        $this->data = $data;
        $this->settings = true;
        $this->add = true;
        $this->settingsData = [['value' => '']];
        $this->units = [['value' => '']];
    }

    public function toggleAddPos($id, $data){
        $this->officeDivisionId = $id;
        $this->data = $data;
        $this->settings = true;
        $this->add = true;
        $this->settingsData = [['value' => '']];
    }

    public function toggleEditPos($id, $data){
        $this->officeDivisionId = $id;
        $positions = Positions::where('office_division_id', $id)
                    ->where('position', '!=', 'Super Admin')
                    ->where('unit_id', null)
                    ->get();
        $this->data = $data;
        $this->settings = true;
        if ($positions->isNotEmpty()) {
            $this->settingsData = $positions->map(function($pos) {
                return ['value' => $pos->position];
            })->toArray();
        } else {
            $this->settingsData = [['value' => '']];
        }
    }

    public function toggleAddUnitPos($divId, $unitId, $data){
        $this->officeDivisionId = $divId;
        $this->unitId = $unitId;
        $this->data = $data;
        $this->settings = true;
        $this->add = true;
        $this->settingsData = [['value' => '']];
    }

    public function toggleEditUnitPos($divId, $unitId, $data){
        $this->officeDivisionId = $divId;
        $this->unitId = $unitId;
        $positions = Positions::where('office_division_id', $divId)
                    ->where('position', '!=', 'Super Admin')
                    ->where('unit_id', $unitId)
                    ->get();
        $this->data = $data;
        $this->settings = true;
        if ($positions->isNotEmpty()) {
            $this->settingsData = $positions->map(function($pos) {
                return ['value' => $pos->position];
            })->toArray();
        } else {
            $this->settingsData = [['value' => '']];
        }
    }

    public function addNewSetting()
    {
        $this->settingsData[] = ['value' => ''];
    }

    public function addNewUnit()
    {
        $this->units[] = ['value' => ''];
    }

    public function removeSetting($index)
    {
        unset($this->settingsData[$index]);
        $this->settingsData = array_values($this->settingsData);
    }

    public function removeUnit($index)
    {
        unset($this->units[$index]);
        $this->units = array_values($this->units);
    }

    public function toggleDeleteSettings($id, $data){ 
        $this->deleteId = $id;
        $this->data = $data;
        $this->deleteMessage = $data;
    }

    public function toggleEditSettings($id, $data){
        $this->settings = true;  
        $this->settingsId = $id;
        $this->data = $data;
        if($data == "office/division"){
            $officeDivisions = OfficeDivisions::where('id', $this->settingsId)->first();
            $this->settings_data = $officeDivisions->office_division;

            if ($officeDivisions->officeDivisionUnits->isNotEmpty()) {
                $this->units = $officeDivisions->officeDivisionUnits->map(function($unit) {
                    return ['value' => $unit->unit];
                })->toArray();
            } else {
                $this->units = [['value' => '']];
            }
        }else if($data == "position"){
            $positions = Positions::where('id', $this->settingsId)->first();
            $this->settings_data = $positions->position;
        }
    }

    public function saveSettings(){
        try {
            $message = null;
            if($this->add){
                if ($this->data == "office/division") {
                    $this->validate([
                        'units.*.value' => 'required|string|max:255',
                    ]);
       
                    $officeDiv = OfficeDivisions::create([
                        'office_division' => $this->settings_data,
                    ]);

                    foreach($this->units as $unit){
                        OfficeDivisionUnits::create([
                            'office_division_id' => $officeDiv->id,
                            'unit' => $unit['value'],
                        ]);
                    }

                    $message = "Office/Division added successfully!";
                } else if ($this->data == "position") {
                    $this->validate([
                        'settingsData.*.value' => 'required|string|max:255',
                    ]);

                    foreach ($this->settingsData as $setting) {
                        Positions::create([
                            'office_division_id' => $this->officeDivisionId,
                            'position' => $setting['value'],
                        ]);
                    }
                    $message = "Position/s added successfully!";
                } else if ($this->data == "unit-position") {
                    $this->validate([
                        'settingsData.*.value' => 'required|string|max:255',
                    ]);

                    foreach ($this->settingsData as $setting) {
                        Positions::create([
                            'office_division_id' => $this->officeDivisionId,
                            'unit_id' => $this->unitId,
                            'position' => $setting['value'],
                        ]);
                    }
                    $message = "Position/s added successfully!";
                }
            }else{
                  // Update existing Office/Division or Position
                if ($this->data == "office/division") {
                    $this->validate([
                        'units.*.value' => 'required|string|max:255',
                    ]);
                    $officeDivisions = OfficeDivisions::where('id', $this->settingsId)->first();
                    $officeDivisions->update([
                        'office_division' => $this->settings_data,
                    ]);

                    // Track existing units
                    $existingUnitIds = $officeDivisions->officeDivisionUnits->pluck('id')->toArray();
                    $updatedUnitIds = [];

                    foreach ($this->units as $index => $unit) {
                        if (isset($officeDivisions->officeDivisionUnits[$index])) {
                            $officeDivisionUnit = $officeDivisions->officeDivisionUnits[$index];
                            $officeDivisionUnit->update([
                                'unit' => $unit['value'],
                            ]);
                            $updatedUnitIds[] = $officeDivisionUnit->id;
                        } else {
                            $newUnit = OfficeDivisionUnits::create([
                                'office_division_id' => $officeDivisions->id,
                                'unit' => $unit['value'],
                            ]);
                            $updatedUnitIds[] = $newUnit->id;
                        }
                    }

                    // Detect removed units and delete them
                    $removedUnitIds = array_diff($existingUnitIds, $updatedUnitIds);
                    OfficeDivisionUnits::whereIn('id', $removedUnitIds)->delete();

                    $message = "Office/Division updated successfully!";
                } else if ($this->data == "position") {
                    $this->validate([
                        'settingsData.*.value' => 'required|string|max:255',
                    ]);
                    
                    $officeDivisions = OfficeDivisions::where('id', $this->officeDivisionId)->first();
                    
                    // Track existing positions
                    $existingPositionIds = $officeDivisions->positions->pluck('id')->toArray();
                    $updatedPositionIds = [];

                    foreach($this->settingsData as $index => $data) {
                        if (isset($officeDivisions->positions[$index])) {
                            $position = $officeDivisions->positions[$index];
                            $position->update([
                                'position' => $data['value'],
                            ]);
                            $updatedPositionIds[] = $position->id;
                        } else {
                            $newPosition = Positions::create([
                                'office_division_id' => $officeDivisions->id,
                                'position' => $data['value'],
                            ]);
                            $updatedPositionIds[] = $newPosition->id;
                        }
                    }

                    // Detect removed positions and delete them
                    $removedPositionIds = array_diff($existingPositionIds, $updatedPositionIds);
                    Positions::whereIn('id', $removedPositionIds)->delete();

                    $message = "Position/s updated successfully!";
                } else if ($this->data == "unit-position") {
                    $this->validate([
                        'settingsData.*.value' => 'required|string|max:255',
                    ]);
                    
                    $officeDivisionsUnits = OfficeDivisionUnits::where('id', $this->unitId)->first();
                    
                    // Track existing positions
                    $existingPositionIds = $officeDivisionsUnits->positions->pluck('id')->toArray();
                    $updatedPositionIds = [];

                    foreach($this->settingsData as $index => $data) {
                        if (isset($officeDivisionsUnits->positions[$index])) {
                            $position = $officeDivisionsUnits->positions[$index];
                            $position->update([
                                'position' => $data['value'],
                            ]);
                            $updatedPositionIds[] = $position->id;
                        } else {
                            $newPosition = Positions::create([
                                'office_division_id' => $this->officeDivisionId,
                                'unit_id' => $officeDivisionsUnits->id,
                                'position' => $data['value'],
                            ]);
                            $updatedPositionIds[] = $newPosition->id;
                        }
                    }

                    // Detect removed positions and delete them
                    $removedPositionIds = array_diff($existingPositionIds, $updatedPositionIds);
                    Positions::whereIn('id', $removedPositionIds)->delete();

                    $message = "Position/s updated successfully!";
                }
            }

            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        } catch(Exception $e) {
            throw $e;
        }
    }

    public function toggleEditRole($userId){
        $this->editRole = true;
        $this->userId = $userId;
        try {
            $admin = User::where('users.id', $userId)
                ->join('positions', 'positions.id', 'users.position_id')
                ->where('positions.position', '!=', 'Super Admin')
                ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                ->where('users.user_role', '!=', 'emp')
                ->where('users.active_status', '!=', 4)
                ->when($this->search, function ($query) {
                    return $query->search(trim($this->search));
                })
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.user_role',
                    'users.emp_code',
                    'users.unit_id',
                    'positions.position',
                    'office_divisions.office_division',
                    'office_divisions.id as divId',
                    'office_division_units.unit',
                    'office_division_units.id as unitId'
                )
                ->first();
            if ($admin) {
                $this->divsUnits = OfficeDivisionUnits::where('office_division_id' , $admin->divId)->get();
                $this->name = $admin->name;
                $this->user_role = $admin->user_role;
                $this->admin_email = $admin->email;
                $this->office_division = $admin->office_division;
                $this->unitName = $admin->unit;
                $this->unit = $admin->unitId;
                $this->position = $admin->position;
                $this->divId = $admin->divId;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleAddRole(){
        $this->editRole = true;
        $this->addRole = true;
    }

    public function toggleEditPosition($userId){
        $this->editPosition = true;
        $this->userId = $userId;
        try {
            $empPos = User::where('users.id', $this->userId)
                    ->join('positions', 'positions.id', 'users.position_id')
                    ->join('office_divisions', 'office_divisions.id', 'users.office_division_id')
                    ->leftJoin('office_division_units', 'office_division_units.id', 'users.unit_id')
                    ->select('users.*', 'positions.position', 'office_divisions.office_division', 'office_division_units.unit')
                    ->first();
            if ($empPos) {
                $this->userId = $empPos->id;
                $this->name = $empPos->name;
                $this->position = $empPos->position;
                $this->office_division = $empPos->office_division;
                $this->positionId = $empPos->position_id;
                $this->officeDivisionId = $empPos->office_division_id;
                $this->activeStatus = $empPos->active_status;
                $this->unitName = $empPos->unit;
                $this->unit = $empPos->unit_id;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function saveRole(){
        try {
            $user = User::where('users.id', $this->userId)
                ->join('positions', 'positions.id', 'users.position_id')
                ->select('users.id', 'users.name', 'users.emp_code','positions.id as posId')
                ->first();
            if($user){
                if($this->addRole){
                    $this->validate([
                        'user_role' => 'required',
                        'divId' => 'required',
                        'admin_email' => 'required|email|unique:users,email',
                        'password' => 'required|min:8',
                        'cpassword' => 'required|same:password',
                    ]);

                    if (!$this->isPasswordComplex($this->password)) {
                        $this->addError('password', 'The password must contain at least one uppercase letter, one number, and one special character.');
                        return;
                    }

                    $payrollId = null;
                    $payrolls = Payrolls::where('user_id', $user->id)->first();
                    $cosRegPayrolls = CosRegPayrolls::where('user_id', $user->id)->first();
                    $cosSkPayrolls = CosSkPayrolls::where('user_id', $user->id)->first();

                    if($payrolls){
                        $payrollId = $payrolls->user_id;
                    }else if($cosRegPayrolls){
                        $payrollId = $cosRegPayrolls->user_id;
                    }else if($cosSkPayrolls){
                        $payrollId = $cosSkPayrolls->user_id;
                    }else{
                        $this->dispatch('swal', [
                            'title' => "This employee don't have a payroll yet!",
                            'icon' => 'error'
                        ]);
                        return;
                    }

                    $admin = User::create([
                        'name' => $user->name,
                        'email' => $this->admin_email,
                        'password' => $this->password,
                        'emp_code' => $this->user_role . '-' .$user->emp_code,
                        'user_role' => $this->user_role,
                        'active_status' => 1,
                        'position_id' => $user->posId,
                        'office_division_id' => $this->divId,
                        'unit_id' => $this->unit,
                    ]);
                }else{
                    $admin = User::where('users.id', $this->userId)
                            ->first();

                    $this->validate([
                        'user_role' => 'required',
                        'office_division' => 'required',
                        'admin_email' => 'required|email',
                    ]);

                    $admin->update([
                        'email' => $this->admin_email,
                        'user_role' => $this->user_role,
                        'office_division_id' => $this->divId,
                        'unit_id' => $this->unit,
                    ]);
                }
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => "Account role updated successfully!",
                'icon' => 'success'
            ]);
    
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Account role update was unsuccessful!",
                'icon' => 'error'
            ]);
            throw $e;
        }
    }

    public function savePosition(){
        try {
            $empPos = User::where('users.id', $this->userId)->first();
            if ($empPos) {
                $empPos->update([
                    'position_id' => $this->positionId,
                    'office_division_id' => $this->officeDivisionId,
                    'unit_id' => $this->unit,
                    'active_status' => $this->activeStatus,
                ]);
                $this->dispatch('swal', [
                    'title' => 'Employee settings updated successfully!',
                    'icon' => 'success'
                ]);
                $this->resetVariables();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function toggleDelete($userId, $message){
        $this->deleteMessage = $message;
        $this->deleteId = $userId;
    }

    public function deleteData(){
        try {
            $message = null;

            if($this->data){
                if($this->data == "office/division"){
                    $officeDivisions = OfficeDivisions::where('id', $this->deleteId)->first();
                    $officeDivisions->delete();
                    $message = "Office/Division deleted successfully!";
                }else if($this->data == "position"){
                    $positions = Positions::where('id', $this->deleteId)->first();
                    $positions->delete();
                    $message = "Position deleted successfully!";
                }else if($this->data == "salary grade"){
                    $sg = SalaryGrade::where('id', $this->deleteId)->first();
                    $sg->delete();
                    $message = "Salary grade deleted successfully!";
                }
            }else{
                $user = User::where('id', $this->deleteId)->first();
                if ($user) {
                    switch($this->deleteMessage){
                        case "role":
                            $user->delete();
                            $user->admin()->delete();
                            $message = "Role deleted successfully!";
                            break;
                        case "payroll signatory":
                            $user->signatories()->where('signatory_type', 'payroll')->delete();
                            $message = "Payroll signatory deleted successfully!";
                            break;
                        case "payslip signatory":
                            $user->signatories()->where('signatory_type', 'payslip')->delete();
                            $message = "Payslip signatory deleted successfully!";
                            break;
                        default:
                            break;
                    }             
                }
            }

            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        } catch (Exception $e) {
            $this->dispatch('swal', [
                'title' => "Deletion of " . $this->deleteMessage . "was unsuccessful!",
                'icon' => 'error'
            ]);
            $this->resetVariables();
            throw $e;
        }
    }

    public function editSG($id){
        $this->isEditing = true;
        $this->editingId = $id;
        $salaryGrade = $this->salaryGrades->firstWhere('id', $id);
        
        $this->salaryGradeData = [
            'salary_grade' => $salaryGrade->salary_grade,
            'step1' => $salaryGrade->step1,
            'step2' => $salaryGrade->step2,
            'step3' => $salaryGrade->step3,
            'step4' => $salaryGrade->step4,
            'step5' => $salaryGrade->step5,
            'step6' => $salaryGrade->step6,
            'step7' => $salaryGrade->step7,
            'step8' => $salaryGrade->step8,
        ];
        
        $this->showSGModal = true;
    }

    public function openSGModal(){
        $this->showSGModal = true;
    }

    public function saveSalaryGrade(){
        try{
            $message = null;
            $this->validate([
                'salaryGradeData.salary_grade' => 'required|integer',
                'salaryGradeData.step1' => 'required|numeric',
                'salaryGradeData.step2' => 'required|numeric',
                'salaryGradeData.step3' => 'required|numeric',
                'salaryGradeData.step4' => 'required|numeric',
                'salaryGradeData.step5' => 'required|numeric',
                'salaryGradeData.step6' => 'required|numeric',
                'salaryGradeData.step7' => 'required|numeric',
                'salaryGradeData.step8' => 'required|numeric',
            ]);
            if ($this->isEditing) {
                SalaryGrade::find($this->editingId)->update($this->salaryGradeData);
                $message = "Salary grade updated successfully!";
            } else {
                SalaryGrade::create($this->salaryGradeData);
                $message = "Salary grade added successfully!";
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toggleDeleteSG($id, $data){
        $this->deleteId = $id;
        $this->data = $data;
        $this->deleteMessage = $data;
    }

    public function exportSalaryGrade(){
        $sgStep = SalaryGrade::all();
        $filters = [
            'sgStep' => $sgStep,
        ];
        return Excel::download(new SalaryGradeExport ($filters), 'Salary-Grades.xlsx');
    }

    public function importFromExcel(){
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            DB::beginTransaction();
            
            Excel::import(new SalaryGradeImport, $this->file);
            
            DB::commit();
            
            $this->dispatch('swal', [
                'title' => "Salary Grade imported successfully!",
                'icon' => 'success'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errorMessages = collect($failures)->map(function ($failure) {
                return "Row {$failure->row()}: {$failure->errors()[0]}";
            })->implode(', ');
            
            $this->dispatch('swal', [
                'title' => "Please upload the correct Salary Grade excel file!",
                'icon' => 'error'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', [
                'title' => 'An error occurred during import: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }

        $this->file = null;
    }

    public function resetVariables(){
        $this->resetValidation();
        $this->userId = null;
        $this->name = null;
        $this->employee_number = null;
        $this->position = null;
        $this->editRole = null;
        $this->addRole = null;
        $this->admin_email = null;
        $this->password = null;
        $this->cpassword = null;
        $this->office_division = null;
        $this->deleteId = null;
        $this->deleteMessage = null;
        $this->settings = null;
        $this->settingsId = null;
        $this->add = null;
        $this->settings_data = null;
        $this->settingsData = [['value' => '']];
        $this->units = [['value' => '']];
        $this->data = null;
        $this->showSGModal = null;
        $this->editingId = null;
        $this->salaryGradeData = [
            'salary_grade' => '',
            'step1' => '', 'step2' => '', 'step3' => '', 'step4' => '',
            'step5' => '', 'step6' => '', 'step7' => '', 'step8' => '',
        ];
        $this->activeStatus = null;
        $this->editPosition = null;
        $this->officeDivisionId = null;
        $this->unitId = null;
        $this->unit = null;
        $this->unitName = null;
    }

    private function isPasswordComplex($password){
        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsNumber = preg_match('/\d/', $password);
        $containsSpecialChar = preg_match('/[^A-Za-z0-9]/', $password); // Changed regex to include special characters
        return $containsUppercase && $containsNumber && $containsSpecialChar;
    }
}
