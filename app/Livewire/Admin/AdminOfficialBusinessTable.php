<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use App\Models\OfficeDivisions;
use App\Models\OfficeDivisionUnits;
use App\Models\OfficialBusiness;
use App\Models\WfhLocation;
use App\Models\WfhLocationRequests;
use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class AdminOfficialBusinessTable extends Component
{
    use WithPagination;
    public $registeredLatitude;
    public $registeredLongitude;
    public $employeeName;
    public $company;
    public $address;
    public $obDate;
    public $obStartTime;
    public $obEndTime;
    public $obTimeIn;
    public $obTimeOut;
    public $obPurpose;
    public $approvedBy;
    public $approvedDate;
    public $disapprovedBy;
    public $disapprovedDate;
    public $approvedBySup;
    public $supApprovedDate;
    public $disapprovedBySup;
    public $supDisapprovedDate;
    public $thisObId;
    public $approveOnly;
    public $search;
    public $search2;
    public $search3;
    public $confirmId;
    public $confirmMessage;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 
    public $viewOB;

    public function render()
    {
        $obs = $this->obs();
        $obRequests = $this->obRequests();
        $disapprovedObs = $this->disapprovedObs();

        return view('livewire.admin.admin-official-business-table', [
            'obs' => $obs,
            'obRequests' => $obRequests,
            'disapprovedObs' => $disapprovedObs,
        ]);
    }


    public function obs(){
        $obs = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
                ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                ->where('official_businesses.status', 1)
                ->when($this->search, function ($query) {
                    return $query->search5(trim($this->search));
                })
                ->select([
                    'official_businesses.*',
                    'user_data.surname',
                    'user_data.first_name',
                    'user_data.middle_name',
                    'user_data.name_extension',
                ])
                ->orderBy('date', 'ASC')
                ->paginate($this->pageSize);

        foreach($obs as $ob){
            $sup = User::where('id', $ob->sup_approver)->first();
            $hr = User::where('id', $ob->approver)->first();

            $ob->supervisor = $sup->name;
            $ob->hr = $hr ? $hr->name : null;
            $ob->supOfficeDiv = OfficeDivisions::where('id', $sup->office_division_id)->first()->office_division;
            $ob->supUnit = OfficeDivisionUnits::where('id', $sup->unit_id)->first()->unit;
        }

        return $obs;
    }

    public function disapprovedObs(){
        $disapprovedObs = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
                ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                ->where('official_businesses.status', 2)
                ->when($this->search3, function ($query) {
                    return $query->search5(trim($this->search3));
                })
                ->select([
                    'official_businesses.*',
                    'user_data.surname',
                    'user_data.first_name',
                    'user_data.middle_name',
                    'user_data.name_extension',
                ])
                ->orderBy('date', 'ASC')
                ->paginate($this->pageSize);

        foreach($disapprovedObs as $ob){
            $sup = User::where('id', $ob->sup_disapprover)->first();
            $hr = User::where('id', $ob->disapprover)->first();

            $ob->supervisor = $sup->name;
            $ob->hr = $hr ? $hr->name : null;
            $ob->supOfficeDiv = OfficeDivisions::where('id', $sup->office_division_id)->first()->office_division;
            $ob->supUnit = OfficeDivisionUnits::where('id', $sup->unit_id)->first()->unit;
        }

        return $disapprovedObs;
    }

    public function obRequests(){
        $user = Auth::user();

        $obRequests = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
            ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
            ->where('official_businesses.status', 0)
            ->when($this->search2, function ($query) {
                return $query->search5(trim($this->search2));
            })
            ->select([
                'official_businesses.*',
                'user_data.surname',
                'user_data.first_name',
                'user_data.middle_name',
                'user_data.name_extension',
            ])
            ->orderBy('date', 'ASC');

        if($user->user_role != 'sv'){
            $obRequests = $obRequests->where(function($query) {
                $query->whereNotNull('official_businesses.date_sup_approved')
                    ->orWhereNotNull('official_businesses.date_sup_disapproved');
            })->paginate($this->pageSize);

            foreach($obRequests as $ob){
                $sup = User::where('id', $ob->sup_approver)->first();
                $ob->supervisor = $sup->name;
                $ob->supOfficeDiv = OfficeDivisions::where('id', $sup->office_division_id)->first()->office_division;
                $ob->supUnit = OfficeDivisionUnits::where('id', $sup->unit_id)->first()->unit;
            }
        }else {
            $obRequests = $obRequests->where('official_businesses.sup_approver', $user->id)
                    ->whereNull('official_businesses.approver')
                    ->where(function($query) {
                        $query->whereNull('official_businesses.date_sup_approved')
                              ->orWhereNull('official_businesses.date_sup_disapproved');
                    })
                    ->paginate($this->pageSize);

            foreach($obRequests as $ob){
                if($ob->date_sup_approved){
                    $ob->supervisor = $user->name;
                    $ob->isApproved = true;
                }
            }
        }

        return $obRequests;
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
            $user = Auth::user();
            $ob = OfficialBusiness::where('id', $this->confirmId)->first();
            if($ob){

                if($user->user_role == 'sv'){
                    $ob->update([
                        'date_sup_approved' => now(),
                    ]);
                }else{
                    $ob->update([
                        'status' => 1,
                        'approver' => $user->id,
                        'date_approved' => now(),
                    ]);

                    // Mark as read notification entry
                    $query = Notification::where('read', false)
                                    ->where('type', 'obrequest')
                                    ->where('user_id', $ob->user_id)
                                    ->first();
                    $query->update(['read' => true]);
                }
                $this->dispatch('swal', [
                    'title' => 'Official Business approved successfully',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'Official Business approval was unsuccessful',
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
            $user = Auth::user();
            $ob = OfficialBusiness::where('id', $this->confirmId)->first();
            if($ob){
                if($user->user_role == 'sv'){
                    $ob->update([
                        'status' => 2,
                        'date_sup_disapproved' => now(),
                    ]);
                }else{
                    $ob->update([
                        'status' => 2,
                        'disapprover' => $user->id,
                        'date_disapproved' => now(),
                    ]);
                
                    // Mark as read notification entry
                    $query = Notification::where('read', false)
                                    ->where('type', 'obrequest')
                                    ->where('user_id', $ob->user_id)
                                    ->first();
                    $query->update(['read' => true]);
                }
                $this->dispatch('swal', [
                    'title' => 'Official Business disapproved successfully',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'Official Business disapproval was unsuccessful',
                    'icon' => 'error'
                ]);
            }
            $this->resetVariables();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function viewThisOB($id, $tab){
        $this->viewOB = true;
        if($tab == 'request'){
            $this->thisObId = $id;
            $this->approveOnly = null;
        }else if($tab == 'disapproved'){
            $this->thisObId = $id;
            $this->approveOnly = true;
        }else{
            $this->thisObId = null;
            $this->approveOnly = null;
        }
        try{
            $ob = OfficialBusiness::where('official_businesses.id', $id)
                ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                ->first();
            if($ob){
                $this->employeeName = trim($ob->surname . ', ' . $ob->first_name . ' ' . 
                    ($ob->middle_name ? $ob->middle_name . ' ' : '') . 
                    ($ob->name_extension ?? ''));
                $this->company = $ob->company;
                $this->address = $ob->address;
                $this->registeredLatitude = $ob->lat;
                $this->registeredLongitude = $ob->lng;
                $this->obDate = $ob->date;
                $this->obStartTime = $ob->time_start;
                $this->obEndTime = $ob->time_end;
                $this->obTimeIn = $ob->time_in;
                $this->obTimeOut = $ob->time_out;
                $this->obPurpose = $ob->purpose;

                $this->approvedBy = $ob->approver ? User::where('id', $ob->approver)->first()->name : 'N/A';
                $this->approvedDate = $ob->date_approved ? Carbon::parse($ob->date_approved)->format('F d, Y'): 'N/A';
                
                $this->disapprovedBy = $ob->disapprover ? User::where('id', $ob->disapprover)->first()->name : 'N/A';
                $this->disapprovedDate = $ob->date_disapproved ? Carbon::parse($ob->date_disapproved)->format('F d, Y'): 'N/A';
                
                $this->approvedBySup = $ob->sup_approver ? User::where('id', $ob->sup_approver)->first()->name : 'N/A';
                $this->supApprovedDate = $ob->date_sup_approved ? Carbon::parse($ob->date_sup_approved)->format('F d, Y'): 'N/A';
                
                $this->disapprovedBySup = $ob->sup_disapprover ? User::where('id', $ob->sup_disapprover)->first()->name : 'N/A';
                $this->supDisapprovedDate = $ob->date_sup_disapproved ? Carbon::parse($ob->date_sup_disapproved)->format('F d, Y'): 'N/A';
                // $this->dispatch('location-updated');
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->confirmId = null;
        $this->thisObId = null;
        $this->employeeName = null;
        $this->company = null;
        $this->address = null;
        $this->registeredLatitude = null;
        $this->registeredLongitude = null;
        $this->obDate = null;
        $this->obStartTime = null;
        $this->obEndTime = null;
        $this->obTimeIn = null;
        $this->obTimeOut = null;
        $this->obPurpose = null;
        $this->approvedBy = null;
        $this->approvedDate = null;
        $this->approveOnly = null;
        $this->viewOB = null;
    }
}
