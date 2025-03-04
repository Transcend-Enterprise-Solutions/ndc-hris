<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use App\Models\OfficialBusiness;
use App\Models\WfhLocation;
use App\Models\WfhLocationRequests;
use Livewire\Component;
use App\Models\User;
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
        $userRole = Auth::user()->user_role;

        $obs = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
                    ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                    ->where('official_businesses.status', 1)
                    ->when($this->search, function ($query) {
                        return $query->search3(trim($this->search));
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

        $obRequests = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
                    ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                    ->where('official_businesses.status', 0)
                    ->when($this->search2, function ($query) {
                        return $query->search3(trim($this->search2));
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

        $disapprovedObs = User::join('official_businesses', 'official_businesses.user_id', 'users.id')
                    ->join('user_data', 'user_data.user_id', 'official_businesses.user_id')
                    ->where('official_businesses.status', 2)
                    ->when($this->search3, function ($query) {
                        return $query->search3(trim($this->search3));
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

        if($userRole != 'sv'){
            $obs->where('official_businesses.date_sup_approved', '!=', null)
                ->where('official_businesses.date_sup_disapproved', '!=', null);
            $obRequests->where('official_businesses.date_sup_approved', '!=', null)
                ->where('official_businesses.date_sup_disapproved', '!=', null);
            $disapprovedObs->where('official_businesses.date_sup_approved', '!=', null)
                ->where('official_businesses.date_sup_disapproved', '!=', null);
        }

        return view('livewire.admin.admin-official-business-table', [
            'obs' => $obs,
            'obRequests' => $obRequests,
            'disapprovedObs' => $disapprovedObs,
        ]);
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
                $ob->update([
                    'status' => 1,
                    'approver' => $user->name,
                    'date_approved' => now(),
                ]);
                $this->dispatch('swal', [
                    'title' => 'Official Business approved successfully',
                    'icon' => 'success'
                ]);

                // Mark as read notification entry
                $query = Notification::where('read', false)
                                ->where('type', 'obrequest')
                                ->where('user_id', $ob->user_id)
                                ->first();
                $query->update(['read' => true]);
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
                $ob->update([
                    'status' => 2,
                    'disapprover' => $user->name,
                    'date_disapproved' => now(),
                ]);
                $this->dispatch('swal', [
                    'title' => 'Official Business disapproved successfully',
                    'icon' => 'success'
                ]);

                // Mark as read notification entry
                $query = Notification::where('read', false)
                                ->where('type', 'obrequest')
                                ->where('user_id', $ob->user_id)
                                ->first();
                $query->update(['read' => true]);
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
                $this->approvedBy = $ob->approver ?: 'N/A';
                $this->approvedDate = $ob->date_approved ?: 'N/A';
                $this->disapprovedBy = $ob->disapprover ?: 'N/A';
                $this->disapprovedDate = $ob->date_disapproved ?: 'N/A';
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
