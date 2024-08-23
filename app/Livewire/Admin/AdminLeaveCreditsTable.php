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
    public $leaveType;
    public $claimableCredits;
    public $claimedCredits;
    public $selectedEmployeeId = null;

    public function openInputCredits()
    {
        $this->inputCredits = true;
    }

    public function closeInputCredits()
    {
        $this->inputCredits = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->leaveType = null;
        $this->claimableCredits = null;
        $this->claimedCredits = null;
    }

    public function resetVariables()
    {
        $this->leaveType = null;
        $this->claimableCredits = null;
        $this->claimedCredits = null;
    }

    public function saveCredits()
    {
        // Ensure an employee is selected and leave type is chosen
        if (is_null($this->employee) || is_null($this->leaveType) || is_null($this->claimableCredits) || is_null($this->claimedCredits)) {
            session()->flash('error', 'Please fill out all required fields.');
            return;
        }

        // Retrieve or create leave credits record for the employee
        $leaveCredits = LeaveCredits::firstOrCreate(
            ['user_id' => $this->employee],
            // Initial values for a new record
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

        // Update the leave credits based on leave type
        switch ($this->leaveType) {
            case 'vacation':
                $leaveCredits->vl_claimable_credits += $this->claimableCredits;
                $leaveCredits->vl_claimed_credits += $this->claimedCredits;
                $leaveCredits->vl_total_credits = $leaveCredits->vl_claimable_credits + $leaveCredits->vl_claimed_credits;
                break;
            case 'sick':
                $leaveCredits->sl_claimable_credits += $this->claimableCredits;
                $leaveCredits->sl_claimed_credits += $this->claimedCredits;
                $leaveCredits->sl_total_credits = $leaveCredits->sl_claimable_credits + $leaveCredits->sl_claimed_credits;
                break;
            case 'spl':
                $leaveCredits->spl_claimable_credits += $this->claimableCredits;
                $leaveCredits->spl_claimed_credits += $this->claimedCredits;
                $leaveCredits->spl_total_credits = $leaveCredits->spl_claimable_credits + $leaveCredits->spl_claimed_credits;
                break;
        }

        $leaveCredits->save();

        // Remove the selected employee from the list
        $this->employees = $this->employees->filter(function ($emp) {
            return $emp->id != $this->selectedEmployeeId;
        });

        $this->dispatch('swal', [
            'title' => "Credits added successfully!",
            'icon' => 'success'
        ]);

        $this->closeInputCredits();
    }

    public function render()
    {
        $this->employees = User::where('user_role', 'emp')->get();

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
