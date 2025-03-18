<?php

namespace App\Livewire\User;

use App\Models\Wfh;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WfhSchedTable extends Component
{
    public $wfhDay;
    public $wfh_reason;
    public $selectedTab = 'pending';
    public $requests = [];
    public $isModalOpen = false;

    // Validation rules
    protected $rules = [
        'wfhDay' => 'required|date|after_or_equal:today',
        'wfh_reason' => 'required|string|min:5|max:500',
    ];

    protected $messages = [
        'wfhDay.required' => 'Please select a date for your WFH request.',
        'wfhDay.date' => 'The WFH date must be a valid date.',
        'wfhDay.after_or_equal' => 'The WFH date must be today or a future date.',
        'wfh_reason.required' => 'Please provide a reason for your WFH request.',
        'wfh_reason.min' => 'Your reason must be at least 5 characters.',
        'wfh_reason.max' => 'Your reason cannot exceed 500 characters.',
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

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['wfhDay', 'wfh_reason']);
        $this->resetValidation();
    }

    public function requestWfh()
    {
        $this->validate();

        // Check if request already exists for this date
        $existingRequest = Wfh::where('user_id', Auth::id())
            ->where('wfhDay', $this->wfhDay)
            ->first();

        if ($existingRequest) {
            $this->dispatch('swal', [
                'title' => 'Request Exists',
                'text' => 'You already have a WFH request for this date.',
                'icon' => 'error'
            ]);
            return;
        }

        // Create new WFH request with reason
        Wfh::create([
            'wfhDay' => $this->wfhDay,
            'wfh_reason' => $this->wfh_reason,
            'status' => 'pending',
            'user_id' => Auth::id()
        ]);

        // Reset form, close modal and reload requests
        $this->reset(['wfhDay', 'wfh_reason']);
        $this->isModalOpen = false;
        $this->loadRequests();

        $this->dispatch('swal', [
            'title' => 'WFH Schedule Requested!',
            'icon' => 'success'
        ]);
    }

    public function cancelRequest($id)
    {
        $request = Wfh::findOrFail($id);

        // Only allow cancellation of pending requests and by the owner
        if ($request->user_id == Auth::id() && $request->status == 'pending') {
            $request->delete();
            $this->loadRequests();

            // Replace session flash with SweetAlert
            $this->dispatch('swal', [
                'title' => 'Request Cancelled',
                'text' => 'WFH request cancelled successfully.',
                'icon' => 'success'
            ]);
        } else {
            // Replace session flash with SweetAlert
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Unable to cancel this request.',
                'icon' => 'error'
            ]);
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
