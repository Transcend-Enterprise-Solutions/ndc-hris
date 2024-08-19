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
use App\Models\Payrolls;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

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

        $payroll = Payrolls::where('user_id', $user->id)->first();

        
            $this->office_or_department = $payroll->office_division ?? 'N/A';
            $this->position = $payroll->position ?? 'N/A';
            $this->salary = $payroll->rate_per_month ?? 0;
        
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
        $this->validate([
            'office_or_department' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'type_of_leave' => 'required|array|min:1',
            'details_of_leave' => 'required',
            'number_of_days' => 'required',
            'commutation' => 'required',
        ]);

        if (empty($this->new_date)) {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        } else {
            $rules['new_date'] = 'required|date';
        }

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

        if ($this->start_date && $this->end_date) {
            $datesInRange = $this->list_of_dates;
            if (in_array($this->start_date, $datesInRange) && in_array($this->end_date, $datesInRange)) {
                $datesString = $this->start_date . ' - ' . $this->end_date;
            } else {
                $datesString = $this->start_date . ' - ' . $this->end_date . ',' . implode(',', $datesInRange);
            }
        } else {
            $datesString = implode(',', $this->list_of_dates);
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $userId = Auth::id();

        $leaveCreditsCalculation = \App\Models\LeaveCreditsCalculation::where('user_id', $userId)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $leaveCreditsEarned = $leaveCreditsCalculation ? $leaveCreditsCalculation->leave_credits_earned : 0;

        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();
        if ($leaveCredits) {
            if (!$leaveCredits->credits_transferred) {
                $leaveCredits->total_credits = $leaveCreditsEarned;
                $leaveCredits->save();

                $leaveCredits->credits_transferred = true;
                $leaveCredits->save();
            }
        } else {
            LeaveCredits::create([
                'user_id' => $userId,
                'total_credits' => $leaveCreditsEarned,
                'vl_claimable_credits' => $leaveCreditsEarned,
                'sl_claimable_credits' => $leaveCreditsEarned,
                'spl_claimable_credits' => $leaveCreditsEarned,
                'credits_transferred' => true
            ]);
        }

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

        // Re-index the array to avoid issues with non-sequential keys
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
        
        $selectedLeaveTypes = $leaveApplication->type_of_leave ? explode(',', $leaveApplication->type_of_leave) : [];
        
        $otherLeave = '';
        foreach ($selectedLeaveTypes as $leaveType) {
            if (strpos($leaveType, 'Others: ') === 0) {
                $otherLeave = str_replace('Others: ', '', $leaveType);
                break;
            }
        }

        $detailsOfLeave = $leaveApplication->details_of_leave ? explode(',', $leaveApplication->details_of_leave) : [];

        $isDetailPresent = function($detail) use ($detailsOfLeave) {
            foreach ($detailsOfLeave as $item) {
                if (Str::startsWith($item, $detail)) {
                    return true;
                }
            }
            return false;
        };

        $getDetailValue = function($detail) use ($detailsOfLeave) {
            foreach ($detailsOfLeave as $item) {
                if (Str::startsWith($item, $detail)) {
                    $parts = explode('=', $item, 2);
                    return count($parts) > 1 ? trim($parts[1]) : '';
                }
            }
            return '';
        };

        $pdf = PDF::loadView('pdf.leave-application', [
            'leaveApplication' => $leaveApplication,
            'selectedLeaveTypes' => $selectedLeaveTypes,
            'otherLeave' => $otherLeave,
            'detailsOfLeave' => $detailsOfLeave,
            'isDetailPresent' => $isDetailPresent,
            'getDetailValue' => $getDetailValue
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'LeaveApplication' . $leaveApplicationId . '.pdf');
    }
    
    public function render()
    {
        $userId = Auth::id();
        $leaveApplications = LeaveApplication::where('user_id', $userId)
            ->with('vacationLeaveDetails', 'sickLeaveDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $leaveCredits = LeaveCredits::where('user_id', $userId)->first();

        return view('livewire.user.leave-application-table', [
            'leaveApplications' => $leaveApplications,
        ]);
    }
}
