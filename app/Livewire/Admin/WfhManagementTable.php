<?php

namespace App\Livewire\Admin;

use App\Models\WfhLocation;
use App\Models\WfhLocationRequests;
use Livewire\Component;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class WfhManagementTable extends Component
{
    use WithPagination;
    public $registeredLatitude;
    public $registeredLongitude;
    public $employeeName;
    public $search;
    public $search2;
    public $confirmId;
    public $confirmMessage;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    public function render()
    {
        $employees = User::where('user_role', 'emp')
                    ->join('user_data', 'user_data.user_id', 'users.id')
                    ->leftJoin('wfh_locations', 'wfh_locations.user_id', 'users.id')
                    ->when($this->search, function ($query) {
                        return $query->search2(trim($this->search));
                    })
                    ->orderBy('user_data.surname', 'ASC')
                    ->paginate($this->pageSize);

        $locRequesters = User::where('user_role', 'emp')
                    ->join('user_data', 'user_data.user_id', 'users.id')
                    ->join('wfh_location_requests', 'wfh_location_requests.user_id', 'users.id')
                    ->when($this->search2, function ($query) {
                        return $query->search2(trim($this->search2));
                    })
                    ->orderBy('user_data.surname', 'ASC')
                    ->paginate($this->pageSize);

        return view('livewire.admin.wfh-management-table', [
            'employees' => $employees,
            'locRequesters' => $locRequesters,
        ]);
    }

    public function viewEmployeeLocation($id){
        try{
            $employee = User::where('users.id', $id)
            ->join('user_data', 'user_data.user_id', 'users.id')
            ->leftJoin('wfh_locations', 'wfh_locations.user_id', 'users.id')
            ->select([
                'users.id',
                'user_data.surname',
                'user_data.first_name',
                'user_data.middle_name',
                'user_data.name_extension',
                'wfh_locations.latitude',
                'wfh_locations.longitude'
            ])
            ->first();

            $this->registeredLatitude = floatval($employee->latitude);
            $this->registeredLongitude = floatval($employee->longitude);
            $this->employeeName = trim($employee->surname . ', ' . $employee->first_name . ' ' . 
                ($employee->middle_name ? $employee->middle_name . ' ' : '') . 
                ($employee->name_extension ?? ''));

            $this->dispatch('location-updated');
        }catch(Exception $e){
            throw $e;
        }
    }

    public function viewPreviousEmployeeLocation($lat, $lng, $name){
        try{
            $this->registeredLatitude = floatval($lat);
            $this->registeredLongitude = floatval($lng);
            $this->employeeName = $name;

            $this->dispatch('location-updated');
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toogleConfirmModal($id, $type){
        $this->confirmId = $id;
        if($type === 'approve'){
            $this->confirmMessage = 'approve';
        }else{
            $this->confirmMessage = 'disapprove';
        }
    }

    public function approveEmployeeLocation(){
        try{
            $wfhLoc = WfhLocation::where('user_id', $this->confirmId)->first();
            $wfhLocReq = WfhLocationRequests::where('user_id', $this->confirmId)->first();
            if($wfhLoc && $wfhLocReq){
                $wfhLoc->update([
                    'latitude' => null,
                    'longitude' => null,
                    'status' => 0,
                ]);

                $wfhLocReq->update([
                    'status' => 1,
                    'approver' => Auth::user()->name,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Change WFH request successfully approved',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'No request record for the selected employee',
                    'icon' => 'error'
                ]);
            }
            $this->resetVariables();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function disapproveEmployeeLocation(){
        try{
            $wfhLocReq = WfhLocationRequests::where('user_id', $this->confirmId)->first();
            if($wfhLocReq){
                $wfhLocReq->delete();

                $this->dispatch('swal', [
                    'title' => 'Change WFH request successfully disapproved',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'No request record for the selected employee',
                    'icon' => 'error'
                ]);
            }
            $this->resetVariables();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->confirmId = null;
    }
}
