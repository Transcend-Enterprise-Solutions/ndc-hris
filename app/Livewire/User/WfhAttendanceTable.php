<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\DTRSchedule;
use App\Models\EmployeesDtr;
use App\Models\TransactionWFH;
use Livewire\WithPagination;

class WfhAttendanceTable extends Component
{
    use WithPagination;
    public $isWFHDay;
    public $showConfirmation = false;
    public $punchState;
    public $errorMessage;
    public $verifyType;

    public $morningInDisabled = false;
    public $morningOutDisabled = true;
    public $afternoonInDisabled = true;
    public $afternoonOutDisabled = true;
    public $scheduleType = 'WFH'; // Default value

    public function checkWFHDay()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('l');
        $currentDate = Carbon::now()->format('Y-m-d');

        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $startDate = Carbon::parse($schedule->start_date)->format('Y-m-d');
            $endDate = Carbon::parse($schedule->end_date)->format('Y-m-d');

            if (in_array($today, $wfhDays) && $currentDate >= $startDate && $currentDate <= $endDate) {
                $this->scheduleType = 'WFH';
            } else {
                $this->scheduleType = 'Onsite';
            }
        } else {
            $this->scheduleType = 'Onsite';
        }
    }

    public function confirmPunch($state, $verifyType)
    {
        $this->punchState = $state;
        $this->verifyType = $verifyType;
        $this->showConfirmation = true;
    }

    public function closeConfirmation()
    {
        $this->showConfirmation = false;
        $this->errorMessage = null;
    }

    public function confirmYes()
    {
        $this->showConfirmation = false;
        $this->punch($this->punchState, $this->verifyType);
    }


    public function punch($state, $verifyType)
    {
        $user = Auth::user();
        $punchTime = Carbon::now();

        // Determine the correct punch_state based on verifyType
        $punchState = (strpos($verifyType, 'In') !== false) ? 0 : 1;

        $punchData = [
            'emp_code' => $user->emp_code,
            'punch_time' => $punchTime,
            'punch_state' => $punchState,
            'punch_state_display' => 'WFH',
            'verify_type_display' => $verifyType,
        ];

        TransactionWFH::create($punchData);

        // Disable buttons based on the action
        if ($verifyType == 'Morning In') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = false;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = true;
        } elseif ($verifyType == 'Morning Out') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = false;
            $this->afternoonOutDisabled = true;
        } elseif ($verifyType == 'Afternoon In') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = false;
        } elseif ($verifyType == 'Afternoon Out') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = true;
        }

        $this->dispatch('swal', [
            'title' => "You have successfully punched the $verifyType!",
            'icon' => 'success'
        ]);
    }


    public function morningIn()
    {
        $this->punch(0, 'Morning In');
    }

    public function morningOut()
    {
        $this->punch(1, 'Morning Out');
    }

    public function afternoonIn()
    {
        $this->punch(0, 'Afternoon In');
    }

    public function afternoonOut()
    {
        $this->punch(1, 'Afternoon Out');
    }

    public function resetVariables()
    {
        $this->password = null;
        $this->errorMessage = null;
    }

    public function resetButtonStatesIfNeeded()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $currentHour = $now->hour;
        // $currentHour = 18;
        $today = $now->format('l');
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();
    
        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $isWFHDay = in_array($today, $wfhDays);
    
            if ($isWFHDay) {
                $this->morningInDisabled = true;
                $this->morningOutDisabled = true;
                $this->afternoonInDisabled = true;
                $this->afternoonOutDisabled = true;
    
                // Fetch transactions for the current day
                $transactions = TransactionWFH::where('emp_code', $user->emp_code)
                    ->where('punch_state_display', 'WFH')
                    ->whereDate('punch_time', Carbon::today())
                    ->pluck('verify_type_display');
    
                if ($currentHour >= 6 && $currentHour < 13) {
                    if (!$transactions->contains('Morning In')) {
                        $this->morningInDisabled = false;
                    } elseif (!$transactions->contains('Morning Out')) {
                        $this->morningOutDisabled = false;
                    }
                }

                if ($currentHour >= 12) {
                    $this->morningInDisabled = true;
                    if (!$transactions->contains('Afternoon In')) {
                        $this->afternoonInDisabled = false;
                    } elseif (!$transactions->contains('Afternoon Out')) {
                        $this->afternoonOutDisabled = false;
                    }
                }
                
                if($currentHour >= 18) {
                    $this->afternoonInDisabled = true;
                }
            }
        }
    }
         

    public function render()
    {
        $this->checkWFHDay();
        $this->resetButtonStatesIfNeeded();
        
        if ($this->scheduleType === 'WFH') {
            $transactions = TransactionWFH::where('emp_code', Auth::user()->emp_code)
                ->whereDate('punch_time', Carbon::today())
                ->orderBy('punch_time', 'asc')
                ->get();
        } else {
            // Fetch onsite punch times from EmployeesDTR table
            $transactions = EmployeesDtr::where('emp_code', Auth::user()->emp_code)
                ->whereDate('date', Carbon::today())
                ->first();
        }
    
        $groupedTransactions = ($this->scheduleType === 'WFH')
            ? $transactions->groupBy('verify_type_display')
            : $transactions;
    
        return view('livewire.user.wfh-attendance-table', [
            'groupedTransactions' => $groupedTransactions,
            'scheduleType' => $this->scheduleType,
        ]);
    }
}