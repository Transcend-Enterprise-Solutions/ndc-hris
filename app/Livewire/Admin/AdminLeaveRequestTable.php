<?php

namespace App\Livewire\Admin;

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

    protected $rules = [
        'status' => 'required_if:showApproveModal,true',
        'otherReason' => 'required_if:status,Other',
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
        $this->validate([
            'status' => 'required',
            'otherReason' => 'required_if:status,Other',
            'days' => 'required_if:status,With Pay,Without Pay|numeric|min:1',
        ]);

        if ($this->selectedApplication) {
            if ($this->status === 'Other') {
                $this->selectedApplication->status = $this->otherReason;
            } else {
                $this->selectedApplication->status = "Approved for: {$this->days} days {$this->status}";
            }

            $this->selectedApplication->save();

            if (in_array('Vacation Leave', explode(',', $this->selectedApplication->type_of_leave))) {
                $vacationLeaveDetails = VacationLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
                if ($vacationLeaveDetails) {
                    if ($this->status === 'Pending') {
                        $vacationLeaveDetails->less_this_application = 1;
                    } else {
                        $vacationLeaveDetails->less_this_application = 0;
                        $vacationLeaveDetails->total_earned += 1;
                        $vacationLeaveDetails->balance -= 1;
                    }
                    $vacationLeaveDetails->save();
                }
            }

            if (in_array('Sick Leave', explode(',', $this->selectedApplication->type_of_leave))) {
                $sickLeaveDetails = SickLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
                if ($sickLeaveDetails) {
                    if ($this->status === 'Pending') {
                        $sickLeaveDetails->less_this_application = 1;
                    } else {
                        $sickLeaveDetails->less_this_application = 0;
                        $sickLeaveDetails->total_earned += 1;
                        $sickLeaveDetails->balance -= 1;
                    }
                    $sickLeaveDetails->save();
                }
            }

            $this->dispatch('notify', [
                'message' => "Leave application approved successfully!",
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
            $this->selectedApplication->status = "Disapproved due to: {$this->disapproveReason}";
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
        $leaveApplications = LeaveApplication::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }

    public function resetVariables()
    {
        $this->status = null;
        $this->otherReason = null;
        $this->days = null;
        $this->disapproveReason = null;
    }
}
