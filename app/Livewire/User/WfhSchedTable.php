<?php

namespace App\Livewire\User;

use App\Models\Wfh;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WfhSchedTable extends Component
{
    public $wfhDay;
    public $selectedTab = 'pending';
    public $requests = [];

    // Validation rules
    protected $rules = [
        'wfhDay' => 'required|date|after_or_equal:today',
    ];

    protected $messages = [
        'wfhDay.required' => 'Please select a date for your WFH request.',
        'wfhDay.date' => 'The WFH date must be a valid date.',
        'wfhDay.after_or_equal' => 'The WFH date must be today or a future date.',
    ];

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->requests = Wfh::where('user_id', Auth::id())
            ->orderBy('wfhDay', 'desc')
            ->get();
    }

    public function requestWfh()
    {
        $this->validate();

        // Check if request already exists for this date
        $existingRequest = Wfh::where('user_id', Auth::id())
            ->where('wfhDay', $this->wfhDay)
            ->first();

        if ($existingRequest) {
            session()->flash('error', 'You already have a WFH request for this date.');
            return;
        }

        // Create new WFH request
        Wfh::create([
            'wfhDay' => $this->wfhDay,
            'status' => 'pending',
            'user_id' => Auth::id()
        ]);

        // Reset form and reload requests
        $this->reset('wfhDay');
        $this->loadRequests();

        session()->flash('message', 'WFH request submitted successfully.');
    }

    public function cancelRequest($id)
    {
        $request = Wfh::findOrFail($id);

        // Only allow cancellation of pending requests and by the owner
        if ($request->user_id == Auth::id() && $request->status == 'pending') {
            $request->delete();
            $this->loadRequests();
            session()->flash('message', 'WFH request cancelled successfully.');
        } else {
            session()->flash('error', 'Unable to cancel this request.');
        }
    }

    public function setSelectedTab($tab)
    {
        $this->selectedTab = $tab;
    }

    public function render()
    {
        return view('livewire.user.wfh-sched-table');
    }
}
