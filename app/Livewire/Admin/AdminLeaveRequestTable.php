<?php

namespace App\Livewire\Admin;

use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;
use App\Models\User;
use App\Models\ESignature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    public $leaveApplicationDetails;
    public $pdfContent;
    public $showPDFPreview = false;

    public $pageSize = 5; 
    public $pageSizes = [5, 10, 20, 30, 50, 100];

    protected $rules = [
        'status' => 'required_if:showApproveModal,true',
        'otherReason' => 'required_if:status,Other|string',
        'days' => 'required_if:status,With Pay,Without Pay|numeric|min:1',
        'disapproveReason' => 'required_if:showDisapproveModal,true'
    ];
    
    public function openApproveModal($applicationId)
    {
        $this->selectedApplication = LeaveApplication::find($applicationId);
        $this->listOfDates = explode(',', $this->selectedApplication->list_of_dates);
        $this->selectedDates = [];
        $this->days = 0;
        $this->status = '';
        $this->otherReason = '';
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
    
    public function calculateWeekdaysInRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $weekdays = 0;
        
        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) {
                $weekdays++;
            }
        }
        
        return $weekdays;
    }

    public function updatedSelectedDates($value)
    {
        $totalDays = 0;
        
        foreach ($this->selectedDates as $date) {
            if (strpos($date, ' - ') !== false) {
                list($startDate, $endDate) = explode(' - ', $date);
                $totalDays += $this->calculateWeekdaysInRange($startDate, $endDate);
            } else {
                $carbonDate = Carbon::parse($date);
                if (!$carbonDate->isWeekend()) {
                    $totalDays++;
                }
            }
        }
        
        $this->days = $totalDays;
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
                $this->selectedApplication->status = "Approved";
                $this->selectedApplication->remarks = $this->otherReason;
                $this->selectedApplication->approved_days = 0;
            } else {
                $this->selectedApplication->status = 'Approved';
                $this->selectedApplication->approved_days = $this->days;
                $this->selectedApplication->remarks = $this->status;

                // if ($this->status === 'With Pay') {
                //     if (!$this->checkLeaveCredits($this->days)) {
                //         return;
                //     }
                //     $this->updateLeaveDetails($this->days, $this->status);
                // }

                $allApprovedDates = [];
                foreach ($this->selectedDates as $date) {
                    if (strpos($date, ' - ') !== false) {
                        $allApprovedDates[] = $date;
                    } else {
                        $allApprovedDates[] = $date;
                    }
                }
                $this->selectedApplication->approved_dates = implode(',', $allApprovedDates);
            }

            $this->selectedApplication->save();

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

            // Check for Mandatory/Forced Leave
            if ($leaveType === "Mandatory/Forced Leave") {
                if ($leaveCredits->fl_claimable_credits < $days || $leaveCredits->vl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient Forced Leave Credits. Available FL: " . number_format($leaveCredits->fl_claimable_credits ?? 0.000, 3));
                    return false;
                }
            }

            // Check individual leave types
            elseif ($leaveType === "Vacation Leave") {
                if ($leaveCredits->vl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient Vacation Leave Credits. Available VL: " . number_format($leaveCredits->vl_claimable_credits ?? 0.000, 3));
                    return false;
                }
            }
            elseif ($leaveType === "Sick Leave") {
                if ($leaveCredits->sl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient Sick Leave Credits. Available SL: " . number_format($leaveCredits->sl_claimable_credits ?? 0.000, 3));
                    return false;
                }
            }
            elseif ($leaveType === "Special Privilege Leave") {
                if ($leaveCredits->spl_claimable_credits < $days) {
                    $this->addError('days', "Insufficient Special Privilege Leave Credits. Available SPL: " . number_format($leaveCredits->spl_claimable_credits ?? 0.000, 3));
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
        $updatedLeaveTypes = [];
    
        foreach ($leaveTypes as $leaveType) {
            $leaveType = trim($leaveType);
            $originalLeaveType = $leaveType;
    
            if ($leaveType === "Mandatory/Forced Leave") {
                // For Mandatory Leave, deduct from both FL and VL
                if ($leaveCredits->fl_claimable_credits >= $days && $leaveCredits->vl_claimable_credits >= $days) {
                    // Deduct from FL
                    $leaveCredits->fl_claimable_credits -= $days;
                    $leaveCredits->fl_claimed_credits += $days;
                    
                    // Deduct from VL
                    $leaveCredits->vl_claimable_credits -= $days;
                    $leaveCredits->vl_claimed_credits += $days;
                } else {
                    $this->addError('days', "Insufficient Mandatory/Forced Leave credits. Available FL: {$leaveCredits->fl_claimable_credits}, VL: {$leaveCredits->vl_claimable_credits}");
                    return;
                }
            } 
            else if ($leaveType === "Vacation Leave") {
                // Directly check and deduct from VL credits
                if ($leaveCredits->vl_claimable_credits >= $days) {
                    $leaveCredits->vl_claimable_credits -= $days;
                    $leaveCredits->vl_claimed_credits += $days;
                } else {
                    $this->addError('days', "Insufficient Vacation Leave credits. Available VL: {$leaveCredits->vl_claimable_credits}");
                    return;
                }
            }
            else if ($leaveType === "Sick Leave") {
                // Directly check and deduct from SL credits
                if ($leaveCredits->sl_claimable_credits >= $days) {
                    $leaveCredits->sl_claimable_credits -= $days;
                    $leaveCredits->sl_claimed_credits += $days;
                } else {
                    $this->addError('days', "Insufficient Sick Leave credits. Available SL: {$leaveCredits->sl_claimable_credits}");
                    return;
                }
            }
            else if ($leaveType === "Special Privilege Leave") {
                if ($leaveCredits->spl_claimable_credits >= $days) {
                    $leaveCredits->spl_claimable_credits -= $days;
                    $leaveCredits->spl_claimed_credits += $days;
                } else {
                    $this->addError('days', "Insufficient Special Privilege Leave credits. Available SPL: {$leaveCredits->spl_claimable_credits}");
                    return;
                }
            }
    
            $updatedLeaveTypes[] = $leaveType;
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
            $leaveCreditsCalculation->leave_credits_earned -= $days;
            $leaveCreditsCalculation->save();
        }
    
        $this->selectedApplication->type_of_leave = implode(',', $updatedLeaveTypes);
        $this->selectedApplication->save();
    }
    
    public function fetchNonEmployeeUsers()
    {
        $this->nonEmployeeUsers = User::where('user_role', '!=', 'emp')
                                      ->where('user_role', '!=', 'hr')
                                      ->where('user_role', '!=', 'sa')
                                      ->get();
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
        $query = LeaveApplication::query()
            ->orderBy('created_at', 'desc')
            ->select('id', 'name', 'date_of_filing', 'type_of_leave', 'details_of_leave', 
                    'number_of_days', 'list_of_dates', 'approved_dates', 'file_name', 'file_path', 
                    'status', 'remarks', 'approved_days');

        // Only show pending requests for HR/SA
        if (auth()->user()->user_role === 'hr' || auth()->user()->user_role === 'sa') {
            $query->where('status', 'Pending');
        }

        $leaveApplications = $query->paginate($this->pageSize)
            ->through(function ($leaveApplication) {
                $leaveApplication->actionsVisible = $leaveApplication->status === 'Pending' &&
                    (auth()->user()->user_role === 'hr' || auth()->user()->user_role === 'sa');
                return $leaveApplication;
            });
        
        return view('livewire.admin.admin-leave-request-table', [
            'leaveApplications' => $leaveApplications,
            'vacationLeaveDetails' => VacationLeaveDetails::orderBy('created_at', 'desc')->paginate(10),
            'sickLeaveDetails' => SickLeaveDetails::orderBy('created_at', 'desc')->paginate(10),
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

    // public function validateLeaveBalance($days)
    // {
    //     $leaveCredits = LeaveCredits::where('user_id', $this->selectedApplication->user_id)->first();

    //     $totalClaimableCredits = ($leaveCredits->vl_claimable_credits ?? 0) +
    //                             ($leaveCredits->sl_claimable_credits ?? 0) +
    //                             ($leaveCredits->spl_claimable_credits ?? 0);

    //     $this->balance = $totalClaimableCredits;

    //     if ($this->status === 'With Pay' && ($totalClaimableCredits < $days || $totalClaimableCredits < 1)) {
    //         $this->addError('days', "Insufficient leave credits. Total available credits: {$totalClaimableCredits}");
    //         return false;
    //     }

    //     return true;
    // }

    public function closeLeaveDetails()
    {
        $this->showPDFPreview = false;
        $this->pdfContent = null;
    }

    public function showPDF($leaveApplicationId)
    {
        $leaveApplication = LeaveApplication::with('user.userData')->findOrFail($leaveApplicationId);
    
        $selectedLeaveTypes = $leaveApplication->type_of_leave ? explode(',', $leaveApplication->type_of_leave) : [];
    
        $otherLeave = '';
        foreach ($selectedLeaveTypes as $leaveType) {
            if (strpos($leaveType, 'Others: ') === 0) {
                $otherLeave = str_replace('Others: ', '', $leaveType);
                break;
            }
        }
    
        $detailsOfLeave = $leaveApplication->details_of_leave ? array_map('trim', explode(',', $leaveApplication->details_of_leave)) : [];
    
        $isDetailPresent = function($detail) use ($detailsOfLeave) {
            foreach ($detailsOfLeave as $item) {
                $parts = explode('=', $item, 2);
                $key = trim($parts[0]);
                if ($key === $detail) {
                    return true;
                }
            }
            return false;
        };
    
        $getDetailValue = function($detail) use ($detailsOfLeave) {
            foreach ($detailsOfLeave as $item) {
                $parts = explode('=', $item, 2);
                if (count($parts) === 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    if ($key === $detail) {
                        return $value;
                    }
                }
            }
            return '';
        };
    
        $daysWithPay = '';
        $daysWithoutPay = '';
        $otherRemarks = '';
    
        if ($leaveApplication->status === 'Approved') {
            if ($leaveApplication->remarks === 'With Pay') {
                $daysWithPay = $leaveApplication->approved_days;
            } elseif ($leaveApplication->remarks === 'Without Pay') {
                $daysWithoutPay = $leaveApplication->approved_days;
            } else {
                $otherRemarks = $leaveApplication->remarks;
            }
        }
    
        $leaveCredits = LeaveCredits::where('user_id', $leaveApplication->user_id)->first();
    
        $firstPagePDF = PDF::loadView('pdf.leave-application', [
            'leaveApplication' => $leaveApplication,
            'selectedLeaveTypes' => $selectedLeaveTypes,
            'otherLeave' => $otherLeave,
            'detailsOfLeave' => $detailsOfLeave,
            'isDetailPresent' => $isDetailPresent,
            'getDetailValue' => $getDetailValue,
            'daysWithPay' => $daysWithPay,
            'daysWithoutPay' => $daysWithoutPay,
            'otherRemarks' => $otherRemarks,
            'leaveCredits' => $leaveCredits
        ]);
    
        // Save the first page PDF to a temporary file
        $tempFirstPagePath = storage_path('app/temp_first_page.pdf');
        file_put_contents($tempFirstPagePath, $firstPagePDF->output());
    
        // Path to the second page template
        $secondPageTemplatePath = storage_path('app/public/pdf_template/secondpage.pdf');
        if (!file_exists($secondPageTemplatePath)) {
            throw new \Exception('Second page template not found at: ' . $secondPageTemplatePath);
        }
    
        // Create a multi-page PDF using FPDI
        $pdf = new \setasign\Fpdi\Fpdi();
    
        // Add the first page
        $pdf->AddPage();
        $pdf->SetTitle('Leave Application');
        $pdf->setSourceFile($tempFirstPagePath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId);
    
        // Add the second page
        $pdf->AddPage();
        $pdf->setSourceFile($secondPageTemplatePath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId);
    
        // Clean up temporary file
        unlink($tempFirstPagePath);
    
        // Define a user-friendly filename
        $fileName = 'Leave_Application_' . $leaveApplication->id . '.pdf';
    
        // Output the final PDF with the specified filename
        $this->pdfContent = base64_encode($pdf->Output($fileName, 'S'));
        $this->showPDFPreview = true;
    }

    public function downloadFile($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            $fullPath = Storage::disk('public')->path($filePath);
            $fileName = basename($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);

            return response()->download($fullPath, $fileName, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);
        }

        // Handle file not found
        $this->dispatch('swal', [
            'title' => 'File not found!',
            'icon' => 'error'
        ]);
    }
}

