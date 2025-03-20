<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\UserData;
use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\VacationLeaveDetails;
use App\Models\SickLeaveDetails;
use App\Models\LeaveCredits;
use App\Models\LeaveApprovals;
use App\Models\Payrolls;
use App\Models\CosRegPayrolls;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\ESignature;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Exports\LeaveCardExport;
use App\Exports\LeaveLedgerExport;
use Carbon\Carbon;
use App\Models\MandatoryFormRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveApplicationTable extends Component
{
    use WithPagination, WithFileUploads;

    public $applyForLeave = false;
    public $name;
    public $office_or_department;
    public $date_of_filing;
    public $position;
    public $salary;
    public $number_of_days;

    public $type_of_leave = [];
    public $details_of_leave = [];
    public $philippines;
    public $abroad;
    public $inHospital;
    public $outPatient;
    public $specialIllnessForWomen;
    public $commutation;
    public $files = [];
    public $other_leave;

    public $start_date;
    public $end_date;
    public $list_of_dates = [];
    public $new_date;

    public $selectedYear;
    public $isDisabled = false;

    public $leaveApplicationDetails;
    public $pdfContent;
    public $showPDFPreview = false;

    public $activeTab = 'pending';

    public $showDropdown = false;
    public $requestSent = false;
    public $requestApproved = false;

    public $pageSize = 5;
    public $pageSizes = [5, 10, 20, 30, 50, 100];

    protected $rules = [
        'office_or_department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'salary' => 'required|string|max:255',
        'type_of_leave' => 'required|array|min:1',
        'files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:2048',
        'number_of_days' => 'required|numeric|min:1'
    ];

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function openLeaveForm()
    {
        $this->loadUserData();
        $this->applyForLeave = true;
    }

    public function closeLeaveForm()
    {
        $this->applyForLeave = false;
        $this->resetForm();
    }

    public function resetVariables()
    {
        $this->office_or_department = null;
        $this->position = null;
        $this->salary = null;
        $this->number_of_days = null;
        $this->type_of_leave = [];
        $this->details_of_leave = [];
        $this->commutation = null;
        $this->philippines = null;
        $this->abroad = null;
        $this->inHospital = null;
        $this->outPatient = null;
        $this->specialIllnessForWomen = null;
        $this->files = [];
        $this->list_of_dates = [];
        $this->new_date = null;
        $this->new_date = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $userData = UserData::where('user_id', $user->id)->first();

        if ($userData) {
            $this->name = $user->name;
            $this->date_of_filing = now()->toDateString();
        }

        $officeDivision = OfficeDivisions::find($user->office_division_id);
        $position = Positions::find($user->position_id);

        $this->office_or_department = $officeDivision ? $officeDivision->office_division : 'N/A';
        $this->position = $position ? $position->position : 'N/A';

        $payroll = Payrolls::where('user_id', $user->id)->first();
        if ($payroll) {
            $this->salary = $payroll->rate_per_month;
        } else {
            $cosRegPayroll = CosRegPayrolls::where('user_id', $user->id)->first();
            if ($cosRegPayroll) {
                $this->salary = $cosRegPayroll->rate_per_month;
            }
        }
    }

    public function resetOtherFields($field)
    {
        $fields = ['philippines', 'abroad', 'inHospital', 'outPatient', 'specialIllnessForWomen'];
        foreach ($fields as $f) {
            if ($f !== $field) {
                $this->{$f} = '';
            }
        }
    }

    public function submitLeaveApplication()
    {
        $rules = [
            'office_or_department' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'type_of_leave' => 'required|string',
            'number_of_days' => 'required',
            'commutation' => 'required',
        ];
    
        // Leave types that require details
        $leaveTypesRequiringDetails = [
            'Vacation Leave',
            'Special Privilege Leave',
            'Sick Leave',
            'Special Leave Benefits for Women',
            'Study Leave',
            'Others'
        ];
    
        // Add validation rule for details_of_leave if the selected type requires it
        if (in_array($this->type_of_leave, $leaveTypesRequiringDetails)) {
            $rules['details_of_leave'] = 'required|string';
            
            // Add specific validation for additional fields based on details selected
            if ($this->details_of_leave === 'Within the Philippines') {
                $rules['philippines'] = 'required|string';
            }
            if ($this->details_of_leave === 'Abroad') {
                $rules['abroad'] = 'required|string';
            }
            if ($this->details_of_leave === 'In Hospital') {
                $rules['inHospital'] = 'required|string';
            }
            if ($this->details_of_leave === 'Out Patient') {
                $rules['outPatient'] = 'required|string';
            }
            if ($this->details_of_leave === 'Women Special Illness') {
                $rules['specialIllnessForWomen'] = 'required|string';
            }
        }
    
        $leaveTypesRequiringDates = [
            'Vacation Leave',
            'Sick Leave',
            'Paternity Leave',
            'Special Privilege Leave',
            'Mandatory/Forced Leave',
            'Solo Parent Leave',
            '10-Day VAWC Leave',
            'Special Emergency (Calamity) Leave',
            'Adoption Leave',
            'CTO Leave',
        ];
    
        if (in_array($this->type_of_leave, $leaveTypesRequiringDates)) {
            $rules['list_of_dates'] = 'required|array|min:1';
        }
    
        // Require file upload if CTO Leave is selected
        if ($this->type_of_leave === 'CTO Leave') {
            $rules['files'] = 'required|array|min:1';
            $rules['files.*'] = 'file|mimes:jpeg,png,jpg,pdf|max:2048';
        }
    
        $this->validate($rules);
    
        // New validation for Vacation Leave and Mandatory/Forced Leave
        $now = now();
        $fiveDaysFromNow = $now->copy()->addDays(5)->startOfDay();
    
        if ($this->type_of_leave === 'Vacation Leave' || $this->type_of_leave === 'Mandatory/Forced Leave') {
            // Validation for dates at least 5 days from now
            $invalidDates = collect($this->list_of_dates)->filter(function ($date) use ($fiveDaysFromNow) {
                return Carbon::parse($date)->startOfDay()->lt($fiveDaysFromNow);
            });
    
            if ($invalidDates->isNotEmpty()) {
                $this->addError('list_of_dates', 'For Vacation Leave or Mandatory/Forced Leave, all leave dates must be at least 5 days from now.');
                return;
            }
    
            // Validation for future dates
            $invalidPastDates = collect($this->list_of_dates)->filter(function ($date) use ($now) {
                return Carbon::parse($date)->startOfDay()->lte($now->startOfDay());
            });
    
            if ($invalidPastDates->isNotEmpty()) {
                $this->addError('list_of_dates', 'For Vacation Leave or Mandatory/Forced Leave, all dates must be in the future.');
                return;
            }
        }
    
        // Handle "Others" type of leave
        if ($this->type_of_leave === 'Others') {
            $this->validate([
                'other_leave' => 'required|string'
            ]);
            $this->type_of_leave = $this->other_leave;
        }
    
        $filePaths = [];
        $fileNames = [];
    
        // Handle file uploads
        if ($this->files) {
            foreach ($this->files as $file) {
                $originalFilename = $file->getClientOriginalName();
                $filePath = $file->storeAs('leavedocu', $originalFilename, 'public');
                $filePaths[] = $filePath;
                $fileNames[] = $originalFilename;
            }
        }
    
        $leaveDetails = null;

        // Only process details if the leave type requires it
        if (in_array($this->type_of_leave, $leaveTypesRequiringDetails)) {
            $leaveDetails = $this->details_of_leave;
            
            // Add additional details if needed based on the selection
            if ($this->details_of_leave === 'Within the Philippines') {
                $leaveDetails .= ' = ' . $this->philippines;
            } elseif ($this->details_of_leave === 'Abroad') {
                $leaveDetails .= ' = ' . $this->abroad;
            } elseif ($this->details_of_leave === 'In Hospital') {
                $leaveDetails .= ' = ' . $this->inHospital;
            } elseif ($this->details_of_leave === 'Out Patient') {
                $leaveDetails .= ' = ' . $this->outPatient;
            } elseif ($this->details_of_leave === 'Women Special Illness') {
                $leaveDetails .= ' = ' . $this->specialIllnessForWomen;
            }
        }
    
        $datesString = '';
        if ($this->start_date && $this->end_date) {
            $datesString = $this->start_date . ' - ' . $this->end_date;
        } else if (!empty($this->list_of_dates)) {
            $datesString = implode(',', $this->list_of_dates);
        }
    
        $userId = Auth::id();
    
        $leaveApplication = LeaveApplication::create([
            'user_id' => $userId,
            'name' => $this->name,
            'office_or_department' => $this->office_or_department,
            'date_of_filing' => $this->date_of_filing,
            'position' => $this->position,
            'salary' => $this->salary,
            'number_of_days' => $this->number_of_days,
            'type_of_leave' => $this->type_of_leave,
            'details_of_leave' => $leaveDetails,
            'commutation' => $this->commutation,
            'status' => 'Pending',
            'file_path' => implode(',', $filePaths),
            'file_name' => implode(',', $fileNames),
            'list_of_dates' => $datesString,
        ]);
    
        // Insert into leave_approvals
        LeaveApprovals::create([
            'user_id' => $userId,
            'application_id' => $leaveApplication->id,
            'stage' => 0
        ]);
    
        // Create specific leave details if needed
        if ($this->type_of_leave === 'Vacation Leave') {
            VacationLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'less_this_application' => 1,
                'recommendation' => 'For approval',
                'status' => 'Pending',
            ]);
        }
    
        if ($this->type_of_leave === 'Sick Leave') {
            SickLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'less_this_application' => 1,
                'recommendation' => 'For approval',
                'status' => 'Pending',
            ]);
        }
    
        $this->dispatch('swal', [
            'title' => "Leave application sent successfully!",
            'icon' => 'success'
        ]);
    
        $this->resetForm();
        $this->closeLeaveForm();
    }

    public function removeFile($index)
    {
        if (isset($this->files[$index])) {
            unset($this->files[$index]);
            $this->files = array_values($this->files);
        }
    }
    public function updatedStartDate($value)
    {
        if ($this->start_date && $this->end_date) {
            $this->calculateWorkingDays();
        }
    }

    public function updatedEndDate($value)
    {
        if ($this->start_date && $this->end_date) {
            $this->calculateWorkingDays();
        }
    }

    protected function calculateWorkingDays()
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        // Validate that end date is not before start date
        if ($end->lt($start)) {
            $this->addError('end_date', 'End date cannot be before start date');
            return;
        }

        $workingDays = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            // Check if current day is not a weekend (Saturday = 6, Sunday = 0)
            if (!$current->isWeekend()) {
                // Here you could also check for holidays if you have a holiday list
                $workingDays++;
            }
            $current->addDay();
        }

        $this->number_of_days = $workingDays;

        // Instead of storing all dates, just store the range
        $this->list_of_dates = [$this->start_date . ' - ' . $this->end_date];
    }

    public function addDate()
    {
        $this->validate([
            'new_date' => 'required|date',
        ]);

        // Clear any previous calculations from date range
        if ($this->start_date && $this->end_date) {
            $this->start_date = null;
            $this->end_date = null;
        }

        if (!in_array($this->new_date, $this->list_of_dates)) {
            // Check if the date is not a weekend
            $date = Carbon::parse($this->new_date);
            if ($date->isWeekend()) {
                $this->addError('new_date', 'Weekends cannot be selected as leave days');
                return;
            }

            $this->list_of_dates[] = $this->new_date;
            $this->number_of_days = count($this->list_of_dates);
        }

        $this->new_date = '';
    }

    public function removeDate($index)
    {
        // Clear any previous calculations from date range
        if ($this->start_date && $this->end_date) {
            $this->start_date = null;
            $this->end_date = null;
        }

        unset($this->list_of_dates[$index]);
        $this->list_of_dates = array_values($this->list_of_dates);
        $this->number_of_days = count($this->list_of_dates);
    }

    public function resetForm()
    {
        $this->reset([
            'office_or_department',
            'position',
            'salary',
            'number_of_days',
            'type_of_leave',
            'details_of_leave',
            'commutation',
            'philippines',
            'abroad',
            'inHospital',
            'outPatient',
            'specialIllnessForWomen',
            'files',
            'other_leave',
            'list_of_dates',
            'new_date',
            'start_date',
            'end_date',
        ]);
    }

    public function exportPDF($leaveApplicationId)
    {
        $leaveApplication = LeaveApplication::with('user.userData')->findOrFail($leaveApplicationId);

        $eSignature = ESignature::where('user_id', $leaveApplication->user_id)->first();

        $signatureImagePath = null;
        if ($eSignature && $eSignature->file_path) {
            $signatureImagePath = Storage::disk('public')->path($eSignature->file_path);
        }

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

        // // Fetch the first approver from leave_approvals
        // $leaveApproval = LeaveApprovals::where('application_id', $leaveApplicationId)->first();
        // $firstApprover = $leaveApproval ? $leaveApproval->first_approver : null;
        // $firstApproverName = $firstApprover ? User::find($firstApprover)->name : 'N/A';
        // $secondApprover = $leaveApproval ? $leaveApproval->second_approver : null;
        // $secondApproverName = $secondApprover ? User::find($secondApprover)->name : 'N/A';
        // $thirdApprover = $leaveApproval ? $leaveApproval->third_approver : null;
        // $thirdApproverName = $thirdApprover ? User::find($thirdApprover)->name : 'N/A';

        // $leaveCredits = LeaveCredits::where('user_id', $leaveApplication->user_id)->first();
        // Fetch the approvers from leave_approvals
        $leaveApproval = LeaveApprovals::where('application_id', $leaveApplicationId)->first();

        // Initialize approver signatures
        $firstApproverSignature = null;
        $secondApproverSignature = null;
        $thirdApproverSignature = null;

        if ($leaveApproval && $leaveApproval->first_approver) {
            $firstApprover = User::find($leaveApproval->first_approver);
            $firstApproverName = $firstApprover ? $firstApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $firstApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                          ->where('user_role', 'emp')
                          ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $firstApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $firstApproverName = 'N/A';
        }

        // Process second approver
        if ($leaveApproval && $leaveApproval->second_approver) {
            $secondApprover = User::find($leaveApproval->second_approver);
            $secondApproverName = $secondApprover ? $secondApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $secondApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                        ->where('user_role', 'emp')
                        ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $secondApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $secondApproverName = 'N/A';
        }

        // Process third approver
        if ($leaveApproval && $leaveApproval->third_approver) {
            $thirdApprover = User::find($leaveApproval->third_approver);
            $thirdApproverName = $thirdApprover ? $thirdApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $thirdApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                        ->where('user_role', 'emp')
                        ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $thirdApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $thirdApproverName = 'N/A';
        }

        $leaveCredits = LeaveCredits::where('user_id', $leaveApplication->user_id)->first();

        // Step 1: Generate the first page (leave-application.php) and save it as a temporary PDF file
        $firstPagePath = storage_path('app/temp/first-page.pdf');
        if (!file_exists(dirname($firstPagePath))) {
            mkdir(dirname($firstPagePath), 0755, true);
        }

        $pdf = PDF::loadView('pdf.leave-application', [
            'leaveApplication' => $leaveApplication,
            'selectedLeaveTypes' => $selectedLeaveTypes,
            'otherLeave' => $otherLeave,
            'detailsOfLeave' => $detailsOfLeave,
            'isDetailPresent' => $isDetailPresent,
            'getDetailValue' => $getDetailValue,
            'daysWithPay' => $daysWithPay,
            'daysWithoutPay' => $daysWithoutPay,
            'otherRemarks' => $otherRemarks,
            'leaveCredits' => $leaveCredits,
            'firstApproverName' => $firstApproverName,
            'secondApproverName' => $secondApproverName,
            'thirdApproverName' => $thirdApproverName,
            'eSignature' => $eSignature,
            'signatureImagePath' => $signatureImagePath,
            'firstApproverSignature' => $firstApproverSignature,
            'secondApproverSignature' => $secondApproverSignature,
            'thirdApproverSignature' => $thirdApproverSignature,
        ]);

        $pdf->save($firstPagePath);

        // Step 2: Path to your second-page template
        $secondPageTemplatePath = storage_path('app/public/pdf_template/secondpage.pdf');

        // Step 3: Combine the first and second pages using FPDI
        $outputPdfPath = storage_path('app/temp/combined-output.pdf');
        $fpdi = new \setasign\Fpdi\Fpdi();

        // Add the first page
        $fpdi->setSourceFile($firstPagePath);
        $firstPageId = $fpdi->importPage(1);
        $fpdi->addPage();
        $fpdi->useTemplate($firstPageId);

        // Add the second page
        $fpdi->setSourceFile($secondPageTemplatePath);
        $secondPageId = $fpdi->importPage(1);
        $fpdi->addPage();
        $fpdi->useTemplate($secondPageId);

        // Save the final combined PDF
        $fpdi->output($outputPdfPath, 'F');

        // Step 4: Stream the combined PDF as a download
        return response()->download($outputPdfPath, 'LeaveApplication' . $leaveApplicationId . '.pdf')->deleteFileAfterSend(true);
    }

    public function render()
    {
        $userId = Auth::id();

        $request = MandatoryFormRequest::where('user_id', $userId)
            ->orderBy('date_requested', 'desc')
            ->first();

        $this->requestSent = $request !== null;
        $this->requestApproved = $request && $request->status === 'approved';

        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();

        $leaveApplications = LeaveApplication::query()
            ->where('user_id', $userId)
            ->when($this->activeTab === 'pending', function ($query) {
                return $query->where('status', 'Pending');
            })
            ->when($this->activeTab === 'approved', function ($query) {
                return $query->where('status', 'Approved');
            })
            ->when($this->activeTab === 'disapproved', function ($query) {
                return $query->where('status', 'Disapproved');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->pageSize);

        return view('livewire.user.leave-application-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function closeLeaveDetails()
    {
        $this->showPDFPreview = false;
        $this->pdfContent = null;
    }

    public function showPDF($leaveApplicationId)
    {
        $leaveApplication = LeaveApplication::with('user.userData')->findOrFail($leaveApplicationId);

        // Get the original applicant's e-signature
        $eSignature = ESignature::where('user_id', $leaveApplication->user_id)->first();

        $signatureImagePath = null;
        if ($eSignature && $eSignature->file_path) {
            $signatureImagePath = Storage::disk('public')->path($eSignature->file_path);
        }

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

        // Fetch approvers from leave_approvals
        $leaveApproval = LeaveApprovals::where('application_id', $leaveApplicationId)->first();

        // Process first approver
        $firstApproverSignature = null;
        if ($leaveApproval && $leaveApproval->first_approver) {
            $firstApprover = User::find($leaveApproval->first_approver);
            $firstApproverName = $firstApprover ? $firstApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $firstApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                          ->where('user_role', 'emp')
                          ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $firstApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $firstApproverName = 'N/A';
        }

        // Process second approver
        $secondApproverSignature = null;
        if ($leaveApproval && $leaveApproval->second_approver) {
            $secondApprover = User::find($leaveApproval->second_approver);
            $secondApproverName = $secondApprover ? $secondApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $secondApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                          ->where('user_role', 'emp')
                          ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $secondApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $secondApproverName = 'N/A';
        }

        // Process third approver
        $thirdApproverSignature = null;
        if ($leaveApproval && $leaveApproval->third_approver) {
            $thirdApprover = User::find($leaveApproval->third_approver);
            $thirdApproverName = $thirdApprover ? $thirdApprover->name : 'N/A';

            // Get emp_code without prefix
            $empCode = preg_replace('/^[^-]+-/', '', $thirdApprover->emp_code);

            // Find corresponding emp user with the same emp_code
            $empUser = User::where('emp_code', $empCode)
                          ->where('user_role', 'emp')
                          ->first();

            if ($empUser) {
                $empSignature = ESignature::where('user_id', $empUser->id)->first();
                if ($empSignature && $empSignature->file_path) {
                    $thirdApproverSignature = Storage::disk('public')->path($empSignature->file_path);
                }
            }
        } else {
            $thirdApproverName = 'N/A';
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
            'leaveCredits' => $leaveCredits,
            'firstApproverName' => $firstApproverName,
            'secondApproverName' => $secondApproverName,
            'thirdApproverName' => $thirdApproverName,
            'eSignature' => $eSignature,
            'signatureImagePath' => $signatureImagePath,
            'firstApproverSignature' => $firstApproverSignature,
            'secondApproverSignature' => $secondApproverSignature,
            'thirdApproverSignature' => $thirdApproverSignature,
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

    public function mount()
    {
        // Set default year to current year
        $this->selectedYear = date('Y');

        $this->list_of_dates = [];
        $this->number_of_days = 0;
    }

    public function updatedSelectedYear()
    {
        // Add any validation or processing when year changes
        $this->isDisabled = false;
    }

    public function exportExcel()
    {
        $export = new LeaveCardExport(Auth::id(), $this->selectedYear);
        return $export->export();
    }

    public function requestForm()
    {
        $userId = Auth::id();

        // Check if the user has already requested today
        $existingRequest = MandatoryFormRequest::where('user_id', $userId)
            ->whereDate('date_requested', now()->toDateString())
            ->first();

        if (!$existingRequest) {
            // Create a new record in 'mandatory_form_request'
            MandatoryFormRequest::create([
                'user_id' => $userId,
                'status' => 'pending',
                'date_requested' => now(),
            ]);
        }
    }

    public function exportMandatoryLeaveForm()
    {
        try {
            // Get the currently logged in user and their data
            $user = Auth::user();
            $userData = UserData::where('user_id', $user->id)->first();

            if (!$userData) {
                $this->dispatch('swal', [
                    'title' => "Error!",
                    'text' => "User data not found.",
                    'icon' => 'error'
                ]);
                return;
            }

            // Get office division
            $officeDivision = OfficeDivisions::find($user->office_division_id);
            $office = $officeDivision ? $officeDivision->office_division : 'N/A';

            // Find all approved Mandatory/Forced Leave applications for the user
            $leaveApplications = LeaveApplication::where('user_id', $user->id)
                ->where('type_of_leave', 'like', '%Mandatory/Forced Leave%')
                ->where('status', 'Approved')
                ->where('remarks', 'With Pay')
                ->get();

            if ($leaveApplications->isEmpty()) {
                $this->dispatch('swal', [
                    'title' => "No Records Found",
                    'text' => "You don't have any approved Mandatory/Forced Leave applications.",
                    'icon' => 'info'
                ]);
                return;
            }

            // Find the mandatory form request for this user
            $mandatoryFormRequest = MandatoryFormRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->latest()
                ->first();

            // Get the name of the approver
            $approverName = null;
            if ($mandatoryFormRequest && $mandatoryFormRequest->approved_by) {
                $approver = User::find($mandatoryFormRequest->approved_by);
                if ($approver) {
                    $approverName = $approver->name;
                }
            }

            // Load the template file
            $templatePath = storage_path('app/public/leave_template/Mandatory Leave Form.xls');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Get the current year
            $year = Carbon::now()->format('Y');

            // Map data to specific cells
            $worksheet->setCellValue('A8', "FOR CALENDAR YEAR " . $year);
            $worksheet->setCellValue('B11', $user->name);
            $worksheet->setCellValue('B12', $office);

            // Collect all approved dates
            $allDates = [];
            foreach ($leaveApplications as $leave) {
                $dates = explode(',', $leave->approved_dates);
                foreach ($dates as $date) {
                    if (trim($date) !== '') {
                        // Check if it's a date range
                        if (strpos($date, ' - ') !== false) {
                            list($startDate, $endDate) = explode(' - ', $date);
                            $start = Carbon::parse($startDate);
                            $end = Carbon::parse($endDate);

                            // Add all weekdays in the range
                            for ($current = $start; $current->lte($end); $current->addDay()) {
                                if (!$current->isWeekend()) {
                                    $allDates[] = $current->format('Y-m-d');
                                }
                            }
                        } else {
                            $allDates[] = $date;
                        }
                    }
                }
            }

            // Sort dates and remove duplicates
            $allDates = array_unique($allDates);
            sort($allDates);

            // Add dates to the worksheet
            foreach ($allDates as $index => $date) {
                $formattedDate = ($index + 1) . ". " . Carbon::parse($date)->format('F d, Y');
                $cellRow = 16 + $index; // Assuming dates start at row 16
                $worksheet->setCellValue("A{$cellRow}", $formattedDate);

                // If we have more than 10 dates, we need to add rows
                if ($index >= 10) {
                    $worksheet->insertNewRowBefore($cellRow + 1, 1);
                }
            }

            // Add user name in C24 with bold formatting (or adjust row if needed due to added rows)
            $signatureRow = 24 + max(0, count($allDates) - 10); // Adjust signature row if rows were added
            $worksheet->setCellValue("C{$signatureRow}", $approverName ?: 'Not available');
            $worksheet->getStyle("C{$signatureRow}")->getFont()->setBold(true);

            // Generate unique filename
            $fileName = 'MandatoryLeaveForm' . $year . '.xlsx';

            // Create response
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => "Error!",
                'text' => "An error occurred while generating the Excel file: " . $e->getMessage(),
                'icon' => 'error'
            ]);
            return null;
        }
    }
}