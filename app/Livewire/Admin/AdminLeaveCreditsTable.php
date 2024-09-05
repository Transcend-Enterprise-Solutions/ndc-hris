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
    public $inputCTO = false;
    public $editCTO = false;
    public $employees = [];
    public $employeesForCTO = [];
    public $employee;
    public $vlClaimableCredits;
    public $vlClaimedCredits;
    public $slClaimableCredits;
    public $slClaimedCredits;
    public $splClaimableCredits;
    public $splClaimedCredits;
    public $ctoClaimableCredits;
    public $ctoClaimedCredits;
    public $selectedEmployeeId = null;
    public $processedEmployees = [];
    public $editingEmployee;

    public function openInputCredits()
    {
        $this->inputCredits = true;
    }

    public function openInputCTO()
    {
        $this->inputCTO = true;
    }

    public function closeInputCredits()
    {
        $this->inputCredits = false;
        $this->resetInputFields();
        $this->resetValidation();
    }

    public function closeInputCTO()
    {
        $this->inputCTO = false;
        $this->resetCTOFields();
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

    public function resetCTOFields()
    {
        $this->ctoClaimableCredits = null;
        $this->ctoClaimedCredits = null;
        $this->selectedEmployeeId = null;
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
        $this->resetCTOFields();
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

    public function saveCTOCredits()
    {
        $this->validate([
            'ctoClaimableCredits' => 'required|numeric',
            'ctoClaimedCredits' => 'required|numeric',
        ]);

        if (is_null($this->selectedEmployeeId)) {
            session()->flash('error', 'Please select an employee.');
            return;
        }

        $leaveCredits = LeaveCredits::firstOrCreate(
            ['user_id' => $this->selectedEmployeeId],
            [
                'cto_claimable_credits' => 0,
                'cto_claimed_credits' => 0,
            ]
        );

        // Update CTO credits
        if (!is_null($this->ctoClaimableCredits)) {
            $leaveCredits->cto_claimable_credits += $this->ctoClaimableCredits;
        }
        if (!is_null($this->ctoClaimedCredits)) {
            $leaveCredits->cto_claimed_credits += $this->ctoClaimedCredits;
        }

        $leaveCredits->save();

        $this->selectedEmployeeId = null;

        $this->dispatch('swal', [
            'title' => "CTO Credits added successfully!",
            'icon' => 'success'
        ]);

        $this->closeInputCTO();
    }

    public function openEditCTO($employeeId)
    {
        $this->editCTO = true;
        $this->editingEmployee = LeaveCredits::where('user_id', $employeeId)->first();
        $this->selectedEmployeeId = $employeeId;
        $this->ctoClaimableCredits = $this->editingEmployee->cto_claimable_credits;
        $this->ctoClaimedCredits = $this->editingEmployee->cto_claimed_credits;
    }

    public function closeEditCTO()
    {
        $this->editCTO = false;
        $this->resetCTOFields();
    }

    public function updateCTOCredits()
    {
        $this->validate([
            'ctoClaimableCredits' => 'required|numeric',
            'ctoClaimedCredits' => 'required|numeric',
        ]);

        if (is_null($this->editingEmployee)) {
            session()->flash('error', 'Please select an employee.');
            return;
        }

        $leaveCredits = LeaveCredits::where('user_id', $this->selectedEmployeeId)->first();

        if (!is_null($this->ctoClaimableCredits)) {
            $leaveCredits->cto_claimable_credits = $this->ctoClaimableCredits;
        }
        if (!is_null($this->ctoClaimedCredits)) {
            $leaveCredits->cto_claimed_credits = $this->ctoClaimedCredits;
        }

        $leaveCredits->save();

        $this->dispatch('swal', [
            'title' => "CTO Credits updated successfully!",
            'icon' => 'success'
        ]);

        $this->closeEditCTO();
    }

    public function render()
    {
        $this->processedEmployees = [];
        // Fetch employees who don't have credits inputted yet
        $this->employees = User::where('user_role', 'emp')
            ->whereDoesntHave('leaveCredits', function ($query) {
                $query->where('credits_inputted', 1);
            })
            ->get();

        $this->employeesForCTO = User::where('user_role', 'emp')->get();
    
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
