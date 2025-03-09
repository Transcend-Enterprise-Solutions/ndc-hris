<?php

namespace App\Livewire\User;

use App\Models\Notification;
use App\Models\OfficeDivisions;
use App\Models\OfficialBusiness;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;


class OfficialBusinessTable extends Component
{
    public $search;
    public $search2;
    public $search3;
    public $search4;
    public $search5;
    public $search6;
    public $editOB;
    public $addOB;
    public $deleteId;
    public $editId;
    public $company;
    public $address;
    public $date;
    public $startTime;
    public $endTime;
    public $purpose;
    public $registeredLatitude = null;
    public $registeredLongitude = null;
    public $newLatitude = null;
    public $newLongitude = null;
    public $isWithinRadius;
    public $isTodayIsOb;
    public $latitude = null;
    public $longitude = null;
    public $formattedTime = null;
    public $obStatus;
    public $viewOB;
    public $approvedBy;
    public $approvedDate;
    public $disapprovedBy;
    public $disapprovedDate;
    public $approvedBySup;
    public $supApprovedDate;
    public $disapprovedBySup;
    public $supDisapprovedDate;
    public $showConfirmation = false;
    public $punchState;
    public $punchObId;
    public $verifyType;
    public $hasObTimeIn;
    public $hasObTimeOut;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 


    public function render(){
        
        $ongoingObs = $this->ongoingObs();
        $upcomingObs = $this->upcomingObs();

        if (!$ongoingObs) {
            $ongoingObs = $upcomingObs->first();
            $this->obStatus = 'UPCOMING';
        }else{
            $this->obStatus = 'ONGOING';
        }

        $completedObs = $this->completedObs();
        $unattendedObs = $this->unattendedObs();
        $obRequests = $this->obRequests();
        $disapprovedObs = $this->disapprovedObs();
        $approvedObs = $this->approvedObs();
            

        return view('livewire.user.official-business-table', [
            'upcomingObs' => $upcomingObs,
            'ongoingObs' => $ongoingObs,
            'completedObs' => $completedObs,
            'unattendedObs' => $unattendedObs,
            'obRequests' => $obRequests,
            'disapprovedObs' => $disapprovedObs,
            'approvedObs' => $approvedObs,
        ]);
    }

    public function ongoingObs(){
        $user = Auth::user();
        $ongoingObs = OfficialBusiness::where('date', '=', now()->toDateString())
            ->where('time_start', '<=', now()->toTimeString())
            ->where('time_end', '>=', now()->toTimeString())
            ->where('time_out', '=', null)
            ->where('user_id', $user->id)
            ->first();

        $this->registeredLatitude = $ongoingObs ? $ongoingObs->lat : null;
        $this->registeredLongitude = $ongoingObs ? $ongoingObs->lng : null;

        // if ($ongoingObs && $upcomingObs->contains('id', $ongoingObs->id)) {
        //     $upcomingObs = $upcomingObs->filter(function ($ob) use ($ongoingObs) {
        //         return $ob->id !== $ongoingObs->id;
        //     });
        // }

        if($ongoingObs){
            if (now()->isSameDay(Carbon::parse($ongoingObs->date))) {
                $this->isTodayIsOb = true;
            }

            if($ongoingObs->time_in){
                $this->hasObTimeIn = $ongoingObs->time_in;
            }
            if($ongoingObs->time_out){
                $this->hasObTimeOut = $ongoingObs->time_out;
            }
        }

        return $ongoingObs;
    }

    public function upcomingObs(){
        $user = Auth::user();
        $upcomingObs = OfficialBusiness::where(function ($query) {
            $query->where('date', '>', now()->toDateString())
                ->orWhere(function ($subQuery) {
                    $subQuery->where('date', '=', now()->toDateString())
                        ->where('time_start', '>', now()->toTimeString());
                });
            })
            ->when($this->search3, function ($query) {
                return $query->search(trim($this->search3));
            })
            ->where('user_id', $user->id)
            ->where('time_out', '=', null)
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->paginate($this->pageSize);

        return $upcomingObs;
    }

    public function completedObs(){
        $user = Auth::user();
        $completedObs = OfficialBusiness::where('time_in', '!=', null)
            ->where('user_id', $user->id)
            ->where('time_out', '!=', null)
            ->when($this->search, function ($query) {
                return $query->search(trim($this->search));
            })
            ->paginate($this->pageSize);
        
        return $completedObs;
    }

    public function unattendedObs(){
        $user = Auth::user();
        $unattendedObs = OfficialBusiness::where('time_in', '=', null)
            ->where('user_id', $user->id)
            ->where('time_out', '=', null)
            ->where('date', '<', now()->toDateString())
            ->when($this->search2, function ($query) {
                return $query->search(trim($this->search2));
            })
            ->paginate($this->pageSize);
        
        return $unattendedObs;
    }

    public function obRequests(){
        $user = Auth::user();
        $obRequests = OfficialBusiness::where(function($query) {
            $query->where(function($q) {
                $q->whereNull('date_sup_approved')
                ->whereNull('date_sup_disapproved')
                ->whereNull('date_approved')
                ->whereNull('date_disapproved');
            })
            ->orWhere(function($q) {
                    $q->where(function($subQ) {
                        $subQ->whereNotNull('date_sup_approved')
                            ->orWhereNotNull('date_sup_disapproved');
                    })
                    ->where(function($subQ) {
                        $subQ->whereNull('date_approved')
                            ->whereNull('date_disapproved');
                    });
                });
            })
            ->where('user_id', $user->id)
            ->where('status', 0)
            ->when($this->search4, function ($query) {
                return $query->search(trim($this->search4));
            })
            ->paginate($this->pageSize);

        foreach ($obRequests as $obReq) {
            $sup = User::where('id', $obReq->sup_approver)->first();
            $hr = User::where('id', $obReq->approver)->first();

            if(!$hr){
                $hr = User::where('id', $obReq->disapprover)->first();
            }

            $obReq->supervisor = $sup->name;
            $obReq->hr = $hr ? $hr->name : null;
        }

        return $obRequests;
    }

    public function disapprovedObs(){
        $user = Auth::user();
        $disapprovedObs = OfficialBusiness::where('status', 2)
        ->where('user_id', $user->id)
        ->when($this->search6, function ($query) {
            return $query->search(trim($this->search6));
        })
        ->paginate($this->pageSize);

        foreach ($disapprovedObs as $obs) {
            $sup = User::where('id', $obs->sup_disapprover)->first();
            $hr = User::where('id', $obs->disapprover)->first();

            $obs->supervisor = $sup->name;
            $obs->hr = $hr ? $hr->name : null;
        }

        return $disapprovedObs;
    }

    public function approvedObs(){
        $user = Auth::user();
        $approvedObs = OfficialBusiness::where('status', 1)
        ->where('user_id', $user->id)
        ->when($this->search5, function ($query) {
            return $query->search(trim($this->search5));
        })
        ->paginate($this->pageSize);

        foreach ($approvedObs as $obs) {
            $sup = User::where('id', $obs->sup_approver)->first();
            $hr = User::where('id', $obs->approver)->first();

            $obs->supervisor = $sup->name;
            $obs->hr = $hr ? $hr->name : null;
        }

        return $approvedObs;
    }




    #[On('locationUpdated')] 
    public function handleLocationUpdate($locationData)
    {
        if (is_string($locationData)) {
            $locationData = json_decode($locationData, true);
        }
        
        $this->latitude = $locationData['latitude'] ?? null;
        $this->longitude = $locationData['longitude'] ?? null;
        $this->formattedTime = $locationData['formattedTime'] ?? null;
        
        // Check if within allowed radius and update UI accordingly
        $this->isWithinRadius = $this->isWithinAllowedRadius();
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius of the Earth in meters
        $R = 6371000;

        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Differences in coordinates
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Haversine formula
        $a = sin($dLat/2) * sin($dLat/2) +
            cos($lat1) * cos($lat2) * 
            sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        // Distance in meters
        $distance = $R * $c;
        
        return $distance;
    }

    private function isWithinAllowedRadius(){
        if (!$this->latitude || !$this->longitude) {
            return false;
        }

        $distance = $this->calculateDistance(
            $this->registeredLatitude,
            $this->registeredLongitude,
            $this->latitude,
            $this->longitude
        );
        return $distance <= 300;
    }

    public function toggleAddOB(){
        $this->addOB = true;
        $this->editOB = true;
    }

    public function toggleEditOB($id){
        $this->editOB = true;
        $this->editId = $id;
        try{
            $ob = OfficialBusiness::where('id', $id)->first();
            if($ob){
                $this->company = $ob->company;
                $this->address = $ob->address;
                $this->registeredLatitude = $ob->lat;
                $this->registeredLongitude = $ob->lng;
                $this->date = $ob->date;
                $this->startTime = $ob->time_start;
                $this->endTime = $ob->time_end;
                $this->purpose = $ob->purpose;
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function toggleDeleteOB($id){
        $this->deleteId = $id;
    }

    public function deleteData(){
        try{
            $ob = OfficialBusiness::where('id', $this->deleteId)->first();
            if($ob){
                $ob->delete();
                $this->dispatch('swal', [
                    'title' => 'Official Business deleted successfully',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'Official Business deletion was unsuccessful',
                    'icon' => 'error'
                ]);
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function saveOB(){
        try{
            $user = Auth::user();
            $this->validate([
                'company' => 'required',
                'address' => 'required',
                'date' => 'required',
                'startTime' => 'required',
                'endTime' => 'required',
                'purpose' => 'required',
                // 'newLatitude' => 'required',
            ]);

            $supervisor = User::where('user_role', 'sv')
                    ->where('office_division_id', $user->office_division_id)
                    ->orderByRaw("CASE 
                        WHEN unit_id IS NOT NULL AND unit_id = ? THEN 1
                        WHEN unit_id IS NULL THEN 2
                        ELSE 3
                    END", [$user->unit_id])
                    ->first();
        
            if(!$supervisor){
                $this->resetVariables();
                $this->dispatch('swal', [
                    'title' => 'No assigned supervisor for your division or unit. Please contact the administrator for assistance.',
                    'icon' => 'error'
                ]);
                return;
            }

        

            if($this->addOB){
                 // Generate a 12-digit random reference number
                $referenceNumber = str_pad(random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);

                OfficialBusiness::create([
                    'user_id' => $user->id,
                    'reference_number' => $referenceNumber,
                    'company' => $this->company,
                    'address' => $this->address,
                    // 'lat' => $this->newLatitude,        
                    // 'lng' => $this->newLongitude,        
                    'date' => $this->date,  
                    'time_start' => $this->startTime,  
                    'time_end' => $this->endTime,  
                    'purpose' => $this->purpose,  
                    'sup_approver' => $supervisor->id,  
                    'sup_disapprover' => $supervisor->id,  
                ]);

                // Create a notification entry
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'obrequest',
                    'notif' => 'obrequest',
                    'read' => 0,
                ]);
            }else{
                $ob = OfficialBusiness::where('id', $this->editId)->first();
                if($ob){
                    $ob->update([
                        'company' => $this->company,
                        'address' => $this->address,
                        // 'lat' => $this->newLatitude,        
                        // 'lng' => $this->newLongitude,        
                        'date' => $this->date,  
                        'time_start' => $this->startTime,  
                        'time_end' => $this->endTime,  
                        'purpose' => $this->purpose,
                    ]);
                }
            }
            $this->resetVariables();
            $this->dispatch('swal', [
                'title' => 'Official Business added successfully',
                'icon' => 'success'
            ]);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function viewThisOB($id){
        $this->viewOB = true;
        try{
            $ob = OfficialBusiness::where('id', $id)->first();
            if($ob){
                $this->company = $ob->company;
                $this->address = $ob->address;
                $this->registeredLatitude = $ob->lat;
                $this->registeredLongitude = $ob->lng;
                $this->date = $ob->date;
                $this->startTime = $ob->time_start;
                $this->endTime = $ob->time_end;
                $this->purpose = $ob->purpose;

                $this->approvedBy = $ob->approver ? User::where('id', $ob->approver)->first()->name : 'N/A';
                $this->approvedDate = $ob->date_approved ? Carbon::parse($ob->date_approved)->format('F d, Y'): 'N/A';
                
                $this->disapprovedBy = $ob->disapprover ? User::where('id', $ob->disapprover)->first()->name : 'N/A';
                $this->disapprovedDate = $ob->date_disapproved ? Carbon::parse($ob->date_disapproved)->format('F d, Y'): 'N/A';
                
                $this->approvedBySup = $ob->sup_approver ? User::where('id', $ob->sup_approver)->first()->name : 'N/A';
                $this->supApprovedDate = $ob->date_sup_approved ? Carbon::parse($ob->date_sup_approved)->format('F d, Y'): 'N/A';
                
                $this->disapprovedBySup = $ob->sup_disapprover ? User::where('id', $ob->sup_disapprover)->first()->name : 'N/A';
                $this->supDisapprovedDate = $ob->date_sup_disapproved ? Carbon::parse($ob->date_sup_disapproved)->format('F d, Y'): 'N/A';
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    public function confirmPunch($id, $state, $verifyType){
        $this->punchObId = $id;
        $this->punchState = $state;
        $this->verifyType = $verifyType;
        $this->showConfirmation = true;
    }

    public function recordObAttendance(){
        try{
            $ob = OfficialBusiness::where('id', $this->punchObId)->first();
            if($ob){
                if($this->punchState == 'timeIn'){
                    $ob->update([
                        'time_in' => now()->toTimeString(),
                    ]);
                }else{
                    $ob->update([
                        'time_out' => now()->toTimeString(),
                    ]);
                }
                $this->dispatch('swal', [
                    'title' => 'Official Business attendance recorded successfully',
                    'icon' => 'success'
                ]);
            }else{
                $this->dispatch('swal', [
                    'title' => 'Official Business attendance recording was unsuccessful',
                    'icon' => 'error'
                ]);
            }
            $this->resetVariables();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function resetVariables(){
        $this->editOB = null;
        $this->addOB = null;
        $this->deleteId = null;
        $this->editId = null;
        $this->company = null;
        $this->address = null;
        $this->registeredLatitude = null;        
        $this->registeredLongitude = null;        
        $this->date = null;  
        $this->startTime = null;  
        $this->endTime = null;  
        $this->purpose = null;
        $this->viewOB = null;
        $this->newLatitude = null;
        $this->newLongitude = null;
        $this->punchState = null;
        $this->verifyType = null;
        $this->showConfirmation = null;
    }
}
