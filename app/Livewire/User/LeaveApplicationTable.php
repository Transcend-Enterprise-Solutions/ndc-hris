<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\UserData;
use App\Models\User;
use App\Models\LeaveApplication;

class LeaveApplicationTable extends Component
{
    public $applyForLeave = false;
    public $name;
    public $office_or_department;
    public $date_of_filing;
    public $position;
    public $salary;
    public $number_of_days;
    public $start_date;
    public $end_date;
    public $type_of_leave = [];
    public $details_of_leave = [];
    public $philippines;
    public $abroad;
    public $inHospital;
    public $outPatient;
    public $specialIllnessForWomen;
    public $commutation;

    protected $rules = [
        'office_or_department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'salary' => 'required|string|max:255',
        'type_of_leave' => 'required|array|min:1',
    ];

    public function openLeaveForm()
    {
        $this->loadUserData();
        $this->applyForLeave = true;
    }

    public function closeLeaveForm()
    {
        $this->applyForLeave = false;
        $this->resetForm();
    }

    public function resetVariables(){
        $this->office_or_department = null;
        $this->position = null;
        $this->salary = null;
        $this->number_of_days = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->type_of_leave = [];
        $this->details_of_leave = [];
        $this->commutation = null;
        $this->philippines = null;
        $this->abroad = null;
        $this->inHospital = null;
        $this->outPatient = null;
        $this->specialIllnessForWomen = null;
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $userData = UserData::where('user_id', $user->id)->first();

        if ($userData) {
            $this->name = $user->name;
            $this->date_of_filing = now()->toDateString();
        }
    }

    public function submitLeaveApplication()
    {
        $this->validate([
            'office_or_department' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'type_of_leave' => 'required|array|min:1',
            'details_of_leave' => 'required',
            'number_of_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'commutation' => 'required',
        ]);

        $leaveDetails = [];
        foreach ($this->details_of_leave as $leaveType) {
            if ($leaveType === 'Within the Philippines') {
                $leaveDetails[] = $leaveType . ' = ' . $this->philippines;
            }
            if ($leaveType === 'Abroad') {
                $leaveDetails[] = $leaveType . ' = ' . $this->abroad;
            }
            if ($leaveType === 'In Hospital') {
                $leaveDetails[] = $leaveType . ' = ' . $this->inHospital;
            }
            if ($leaveType === 'Out Patient') {
                $leaveDetails[] = $leaveType . ' = ' . $this->outPatient;
            }
            if ($leaveType === 'Women Special Illness') {
                $leaveDetails[] = $leaveType . ' = ' . $this->specialIllnessForWomen;
            }
        }

        $leaveDetailsString = implode(', ', $leaveDetails);


        LeaveApplication::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'office_or_department' => $this->office_or_department,
            'date_of_filing' => $this->date_of_filing,
            'position' => $this->position,
            'salary' => $this->salary,
            'number_of_days' => $this->number_of_days,
            'type_of_leave' => implode(',', $this->type_of_leave),
            'details_of_leave' => $leaveDetailsString,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'commutation' => $this->commutation,
        ]);

        $this->dispatch('notify', [
            'message' => "Leave Application sent successfully!",
            'type' => 'success'
        ]);
        $this->resetForm();
        $this->closeLeaveForm();
    }

    public function resetForm()
    {
        $this->reset([
            'office_or_department', 
            'position', 
            'salary', 
            'number_of_days', 
            'type_of_leave', 
            'details_of_leave', 
            'start_date', 
            'end_date', 
            'commutation',
            'philippines',
            'abroad',
            'inHospital',
            'outPatient',
            'specialIllnessForWomen',
        ]);
    }

    public function render()
    {
        return view('livewire.user.leave-application-table');
    }
}
