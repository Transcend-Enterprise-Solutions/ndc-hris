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
            $this->{$this->punchState}($this->verifyType);
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
        if ($verifyType == 'Morning In') {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = false;
        } elseif ($verifyType == 'Morning Out') {
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = false;
        } elseif ($verifyType == 'Afternoon In') {
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = false;
        } elseif ($verifyType == 'Afternoon Out') {
            $this->afternoonOutDisabled = true;
        }
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
        // Check if the current day has ended and if the next day is a WFH day
        $now = Carbon::now();
        $endOfDay = $now->copy()->endOfDay();

        if ($now->greaterThan($endOfDay)) {
            // Current day has ended, check for next day's schedule
            $user = Auth::user();
            $nextDay = $now->addDay()->format('l');
            $nextDaySchedule = DTRSchedule::where('emp_code', $user->emp_code)->first();

            if ($nextDaySchedule) {
                $wfhDays = explode(',', $nextDaySchedule->wfh_days);
                if (in_array($nextDay, $wfhDays)) {
                    // Reset button states if the next day is a WFH day
                    $this->morningInDisabled = false;
                    $this->morningOutDisabled = true;
                    $this->afternoonInDisabled = true;
                    $this->afternoonOutDisabled = true;
                }
            }
        }
    }

    public function render()
    {
        $this->checkWFHDay();
        $this->resetButtonStatesIfNeeded();
        
        $transactions = Transaction::where('emp_code', Auth::user()->emp_code)
                                    ->where('punch_state_display', 'WFH')
                                    ->paginate(4);

        // Check which buttons should be disabled based on previous transactions
        if ($transactions->contains('verify_type_display', 'Morning In')) {
            $this->morningInDisabled = true;
            $this->morningOutDisabled = false;
        }
        if ($transactions->contains('verify_type_display', 'Morning Out')) {
            $this->morningOutDisabled = true;
            $this->afternoonInDisabled = false;
        }
        if ($transactions->contains('verify_type_display', 'Afternoon In')) {
            $this->afternoonInDisabled = true;
            $this->afternoonOutDisabled = false;
        }
        if ($transactions->contains('verify_type_display', 'Afternoon Out')) {
            $this->afternoonOutDisabled = true;
        }

        return view('livewire.user.wfh-attendance-table', [
            'transactions' => $transactions,
        ]);
    }
}