<?php

namespace App\Livewire\Admin;

use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;

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
        $this->showApproveModal = true;
    }

    public function openDisapproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->reset(['disapproveReason']);
        $this->showDisapproveModal = true;
    }

    public function closeApproveModal()
    {
        $this->showApproveModal = false;
        $this->resetVariables();
    }

    public function closeDisapproveModal()
    {
        $this->showDisapproveModal = false;
        $this->resetVariables();
    }

    public function updateStatus()
    {
        if ($this->status === 'With Pay') {
            $this->validate([
                'status' => 'required',
                'days' => 'required|numeric|min:1',
            ]);
    
            if (in_array($this->selectedApplication->type_of_leave, ['Vacation Leave', 'Sick Leave', 'Special Privilege Leave'])) {
                if (!$this->validateLeaveBalance($this->days)) {
                    return;
                }
            }
        } else {
            $this->validate([
                'status' => 'required',
                'days' => 'required|numeric|min:1',
            ]);
        }
    
        if ($this->selectedApplication) {
            if ($this->status === 'Other') {
                $this->validate([
                    'status' => 'required',
                    'otherReason' => 'required',
                ]);
                $this->selectedApplication->status = "Other";
                $this->selectedApplication->remarks = $this->otherReason;
            } else {
                $this->selectedApplication->status = 'Approved';
                $this->selectedApplication->approved_days = $this->days;
                $this->selectedApplication->remarks = $this->status === 'With Pay' ? 'With Pay' : 'Without Pay';
    
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
    
                if (in_array($this->selectedApplication->type_of_leave, ['Vacation Leave', 'Sick Leave', 'Special Privilege Leave'])) {
                    $this->updateLeaveDetails($this->days, $this->status);
                }
            }
    
            $this->selectedApplication->save();
    
            $this->dispatch('swal', [
                'title' => "Leave application {$this->status} successfully!",
                'icon' => 'success'
            ]);
    
            $this->closeApproveModal();
        }
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


    protected function updateLeaveDetails($days, $status)
    {
        $user_id = $this->selectedApplication->user_id;
        $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();

        if (!$leaveCredits) {
            return;
        }

        $remainingDays = $days;

        if ($leaveCredits->spl_claimable_credits >= $remainingDays) {
            $leaveCredits->spl_claimable_credits -= $remainingDays;
            $leaveCredits->spl_claimed_credits += $remainingDays;
            $remainingDays = 0;
        } else {
            $remainingDays -= $leaveCredits->spl_claimable_credits;
            $leaveCredits->spl_claimed_credits += $leaveCredits->spl_claimable_credits;
            $leaveCredits->spl_claimable_credits = 0;
        }

        if ($remainingDays > 0) {
            if (in_array('Vacation Leave', explode(',', $this->selectedApplication->type_of_leave))) {
                if ($leaveCredits->vl_claimable_credits >= $remainingDays) {
                    $leaveCredits->vl_claimable_credits -= $remainingDays;
                    $leaveCredits->vl_claimed_credits += $remainingDays;
                    $remainingDays = 0;
                } else {
                    $this->addError('days', "Insufficient vacation leave credits. Available: {$leaveCredits->vl_claimable_credits}");
                    return;
                }
            } elseif (in_array('Sick Leave', explode(',', $this->selectedApplication->type_of_leave))) {
                if ($leaveCredits->sl_claimable_credits >= $remainingDays) {
                    $leaveCredits->sl_claimable_credits -= $remainingDays;
                    $leaveCredits->sl_claimed_credits += $remainingDays;
                    $remainingDays = 0;
                } else {
                    $this->addError('days', "Insufficient sick leave credits. Available: {$leaveCredits->sl_claimable_credits}");
                    return;
                }
            }
        }

        $leaveCredits->save();

        $month = date('m', strtotime($this->selectedApplication->start_date));
        $year = date('Y', strtotime($this->selectedApplication->start_date));

        $leaveCreditsCalculation = LeaveCreditsCalculation::where('user_id', $user_id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($leaveCreditsCalculation) {
            $leaveCreditsCalculation->leave_credits_earned -= $days;
            $leaveCreditsCalculation->save();
        }

        if (in_array('Vacation Leave', explode(',', $this->selectedApplication->type_of_leave))) {
            $vacationLeaveDetails = VacationLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
            if ($vacationLeaveDetails) {
                $vacationLeaveDetails->less_this_application = $status === 'Pending' ? 1 : 0;
                $vacationLeaveDetails->status = $status === 'Pending' ? 'Pending' : 'Approved';
                $vacationLeaveDetails->save();
            }
        }

        if (in_array('Sick Leave', explode(',', $this->selectedApplication->type_of_leave))) {
            $sickLeaveDetails = SickLeaveDetails::where('application_id', $this->selectedApplication->id)->first();
            if ($sickLeaveDetails) {
                $sickLeaveDetails->less_this_application = $status === 'Pending' ? 1 : 0;
                $sickLeaveDetails->status = $status === 'Pending' ? 'Pending' : 'Approved';
                $sickLeaveDetails->save();
            }
        }
    }

}

