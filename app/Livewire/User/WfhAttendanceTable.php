<?php

namespace App\Livewire\User;

use App\Models\WfhLocationRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DTRSchedule;
use App\Models\EmployeesDtr;
use App\Models\Notification;
use App\Models\TransactionWFH;
use App\Models\WfhLocation;
use Exception;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class WfhAttendanceTable extends Component
{
    use WithPagination;
    public $isWFHDay;
    public $showConfirmation = false;
    public $punchState;
    public $errorMessage;
    public $verifyType;
    public $editLocation;
    public $hasWFHLocation;
    public $address;
    public $search;
    public $morningInDisabled = false;
    public $morningOutDisabled = true;
    public $afternoonInDisabled = true;
    public $afternoonOutDisabled = true;
    public $scheduleType = 'WFH'; // Default value

    public $registeredLatitude;
    public $registeredLongitude;

    public $latitude = null;
    public $longitude = null;
    public $formattedTime = null;
    public $isWithinRadius;
    public $locReqGranted = true;
    public $hasRequested;
    public $approvedBy;
    public $approvedDate;
    public $disapprovedBy;
    public $disapprovedDate;
    public $newLat;
    public $newLng;
    public $editLocMessage;
    public $pageSize = 10; 
    public $pageSizes = [10, 20, 30, 50, 100]; 
    public $approveOnly;


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

    #[On('timeUpdate')] 
    public function handleTimeUpdate($time){
        $this->formattedTime = $time ?? null;
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

    // Method to check if current location is within radius
    private function isWithinAllowedRadius()
    {
        if (!$this->hasWFHLocation || !$this->latitude || !$this->longitude) {
            return false;
        }

        $distance = $this->calculateDistance(
            $this->registeredLatitude,
            $this->registeredLongitude,
            $this->latitude,
            $this->longitude
        );

        // Check if within 20 meters
        return $distance <= 20;
    }

    public function checkWFHDay()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('l');
        $currentDate = Carbon::now()->format('Y-m-d');
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Get the most recent active schedule for the current month
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)
            ->where(function ($query) use ($startOfMonth, $currentDate) {
                $query->where('start_date', '>=', $startOfMonth)
                    ->orWhere(function ($q) use ($currentDate) {
                        $q->where('end_date', '>=', $currentDate);
                    });
            })
            ->orderBy('start_date', 'desc')
            ->first();

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $startDate = Carbon::parse($schedule->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($schedule->end_date)->format('Y-m-d');

            if (in_array($today, $wfhDays) && $currentDate >= $startDate && $currentDate <= $endDate) {
                $this->scheduleType = 'WFH';
            } else {
                $this->scheduleType = 'Onsite';
            }
        } else {
            $this->scheduleType = 'Onsite';
        }
    }

    public function confirmPunch($state, $verifyType)
    {
        $this->punchState = $state;
        $this->verifyType = $verifyType;
        $this->showConfirmation = true;
    }

    public function closeConfirmation()
    {
        $this->showConfirmation = false;
        $this->errorMessage = null;
    }

    public function confirmYes()
    {
        $this->showConfirmation = false;
        $this->punch($this->punchState, $this->verifyType);
    }

    public function punch($state, $verifyType)
    {
        $user = Auth::user();
        $punchTime = Carbon::now();

        // If attempting to punch Afternoon In, check the interval after Morning Out
        if ($verifyType == 'Afternoon In') {
            // Get the latest Morning Out punch
            $lastMorningOut = TransactionWFH::where('emp_code', $user->emp_code)
                ->where('verify_type_display', 'Morning Out')
                ->latest('punch_time')
                ->first();

            if ($lastMorningOut) {
                $timeSinceLastPunch = Carbon::parse($lastMorningOut->punch_time)->diffInMinutes($punchTime);
                
                if ($timeSinceLastPunch < 1) {
                    $this->dispatch('swal', [
                        'title' => 'You must wait at least 1 minute after Morning Out before punching Afternoon In.',
                        'icon' => 'warning'
                    ]);
                    return;
                }
            }
        }

        // Determine the correct punch_state based on verifyType
        $punchState = (strpos($verifyType, 'In') !== false) ? 0 : 1;

        $punchData = [
            'emp_code' => $user->emp_code,
            'punch_time' => $punchTime,
            'punch_state' => $punchState,
            'punch_state_display' => 'WFH',
            'verify_type_display' => $verifyType,
        ];

        TransactionWFH::create($punchData);

        // Disable buttons based on the action
        if ($verifyType == 'Morning In') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = false;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = true;
        } elseif ($verifyType == 'Morning Out') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = false;
            $this->afternoonOutDisabled = true;
        } elseif ($verifyType == 'Afternoon In') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = false;
        } elseif ($verifyType == 'Afternoon Out') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = true;
        }

        $this->dispatch('swal', [
            'title' => "You have successfully punched the $verifyType!",
            'icon' => 'success'
        ]);
    }

    public function morningIn()
    {
        $this->punch(0, 'Morning In');
    }

    public function morningOut()
    {
        $this->punch(1, 'Morning Out');
    }

    public function afternoonIn()
    {
        $this->punch(0, 'Afternoon In');
    }

    public function afternoonOut()
    {
        $this->punch(1, 'Afternoon Out');
    }

    public function resetVariables(){
        // $this->password = null;
        $this->errorMessage = null;
        $this->editLocation = null;
        $this->showConfirmation = null;
        $this->newLat = null;
        $this->newLng = null;
        $this->editLocMessage = null;
        $this->approvedBy = null;
        $this->approvedDate = null;
        $this->disapprovedBy = null;
        $this->disapprovedDate = null;
    }

    public function resetButtonStatesIfNeeded()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentHour = $now->hour;
        // $currentHour = 18;
        $today = $now->format('l');
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();
    
        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $isWFHDay = in_array($today, $wfhDays);
    
            if ($isWFHDay) {
                $this->morningInDisabled = true;
                $this->morningOutDisabled = true;
                $this->afternoonInDisabled = true;
                $this->afternoonOutDisabled = true;
    
                // Fetch transactions for the current day
                $transactions = TransactionWFH::where('emp_code', $user->emp_code)
                    ->where('punch_state_display', 'WFH')
                    ->whereDate('punch_time', Carbon::today())
                    ->pluck('verify_type_display');
    
                if ($currentHour >= 6 && $currentHour < 13) {
                    if (!$transactions->contains('Morning In')) {
                        $this->morningInDisabled = false;
                    } elseif (!$transactions->contains('Morning Out')) {
                        $this->morningOutDisabled = false;
                    }
                }

                if ($currentHour >= 12) {
                    $this->morningInDisabled = true;
                    if (!$transactions->contains('Afternoon In')) {
                        $this->afternoonInDisabled = false;
                    } elseif (!$transactions->contains('Afternoon Out')) {
                        $this->afternoonOutDisabled = false;
                    }
                }
                
                if($currentHour >= 18) {
                    $this->afternoonInDisabled = true;
                }
            }
        }
    }

    public function toggleEditLocation($type){
        if($type == 'request'){
            $this->editLocMessage = 'Change';
        }else{
            $this->editLocMessage = 'Register';
        }
        $this->editLocation = true;
        $this->dispatch('init-map2');
    }

    public function saveLocation(){
        try{
            $this->validate([
                'address' => 'required',
                'newLat' => 'required',
                'newLng' => 'required',
            ]);

            $user = Auth::user();
            $wfhLoc = WfhLocation::where('user_id', $user->id)->first();
            
            if($this->editLocMessage == 'Change'){
                WfhLocationRequests::create([
                    'user_id' => $user->id,
                    'address' => $this->address,
                    'curr_lat' => $this->newLat,
                    'curr_lng' => $this->newLng,
                    'status' => 0,
                ]);
                $this->dispatch('swal', [
                    'title' => "New WFH location requested successfully!",
                    'icon' => 'success'
                ]);
                $this->locReqGranted = false;

                // Create a notification entry
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'locrequest',
                    'notif' => 'locrequest',
                    'read' => 0,
                ]);
            }else{
                $wfhReq = WfhLocationRequests::create([
                    'user_id' => $user->id,
                    'address' => $this->address,
                    'curr_lat' => $this->newLat,
                    'curr_lng' => $this->newLng,
                    'status' => 1,
                    'approver' => 'First-time Registration',
                    'date_approved' => now(),
                ]);

                WfhLocation::create([
                    'user_id' => $user->id,
                    'address' => $this->address,
                    'latitude' => $this->newLat,
                    'longitude' => $this->newLng,
                    'wfh_loc_req_id' => $wfhReq->id,
                ]);
                $this->dispatch('swal', [
                    'title' => "WFH location added successfully!",
                    'icon' => 'success'
                ]);
                $this->hasWFHLocation = true;
            }
            $this->resetVariables();
        }catch(Exception $e){
            throw $e;
        }
    }

    public function showLocReqHistory(){
        try{
            
        }catch(Exception $e){
            throw $e;
        }
    }

    public function mount(){
        $userId = Auth::user()->id;
        $wfhLocation = WfhLocation::where('user_id', $userId)->first();
        if($wfhLocation){
            $this->hasWFHLocation = true;
            $this->registeredLatitude = floatval($wfhLocation->latitude);
            $this->registeredLongitude = floatval($wfhLocation->longitude);
            $this->hasRequested = $wfhLocation->status ? false : true;
        }

        $wfhLocationRequest = WfhLocationRequests::where('user_id', $userId)
                ->where('status', 0)
                ->orderBy('created_at', 'desc')
                ->first();
        if($wfhLocationRequest){
            $this->locReqGranted = false;
        }
    }

    public function viewWFHLocHistory($id){
        try{
            $wfhLocRequest = WfhLocationRequests::where('wfh_location_requests.id', $id)
                ->join('users', 'users.id', 'wfh_location_requests.user_id')
                ->select([
                    'wfh_location_requests.*',
                    'users.name'
                ])
                ->first();
            
            if($wfhLocRequest->status == 2){
                $this->approveOnly = true;
            }else{
                $this->approveOnly = false;

            }
            $this->registeredLatitude = floatval($wfhLocRequest->curr_lat);
            $this->registeredLongitude = floatval($wfhLocRequest->curr_lng);
            $this->address = $wfhLocRequest->address;
            $this->approvedBy = $wfhLocRequest->approver;
            $this->approvedDate = $wfhLocRequest->date_approved;
            $this->disapprovedBy = $wfhLocRequest->disapprover;
            $this->disapprovedDate = $wfhLocRequest->date_disapproved;

            $this->dispatch('showWFHLocHistory');
        }catch(Exception $e){
            throw $e;
        }
    }
         
    public function render()
    {
        $this->checkWFHDay();
        $this->resetButtonStatesIfNeeded();
        
        if ($this->scheduleType === 'WFH') {
            $transactions = TransactionWFH::where('emp_code', Auth::user()->emp_code)
                ->whereDate('punch_time', Carbon::today())
                ->orderBy('punch_time', 'asc')
                ->get();
        } else {
            // Fetch onsite punch times from EmployeesDTR table
            $transactions = EmployeesDtr::where('emp_code', Auth::user()->emp_code)
                ->whereDate('date', Carbon::today())
                ->first();
        }
    
        $groupedTransactions = ($this->scheduleType === 'WFH')
            ? $transactions->groupBy('verify_type_display')
            : $transactions;

        $userId = Auth::user()->id;
        $history = WfhLocationRequests::where('user_id', $userId)
            ->when($this->search, function ($query) {
                return $query->search(trim($this->search));
            })
            ->orderBy('created_at', 'ASC')
            ->paginate($this->pageSize);
    
        return view('livewire.user.wfh-attendance-table', [
            'groupedTransactions' => $groupedTransactions,
            'scheduleType' => $this->scheduleType,
            'history' => $history,
        ]);
    }
}