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
use App\Models\CosSkPayrolls;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\ESignature;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Exports\LeaveCardExport; 

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
    
    public $startDate;
    public $endDate;

    public $activeTab = 'pending';

    protected $rules = [
        'office_or_department' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'salary' => 'required|string|max:255',
        'type_of_leave' => 'required|array|min:1',
        'files.*' => 'file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
    ];

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
            } else {
                $cosSkPayroll = CosSkPayrolls::where('user_id', $user->id)->first();
                $this->salary = $cosSkPayroll ? $cosSkPayroll->rate_per_month : 0;
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

    public function addDate()
    {
        $this->validate([
            'new_date' => 'required|date',
        ]);

        if (!in_array($this->new_date, $this->list_of_dates)) {
            $this->list_of_dates[] = $this->new_date;
        }

        $this->new_date = '';
    }

    public function submitLeaveApplication()
    {
        $rules = [
            'office_or_department' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'type_of_leave' => 'required|array|min:1',
            'details_of_leave' => 'required',
            'number_of_days' => 'required',
            'commutation' => 'required',
        ];

        // Check if list_of_dates is present in the form
        // if ($this->list_of_dates !== null) {
        //     $rules['list_of_dates'] = 'required|min:1';
        // }

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

        if (!empty(array_intersect($this->type_of_leave, $leaveTypesRequiringDates))) {
            $rules['list_of_dates'] = 'required|array|min:1';
        }

        // Require file upload if CTO Leave is selected
        if (in_array('CTO Leave', $this->type_of_leave)) {
            $rules['files'] = 'required|array|min:1';
            $rules['files.*'] = 'file|mimes:jpg,jpeg,png,gif,svg|max:2048';
        }

        $this->validate($rules);

        if (in_array('Others', $this->type_of_leave)) {
            $this->type_of_leave = array_filter($this->type_of_leave, function ($leave) {
                return $leave !== 'Others';
            });

            $this->type_of_leave[] = $this->other_leave;
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

        $leaveDetails = [];
        foreach ($this->details_of_leave as $leaveType) {
            if ($leaveType === 'Within the Philippines') {
                $leaveDetails[] = $leaveType . ' = ' . $this->philippines;
            } elseif ($leaveType === 'Abroad') {
                $leaveDetails[] = $leaveType . ' = ' . $this->abroad;
            } elseif ($leaveType === 'In Hospital') {
                $leaveDetails[] = $leaveType . ' = ' . $this->inHospital;
            } elseif ($leaveType === 'Out Patient') {
                $leaveDetails[] = $leaveType . ' = ' . $this->outPatient;
            } elseif ($leaveType === 'Women Special Illness') {
                $leaveDetails[] = $leaveType . ' = ' . $this->specialIllnessForWomen;
            } elseif ($leaveType === 'Completion of Masters Degree' ||
                      $leaveType === 'BAR/Board Examination Review' ||
                      $leaveType === 'Monetization of Leave Credits' ||
                      $leaveType === 'Terminal Leave') {
                $leaveDetails[] = $leaveType;
            }
        }

        $leaveDetailsString = implode(', ', $leaveDetails);
        $filePathsString = implode(',', $filePaths);

        $datesString = '';

        if ($this->start_date && $this->end_date) {
            $datesString = $this->start_date . ' - ' . $this->end_date;
        }

        if (!empty($this->list_of_dates)) {
            if (!empty($datesString)) {
                $datesString .= ', ';
            }
            $datesString .= implode(',', $this->list_of_dates);
        }

        // $currentMonth = now()->month;
        // $currentYear = now()->year;
        $userId = Auth::id();

        $leaveApplication = LeaveApplication::create([
            'user_id' => $userId,
            'name' => $this->name,
            'office_or_department' => $this->office_or_department,
            'date_of_filing' => $this->date_of_filing,
            'position' => $this->position,
            'salary' => $this->salary,
            'number_of_days' => $this->number_of_days,
            'type_of_leave' => implode(',', $this->type_of_leave),
            'details_of_leave' => $leaveDetailsString,
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

    public function removeDate($index)
    {
        unset($this->list_of_dates[$index]);

        $this->list_of_dates = array_values($this->list_of_dates);
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
        $leaveApplication = LeaveApplication::findOrFail($leaveApplicationId);

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

        // Fetch the first approver from leave_approvals
        $leaveApproval = LeaveApprovals::where('application_id', $leaveApplicationId)->first();
        $firstApprover = $leaveApproval ? $leaveApproval->first_approver : null;
        $firstApproverName = $firstApprover ? User::find($firstApprover)->name : 'N/A';
        $secondApprover = $leaveApproval ? $leaveApproval->second_approver : null;
        $secondApproverName = $secondApprover ? User::find($secondApprover)->name : 'N/A';
        $thirdApprover = $leaveApproval ? $leaveApproval->third_approver : null;
        $thirdApproverName = $thirdApprover ? User::find($thirdApprover)->name : 'N/A';

        $leaveCredits = LeaveCredits::where('user_id', $leaveApplication->user_id)->first();

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
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'LeaveApplication' . $leaveApplicationId . '.pdf');
    }

    public function exportExcel()
    {
        $leaveApplication = LeaveApplication::where('user_id', Auth::id())
            ->latest('created_at')
            ->first();

        if (!$leaveApplication) {
            session()->flash('error', 'No leave application found for the current user.');
            return;
        }

        $export = new LeaveCardExport($leaveApplication->id, $this->startDate, $this->endDate);

        return $export->export();
    }

    public function render()
    {
        $userId = Auth::id();
        // $leaveApplications = LeaveApplication::where('user_id', $userId)
        //     ->with('vacationLeaveDetails', 'sickLeaveDetails')
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        $pendingApplications = $this->getApplications(['Pending']);
        $approvedApplications = $this->getApplications(['Approved by HR', 'Approved by Supervisor', 'Approved']);
        $disapprovedApplications = $this->getApplications(['Disapproved']);

        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();

        return view('livewire.user.leave-application-table', [
            'pendingApplications' => $pendingApplications,
            'approvedApplications' => $approvedApplications,
            'disapprovedApplications' => $disapprovedApplications,
            // 'leaveApplications' => $leaveApplications,
        ]);
    }

    private function getApplications($statuses)
    {
        return LeaveApplication::where('user_id', Auth::id())
            ->whereIn('status', $statuses)
            ->with('vacationLeaveDetails', 'sickLeaveDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], $this->getPaginationPageName($statuses[0]));
    }

    private function getPaginationPageName($status)
    {
        return strtolower(str_replace(' ', '_', $status)) . '_page';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }
}
