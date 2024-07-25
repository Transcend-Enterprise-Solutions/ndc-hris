<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\User;
use App\Models\DTRSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminDtrTable extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $searchTerm = '';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function getTransactions()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        $period = CarbonPeriod::create($startDate, $endDate);

        $query = User::query();
        if ($this->searchTerm) {
            $query->where('name', 'like', "%{$this->searchTerm}%")
                  ->orWhere('emp_code', 'like', "%{$this->searchTerm}%");
        }

        $users = $query->get();

        $groupedTransactions = [];
        foreach ($users as $user) {
            foreach ($period as $date) {
                $dateString = $date->format('Y-m-d');
                $groupedTransactions[$user->emp_code][$dateString] = [];
            }

            $transactions = Transaction::where('emp_code', $user->emp_code)
                ->whereBetween('punch_time', [$startDate, $endDate])
                ->get();

            foreach ($transactions as $transaction) {
                $date = Carbon::parse($transaction->punch_time)->format('Y-m-d');
                $groupedTransactions[$user->emp_code][$date][] = [
                    'punch_time' => Carbon::parse($transaction->punch_time),
                    'punch_state' => $transaction->punch_state,
                ];
            }
        }

        return $groupedTransactions;
    }

    public function calculateTimeRecords($dateTransactions, $empCode, $date)
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

        $totalMinutesRendered = 0;
        if ($morningIn && $morningOut) {
            $morningStart = max($defaultStartTime, $morningIn);
            $morningEnd = min($defaultEndTime, $morningOut);
            $totalMinutesRendered += max(0, $morningStart->diffInMinutes($morningEnd));
        }
        if ($afternoonIn && $afternoonOut) {
            $afternoonStart = max($defaultStartTime, $afternoonIn);
            $afternoonEnd = min($defaultEndTime, $afternoonOut);
            $totalMinutesRendered += max(0, $afternoonStart->diffInMinutes($afternoonEnd));
        }

        $requiredMinutes = 8 * 60; // 8 hours in minutes
        $undertime = 0;
        if ($totalMinutesRendered < $requiredMinutes) {
            $undertime = $requiredMinutes - $totalMinutesRendered;
        }

        $late = max($late, $undertime);
        $totalMinutesRendered = min($totalMinutesRendered, $requiredMinutes);

        // Convert minutes to hours and minutes format
        $formatTime = function($minutes) {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $remainingMinutes);
        };

        $formattedLate = $formatTime($late);
        $formattedOvertime = $formatTime($overtime);

        $totalHoursRendered = floor($totalMinutesRendered / 60);
        $totalMinutes = $totalMinutesRendered % 60;

        $remarks = '';
        if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            $remarks = $dayOfWeek;
        } elseif (empty($dateTransactions)) {
            $remarks = 'Absent';
        }

        return [
            'dayOfWeek' => $dayOfWeek,
            'location' => $location,
            'morningIn' => $morningIn ? $morningIn->format('H:i:s') : '-',
            'morningOut' => $morningOut ? $morningOut->format('H:i:s') : '-',
            'afternoonIn' => $afternoonIn ? $afternoonIn->format('H:i:s') : '-',
            'afternoonOut' => $afternoonOut ? $afternoonOut->format('H:i:s') : '-',
            'late' => $formattedLate,
            'overtime' => $formattedOvertime,
            'totalHoursRendered' => sprintf('%02d:%02d', $totalHoursRendered, $totalMinutes),
            'remarks' => $remarks,
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
        $paginatedUsers = User::query()
            ->where(function($query) {
                if ($this->searchTerm) {
                    $query->where('name', 'like', "%{$this->searchTerm}%")
                          ->orWhere('emp_code', 'like', "%{$this->searchTerm}%");
                }
            })
            ->paginate(1);

        $transactions = $this->getTransactions();

        return view('livewire.admin.admin-dtr-table', [
            'users' => $paginatedUsers,
            'transactions' => $transactions,
        ]);
    }
}
