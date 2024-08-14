<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\DTRSchedule;
use App\Models\Transaction;
use Livewire\WithPagination;

class WfhAttendanceTable extends Component
{
    use WithPagination;
    public $isWFHDay;
    public $inputPassword = false;
    public $password;
    public $punchState;
    public $errorMessage;
    public $verifyType;

    public $morningInDisabled = false;
    public $morningOutDisabled = true;
    public $afternoonInDisabled = true;
    public $afternoonOutDisabled = true;

    public function checkWFHDay()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('l');
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $this->isWFHDay = in_array($today, $wfhDays);
        } else {
            $this->isWFHDay = false;
        }
    }

    public function confirmPunch($state, $verifyType)
    {
        $this->punchState = $state;
        $this->verifyType = $verifyType;
        $this->inputPassword = true;
    }

    public function closeVerification()
    {
        $this->inputPassword = false;
        $this->password = null;
        $this->errorMessage = null;
    }

    public function verifyPassword()
    {
        $user = Auth::user();

        if (Hash::check($this->password, $user->password)) {
            $this->inputPassword = false;
            $this->password = '';
            $this->errorMessage = null;
            // $this->{$this->punchState}();
            // $this->{$this->punchState}($this->verifyType);
            $this->punch($this->punchState, $this->verifyType);
        } else {
            $this->errorMessage = 'Incorrect password. Please try again.';
        }
    }

    public function punch($state, $verifyType)
    {
        $user = Auth::user();
        $punchTime = Carbon::now();

        $punchData = [
            'emp_code' => $user->emp_code,
            'punch_time' => $punchTime,
            'punch_state' => $state,
            'punch_state_display' => 'WFH',
            'verify_type_display' => $verifyType,
        ];

        Transaction::create($punchData);

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
        $today = $now->format('l');
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();
    
        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $isWFHDay = in_array($today, $wfhDays);
    
            if ($isWFHDay) {
                // Reset button states based on current time
                if ($currentHour >= 7 && $currentHour < 12) { // Morning: 6 AM to 12 PM
                    $this->morningInDisabled = false;
                    $this->morningOutDisabled = false;
                    $this->afternoonInDisabled = true;
                    $this->afternoonOutDisabled = true;
                } else { // Afternoon: 12 PM onward
                    $this->morningInDisabled = true;
                    $this->morningOutDisabled = true;
                    $this->afternoonInDisabled = false;
                    $this->afternoonOutDisabled = false;
                }
    
                // Additional check to ensure buttons are not enabled if already punched for the respective time
                $transactions = Transaction::where('emp_code', $user->emp_code)
                    ->where('punch_state_display', 'WFH')
                    ->whereDate('punch_time', Carbon::today())
                    ->pluck('verify_type_display');
    
                if ($transactions->contains('Morning In')) {
                    $this->morningInDisabled = true;
                }
                if ($transactions->contains('Morning Out')) {
                    $this->morningOutDisabled = true;
                }
                if ($transactions->contains('Afternoon In')) {
                    $this->afternoonInDisabled = true;
                }
                if ($transactions->contains('Afternoon Out')) {
                    $this->afternoonOutDisabled = true;
                }
            }
        }
    }       

    public function render()
    {
        $this->checkWFHDay();
        $this->resetButtonStatesIfNeeded(); // Ensure buttons are updated based on time and transactions
        
        $transactions = Transaction::where('emp_code', Auth::user()->emp_code)
                                    ->where('punch_state_display', 'WFH')
                                    ->whereDate('punch_time', Carbon::today())
                                    ->orderBy('punch_time', 'asc')
                                    ->get();
        
        // Group transactions by punch type
        $groupedTransactions = $transactions->groupBy('verify_type_display');

        return view('livewire.user.wfh-attendance-table', [
            'groupedTransactions' => $groupedTransactions,
        ]);
    }
}