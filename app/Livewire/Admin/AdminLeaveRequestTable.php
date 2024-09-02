<?php

namespace App\Livewire\Admin;

use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;
use App\Models\LeaveApprovals;
use Illuminate\Support\Facades\Auth;

class AdminLeaveRequestTable extends Component
{
    use WithPagination;

    public $showApproveModal = false;
    public $showDisapproveModal = false;
    public $selectedApplication;
    public $status;
    public $otherReason;
    public $days;
    public $disapproveReason;
    public $balance;
    public $listOfDates = [];
    public $selectedDates = [];
    public $nonEmployeeUsers = [];
    public $endorser1;
    public $endorser2;
    public $showEndorserApprove = false;
    public $showEndorserDisapprove = false;


    protected $rules = [
        'status' => 'required_if:showApproveModal,true',
        'otherReason' => 'required_if:status,Other|string',
        'days' => 'required_if:status,With Pay,Without Pay|numeric|min:1',
        // 'approvedStartDate' => 'required_if:status,With Pay|date',
        // 'approvedEndDate' => 'required_if:status,With Pay|date|after_or_equal:approvedStartDate',
        'disapproveReason' => 'required_if:showDisapproveModal,true'
    ];

    public function openApproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->listOfDates = explode(',', $this->selectedApplication->list_of_dates);
        $this->reset(['status', 'otherReason', 'days']);

        // Check if the logged-in user is one of the endorsers
        if (Auth::id() == $this->selectedApplication->endorser1_id || Auth::id() == $this->selectedApplication->endorser2_id) {
            $this->showEndorserApprove = true;
        } else {
            $this->showApproveModal = true;
        }

        $this->fetchNonEmployeeUsers();
    }

    public function openDisapproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['disapproveReason']);
        
        // Check if the logged-in user is one of the endorsers
        if (Auth::id() == $this->selectedApplication->endorser1_id || Auth::id() == $this->selectedApplication->endorser2_id) {
            $this->showEndorserDisapprove = true;
        } else {
            $this->showDisapproveModal = true;
        }
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->showEndorserApprove = false;
        $this->resetVariables();
    }
    
    public function closeDisapproveModal()
    {
        $this->showDisapproveModal = false;
        $this->showEndorserDisapprove = false;
        $this->resetVariables();
    }

    public function endorserApproveLeave()
    {
        $application = LeaveApplication::find($this->selectedApplication->id);
        // Implement the logic to approve the leave application
        $application->status = 'Approved by Endorser';
        $application->save();

        $this->closeEndorserApproveModal();
    }

    public function endorserDisapproveLeave()
    {
        $application = LeaveApplication::find($this->selectedApplication->id);
        // Implement the logic to disapprove the leave application
        $application->status = 'Disapproved by Endorser';
        $application->disapprove_reason = $this->disapproveReason;
        $application->save();

        $this->closeEndorserDisapproveModal();
    }

    public function openEndorserApproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['status', 'otherReason', 'days']);
        $this->showEndorserApprove = true;
    }

    public function openEndorserDisapproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['disapproveReason']);
        $this->showEndorserDisapprove = true;
    }

    public function closeEndorserApproveModal()
    {
        $this->showEndorserApprove = false;
        $this->resetVariables();
    }

    public function closeEndorserDisapproveModal()
    {
        $this->showEndorserDisapprove = false;
        $this->resetVariables();
    }
    
    public function updateStatus()
    {
        $this->validate([
            'status' => 'required',
            'days' => 'required|numeric|min:1',
        ]);
    
        if ($this->selectedApplication) {
            if ($this->status === 'Other') {
                $this->validate(['otherReason' => 'required|string']);
                $this->selectedApplication->status = "Approved by HR";
                $this->selectedApplication->remarks = $this->otherReason;
                $this->selectedApplication->approved_days = 0;
            } else {
                $this->selectedApplication->status = 'Approved by HR';
                $this->selectedApplication->approved_days = $this->days;
                $this->selectedApplication->remarks = $this->status;
    
                if ($this->status === 'With Pay') {
                    $this->updateLeaveDetails($this->days, $this->status);
                    
                    if ($this->getErrorBag()->has('days')) {
                        // There was an error in updateLeaveDetails, so we return without saving
                        return;
                    }
                }
    
                $allApprovedDates = [];
                foreach ($this->selectedDates as $date) {
                    if (strpos($date, ' - ') !== false) {
                        $range = explode(' - ', $date);
                        $allApprovedDates = array_merge($allApprovedDates, $range);
                    } else {
                        $allApprovedDates[] = $date;
                    }
                }
                $this->selectedApplication->approved_dates = implode(',', $allApprovedDates);
            }
    
            $this->selectedApplication->endorser1_id = $this->endorser1;
            $this->selectedApplication->endorser2_id = $this->endorser2;
            $this->selectedApplication->save();

            // Update leave_approvals stage
            LeaveApprovals::where('application_id', $this->selectedApplication->id)
                ->update(['stage' => 1]);
    
            $this->dispatch('swal', [
                'title' => "Leave application {$this->status} successfully!",
                'icon' => 'success'
            ]);
    
            $this->closeApproveModal();
        }
    }
    
    protected function updateLeaveDetails($days, $status)
    {
        $user_id = $this->selectedApplication->user_id;
        $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();
    
        if (!$leaveCredits) {
            $this->addError('days', "Leave credits not found for this user.");
            return;
        }
    
        $leaveTypes = explode(',', $this->selectedApplication->type_of_leave);
    
        foreach ($leaveTypes as $leaveType) {
            $totalDeducted = 0; // Track total days deducted across SPL, SL, and VL
    
            // Deduct from SPL first
            if ($leaveCredits->spl_claimable_credits > 0) {
                $deduct = min($days, $leaveCredits->spl_claimable_credits);
                $leaveCredits->spl_claimable_credits -= $deduct;
                $leaveCredits->spl_claimed_credits += $deduct;
                $totalDeducted += $deduct;
            }
    
            // If more days are needed, try deducting from SL next
            if ($totalDeducted < $days && $leaveCredits->sl_claimable_credits > 0) {
                $remainingDays = $days - $totalDeducted;
                $deduct = min($remainingDays, $leaveCredits->sl_claimable_credits);
                $leaveCredits->sl_claimable_credits -= $deduct;
                $leaveCredits->sl_claimed_credits += $deduct;
                $totalDeducted += $deduct;
            }
    
            // Finally, if still more days are needed, try deducting from VL
            if ($totalDeducted < $days && $leaveCredits->vl_claimable_credits > 0) {
                $remainingDays = $days - $totalDeducted;
                $deduct = min($remainingDays, $leaveCredits->vl_claimable_credits);
                $leaveCredits->vl_claimable_credits -= $deduct;
                $leaveCredits->vl_claimed_credits += $deduct;
                $totalDeducted += $deduct;
            }
    
            // If the total deducted days are still less than requested, show an error
            if ($totalDeducted < $days) {
                $this->addError('days', "Insufficient leave credits. Available SPL: {$leaveCredits->spl_claimable_credits}, SL: {$leaveCredits->sl_claimable_credits}, VL: {$leaveCredits->vl_claimable_credits}");
                return;
            }
    
            $leaveCredits->save();
    
            // Updating LeaveCreditsCalculation
            $month = date('m', strtotime($this->selectedApplication->start_date));
            $year = date('Y', strtotime($this->selectedApplication->start_date));
    
            $leaveCreditsCalculation = LeaveCreditsCalculation::where('user_id', $user_id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
    
            if ($leaveCreditsCalculation) {
                $leaveCreditsCalculation->leave_credits_earned -= $totalDeducted;
                $leaveCreditsCalculation->save();
            }
    
            // Break out after processing the current leave type
            break;
        }
    }
    
    public function fetchNonEmployeeUsers()
    {
        // Fetch users where user_role is not 'emp'
        $this->nonEmployeeUsers = \App\Models\User::where('user_role', '!=', 'emp')->get();
    }

    public function disapproveLeave()
    {
        $this->validate([
            'disapproveReason' => 'required'
        ]);

        if ($this->selectedApplication) {
            $this->selectedApplication->status = "Disapproved";
            $this->selectedApplication->remarks = $this->disapproveReason;
            $this->selectedApplication->approved_days = 0;
            $this->selectedApplication->save();

            $this->dispatch('swal', [
                'title' => "Leave application disapproved for reason: {$this->disapproveReason}!",
                'icon' => 'success'
            ]);

            $this->closeDisapproveModal();
        }
    }

    public function render()
    {
        $this->fetchNonEmployeeUsers();
        $leaveApplications = LeaveApplication::orderBy('created_at', 'desc')
            ->select('id', 'name', 'date_of_filing', 'type_of_leave', 'details_of_leave', 'number_of_days', 'list_of_dates', 'approved_dates', 'file_name', 'file_path', 'status', 'remarks', 'approved_days')
            ->paginate(10);

        $vacationLeaveDetails = VacationLeaveDetails::orderBy('created_at', 'desc')->paginate(10);

        $sickLeaveDetails = SickLeaveDetails::orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
            'vacationLeaveDetails' => $vacationLeaveDetails,
            'sickLeaveDetails' => $sickLeaveDetails,
        ]);
    }

    public function resetVariables()
    {
        $this->status = null;
        $this->otherReason = null;
        $this->days = null;
        $this->listOfDates = [];
        $this->disapproveReason = null;
    }

    public function validateLeaveBalance($days)
    {
        $leaveCredits = LeaveCredits::where('user_id', $this->selectedApplication->user_id)->first();

        $totalClaimableCredits = ($leaveCredits->vl_claimable_credits ?? 0) +
                                ($leaveCredits->sl_claimable_credits ?? 0) +
                                ($leaveCredits->spl_claimable_credits ?? 0);

        $this->balance = $totalClaimableCredits;

        if ($this->status === 'With Pay' && ($totalClaimableCredits < $days || $totalClaimableCredits < 1)) {
            $this->addError('days', "Insufficient leave credits. Total available credits: {$totalClaimableCredits}");
            return false;
        }

        return true;
    }

}

