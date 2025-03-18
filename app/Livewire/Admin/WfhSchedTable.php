<?php

namespace App\Livewire\Admin;

use App\Models\Wfh;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WfhSchedTable extends Component
{
    public $selectedTab = 'pending';
    public $requests = [];
    public $reason = '';

    protected $listeners = ['refreshRequests' => 'loadRequests'];

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->requests = Wfh::with('user')
            ->orderBy('wfhDay', 'desc')
            ->get();
    }

    public function approveRequest($id)
    {
        $request = Wfh::findOrFail($id);

        if ($request->status == 'pending') {
            $request->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

            $this->loadRequests();
            session()->flash('message', 'WFH request approved successfully.');
        } else {
            session()->flash('error', 'Unable to approve this request.');
        }
    }

    public function rejectRequest($id)
    {
        $request = Wfh::findOrFail($id);

        if ($request->status == 'pending') {
            $request->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $this->reason
            ]);

            $this->reset('reason');
            $this->loadRequests();
            session()->flash('message', 'WFH request rejected successfully.');
        } else {
            session()->flash('error', 'Unable to reject this request.');
        }
    }

    public function setSelectedTab($tab)
    {
        $this->selectedTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.wfh-sched-table');
    }
}
