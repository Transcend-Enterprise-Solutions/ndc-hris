<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DTRSchedule;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyScheduleTable extends Component
{
    public $currentMonth;
    public $currentYear;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function goToPreviousMonth()
    {
        $this->currentMonth--;
        if ($this->currentMonth < 1) {
            $this->currentMonth = 12;
            $this->currentYear--;
        }
    }

    public function goToNextMonth()
    {
        $this->currentMonth++;
        if ($this->currentMonth > 12) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
    }

    public function render()
    {
        // Define start and end of the month
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Fetch schedules and holidays within the month range
        $schedules = DTRSchedule::where('emp_code', Auth::user()->emp_code)
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                          $query->where('start_date', '<=', $endOfMonth)
                                ->where('end_date', '>=', $startOfMonth);
                      });
            })
            ->get();

        $holidays = Holiday::whereBetween('holiday_date', [$startOfMonth, $endOfMonth])->get();

        return view('livewire.user.my-schedule-table', [
            'schedules' => $schedules,
            'holidays' => $holidays,
            'startOfMonth' => $startOfMonth,
            'endOfMonth' => $endOfMonth,
        ]);
    }
}
