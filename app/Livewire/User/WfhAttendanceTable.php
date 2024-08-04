<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\DTRSchedule;
use App\Models\Transaction;

class WfhAttendanceTable extends Component
{
    public $isWFHDay;
    public $inputPassword = false;
    public $password;
    public $punchState;
    public $errorMessage;

    public function checkWFHDay()
    {
        $user = Auth::user();
        $today = Carbon::now()->format('l'); // Get current day of the week, e.g., 'Tuesday'
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();

        if ($schedule) {
            $wfhDays = explode(', ', $schedule->wfh_days);
            $this->isWFHDay = in_array($today, $wfhDays);
        } else {
            $this->isWFHDay = false;
        }
    }

    public function confirmPunch($state)
    {
        $this->punchState = $state;
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
            $this->{$this->punchState}();
        } else {
            $this->errorMessage = 'Incorrect password. Please try again.';
        }
    }

    public function punch($state)
    {
        $user = Auth::user();
        $punchTime = Carbon::now();

        $punchData = [
            'emp_code' => $user->emp_code,
            'punch_time' => $punchTime,
            'punch_state' => $state,
            'punch_state_display' => 'WFH',
        ];

        Transaction::create($punchData);
    }

    public function morningIn()
    {
        $this->punch(0);
    }

    public function morningOut()
    {
        $this->punch(1);
    }

    public function afternoonIn()
    {
        $this->punch(0);
    }

    public function afternoonOut()
    {
        $this->punch(1);
    }

    public function resetVariables()
    {
        $this->password = null;
        $this->errorMessage = null;
    }

    public function render()
    {
        $this->checkWFHDay();
        return view('livewire.user.wfh-attendance-table');
    }
}
