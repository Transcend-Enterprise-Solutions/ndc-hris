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
use Carbon\CarbonPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AutoSaveDtrRecordsMonthly implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Carbon $startDate;
    protected Carbon $endDate;

    public function __construct()
    {
        $this->startDate = Carbon::now()->startOfMonth();
        $this->endDate = Carbon::now()->endOfMonth();
    }

    public function handle()
    {
        $this->logJobStart();

        try {
            $this->processAllEmployees();
            $this->logJobSuccess();
        } catch (\Exception $e) {
            $this->logJobError($e);
        }
    }

    protected function processAllEmployees(): void
    {
        User::where('user_role', 'emp')->chunk(100, function ($users) {
            foreach ($users as $user) {
                $this->processEmployeeMonth($user);
            }
        });
    }

    protected function processEmployeeMonth(User $user): void
    {
        Log::info("Processing user: {$user->emp_code}");

        $datePeriod = CarbonPeriod::create($this->startDate, $this->endDate);

        foreach ($datePeriod as $date) {
            $this->processEmployeeDay($user, $date);
        }
    }

    protected function processEmployeeDay(User $user, Carbon $date): void
    {
        $currentDate = $date->toDateString();
        $schedule = $this->getUserSchedule($user->emp_code, $currentDate);
        $isWFH = $this->isWorkFromHomeDay($schedule, $date);

        $transactions = $this->getTransactionsForDay($user->emp_code, $currentDate, $isWFH);
        $approvedLeaves = $this->getApprovedLeaves($user->id, $currentDate);

        $this->logTransactionCount($user->emp_code, $currentDate, $transactions->count());

        $calculatedData = $this->calculateTimeRecords($transactions, $user->emp_code, $currentDate, $approvedLeaves);
        $this->logCalculatedData($user->emp_code, $currentDate, $calculatedData);

        $this->saveDtrRecord($user, $currentDate, $calculatedData);
    }

    protected function getUserSchedule(string $empCode, string $date): ?DTRSchedule
    {
        return DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();
    }

    protected function isWorkFromHomeDay(?DTRSchedule $schedule, Carbon $date): bool
    {
        if (!$schedule) {
            return false;
        }

        $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
        return in_array($date->format('l'), $wfhDays);
    }

    protected function getTransactionsForDay(string $empCode, string $date, bool $isWFH)
    {
        $transactionModel = $isWFH ? TransactionWFH::class : Transaction::class;

        return $transactionModel::where('emp_code', $empCode)
            ->whereDate('punch_time', $date)
            ->orderBy('punch_time')
            ->get();
    }

    protected function getApprovedLeaves(int $userId, string $date)
    {
        return LeaveApplication::where('user_id', $userId)
            ->where('status', 'Approved')
            ->whereRaw("FIND_IN_SET(?, approved_dates) > 0", [$date])
            ->get();
    }

    protected function calculateTimeRecords($transactions, $empCode, $date, $approvedLeaves): array
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');
        $schedule = $this->getUserSchedule($empCode, $date);

        $location = $this->determineLocation($schedule, $dayOfWeek);
        $timeData = $this->extractTimeData($transactions, $empCode, $date, $schedule);

        $calculatedTimes = $this->calculateTimes($timeData, $carbonDate, $schedule);

        // Debug log the calculated times
        Log::info("Calculated times for {$empCode} on {$date}: " . json_encode($calculatedTimes));

        // Pass the flexi flag to determineRemarks
        $isFlexi = $schedule ? $schedule->is_flexi == 1 : false;
        $remarks = $this->determineRemarks($timeData, $dayOfWeek, $approvedLeaves, $date, $isFlexi, $schedule, $calculatedTimes);

        // Debug log the final remarks
        Log::info("Final remarks for {$empCode} on {$date}: {$remarks} (isFlexi: " . ($isFlexi ? 'true' : 'false') . ")");

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

        $timeData['defaultStartTime'] = Carbon::parse($date)->setTimeFromTimeString($schedule->default_start_time);
        $timeData['defaultEndTime'] = Carbon::parse($date)->setTimeFromTimeString($schedule->default_end_time);

        // Process morning in
        $morningInTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $transaction->punch_state == 0 && $time->hour < 12;
        });

        if ($morningInTransactions->isNotEmpty()) {
            $timeData['morningIn'] = Carbon::parse($morningInTransactions->first()->punch_time);
        } else {
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

        $isFlexi = $schedule->is_flexi == 1;

        // Debug log
        Log::info("Schedule check - isFlexi: " . ($isFlexi ? 'true' : 'false') . ", schedule->is_flexi: " . $schedule->is_flexi);

        // Calculate total hours rendered
        $totalMinutesRendered = 0;

        if ($timeData['morningIn'] && $timeData['morningOut']) {
            $morningEnd = min($timeData['lunchBreakStart'], $timeData['morningOut']);
            $totalMinutesRendered += max(0, $timeData['morningIn']->diffInMinutes($morningEnd));
        }

        if ($timeData['afternoonIn'] && $timeData['afternoonOut']) {
            $afternoonStart = max($timeData['lunchBreakEnd'], $timeData['afternoonIn']);
            $totalMinutesRendered += max(0, $afternoonStart->diffInMinutes($timeData['afternoonOut']));
        }

        $result['total_hours_rendered'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($totalMinutesRendered)
            ->format('H:i');

        // Calculate lateness
        $lateMinutes = 0;

        if ($isFlexi) {
            // FLEXI SCHEDULE: Only late if time in is after 9:00 AM
            $flexiCutoff = $carbonDate->copy()->setTime(9, 0, 0);
            $firstTimeIn = $timeData['morningIn'] ?? $timeData['afternoonIn'];

            if ($firstTimeIn && $firstTimeIn->gt($flexiCutoff)) {
                $lateMinutes = $firstTimeIn->diffInMinutes($flexiCutoff);
                Log::info("FLEXI - Late calculation: Time in {$firstTimeIn->format('H:i:s')} > 09:00:00, late minutes: {$lateMinutes}");
            } else {
                Log::info("FLEXI - No late: Time in " . ($firstTimeIn ? $firstTimeIn->format('H:i:s') : 'null') . " <= 09:00:00");
            }
        } else {
            // REGULAR SCHEDULE
            if ($timeData['morningIn'] && $timeData['morningIn']->gt($timeData['defaultStartTime'])) {
                $lateMinutes = $timeData['morningIn']->diffInMinutes($timeData['defaultStartTime']);
            } elseif (!$timeData['morningIn'] && $timeData['afternoonIn']) {
                $lateMinutes = 4 * 60; // 4 hours penalty for missing morning
                $expectedAfternoonStart = $timeData['lunchBreakEnd'];
                if ($timeData['afternoonIn']->gt($expectedAfternoonStart)) {
                    $lateMinutes += $timeData['afternoonIn']->diffInMinutes($expectedAfternoonStart);
                }
            }
        }

        $result['late'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($lateMinutes)
            ->format('H:i');

        // Calculate undertime
        $undertimeMinutes = 0;

        if ($isFlexi) {
            // FLEXI SCHEDULE: Calculate based on flexi end time
            $expectedEndTime = $this->calculateFlexiEndTime($timeData, $carbonDate, $schedule);
            $actualEndTime = $timeData['afternoonOut'] ?? $timeData['morningOut'];

            Log::info("FLEXI - Undertime calculation: Expected end {$expectedEndTime->format('H:i:s')}, Actual end " . ($actualEndTime ? $actualEndTime->format('H:i:s') : 'null'));

            if ($actualEndTime && $actualEndTime->lt($expectedEndTime)) {
                $undertimeMinutes = $expectedEndTime->diffInMinutes($actualEndTime);
                Log::info("FLEXI - Undertime minutes: {$undertimeMinutes}");
            } elseif (!$timeData['afternoonOut'] && $timeData['morningIn']) {
                $undertimeMinutes = 4 * 60; // 4 hours penalty
                Log::info("FLEXI - No afternoon out, 4 hour penalty");
            } else {
                Log::info("FLEXI - No undertime");
            }
        } else {
            // REGULAR SCHEDULE
            if ($timeData['afternoonOut'] && $timeData['afternoonOut']->lt($timeData['defaultEndTime'])) {
                $undertimeMinutes = $timeData['defaultEndTime']->diffInMinutes($timeData['afternoonOut']);
            } elseif ($timeData['morningIn'] && !$timeData['afternoonOut']) {
                $undertimeMinutes = 4 * 60; // 4 hours penalty for missing afternoon
            }
        }

        $result['ut'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($undertimeMinutes)
            ->format('H:i');

        // Calculate overtime
        $overtimeMinutes = 0;

        if ($isFlexi) {
            // FLEXI SCHEDULE
            $expectedEndTime = $this->calculateFlexiEndTime($timeData, $carbonDate, $schedule);
            $actualEndTime = $timeData['afternoonOut'] ?? $timeData['morningOut'];

            if ($actualEndTime && $actualEndTime->gt($expectedEndTime)) {
                $overtimeMinutes = $actualEndTime->diffInMinutes($expectedEndTime);
                Log::info("FLEXI - Overtime minutes: {$overtimeMinutes}");
            }
        } else {
            // REGULAR SCHEDULE
            if ($timeData['afternoonOut'] && $timeData['afternoonOut']->gt($timeData['defaultEndTime'])) {
                $overtimeMinutes = $timeData['afternoonOut']->diffInMinutes($timeData['defaultEndTime']);
            }
        }

        $result['overtime'] = Carbon::createFromTime(0, 0, 0)
            ->addMinutes($overtimeMinutes)
            ->format('H:i');

        // Debug final result
        Log::info("Final calculated times: Late={$result['late']}, UT={$result['ut']}, OT={$result['overtime']}");

        return $result;
    }

    /**
     * Calculate expected end time for flexi schedule based on time in
     */
    protected function calculateFlexiEndTime(array $timeData, Carbon $carbonDate, DTRSchedule $schedule): Carbon
    {
        $firstTimeIn = $timeData['morningIn'] ?? $timeData['afternoonIn'];

        if ($firstTimeIn) {
            // For flexi schedule: earliest time in is 7:00 AM
            $earliestTimeIn = $carbonDate->copy()->setTime(7, 0, 0);

            // If they time in before 7:00 AM, consider it as 7:00 AM
            $effectiveTimeIn = $firstTimeIn->lt($earliestTimeIn) ? $earliestTimeIn : $firstTimeIn;

            // If they timed in before lunch break (before 12:00), add 9 hours total (8 work + 1 lunch)
            if ($effectiveTimeIn->hour < 12) {
                return $effectiveTimeIn->copy()->addHours(9); // 8 work hours + 1 lunch hour
            } else {
                // If they only timed in afternoon, they should work 8 hours from afternoon time in
                return $effectiveTimeIn->copy()->addHours(8);
            }
        }

        // Fallback to default end time
        return $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
    }

    protected function determineRemarks(array $timeData, string $dayOfWeek, $approvedLeaves, string $date, bool $isFlexi = false, ?DTRSchedule $schedule = null, array $calculatedTimes = []): string
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

        // Debug log the calculated times being checked
        Log::info("Remarks calculation - Late: " . ($calculatedTimes['late'] ?? 'null') . ", UT: " . ($calculatedTimes['ut'] ?? 'null'));

        // Use ONLY the calculated times to determine late/undertime status
        $isLate = isset($calculatedTimes['late']) && $calculatedTimes['late'] !== '00:00';
        $isUndertime = isset($calculatedTimes['ut']) && $calculatedTimes['ut'] !== '00:00';

        Log::info("Remarks flags - isLate: " . ($isLate ? 'true' : 'false') . ", isUndertime: " . ($isUndertime ? 'true' : 'false'));

        // Determine final remarks based on calculated values only
        if ($isLate && $isUndertime) {
            Log::info("Returning: Late/Undertime");
            return 'Late/Undertime';
        } elseif ($isLate) {
            Log::info("Returning: Late");
            return 'Late';
        } elseif ($isUndertime) {
            Log::info("Returning: Undertime");
            return 'Undertime';
        }

        Log::info("Returning: Present");
        return 'Present';
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

    protected function logJobStart(): void
    {
        echo "AutoSaveDtrRecordsMonthly job started\n";
        Log::info("AutoSaveDtrRecordsMonthly job started");
        echo "Processing month: {$this->startDate->format('F Y')}\n";
        Log::info("Processing month: {$this->startDate->format('F Y')}");
    }

    protected function logJobSuccess(): void
    {
        echo "AutoSaveDtrRecordsMonthly job completed successfully\n";
        Log::info("AutoSaveDtrRecordsMonthly job completed successfully");
    }

    protected function logJobError(\Exception $e): void
    {
        echo "AutoSaveDtrRecordsMonthly job failed: " . $e->getMessage() . "\n";
        Log::error("AutoSaveDtrRecordsMonthly job failed: " . $e->getMessage());
        Log::error($e->getTraceAsString());
    }

    protected function logTransactionCount(string $empCode, string $date, int $count): void
    {
        echo "Total transactions found for user {$empCode} on {$date}: {$count}\n";
        Log::info("Total transactions found for user {$empCode} on {$date}: {$count}");
    }

    protected function logCalculatedData(string $empCode, string $date, array $data): void
    {
        echo "Calculated data for user {$empCode} on {$date}: " . json_encode($data) . "\n";
        Log::info("Calculated data for user {$empCode} on {$date}: " . json_encode($data));
    }
}
