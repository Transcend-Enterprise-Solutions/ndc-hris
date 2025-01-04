<?php

namespace App\Livewire\User;

use App\Models\OfficeDivisions;
use App\Models\OfficialBusiness;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;


class OfficialBusinessTable extends Component
{
    public $search;
    public $search2;
    public $search3;
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
    public $registeredLatitude;
    public $registeredLongitude;
    public $isWithinRadius;
    public $latitude = null;
    public $longitude = null;
    public $formattedTime = null;


    public function render(){
        $upcomingObs = OfficialBusiness::where('date', '>', now())
            ->orWhere(function ($query) {
                $query->where('date', '=', now()->toDateString())
                    ->where('time_start', '>', now()->toTimeString());
            })
            ->get();

        $ongoingObs = OfficialBusiness::where('date', '=', now()->toDateString())
            ->where('time_start', '<=', now()->toTimeString())
            ->where('time_end', '>=', now()->toTimeString())
            ->get();


        $completedObs = OfficialBusiness::where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($query) {
                        $query->where('date', '=', now()->toDateString())
                                ->where('time_end', '<', now()->toTimeString());
                    });
            })
            ->get();


        return view('livewire.user.official-business-table', [
            'upcomingObs' => $upcomingObs,
            'ongoingObs' => $ongoingObs,
            'completedObs' => $completedObs,
        ]);
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
        return $distance <= 30;
    }

    public function toggleAddOB(){
        $this->addOB = true;
        $this->editOB = true;
    }

    public function toggleEditOB($id){
        $this->editOB = true;
        $this->editId = $id;
    }

    public function toggleDeleteOB($id){
        $this->deleteId = $id;
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
                'registeredLatitude' => 'required',
            ]);

            if($this->addOB){
                 // Generate a 12-digit random reference number
                $referenceNumber = str_pad(random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);

                OfficialBusiness::create([
                    'user_id' => $user->id,
                    'reference_number' => $referenceNumber,
                    'company' => $this->company,
                    'address' => $this->address,
                    'lat' => $this->registeredLatitude,        
                    'lng' => $this->registeredLongitude,        
                    'date' => $this->date,  
                    'time_start' => $this->startTime,  
                    'time_end' => $this->endTime,  
                    'purpose' => $this->purpose,  
                ]);
            }else{
                $ob = OfficeDivisions::where('id', $this->editId)->first();
                if($ob){
                    $ob->update([
                        'company' => $this->company,
                        'address' => $this->address,
                        'lat' => $this->registeredLatitude,        
                        'lng' => $this->registeredLongitude,        
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
    }
}
