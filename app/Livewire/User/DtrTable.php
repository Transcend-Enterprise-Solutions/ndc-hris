<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\DTRSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DtrTable extends Component
{
    public $transactions = [];

    public function mount()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $transactions = Transaction::where('emp_code', Auth::user()->emp_code)
                                    ->whereBetween('punch_time', [$startDate, $endDate])
                                    ->orderBy('punch_time')
                                    ->get();

        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->punch_time)->format('Y-m-d');
            if (!isset($groupedTransactions[$date])) {
                $groupedTransactions[$date] = [];
            }
            $groupedTransactions[$date][] = [
                'punch_time' => Carbon::parse($transaction->punch_time),
                'punch_state' => $transaction->punch_state,
            ];
        }

        $this->transactions = $groupedTransactions;
    }

    public function calculateTimeRecords($dateTransactions, $date)
    {
        $morningPunches = collect();
        $afternoonPunches = collect();

        foreach ($dateTransactions as $transaction) {
            $punchTime = Carbon::parse($transaction['punch_time']);
            if ($punchTime->hour <= 12) {
                $morningPunches->push($transaction);
            } else {
                $afternoonPunches->push($transaction);
            }
        }

        $morningIn = $this->getFirstInPunch($morningPunches);
        $morningOut = $this->getLastOutPunch($morningPunches);
        $afternoonIn = $this->getFirstInPunch($afternoonPunches);
        $afternoonOut = $this->getLastOutPunch($afternoonPunches);

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');

        $location = 'Onsite';
        $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('09:30:00');
        $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:30:00');

        $schedule = DTRSchedule::where('emp_code', Auth::user()->emp_code)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $wfhDays = array_map('trim', $wfhDays);
            $wfhDays = array_map('ucfirst', $wfhDays);

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

        $totalHoursRendered = min($totalHoursRendered, 8);

        $result = [
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

        return $result;
    }

    private function getFirstInPunch($punches)
    {
        return $punches->where('punch_state', 0)->sortBy('punch_time')->first()['punch_time'] ?? null;
    }

    private function getLastOutPunch($punches)
    {
        return $punches->where('punch_state', 1)->sortByDesc('punch_time')->first()['punch_time'] ?? null;
    }

    public function getFormattedTransactionsProperty()
    {
        $formattedTransactions = [];

        foreach ($this->transactions as $date => $dateTransactions) {
            $formattedTransactions[$date] = $this->calculateTimeRecords($dateTransactions, $date);
        }

        return $formattedTransactions;
    }

    public function render()
    {
        return view('livewire.user.dtr-table', [
            'formattedTransactions' => $this->formattedTransactions,
        ]);
    }
}
