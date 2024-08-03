<?php

namespace App\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DTRSchedule;
use App\Models\Transaction;

class WfhAttendanceTable extends Component
{
    public $isWFHDay;

    public function render()
    {
        $this->checkWFHDay();
        return view('livewire.user.wfh-attendance-table');
    }

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
        $this->punch(0); // 0 for Morning In
    }

    public function morningOut()
    {
        $this->punch(1); // 1 for Morning Out
    }

    public function afternoonIn()
    {
        $this->punch(0); // 0 for Afternoon In
    }

    public function afternoonOut()
    {
        $this->punch(1); // 1 for Afternoon Out
    }
}
