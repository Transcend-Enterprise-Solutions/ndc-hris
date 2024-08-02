<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\UserData;
use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;
use App\Models\LeaveCredits;  // Import LeaveCredits model
use Illuminate\Support\Facades\Storage;

class LeaveApplicationTable extends Component
{
    use WithPagination, WithFileUploads;

    public $applyForLeave = false;
    public $name;
    public $office_or_department;
    public $date_of_filing;
    public $position;
    public $salary;
    public $number_of_days;
    public $start_date;
    public $end_date;
    public $type_of_leave = '';
    public $details_of_leave = [];
    public $philippines;
    public $abroad;
    public $inHospital;
    public $outPatient;
    public $specialIllnessForWomen;
    public $commutation;
    public $files = [];

    protected $rules = [
        'office_or_department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'salary' => 'required|string|max:255',
        'type_of_leave' => 'required',
        'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
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

    public function resetVariables()
    {
        $this->office_or_department = null;
        $this->position = null;
        $this->salary = null;
        $this->number_of_days = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->type_of_leave = '';
        $this->details_of_leave = [];
        $this->commutation = null;
        $this->philippines = null;
        $this->abroad = null;
        $this->inHospital = null;
        $this->outPatient = null;
        $this->specialIllnessForWomen = null;
        $this->files = [];
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
            'type_of_leave' => 'required',
            'number_of_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'commutation' => 'required',
        ]);

        $filePaths = [];
        $fileNames = [];

        // Handle file uploads
        if ($this->files) {
            foreach ($this->files as $file) {
                $originalFilename = $file->getClientOriginalName();
                $filePath = $file->storeAs('leavedocu', $originalFilename, 'public');
                $filePaths[] = $filePath;
                $fileNames[] = $originalFilename;
            }
        }

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
            if ($leaveType === 'Completion of Masters Degree') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'BAR/Board Examination Review') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'Monetization of Leave Credits') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'Terminal Leave') {
                $leaveDetails[] = $leaveType;
            }
        }

        $leaveDetailsString = implode(', ', $leaveDetails);
        $filePathsString = implode(',', $filePaths);

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $userId = Auth::id();

        // Get leave credits earned from LeaveCreditsCalculation table
        $leaveCreditsCalculation = \App\Models\LeaveCreditsCalculation::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $leaveCreditsEarned = $leaveCreditsCalculation ? $leaveCreditsCalculation->leave_credits_earned : 0;

        // Check if credits have already been transferred
        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();
        if ($leaveCredits) {
            if (!$leaveCredits->credits_transferred) {
                // Transfer data to LeaveCredits table
                $leaveCredits->total_credits = $leaveCreditsEarned;
                $leaveCredits->save();

                // Update the credits_transferred flag
                $leaveCredits->credits_transferred = true;
                $leaveCredits->save();
            }
        } else {
            // Create a new record and set the credits_transferred flag to true
            LeaveCredits::create([
                'user_id' => $userId,
                'total_credits' => $leaveCreditsEarned,
                'claimable_credits' => $leaveCreditsEarned,
                'credits_transferred' => true
            ]);
        }

        // Create leave application
        $leaveApplication = LeaveApplication::create([
            'user_id' => $userId,
            'name' => $this->name,
            'office_or_department' => $this->office_or_department,
            'date_of_filing' => $this->date_of_filing,
            'position' => $this->position,
            'salary' => $this->salary,
            'number_of_days' => $this->number_of_days,
            'type_of_leave' => $this->type_of_leave,
            'details_of_leave' => $leaveDetailsString,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'commutation' => $this->commutation,
            'status' => 'Pending',
            'file_path' => implode(',', $filePaths),  // Concatenate file paths
            'file_name' => implode(',', $fileNames),
        ]);

        if ($this->type_of_leave === 'Vacation Leave') {
            VacationLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'less_this_application' => 1,
                // 'balance' => $leaveCreditsEarned,
                'recommendation' => 'For approval',
                'status' => 'Pending', // You may update this based on your requirements
            ]);
        }

        if ($this->type_of_leave === 'Sick Leave') {
            SickLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'less_this_application' => 1,
                // 'balance' => $leaveCreditsEarned,
                'recommendation' => 'For approval',
                'status' => 'Pending', // You may update this based on your requirements
            ]);
        }

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
            'files',
        ]);
    }

    public function render()
    {
        $userId = Auth::id();
        $leaveApplications = LeaveApplication::where('user_id', $userId)
            ->with('vacationLeaveDetails', 'sickLeaveDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch total credits from LeaveCredits table
        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();

        return view('livewire.user.leave-application-table', [
            'leaveApplications' => $leaveApplications,
            'totalCredits' => $leaveCredits ? $leaveCredits->total_credits : 0,
        ]);
    }
}
