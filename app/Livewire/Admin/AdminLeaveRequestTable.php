<?php

namespace App\Livewire\Admin;

use App\Models\LeaveCredits;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;

class AdminLeaveRequestTable extends Component
{
    use WithPagination;

    public $showApproveModal = false;
    public $showDisapproveModal = false;
    public $selectedApplication;
    public $status;
    public $otherReason;
    public $days;
    public $disapproveReason;
    public $balance; // Track balance for validation

    protected $rules = [
        'status' => 'required_if:showApproveModal,true',
        'otherReason' => 'required_if:status,Other|string',
        'days' => 'required_if:status,With Pay,Without Pay|numeric|min:1',
        'disapproveReason' => 'required_if:showDisapproveModal,true'
    ];

    public function openApproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['status', 'otherReason', 'days']);
        $this->showApproveModal = true;
    }

    public function openDisapproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['disapproveReason']);
        $this->showDisapproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->resetVariables();
    }

    public function closeDisapproveModal()
    {
        $this->showDisapproveModal = false;
        $this->resetVariables();
    }

    public function updateStatus()
    {
        if ($this->status === 'With Pay') {
            $this->validate([
                'status' => 'required',
                'days' => 'required|numeric|min:1',
            ]);

            // Validate leave balance
            $this->validateLeaveBalance($this->days);

            // Check if balance is sufficient
            if ($this->balance < $this->days) {
                // Set a validation error message
                $this->addError('days', "This employee has a remaining balance of {$this->balance}. Please approve it for fewer or equal to that amount.");

                // Stop further processing
                return;
            }
        } else {
            $this->validate([
                'status' => 'required',
                'days' => 'required|numeric|min:1',
            ]);
        }

        if ($this->selectedApplication) {
            if ($this->status === 'Other') {
                $this->validate([
                    'status' => 'required',
                    'otherReason' => 'required',
                ]);
                $this->selectedApplication->status = "Other";
                $this->selectedApplication->remarks = $this->otherReason;
            } else {
                $this->selectedApplication->status = $this->status === 'With Pay' ? 'Approved' : 'Approved';
                $this->selectedApplication->approved_days = $this->days;
                $this->selectedApplication->remarks = $this->status === 'With Pay' ? 'With Pay' : 'Without Pay';
                $this->updateLeaveDetails($this->days, $this->status);
            }

            $this->selectedApplication->save();

            $this->dispatch('notify', [
                'message' => "Leave application {$this->status} successfully!",
                'type' => 'success'
            ]);

            $this->closeApproveModal();
        }
    }

    public function disapproveLeave()
    {
        $this->validate([
            'disapproveReason' => 'required'
        ]);

        if ($this->selectedApplication) {
            $this->selectedApplication->status = "Disapproved";
            $this->selectedApplication->remarks = $this->disapproveReason;
            $this->selectedApplication->save();

            $this->dispatch('notify', [
                'message' => "Leave application disapproved for reason: {$this->disapproveReason}!",
                'type' => 'error'
            ]);

            $this->closeDisapproveModal();
        }
    }

    public function render()
    {
        $leaveApplications = LeaveApplication::orderBy('created_at', 'desc')
            ->select('id', 'name', 'date_of_filing', 'type_of_leave', 'details_of_leave', 'number_of_days', 'start_date', 'end_date', 'file_name', 'file_path', 'status', 'remarks', 'approved_days')
            ->paginate(10);

        $vacationLeaveDetails = VacationLeaveDetails::orderBy('created_at', 'desc')->paginate(10);

        $sickLeaveDetails = SickLeaveDetails::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
            'vacationLeaveDetails' => $vacationLeaveDetails,
            'sickLeaveDetails' => $sickLeaveDetails,
        ]);
    }

    public function resetVariables()
    {
        $this->status = null;
        $this->otherReason = null;
        $this->days = null;
        $this->disapproveReason = null;
    }

    public function validateLeaveBalance($days)
    {
        // Fetch current balance for the user
        $vacationLeaveDetails = VacationLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
        $sickLeaveDetails = SickLeaveDetails::where('application_id', $this->selectedApplication->id)->first();

        $vacationBalance = $vacationLeaveDetails ? $vacationLeaveDetails->balance : 0;
        $sickBalance = $sickLeaveDetails ? $sickLeaveDetails->balance : 0;

        $this->balance = $vacationBalance + $sickBalance;

        // Check if the balance is sufficient
        if ($this->status === 'With Pay' && $this->balance < $days) {
            $this->status = 'Without Pay';  // Automatically set status to Without Pay if balance is insufficient
        }
    }

    protected function updateLeaveDetails($days, $status)
    {
        // Handle vacation leave
        if (in_array('Vacation Leave', explode(',', $this->selectedApplication->type_of_leave))) {
            $vacationLeaveDetails = VacationLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
            if ($vacationLeaveDetails) {
                if ($status === 'With Pay') {
                    $vacationLeaveDetails->balance -= $days;

                    // Update claimable_credits and total_claimed_credits in leave_credits
                    $user_id = $this->selectedApplication->user_id;
                    $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();
                    if ($leaveCredits) {
                        $leaveCredits->claimable_credits -= $days;
                        $leaveCredits->total_claimed_credits = ($leaveCredits->total_claimed_credits ?? 0) + $days;
                        $leaveCredits->save();
                    }
                }
                $vacationLeaveDetails->less_this_application = $status === 'Pending' ? 1 : 0;
                $vacationLeaveDetails->status = $status === 'Pending' ? 'Pending' : 'Approved';
                $vacationLeaveDetails->save();
            }
        }

        // Handle sick leave
        if (in_array('Sick Leave', explode(',', $this->selectedApplication->type_of_leave))) {
            $sickLeaveDetails = SickLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
            if ($sickLeaveDetails) {
                if ($status === 'With Pay') {
                    $sickLeaveDetails->balance -= $days;

                    // Update claimable_credits and total_claimed_credits in leave_credits
                    $user_id = $this->selectedApplication->user_id;
                    $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();
                    if ($leaveCredits) {
                        $leaveCredits->claimable_credits -= $days;
                        $leaveCredits->total_claimed_credits = ($leaveCredits->total_claimed_credits ?? 0) + $days;
                        $leaveCredits->save();
                    }
                }
                $sickLeaveDetails->less_this_application = $status === 'Pending' ? 1 : 0;
                $sickLeaveDetails->status = $status === 'Pending' ? 'Pending' : 'Approved';
                $sickLeaveDetails->save();
            }
        }
    }
}
