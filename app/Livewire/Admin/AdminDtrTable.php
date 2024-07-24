<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\User;
use App\Models\DTRSchedule;
use Carbon\Carbon;

class AdminDtrTable extends Component
{
    public $transactions = [];
    public $startDate;
    public $endDate;
    public $searchTerm = '';

    public function mount()
    {
        // Initialize default date range to current month
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $this->loadTransactions();
    }

    public function updatedStartDate()
    {
        $this->loadTransactions();
    }

    public function updatedEndDate()
    {
        $this->loadTransactions();
    }

    public function updatedSearchTerm()
    {
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $query = Transaction::query()
            ->whereBetween('punch_time', [$this->startDate, $this->endDate]);

        if ($this->searchTerm) {
            $empCodes = User::where('name', 'like', "%{$this->searchTerm}%")
                ->orWhere('emp_code', 'like', "%{$this->searchTerm}%")
                ->pluck('emp_code');
            $query->whereIn('emp_code', $empCodes);
        }

        $transactions = $query->orderBy('punch_time')->get();
        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->punch_time)->format('Y-m-d');
            $empCode = $transaction->emp_code;
            if (!isset($groupedTransactions[$empCode])) {
                $groupedTransactions[$empCode][$date] = [];
            }
            $groupedTransactions[$empCode][$date][] = [
                'punch_time' => Carbon::parse($transaction->punch_time),
                'punch_state' => $transaction->punch_state,
            ];
        }

        $this->transactions = $groupedTransactions;
    }

    public function calculateTimeRecords($dateTransactions, $empCode, $date)
    {
        $morningPunches = collect();
        $afternoonPunches = collect();

        foreach ($dateTransactions as $transaction) {
            $punchTime = Carbon::parse($transaction['punch_time']);
            if ($punchTime->hour < 12) {
                $morningPunches->push($transaction);
            } else {
                $afternoonPunches->push($transaction);
            }
        }

        $morningIn = $this->getFirstInPunch($morningPunches);
        $morningOut = $this->getLastOutPunch($morningPunches);
        $afternoonIn = $this->getFirstInPunch($afternoonPunches);
        $afternoonOut = $this->getLastOutPunch($afternoonPunches);

        $schedule = DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');

        $location = 'Onsite';
        $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('07:00:00');
        $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:30:00');

        if ($schedule) {
            $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));

            if (in_array($dayOfWeek, $wfhDays)) {
                $location = 'WFH';
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('08:00:00');
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('17:00:00');
            } else {
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_start_time);
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
            }
        }

        if ($dayOfWeek === 'Monday' && $location !== 'WFH') {
            $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('09:00:00');
            $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:00:00');
        }

        $latenessThreshold = $dayOfWeek === 'Monday' ? '09:00:00' : '09:30:00';
        $latenessThreshold = ($location === 'WFH') ? '08:00:00' : $latenessThreshold;
        $latenessThresholdTime = $carbonDate->copy()->setTimeFromTimeString($latenessThreshold);

        $late = 0;
        if ($morningIn && $morningIn->gt($latenessThresholdTime)) {
            $late = $morningIn->diffInMinutes($latenessThresholdTime);
        }

        $overtime = 0;
        if ($afternoonOut && $afternoonOut->gt($defaultEndTime)) {
            $overtime = $afternoonOut->diffInMinutes($defaultEndTime);
        }

        $totalHoursRendered = 0;
        if ($morningIn && $morningOut) {
            $morningStart = max($defaultStartTime, $morningIn);
            $morningEnd = min($defaultEndTime, $morningOut);
            $totalHoursRendered += max(0, $morningStart->diffInMinutes($morningEnd)) / 60;
        }
        if ($afternoonIn && $afternoonOut) {
            $afternoonStart = max($defaultStartTime, $afternoonIn);
            $afternoonEnd = min($defaultEndTime, $afternoonOut);
            $totalHoursRendered += max(0, $afternoonStart->diffInMinutes($afternoonEnd)) / 60;
        }

        $requiredHours = 8;
        $undertime = 0;
        if ($totalHoursRendered < $requiredHours) {
            $undertime = ($requiredHours - $totalHoursRendered) * 60; // Convert to minutes
        }

        $late = max($late, $undertime);
        $totalHoursRendered = min($totalHoursRendered, 8);

        return [
            'dayOfWeek' => $dayOfWeek,
            'location' => $location,
            'morningIn' => $morningIn ? $morningIn->format('H:i:s') : '-',
            'morningOut' => $morningOut ? $morningOut->format('H:i:s') : '-',
            'afternoonIn' => $afternoonIn ? $afternoonIn->format('H:i:s') : '-',
            'afternoonOut' => $afternoonOut ? $afternoonOut->format('H:i:s') : '-',
            'late' => $late,
            'overtime' => $overtime,
            'totalHoursRendered' => round($totalHoursRendered, 2),
        ];
    }

    private function getFirstInPunch($punches)
    {
        return $punches->where('punch_state', 0)->sortBy('punch_time')->first()['punch_time'] ?? null;
    }

    private function getLastOutPunch($punches)
    {
        return $punches->where('punch_state', 1)->sortByDesc('punch_time')->first()['punch_time'] ?? null;
    }

    public function render()
    {
        return view('livewire.admin.admin-dtr-table');
    }
}
