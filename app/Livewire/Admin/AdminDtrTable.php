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

    public function mount()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $transactions = Transaction::whereBetween('punch_time', [$startDate, $endDate])
                                    ->orderBy('punch_time')
                                    ->get();

        $groupedTransactions = [];
        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->punch_time)->format('Y-m-d');
            $empCode = $transaction->emp_code;
            if (!isset($groupedTransactions[$empCode])) {
                $groupedTransactions[$empCode] = [];
            }
            if (!isset($groupedTransactions[$empCode][$date])) {
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

        // Separate morning and afternoon punches
        foreach ($dateTransactions as $transaction) {
            $punchTime = Carbon::parse($transaction['punch_time']);
            if ($punchTime->hour < 12) {
                $morningPunches->push($transaction);
            } else {
                $afternoonPunches->push($transaction);
            }
        }

        // Determine the first in and last out punches for morning and afternoon
        $morningIn = $this->getFirstInPunch($morningPunches);
        $morningOut = $this->getLastOutPunch($morningPunches);
        $afternoonIn = $this->getFirstInPunch($afternoonPunches);
        $afternoonOut = $this->getLastOutPunch($afternoonPunches);

        // Retrieve the employee's schedule within the date range
        $schedule = DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');

        // Set default location and times
        $location = 'Onsite';
        $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('09:30:00');
        $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:30:00');

        if ($schedule) {
            $wfhDays = explode(',', $schedule->wfh_days);
            $wfhDays = array_map('trim', $wfhDays); // Trim whitespace from each day
            $wfhDays = array_map('ucfirst', $wfhDays); // Capitalize the first letter of each day

            if (in_array($dayOfWeek, $wfhDays)) {
                $location = 'WFH';
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('08:00:00');
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('17:00:00');
            } else {
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_start_time);
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
            }
        }

        // Adjust for Mondays if it's not a WFH day
        if ($dayOfWeek === 'Monday' && $location !== 'WFH') {
            $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('09:00:00');
            $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:00:00');
        }

        // Calculate lateness using 9:00 for Mondays and 9:30 for other days if not WFH
        $latenessThreshold = $dayOfWeek === 'Monday' ? '09:00:00' : '09:30:00';
        $latenessThreshold = ($location === 'WFH') ? '08:00:00' : $latenessThreshold;
        $latenessThresholdTime = $carbonDate->copy()->setTimeFromTimeString($latenessThreshold);

        $late = 0;
        if ($morningIn && $morningIn->gt($latenessThresholdTime)) {
            $late = $morningIn->diffInMinutes($latenessThresholdTime);
        }

        // Calculate overtime
        $overtime = 0;
        if ($afternoonOut && $afternoonOut->gt($defaultEndTime)) {
            $overtime = $afternoonOut->diffInMinutes($defaultEndTime);
        }

        // Calculate total hours rendered
        $totalHoursRendered = 0;
        if ($morningIn && $morningOut) {
            $totalHoursRendered += max(0, $morningIn->copy()->max($defaultStartTime)->diffInMinutes($morningOut)) / 60;
        }
        if ($afternoonIn && $afternoonOut) {
            $totalHoursRendered += max(0, $afternoonIn->copy()->max($defaultStartTime)->diffInMinutes($afternoonOut)) / 60;
        }

        // Limit total rendered hours to 8 hours
        $totalHoursRendered = min($totalHoursRendered, 8);

        // Subtract overtime hours if the afternoon out is late
        if ($afternoonOut && $afternoonOut->gt($defaultEndTime)) {
            $totalHoursRendered -= $overtime / 60;
        }

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

    public function render()
    {
        return view('livewire.admin.admin-dtr-table');
    }
}
