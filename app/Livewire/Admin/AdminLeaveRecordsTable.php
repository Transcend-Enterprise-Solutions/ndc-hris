<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class AdminLeaveRecordsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'pending';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $leaveApplications = LeaveApplication::query()
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->activeTab === 'pending', function ($query) {
                return $query->where('status', 'Pending');
            })
            ->when($this->activeTab === 'approved', function ($query) {
                return $query->where('status', 'Approved');
            })
            ->when($this->activeTab === 'disapproved', function ($query) {
                return $query->where('status', 'Disapproved');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.admin-leave-records-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }
}
