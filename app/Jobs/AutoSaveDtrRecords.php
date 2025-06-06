<?php
namespace App\Jobs;

use App\Models\{
    Transaction,
    TransactionWFH,
    User,
    DTRSchedule,
    EmployeesDtr,
    Holiday,
    LeaveApplication
};
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoSaveDtrRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {

        $this->logJobStart();
        $currentDate = Carbon::now()->toDateString();

        try {
            User::where('user_role', 'emp')->chunk(100, function ($users) use ($currentDate) {
                foreach ($users as $user) {
                    $this->processUserDtr($user, $currentDate);
                }
            });

            $this->logJobSuccess();
        } catch (\Exception $e) {
            $this->logJobError($e);
        }
    }

    protected function processUserDtr(User $user, string $currentDate): void
    {
        Log::info("Processing user: {$user->emp_code}");

        $transactions = $this->getUserTransactions($user, $currentDate);
        $approvedLeaves = $this->getApprovedLeaves($user, $currentDate);
        $calculatedData = $this->calculateTimeRecords($transactions, $user->emp_code, $currentDate, $approvedLeaves);

        $this->saveDtrRecord($user, $currentDate, $calculatedData);
    }

    protected function getUserTransactions(User $user, string $date)
    {
        $schedule = $this->getUserSchedule($user->emp_code, $date);
        $isWFH = $this->isWorkFromHomeDay($schedule, $date);

        $transactionModel = $isWFH ? TransactionWFH::class : Transaction::class;

        return $transactionModel::where('emp_code', $user->emp_code)
            ->whereDate('punch_time', $date)
            ->orderBy('punch_time')
            ->get();
    }

    protected function getUserSchedule(string $empCode, string $date): ?DTRSchedule
    {
        return DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();
    }

    protected function isWorkFromHomeDay(?DTRSchedule $schedule, string $date): bool
    {
        if (!$schedule) {
            return false;
        }

        $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
        $dayOfWeek = Carbon::parse($date)->format('l');

        return in_array($dayOfWeek, $wfhDays);
    }

    protected function getApprovedLeaves(User $user, string $date)
    {
        return LeaveApplication::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->whereRaw("FIND_IN_SET(?, approved_dates) > 0", [$date])
            ->get();
    }

    protected function saveDtrRecord(User $user, string $date, array $data): void
    {
        try {
            $record = EmployeesDtr::updateOrCreate(
                ['user_id' => $user->id, 'date' => $date],
                array_merge(['emp_code' => $user->emp_code], $data)
            );

            Log::info("DTR record saved/updated for user {$user->emp_code} on {$date}. Record ID: {$record->id}");
        } catch (\Exception $e) {
            Log::error("Error saving DTR record for user {$user->emp_code} on {$date}: " . $e->getMessage());
        }
    }

    protected function calculateTimeRecords($transactions, $empCode, $date, $approvedLeaves)
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');
        $schedule = $this->getUserSchedule($empCode, $date);

        $location = $this->determineLocation($schedule, $dayOfWeek);
        $timeData = $this->extractTimeData($transactions, $empCode, $date, $schedule);

        $calculatedTimes = $this->calculateTimes($timeData, $carbonDate, $schedule);
        $remarks = $this->determineRemarks($timeData, $dayOfWeek, $approvedLeaves, $date);

        return array_merge([
            'day_of_week' => $dayOfWeek,
            'location' => $location,
            'remarks' => $remarks,
        ], $calculatedTimes);
    }

    protected function determineLocation(?DTRSchedule $schedule, string $dayOfWeek): string
    {
        if (!$schedule) {
            return 'Onsite';
        }

        $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
        return in_array($dayOfWeek, $wfhDays) ? 'WFH' : 'Onsite';
    }

    protected function extractTimeData($transactions, $empCode, $date, ?DTRSchedule $schedule): array
    {
        $carbonDate = Carbon::parse($date);
        $timeData = [
            'morningIn' => null,
            'morningOut' => null,
            'afternoonIn' => null,
            'afternoonOut' => null,
            'lunchBreakStart' => $carbonDate->copy()->setTimeFromTimeString('12:00:00'),
            'lunchBreakEnd' => $carbonDate->copy()->setTimeFromTimeString('13:00:00'),
        ];

        if (!$schedule) {
            return $timeData;
        }

        // Get morning in transactions (before 12:00)
        $morningInTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $transaction->punch_state == 0 && $time->hour < 12;
        });

        if ($morningInTransactions->isNotEmpty()) {
            $timeData['morningIn'] = Carbon::parse($morningInTransactions->first()->punch_time);
        } else {
            // Check if first punch is afternoon in
            $firstPunch = $transactions->first();
            if ($firstPunch && Carbon::parse($firstPunch->punch_time)->hour >= 12) {
                $timeData['afternoonIn'] = Carbon::parse($firstPunch->punch_time);
            }
        }

        // Process morning out if morning in exists
        if ($timeData['morningIn']) {
            $morningOutTransactions = $transactions->filter(function ($transaction) use ($timeData) {
                $time = Carbon::parse($transaction->punch_time);
                return $transaction->punch_state == 1 && $time->gt($timeData['morningIn']) && $time->hour < 13;
            });

            if ($morningOutTransactions->isNotEmpty()) {
                $timeData['morningOut'] = Carbon::parse($morningOutTransactions->last()->punch_time);
            }

            // Automate lunch break times if morning out is before 12
            if ($timeData['morningIn']->lt($timeData['lunchBreakStart'])) {
                $timeData['morningOut'] = $timeData['morningOut'] ?? $timeData['lunchBreakStart'];
                $timeData['afternoonIn'] = $timeData['lunchBreakEnd'];
            }
        }

        // Process afternoon in if no morning out
        if (!$timeData['morningOut'] && !$timeData['afternoonIn']) {
            $afternoonInTransactions = $transactions->filter(function ($transaction) {
                $time = Carbon::parse($transaction->punch_time);
                return $transaction->punch_state == 0 && $time->hour >= 13;
            });

            if ($afternoonInTransactions->isNotEmpty()) {
                $timeData['afternoonIn'] = Carbon::parse($afternoonInTransactions->first()->punch_time);
            }
        }

        // Process afternoon out
        $afternoonOutTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $transaction->punch_state == 1 && $time->hour >= 13;
        });

        if ($afternoonOutTransactions->isNotEmpty()) {
            $timeData['afternoonOut'] = Carbon::parse($afternoonOutTransactions->last()->punch_time);
        }

        $timeData['defaultStartTime'] = Carbon::parse($date)->setTimeFromTimeString($schedule->default_start_time);
        $timeData['defaultEndTime'] = Carbon::parse($date)->setTimeFromTimeString($schedule->default_end_time);

        return $timeData;
    }

    protected function calculateTimes(array $timeData, Carbon $carbonDate, ?DTRSchedule $schedule): array
    {
        $result = [
            'morning_in' => $timeData['morningIn']?->format('H:i:s'),
            'morning_out' => $timeData['morningOut']?->format('H:i:s'),
            'afternoon_in' => $timeData['afternoonIn']?->format('H:i:s'),
            'afternoon_out' => $timeData['afternoonOut']?->format('H:i:s'),
            'total_hours_rendered' => '00:00',
            'late' => '00:00',
            'overtime' => '00:00',
            'ut' => '00:00',
        ];

        if (!$schedule) {
            return $result;
        }

        // Check if this is a flexi schedule
        $isFlexi = $schedule->is_flexi == 1;

        // Calculate total hours rendered
        $totalMinutesRendered = 0;

        if ($timeData['morningIn'] && $timeData['morningOut']) {
            $morningEnd = min($timeData['lunchBreakStart'], $timeData['morningOut']);
            $totalMinutesRendered += max(0, $timeData['morningIn']->diffInMinutes($morningEnd));
        }

        if ($timeData['afternoonIn'] && $timeData['afternoonOut']) {
            $afternoonStart = max($timeData['lunchBreakEnd'], $timeData['afternoonIn']);
            $afternoonEnd = min($timeData['defaultEndTime'], $timeData['afternoonOut']);
            $totalMinutesRendered += max(0, $afternoonStart->diffInMinutes($afternoonEnd));
        }

        $result['total_hours_rendered'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($totalMinutesRendered)
            ->format('H:i');

        // Calculate lateness based on flexi schedule
        $lateMinutes = 0;

        if ($isFlexi) {
            // For flexi schedule: only late if time in is after 9:00 AM
            $flexiCutoff = $carbonDate->copy()->setTime(9, 0, 0);

            if ($timeData['morningIn'] && $timeData['morningIn']->gt($flexiCutoff)) {
                $lateMinutes = $timeData['morningIn']->diffInMinutes($flexiCutoff);
            } elseif (!$timeData['morningIn'] && $timeData['afternoonIn']) {
                // If no morning in but has afternoon in, check if afternoon in is after 9:00 AM
                if ($timeData['afternoonIn']->gt($flexiCutoff)) {
                    $lateMinutes = $timeData['afternoonIn']->diffInMinutes($flexiCutoff);
                }
            }
        } else {
            // Regular schedule calculation
            if ($timeData['morningIn'] && $timeData['morningIn']->gt($timeData['defaultStartTime'])) {
                $lateMinutes = $timeData['morningIn']->diffInMinutes($timeData['defaultStartTime']);
            } elseif (!$timeData['morningIn'] && $timeData['afternoonIn']) {
                $lateMinutes = 4 * 60; // 4 hours penalty for missing morning

                // Additional lateness if afternoon in is after 13:00
                $afternoonThreshold = $carbonDate->copy()->setTime(13, 0, 0);
                if ($timeData['afternoonIn']->gt($afternoonThreshold)) {
                    $lateMinutes += $timeData['afternoonIn']->diffInMinutes($afternoonThreshold);
                }
            }
        }

        $result['late'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($lateMinutes)
            ->format('H:i');

        // Calculate undertime based on flexi schedule
        $undertimeMinutes = 0;

        if ($isFlexi) {
            // For flexi schedule: calculate expected end time based on time in
            $expectedEndTime = $this->calculateFlexiEndTime($timeData, $carbonDate, $schedule);

            if ($timeData['afternoonOut']) {
                if ($timeData['afternoonOut']->lt($expectedEndTime)) {
                    $undertimeMinutes = $expectedEndTime->diffInMinutes($timeData['afternoonOut']);
                }
            } elseif ($timeData['morningIn']) {
                $undertimeMinutes = 4 * 60; // 4 hours penalty for missing afternoon
            }
        } else {
            // Regular schedule calculation
            if ($timeData['afternoonOut']) {
                if ($timeData['afternoonOut']->lt($timeData['defaultEndTime'])) {
                    $undertimeMinutes = $timeData['defaultEndTime']->diffInMinutes($timeData['afternoonOut']);
                }
            } elseif ($timeData['morningIn']) {
                $undertimeMinutes = 4 * 60; // 4 hours penalty for missing afternoon
            }
        }

        $result['ut'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($undertimeMinutes)
            ->format('H:i');

        // Calculate overtime based on flexi schedule
        $overtimeMinutes = 0;

        if ($isFlexi) {
            $expectedEndTime = $this->calculateFlexiEndTime($timeData, $carbonDate, $schedule);

            if ($timeData['afternoonOut'] && $timeData['afternoonOut']->gt($expectedEndTime)) {
                $overtimeMinutes = $timeData['afternoonOut']->diffInMinutes($expectedEndTime);
            }
        } else {
            // Regular schedule calculation
            if ($timeData['afternoonOut'] && $timeData['afternoonOut']->gt($timeData['defaultEndTime'])) {
                $overtimeMinutes = $timeData['afternoonOut']->diffInMinutes($timeData['defaultEndTime']);
            }
        }

        $result['overtime'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($overtimeMinutes)
            ->format('H:i');

        return $result;
    }

    /**
     * Calculate expected end time for flexi schedule based on time in
     */
    protected function calculateFlexiEndTime(array $timeData, Carbon $carbonDate, DTRSchedule $schedule): Carbon
    {
        // Standard work hours (8 hours + 1 hour lunch break = 9 hours total)
        $standardWorkHours = 9;

        if ($timeData['morningIn']) {
            // Add 9 hours to morning in time
            return $timeData['morningIn']->copy()->addHours($standardWorkHours);
        } elseif ($timeData['afternoonIn']) {
            // If only afternoon in, calculate based on afternoon in time
            // Assume they should work until at least the default end time
            return $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
        }

        // Fallback to default end time
        return $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
    }

    protected function determineRemarks(array $timeData, string $dayOfWeek, $approvedLeaves, string $date): string
    {
        // Check for weekends first
        if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            return $dayOfWeek;
        }

        // Check for holidays
        if (Holiday::whereDate('holiday_date', $date)->exists()) {
            return 'Holiday';
        }

        // Check for leaves
        if ($approvedLeaves->isNotEmpty()) {
            return 'Leave';
        }

        // Determine based on time entries
        if (!$timeData['morningIn'] && !$timeData['afternoonIn']) {
            return 'Absent';
        }

        if (($timeData['morningIn'] && !$timeData['morningOut']) || ($timeData['afternoonIn'] && !$timeData['afternoonOut'])) {
            return 'Incomplete';
        }

        $late = $timeData['morningIn'] && $timeData['morningIn']->gt($timeData['defaultStartTime'] ?? Carbon::now());
        $undertime = $timeData['afternoonOut'] && $timeData['afternoonOut']->lt($timeData['defaultEndTime'] ?? Carbon::now());

        if ($late && $undertime) {
            return 'Late/Undertime';
        } elseif ($late) {
            return 'Late';
        } elseif ($undertime) {
            return 'Undertime';
        }

        return 'Present';
    }

    protected function logJobStart(): void
    {
        Log::info("AutoSaveDtrRecords job started");
    }

    protected function logJobSuccess(): void
    {
        Log::info("AutoSaveDtrRecords job completed successfully");
    }

    protected function logJobError(\Exception $e): void
    {
        Log::error("AutoSaveDtrRecords job failed: " . $e->getMessage());
        Log::error($e->getTraceAsString());
    }
}
