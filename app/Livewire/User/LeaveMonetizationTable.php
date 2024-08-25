<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\LeaveCredits;
use App\Models\MonetizationRequest;
use Livewire\WithPagination;

class LeaveMonetizationTable extends Component
{
    use WithPagination;

    public $monetizationForm = false;
    public $vlCredits;
    public $slCredits;

    protected $rules = [
        'vlCredits' => 'nullable|numeric|min:0',
        'slCredits' => 'nullable|numeric|min:0',
    ];

    public function openRequestForm()
    {
        $this->resetVariables();
        $this->monetizationForm = true;
    }

    public function closeRequestForm()
    {
        $this->monetizationForm = false;
    }

    public function resetVariables()
    {
        $this->vlCredits = null;
        $this->slCredits = null;
    }

    public function submitMonetizationRequest()
    {
        $this->validate();

        // Fetch the current user's leave credits
        $leaveCredits = LeaveCredits::where('user_id', auth()->id())->firstOrFail();

        // Save the monetization request with status 'Pending'
        MonetizationRequest::create([
            'user_id' => auth()->id(),
            'vl_credits_requested' => $this->vlCredits ?? 0,
            'sl_credits_requested' => $this->slCredits ?? 0,
            'status' => 'Pending',
        ]);

        // Close the form
        $this->closeRequestForm();

        // Dispatch success notification
        $this->dispatch('swal', [
            'title' => "Monetization Request Submitted!",
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $requests = MonetizationRequest::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

        return view('livewire.user.leave-monetization-table', [
            'requests' => $requests,
        ]);
    }
}

