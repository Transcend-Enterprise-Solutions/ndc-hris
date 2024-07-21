<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveApplication;

class AdminLeaveRequestTable extends Component
{
    use WithPagination;

    public function updateStatus($applicationId, $status)
    {
        $leaveApplication = LeaveApplication::find($applicationId);
        if ($leaveApplication) {
            $leaveApplication->status = $status;
            $leaveApplication->save();

            $this->dispatch('notify', [
                'message' => "Status updated to {$status}!",
                'type' => 'success'
            ]);
        }
    }

    public function render()
    {
        $leaveApplications = LeaveApplication::with('vacationLeaveDetails', 'sickLeaveDetails')->paginate(10);

        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }
}
