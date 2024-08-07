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
        if ($this->isButtonDisabled($state)) {
            // If the button is disabled, don't proceed
            return;
        }

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
        if ($this->isButtonDisabled($state)) {
            // If the button is disabled, don't proceed
            return;
        }

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

        // Update button states
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

        $this->dispatch('swal', [
            'title' => "You have successfully punched the $verifyType!",
            'icon' => 'success'
        ]);
    }

    public function morningIn()
    {
        if (!$this->isButtonDisabled('morningIn')) {
            $this->punch(0, 'Morning In');
        }
    }

    public function morningOut()
    {
        if (!$this->isButtonDisabled('morningOut')) {
            $this->punch(1, 'Morning Out');
        }
    }

    public function afternoonIn()
    {
        if (!$this->isButtonDisabled('afternoonIn')) {
            $this->punch(0, 'Afternoon In');
        }
    }

    public function afternoonOut()
    {
        if (!$this->isButtonDisabled('afternoonOut')) {
            $this->punch(1, 'Afternoon Out');
        }
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
        $today = $now->format('l');
        $schedule = DTRSchedule::where('emp_code', $user->emp_code)->first();

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $isWFHDay = in_array($today, $wfhDays);

            if ($isWFHDay) {
                // Check if there are any transactions for today
                $todayTransactions = Transaction::where('emp_code', $user->emp_code)
                    ->where('punch_state_display', 'WFH')
                    ->whereDate('punch_time', $now->toDateString())
                    ->count();

                if ($todayTransactions == 0) {
                    // Reset button states if it's a WFH day and no transactions have been made yet
                    $this->morningInDisabled = false;
                    $this->morningOutDisabled = true;
                    $this->afternoonInDisabled = true;
                    $this->afternoonOutDisabled = true;
                }
            }
        }
    }

    private function isButtonDisabled($button)
    {
        switch ($button) {
            case 'morningIn':
                return $this->morningInDisabled;
            case 'morningOut':
                return $this->morningOutDisabled;
            case 'afternoonIn':
                return $this->afternoonInDisabled;
            case 'afternoonOut':
                return $this->afternoonOutDisabled;
            default:
                return true;
        }
    }

    public function render()
    {
        $this->checkWFHDay();
        $this->resetButtonStatesIfNeeded(); // This will now reset buttons at the start of each WFH day
        
        $transactions = Transaction::where('emp_code', Auth::user()->emp_code)
                                    ->where('punch_state_display', 'WFH')
                                    ->whereDate('punch_time', Carbon::today()) // Only get today's transactions
                                    ->orderBy('punch_time', 'asc')
                                    ->get();

        // Reset button states before checking transactions
        $this->morningInDisabled = false;
        $this->morningOutDisabled = true;
        $this->afternoonInDisabled = true;
        $this->afternoonOutDisabled = true;

        // Update button states based on today's transactions
        foreach ($transactions as $transaction) {
            switch ($transaction->verify_type_display) {
                case 'Morning In':
                    $this->morningInDisabled = true;
                    $this->morningOutDisabled = false;
                    break;
                case 'Morning Out':
                    $this->morningOutDisabled = true;
                    $this->afternoonInDisabled = false;
                    break;
                case 'Afternoon In':
                    $this->afternoonInDisabled = true;
                    $this->afternoonOutDisabled = false;
                    break;
                case 'Afternoon Out':
                    $this->afternoonOutDisabled = true;
                    break;
            }
        }

        // Paginate all transactions for display
        $paginatedTransactions = Transaction::where('emp_code', Auth::user()->emp_code)
                                            ->where('punch_state_display', 'WFH')
                                            ->orderBy('punch_time', 'desc')
                                            ->paginate(4);

        return view('livewire.user.wfh-attendance-table', [
            'transactions' => $paginatedTransactions,
        ]);
    }
}