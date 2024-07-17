<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\User;
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

    public function calculateTimeRecords($dateTransactions)
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

        $startOfWork = Carbon::parse($morningIn ? $morningIn->format('Y-m-d') : now()->format('Y-m-d'))->setTime(8, 0);
        $endOfWork = Carbon::parse($afternoonOut ? $afternoonOut->format('Y-m-d') : now()->format('Y-m-d'))->setTime(17, 0);

        $late = $morningIn && $morningIn->gt($startOfWork) ? $morningIn->diffInMinutes($startOfWork) : 0;
        $overtime = $afternoonOut && $afternoonOut->gt($endOfWork) ? $afternoonOut->diffInMinutes($endOfWork) : 0;

        $totalHoursRendered = 0;
        if ($morningIn && $morningOut) {
            $totalHoursRendered += $morningIn->diffInMinutes($morningOut) / 60;
        }
        if ($afternoonIn && $afternoonOut) {
            $totalHoursRendered += $afternoonIn->diffInMinutes($afternoonOut) / 60;
        }

        return [
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
