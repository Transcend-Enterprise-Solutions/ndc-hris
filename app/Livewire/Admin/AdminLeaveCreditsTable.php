<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LeaveCredits;
use Livewire\WithPagination;
use App\Models\User;

class AdminLeaveCreditsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $inputCredits = false;
    public $employees = [];
    public $employee;
    public $vlClaimableCredits;
    public $vlClaimedCredits;
    public $slClaimableCredits;
    public $slClaimedCredits;
    public $splClaimableCredits;
    public $splClaimedCredits;
    public $selectedEmployeeId = null;
    public $processedEmployees = [];

    public function openInputCredits()
    {
        $this->inputCredits = true;
    }

    public function closeInputCredits()
    {
        $this->inputCredits = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function resetInputFields()
    {
        $this->vlClaimableCredits = null;
        $this->vlClaimedCredits = null;
        $this->slClaimableCredits = null;
        $this->slClaimedCredits = null;
        $this->splClaimableCredits = null;
        $this->splClaimedCredits = null;
        $this->resetValidation();
    }

    public function resetVariables()
    {
        $this->vlClaimableCredits = null;
        $this->vlClaimedCredits = null;
        $this->slClaimableCredits = null;
        $this->slClaimedCredits = null;
        $this->splClaimableCredits = null;
        $this->splClaimedCredits = null;
        $this->resetValidation();
    }

    public function saveCredits()
    {
        $this->validate([
            'vlClaimableCredits' => 'required|numeric',
            'vlClaimedCredits' => 'required|numeric',
            'slClaimableCredits' => 'required|numeric',
            'slClaimedCredits' => 'required|numeric',
            'splClaimableCredits' => 'required|numeric',
            'splClaimedCredits' => 'required|numeric',
        ]);

        if (is_null($this->employee) ||
            (is_null($this->vlClaimableCredits) && is_null($this->vlClaimedCredits) &&
            is_null($this->slClaimableCredits) && is_null($this->slClaimedCredits) &&
            is_null($this->splClaimableCredits) && is_null($this->splClaimedCredits))
        ) {
            session()->flash('error', 'Please fill out all required fields.');
            return;
        }

        // Retrieve or create leave credits record for the employee
        $leaveCredits = LeaveCredits::firstOrCreate(
            ['user_id' => $this->employee],
            [
                'vl_claimable_credits' => 0,
                'vl_claimed_credits' => 0,
                'sl_claimable_credits' => 0,
                'sl_claimed_credits' => 0,
                'spl_claimable_credits' => 0,
                'spl_claimed_credits' => 0,
                'vl_total_credits' => 0,
                'sl_total_credits' => 0,
                'spl_total_credits' => 0,
            ]
        );

        // Update Vacation Leave credits
        if (!is_null($this->vlClaimableCredits)) {
            $leaveCredits->vl_claimable_credits += $this->vlClaimableCredits;
        }
        if (!is_null($this->vlClaimedCredits)) {
            $leaveCredits->vl_claimed_credits += $this->vlClaimedCredits;
        }
        $leaveCredits->vl_total_credits = $leaveCredits->vl_claimable_credits + $leaveCredits->vl_claimed_credits;

        // Update Sick Leave credits
        if (!is_null($this->slClaimableCredits)) {
            $leaveCredits->sl_claimable_credits += $this->slClaimableCredits;
        }
        if (!is_null($this->slClaimedCredits)) {
            $leaveCredits->sl_claimed_credits += $this->slClaimedCredits;
        }
        $leaveCredits->sl_total_credits = $leaveCredits->sl_claimable_credits + $leaveCredits->sl_claimed_credits;

        // Update Special Privilege Leave credits
        if (!is_null($this->splClaimableCredits)) {
            $leaveCredits->spl_claimable_credits += $this->splClaimableCredits;
        }
        if (!is_null($this->splClaimedCredits)) {
            $leaveCredits->spl_claimed_credits += $this->splClaimedCredits;
        }
        $leaveCredits->spl_total_credits = $leaveCredits->spl_claimable_credits + $leaveCredits->spl_claimed_credits;

        $leaveCredits->credits_inputted = 1;
        $leaveCredits->save();

        $this->selectedEmployeeId = null;

        $this->dispatch('swal', [
            'title' => "Credits added successfully!",
            'icon' => 'success'
        ]);

        $this->closeInputCredits();
    }

    public function mount()
    {
        $this->processedEmployees = [];
    }

    public function render()
    {
        // Fetch employees who don't have credits inputted yet
        $this->employees = User::where('user_role', 'emp')
            ->whereDoesntHave('leaveCredits', function ($query) {
                $query->where('credits_inputted', 1);
            })
            ->get();
    
        $leaveCredits = LeaveCredits::with('user')
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    
        return view('livewire.admin.admin-leave-credits-table', [
            'leaveCredits' => $leaveCredits,
        ]);
    }
     
}
