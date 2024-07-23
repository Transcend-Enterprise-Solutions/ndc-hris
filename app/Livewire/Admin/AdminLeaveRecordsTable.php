<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\Auth;

class AdminLeaveRecordsTable extends Component
{
    public function render()
    {
        // Fetch all leave applications with the related user name
        $leaveApplications = LeaveApplication::with('user') // Assuming there's a relationship set up
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.admin-leave-records-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }
}
