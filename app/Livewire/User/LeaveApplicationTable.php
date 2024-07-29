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
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeesDtr;
use App\Models\LeaveCredits;
use Carbon\Carbon;

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
    public $start_date;
    public $end_date;
    public $type_of_leave = [];
    public $details_of_leave = [];
    public $philippines;
    public $abroad;
    public $inHospital;
    public $outPatient;
    public $specialIllnessForWomen;
    public $commutation;
    public $files = [];

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
        $this->start_date = null;
        $this->end_date = null;
        $this->type_of_leave = [];
        $this->details_of_leave = [];
        $this->commutation = null;
        $this->philippines = null;
        $this->abroad = null;
        $this->inHospital = null;
        $this->outPatient = null;
        $this->specialIllnessForWomen = null;
        $this->files = [];
    }

    public function loadUserData()
    {
        $user = Auth::user();
        $userData = UserData::where('user_id', $user->id)->first();

        if ($userData) {
            $this->name = $user->name;
            $this->date_of_filing = now()->toDateString();
        }
    }

    public function submitLeaveApplication()
    {
        $this->validate([
            'office_or_department' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'type_of_leave' => 'required|array|min:1',
            'number_of_days' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'commutation' => 'required',
        ]);

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
            }
            if ($leaveType === 'Abroad') {
                $leaveDetails[] = $leaveType . ' = ' . $this->abroad;
            }
            if ($leaveType === 'In Hospital') {
                $leaveDetails[] = $leaveType . ' = ' . $this->inHospital;
            }
            if ($leaveType === 'Out Patient') {
                $leaveDetails[] = $leaveType . ' = ' . $this->outPatient;
            }
            if ($leaveType === 'Women Special Illness') {
                $leaveDetails[] = $leaveType . ' = ' . $this->specialIllnessForWomen;
            }
            if ($leaveType === 'Completion of Masters Degree') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'BAR/Board Examination Review') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'Monetization of Leave Credits') {
                $leaveDetails[] = $leaveType;
            }
            if ($leaveType === 'Terminal Leave') {
                $leaveDetails[] = $leaveType;
            }
        }

        $leaveDetailsString = implode(', ', $leaveDetails);
        $filePathsString = implode(',', $filePaths);

        $leaveApplication = LeaveApplication::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'office_or_department' => $this->office_or_department,
            'date_of_filing' => $this->date_of_filing,
            'position' => $this->position,
            'salary' => $this->salary,
            'number_of_days' => $this->number_of_days,
            'type_of_leave' => implode(',', $this->type_of_leave),
            'details_of_leave' => $leaveDetailsString,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'commutation' => $this->commutation,
            'status' => 'Pending',
            'file_path' => implode(',', $filePaths),
            'file_name' => implode(',', $fileNames),
        ]);

        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');

        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth();

        $totalLateMinutes = EmployeesDtr::where('user_id', Auth::id())
            ->where('remarks', 'Late')
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->sum(function ($dtr) {
                $late = $dtr->late;
                if ($late) {
                    list($hours, $minutes) = explode(':', $late);
                    return ($hours * 60) + intval($minutes);
                }
                return 0;
            });

        $hours = floor($totalLateMinutes / 60);
        $minutes = $totalLateMinutes % 60;
        $formattedLateTime = sprintf('%02d:%02d', $hours, $minutes);

        $totalCreditsEarned = $totalLateMinutes / 480;

        $leaveCreditsTable = [
            ['DaysPresent' => 30.00, 'DaysAbsent' => 0.00, 'LeaveCreditsEarned' => 1.250],
            ['DaysPresent' => 29.50, 'DaysAbsent' => 0.50, 'LeaveCreditsEarned' => 1.229],
            ['DaysPresent' => 29.00, 'DaysAbsent' => 1.00, 'LeaveCreditsEarned' => 1.208],
            ['DaysPresent' => 28.50, 'DaysAbsent' => 1.50, 'LeaveCreditsEarned' => 1.188],
            ['DaysPresent' => 28.00, 'DaysAbsent' => 2.00, 'LeaveCreditsEarned' => 1.167],
            ['DaysPresent' => 27.50, 'DaysAbsent' => 2.50, 'LeaveCreditsEarned' => 1.146],
            ['DaysPresent' => 27.00, 'DaysAbsent' => 3.00, 'LeaveCreditsEarned' => 1.125],
            ['DaysPresent' => 26.50, 'DaysAbsent' => 3.50, 'LeaveCreditsEarned' => 1.104],
            ['DaysPresent' => 26.00, 'DaysAbsent' => 4.00, 'LeaveCreditsEarned' => 1.083],
            ['DaysPresent' => 25.50, 'DaysAbsent' => 4.50, 'LeaveCreditsEarned' => 1.063],
            ['DaysPresent' => 25.00, 'DaysAbsent' => 5.00, 'LeaveCreditsEarned' => 1.042],
            ['DaysPresent' => 24.50, 'DaysAbsent' => 5.50, 'LeaveCreditsEarned' => 1.021],
            ['DaysPresent' => 24.00, 'DaysAbsent' => 6.00, 'LeaveCreditsEarned' => 1.000],
            ['DaysPresent' => 23.50, 'DaysAbsent' => 6.50, 'LeaveCreditsEarned' => 0.979],
            ['DaysPresent' => 23.00, 'DaysAbsent' => 7.00, 'LeaveCreditsEarned' => 0.958],
            ['DaysPresent' => 22.50, 'DaysAbsent' => 7.50, 'LeaveCreditsEarned' => 0.938],
            ['DaysPresent' => 22.00, 'DaysAbsent' => 8.00, 'LeaveCreditsEarned' => 0.917],
            ['DaysPresent' => 21.50, 'DaysAbsent' => 8.50, 'LeaveCreditsEarned' => 0.896],
            ['DaysPresent' => 21.00, 'DaysAbsent' => 9.00, 'LeaveCreditsEarned' => 0.875],
            ['DaysPresent' => 20.50, 'DaysAbsent' => 9.50, 'LeaveCreditsEarned' => 0.854],
            ['DaysPresent' => 20.00, 'DaysAbsent' => 10.00, 'LeaveCreditsEarned' => 0.833],
            ['DaysPresent' => 19.50, 'DaysAbsent' => 10.50, 'LeaveCreditsEarned' => 0.813],
            ['DaysPresent' => 19.00, 'DaysAbsent' => 11.00, 'LeaveCreditsEarned' => 0.792],
            ['DaysPresent' => 18.50, 'DaysAbsent' => 11.50, 'LeaveCreditsEarned' => 0.771],
            ['DaysPresent' => 18.00, 'DaysAbsent' => 12.00, 'LeaveCreditsEarned' => 0.750],
            ['DaysPresent' => 17.50, 'DaysAbsent' => 12.50, 'LeaveCreditsEarned' => 0.729],
            ['DaysPresent' => 17.00, 'DaysAbsent' => 13.00, 'LeaveCreditsEarned' => 0.708],
            ['DaysPresent' => 16.50, 'DaysAbsent' => 13.50, 'LeaveCreditsEarned' => 0.687],
            ['DaysPresent' => 16.00, 'DaysAbsent' => 14.00, 'LeaveCreditsEarned' => 0.667],
            ['DaysPresent' => 15.50, 'DaysAbsent' => 14.50, 'LeaveCreditsEarned' => 0.646],
            ['DaysPresent' => 15.00, 'DaysAbsent' => 15.00, 'LeaveCreditsEarned' => 0.625],
            ['DaysPresent' => 14.50, 'DaysAbsent' => 15.50, 'LeaveCreditsEarned' => 0.604],
            ['DaysPresent' => 14.00, 'DaysAbsent' => 16.00, 'LeaveCreditsEarned' => 0.583],
            ['DaysPresent' => 13.50, 'DaysAbsent' => 16.50, 'LeaveCreditsEarned' => 0.562],
            ['DaysPresent' => 13.00, 'DaysAbsent' => 17.00, 'LeaveCreditsEarned' => 0.542],
            ['DaysPresent' => 12.50, 'DaysAbsent' => 17.50, 'LeaveCreditsEarned' => 0.521],
            ['DaysPresent' => 12.00, 'DaysAbsent' => 18.00, 'LeaveCreditsEarned' => 0.500],
            ['DaysPresent' => 11.50, 'DaysAbsent' => 18.50, 'LeaveCreditsEarned' => 0.479],
            ['DaysPresent' => 11.00, 'DaysAbsent' => 19.00, 'LeaveCreditsEarned' => 0.458],
            ['DaysPresent' => 10.50, 'DaysAbsent' => 19.50, 'LeaveCreditsEarned' => 0.437],
            ['DaysPresent' => 10.00, 'DaysAbsent' => 20.00, 'LeaveCreditsEarned' => 0.417],
            ['DaysPresent' => 9.50, 'DaysAbsent' => 20.50, 'LeaveCreditsEarned' => 0.396],
            ['DaysPresent' => 9.00, 'DaysAbsent' => 21.00, 'LeaveCreditsEarned' => 0.375],
            ['DaysPresent' => 8.50, 'DaysAbsent' => 21.50, 'LeaveCreditsEarned' => 0.354],
            ['DaysPresent' => 8.00, 'DaysAbsent' => 22.00, 'LeaveCreditsEarned' => 0.333],
            ['DaysPresent' => 7.50, 'DaysAbsent' => 22.50, 'LeaveCreditsEarned' => 0.312],
            ['DaysPresent' => 7.00, 'DaysAbsent' => 23.00, 'LeaveCreditsEarned' => 0.292],
            ['DaysPresent' => 6.50, 'DaysAbsent' => 23.50, 'LeaveCreditsEarned' => 0.271],
            ['DaysPresent' => 6.00, 'DaysAbsent' => 24.00, 'LeaveCreditsEarned' => 0.250],
            ['DaysPresent' => 5.50, 'DaysAbsent' => 24.50, 'LeaveCreditsEarned' => 0.229],
            ['DaysPresent' => 5.00, 'DaysAbsent' => 25.00, 'LeaveCreditsEarned' => 0.208],
            ['DaysPresent' => 4.50, 'DaysAbsent' => 25.50, 'LeaveCreditsEarned' => 0.187],
            ['DaysPresent' => 4.00, 'DaysAbsent' => 26.00, 'LeaveCreditsEarned' => 0.167],
            ['DaysPresent' => 3.50, 'DaysAbsent' => 26.50, 'LeaveCreditsEarned' => 0.146],
            ['DaysPresent' => 3.00, 'DaysAbsent' => 27.00, 'LeaveCreditsEarned' => 0.125],
            ['DaysPresent' => 2.50, 'DaysAbsent' => 27.50, 'LeaveCreditsEarned' => 0.104],
            ['DaysPresent' => 2.00, 'DaysAbsent' => 28.00, 'LeaveCreditsEarned' => 0.083],
            ['DaysPresent' => 1.50, 'DaysAbsent' => 28.50, 'LeaveCreditsEarned' => 0.062],
            ['DaysPresent' => 1.00, 'DaysAbsent' => 29.00, 'LeaveCreditsEarned' => 0.042],
            ['DaysPresent' => 0.50, 'DaysAbsent' => 29.50, 'LeaveCreditsEarned' => 0.021],
            ['DaysPresent' => 0.00, 'DaysAbsent' => 30.00, 'LeaveCreditsEarned' => 0.000],
        ];

        $leaveCreditsEarned = 0;
        foreach ($leaveCreditsTable as $row) {
            if ($totalCreditsEarned <= $row['DaysAbsent']) {
                $leaveCreditsEarned = $row['LeaveCreditsEarned'];
                break;
            }
        }

        if (in_array('Vacation Leave', $this->type_of_leave)) {
            $user_id = Auth::user()->id;

            // Fetch or initialize LeaveCredits
            $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();

            if ($leaveCredits) {
                // Update existing LeaveCredits record
                $initialBalance = $leaveCredits->claimable_credits;
                $totalBalance = $initialBalance + $leaveCreditsEarned;

                $leaveCredits->update([
                    'total_credits' => $totalBalance,
                    'claimable_credits' => $leaveCredits->claimable_credits + $leaveCreditsEarned,
                ]);
            } else {
                // Create a new LeaveCredits record if it does not exist
                $totalBalance = $leaveCreditsEarned;  // No initial balance, just set the earned amount
                LeaveCredits::create([
                    'user_id' => $user_id,
                    'total_credits' => $totalBalance,
                    'claimable_credits' => $totalBalance,
                    'total_claimed_credits' => 0,
                ]);
            }

            // Create a new VacationLeaveDetails record
            VacationLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'late' => $formattedLateTime,
                'totalCreditsEarned' => $totalCreditsEarned,
                'leave_credits_earned' => $leaveCreditsEarned,
                'balance' => $totalBalance,
                'recommendation' => 'For approval',
                'less_this_application' => 1,
                'status' => 'Pending',
                'month' => $currentMonth,
            ]);

        } else if (in_array('Sick Leave', $this->type_of_leave)) {

            $user_id = Auth::user()->id;

            // Fetch or initialize LeaveCredits
            $leaveCredits = LeaveCredits::where('user_id', $user_id)->first();

            if ($leaveCredits) {
                // Update existing LeaveCredits record
                $initialBalance = $leaveCredits->claimable_credits;
                $totalBalance = $initialBalance + $leaveCreditsEarned;

                $leaveCredits->update([
                    'total_credits' => $totalBalance,
                    'claimable_credits' => $leaveCredits->claimable_credits + $leaveCreditsEarned,
                ]);
            } else {
                // Create a new LeaveCredits record if it does not exist
                $totalBalance = $leaveCreditsEarned;  // No initial balance, just set the earned amount
                LeaveCredits::create([
                    'user_id' => $user_id,
                    'total_credits' => $totalBalance,
                    'claimable_credits' => $totalBalance,
                    'total_claimed_credits' => 0,
                ]);
            }

            SickLeaveDetails::create([
                'application_id' => $leaveApplication->id,
                'late' => $formattedLateTime,
                'totalCreditsEarned' => $totalCreditsEarned,
                'leave_credits_earned' => $leaveCreditsEarned,
                'balance' => $totalBalance,
                'recommendation' => 'For approval',
                'less_this_application' => 1,
                'status' => 'Pending',
                'month' => $currentMonth,
            ]);
        }

        $this->dispatch('notify', [
            'message' => "Leave Application sent successfully!",
            'type' => 'success'
        ]);
        $this->resetForm();
        $this->closeLeaveForm();
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
            'start_date',
            'end_date',
            'commutation',
            'philippines',
            'abroad',
            'inHospital',
            'outPatient',
            'specialIllnessForWomen',
            'files',
        ]);
    }

    public function updated($propertyName)
    {
        $this->resetPage(); // Reset pagination when month/year is changed
    }

    public function render()
    {
        $leaveApplications = LeaveApplication::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $vacationLeaveDetails = VacationLeaveDetails::where('application_id', function ($query) {
            $query->select('id')
                ->from('leave_application')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(1);
        })->first();
        $sickLeaveDetails = SickLeaveDetails::where('application_id', function ($query) {
            $query->select('id')
                ->from('leave_application')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->limit(1);
        })->first();

        $leaveCredits = LeaveCredits::where('user_id', Auth::id())->first();
        $claimableCredits = $leaveCredits ? $leaveCredits->claimable_credits : 0;
        $totalClaimedCredits = $leaveCredits ? $leaveCredits->total_claimed_credits : 0;

        return view('livewire.user.leave-application-table', [
            'leaveApplications' => $leaveApplications,
            'vacationLeaveDetails' => $vacationLeaveDetails,
            'sickLeaveDetails' => $sickLeaveDetails,
            'claimableCredits' => $claimableCredits,
            'totalClaimedCredits' => $totalClaimedCredits,
        ]);
    }

}
