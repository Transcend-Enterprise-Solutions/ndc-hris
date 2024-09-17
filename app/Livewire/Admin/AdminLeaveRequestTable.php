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
use App\Models\User;
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
            if (Auth::id() == $this->selectedApplication->endorser1_id) {
                $this->showEndorserApprove = true;
            } else {
                $this->showApproveModal = true;
            }
        } else {
            $this->showApproveModal = true;
        }

        $this->fetchNonEmployeeUsers();
    }

    public function openDisapproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['disapproveReason']);
        
        if (Auth::id() == $this->selectedApplication->endorser1_id || Auth::id() == $this->selectedApplication->endorser2_id) {
            if (Auth::id() == $this->selectedApplication->endorser1_id) {
                $this->showEndorserDisapprove = true;
            } else {
                $this->showDisapproveModal = true;
            }
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
        
        if (Auth::id() == $application->endorser1_id) {
            $application->status = 'Approved by Supervisor';
            $application->stage = 2;
            $application->save();

            LeaveApprovals::updateOrCreate(
                ['application_id' => $application->id],
                ['second_approver' => Auth::id(), 'stage' => 2]
            );
            // Move to endorser2 if endorser1 has approved
            if ($application->endorser2_id) {

                $this->dispatch('swal', [
                    'title' => "Leave application approved by Supervisor. Wait for the final approval!",
                    'icon' => 'success'
                ]);
            }
            // else {
            //     // No endorser2, so HR completes the approval
            //     $application->status = 'Approved'; // Final approval status
            //     $application->stage = 3;
            //     $application->save();

            //     LeaveApprovals::updateOrCreate(
            //         ['application_id' => $application->id],
            //         ['third_approver' => Auth::id(), 'stage' => 3]
            //     );

            //     $this->dispatch('swal', [
            //         'title' => "Leave application approved by Endorser1 and finalized.",
            //         'icon' => 'success'
            //     ]);
            // }
        } elseif (Auth::id() == $application->endorser2_id) {
            $application->status = 'Approved';
            $application->stage = 3;
            $application->save();

            LeaveApprovals::updateOrCreate(
                ['application_id' => $application->id],
                ['third_approver' => Auth::id(), 'stage' => 3]
            );

            if ($application->remarks === 'With Pay') {
                $this->updateLeaveDetails($application->approved_days, $application->remarks);
            }

            $this->dispatch('swal', [
                'title' => "Leave application approved successfully!",
                'icon' => 'success'
            ]);
        }

        $this->closeEndorserApproveModal();
    }

    public function endorserDisapproveLeave()
    {
        $application = LeaveApplication::find($this->selectedApplication->id);
        
        // Check if the logged-in user is endorser1 and update the status accordingly
        if (Auth::id() == $application->endorser1_id) {
            $application->status = 'Disapproved by Endorser1';
            $application->stage = 4; // Stage for disapproved
        } elseif (Auth::id() == $application->endorser2_id) {
            $application->status = 'Disapproved by Endorser2';
            $application->stage = 4; // Stage for disapproved
        }

        $application->disapprove_reason = $this->disapproveReason;
        $application->save();

        LeaveApprovals::where('application_id', $application->id)
            ->update(['stage' => 4]);

        $this->dispatch('swal', [
            'title' => "Leave application disapproved.",
            'icon' => 'success'
        ]);

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
                $this->selectedApplication->stage = 1;

                LeaveApprovals::updateOrCreate(
                    ['application_id' => $this->selectedApplication->id],
                    ['first_approver' => Auth::id(), 'stage' => 1]
                );
            } else {
                $this->selectedApplication->status = 'Approved by HR';
                $this->selectedApplication->approved_days = $this->days;
                $this->selectedApplication->remarks = $this->status;
                $this->selectedApplication->stage = 1;

                LeaveApprovals::updateOrCreate(
                    ['application_id' => $this->selectedApplication->id],
                    ['first_approver' => Auth::id(), 'stage' => 1]
                );
    
                // if ($this->status === 'With Pay') {
                //     $this->updateLeaveDetails($this->days, $this->status);
                    
                //     if ($this->getErrorBag()->has('days')) {
                //         // There was an error in updateLeaveDetails, so we return without saving
                //         return;
                //     }
                // }
                if ($this->status === 'With Pay') {
                    if (!$this->checkLeaveCredits($this->days)) {
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

            LeaveApprovals::updateOrCreate(
                ['application_id' => $this->selectedApplication->id],
                [
                    'first_approver' => Auth::id(),
                    'stage' => 1
                ]
            );
    
            $this->dispatch('swal', [
                'title' => "Leave application {$this->status} successfully!",
                'icon' => 'success'
            ]);
    
            $this->closeApproveModal();
        }
    }

    protected function checkLeaveCredits($days)
    {
        $user_id = $this->selectedApplication->user_id;
        $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();
    
        if (!$leaveCredits) {
            $this->addError('days', "Leave credits not found for this user.");
            return false;
        }
    
        $leaveTypes = explode(',', $this->selectedApplication->type_of_leave);
        foreach ($leaveTypes as $leaveType) {
            $leaveType = trim($leaveType);
    
            if ($leaveType === "Mandatory/Forced Leave") {
                if ($leaveCredits->fl_claimable_credits < $days || $leaveCredits->vl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient Forced Leave Credits. Available FL: {$leaveCredits->fl_claimable_credits}");
                    return false;
                }
            } else {
                $totalCredits = $leaveCredits->spl_claimable_credits + $leaveCredits->sl_claimable_credits + $leaveCredits->vl_claimable_credits;
                if ($totalCredits < $days) {
                    $this->addError('days', "Insufficient leave credits. Available credits: {$totalCredits}");
                    return false;
                }
            }
        }
    
        return true;
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
            $leaveType = trim($leaveType);  // Remove any leading/trailing whitespace

            if ($leaveType === "Vacation Leave" || $leaveType === "Sick Leave") {
                $totalDeducted = 0;
    
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
    
            } elseif ($leaveType === "Mandatory/Forced Leave") {
                // Deduct from both FL and VL simultaneously
                if ($leaveCredits->fl_claimable_credits < $days || $leaveCredits->vl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient leave credits for Mandatory/Forced Leave");
                    return;
                }
    
                // Deduct from FL
                $leaveCredits->fl_claimable_credits -= $days;
                // $leaveCredits->fl_claimed_credits += $days;
    
                // Deduct from VL
                $leaveCredits->vl_claimable_credits -= $days;
                $leaveCredits->vl_claimed_credits += $days;
    
                $leaveCredits->save();
            } else {

                continue;
            }

            break;
        }
    }
    
    public function fetchNonEmployeeUsers()
    {
        $this->nonEmployeeUsers = User::where('user_role', '!=', 'emp')
                                      ->where('user_role', '!=', 'hr')
                                      ->where('user_role', '!=', 'sa')
                                      ->get();
    }

    public function getFilteredEndorser2UsersProperty()
    {
        // Filter out the selected endorser1 from the list of users for endorser2
        return $this->nonEmployeeUsers->filter(function ($user) {
            return $user->id != $this->endorser1;
        });
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
        
        $loggedInUserId = auth()->id();
        $userRole = auth()->user()->user_role;
        
        $leaveApplications = LeaveApplication::orderBy('created_at', 'desc')
            ->select('id', 'name', 'date_of_filing', 'type_of_leave', 'details_of_leave', 'number_of_days', 'list_of_dates', 'approved_dates', 'file_name', 'file_path', 'status', 'remarks', 'approved_days', 'endorser1_id', 'endorser2_id', 'stage')
            ->paginate(10)
            ->through(function ($leaveApplication) use ($loggedInUserId, $userRole) {
                $leaveApplication->isApprovedByHR = $leaveApplication->status === 'Approved by HR';
                $leaveApplication->isPending = $leaveApplication->status === 'Pending';
                $leaveApplication->isHR = $userRole === 'hr' || $userRole === 'sa';
                $leaveApplication->isEndorser1 = $loggedInUserId === $leaveApplication->endorser1_id;
                $leaveApplication->isEndorser2 = $loggedInUserId === $leaveApplication->endorser2_id;
                $leaveApplication->isEndorser = $leaveApplication->isEndorser1 || $leaveApplication->isEndorser2;
    
                // Actions visibility logic
                if ($leaveApplication->stage == 0) {
                    $leaveApplication->actionsVisible = $leaveApplication->isHR;
                } elseif ($leaveApplication->stage == 1) {
                    $leaveApplication->actionsVisible = $leaveApplication->isEndorser1;
                    $leaveApplication->isEndorser1Approved = $leaveApplication->isEndorser1 && $leaveApplication->status === 'Approved by Supervisor';
                    $leaveApplication->isEndorser2Approved = $leaveApplication->isEndorser2 && $leaveApplication->status === 'Approved';
                } elseif ($leaveApplication->stage == 2) {
                    $leaveApplication->actionsVisible = $leaveApplication->isEndorser2;
                    $leaveApplication->isEndorser2Approved = $leaveApplication->isEndorser2 && $leaveApplication->status === 'Approved';
                }
    
                return $leaveApplication;
            });
        
        $vacationLeaveDetails = VacationLeaveDetails::orderBy('created_at', 'desc')->paginate(10);
        $sickLeaveDetails = SickLeaveDetails::orderBy('created_at', 'desc')->paginate(10);
        
        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
            'vacationLeaveDetails' => $vacationLeaveDetails,
            'sickLeaveDetails' => $sickLeaveDetails,
            'filteredEndorser2Users' => $this->filteredEndorser2Users,
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

