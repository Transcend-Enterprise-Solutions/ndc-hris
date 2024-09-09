<?php
namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use App\Models\DTRSchedule;
use App\Models\EmployeesDtr;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class AutoSaveDtrRecords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        echo "AutoSaveDtrRecords job started\n";
        Log::info("AutoSaveDtrRecords job started");

        try {
            // Get the current date
            $currentDate = Carbon::now()->toDateString();

            echo "Processing date: {$currentDate}\n";
            Log::info("Processing date: {$currentDate}");

            $users = User::where('user_role', 'emp')->get();

            foreach ($users as $user) {
                echo "Processing user: {$user->emp_code}\n";
                Log::info("Processing user: {$user->emp_code}");

                // Get the transactions for the current date
                $transactions = Transaction::where('emp_code', $user->emp_code)
                    ->whereDate('punch_time', $currentDate)
                    ->orderBy('punch_time')
                    ->get();

                // Get approved leaves for the current date
                $approvedLeaves = LeaveApplication::where('user_id', $user->id)
                    ->where('status', 'Approved')
                    ->whereRaw("FIND_IN_SET(?, approved_dates) > 0", [$currentDate])
                    ->get();

                echo "Total transactions found for user {$user->emp_code}: " . $transactions->count() . "\n";
                Log::info("Total transactions found for user {$user->emp_code}: " . $transactions->count());

                // Process the transactions for the current date
                $calculatedData = $this->calculateTimeRecords($transactions, $user->emp_code, $currentDate, $approvedLeaves);
                echo "Calculated data for user {$user->emp_code} on {$currentDate}: " . json_encode($calculatedData) . "\n";
                Log::info("Calculated data for user {$user->emp_code} on {$currentDate}: " . json_encode($calculatedData));

                try {
                    $record = EmployeesDtr::updateOrCreate(
                        ['user_id' => $user->id, 'date' => $currentDate],
                        array_merge(['emp_code' => $user->emp_code], $calculatedData)
                    );
                    echo "DTR record saved/updated for user {$user->emp_code} on {$currentDate}. Record ID: " . $record->id . "\n";
                    Log::info("DTR record saved/updated for user {$user->emp_code} on {$currentDate}. Record ID: " . $record->id);
                } catch (\Exception $e) {
                    echo "Error saving DTR record for user {$user->emp_code} on {$currentDate}: " . $e->getMessage() . "\n";
                    Log::error("Error saving DTR record for user {$user->emp_code} on {$currentDate}: " . $e->getMessage());
                }
            }

            echo "AutoSaveDtrRecords job completed successfully\n";
            Log::info("AutoSaveDtrRecords job completed successfully");
        } catch (\Exception $e) {
            echo "AutoSaveDtrRecords job failed: " . $e->getMessage() . "\n";
            Log::error("AutoSaveDtrRecords job failed: " . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    private function calculateTimeRecords($transactions, $empCode, $date, $approvedLeaves)
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = $carbonDate->format('l');
        // Fetch the schedule from DTRSchedule table
        $schedule = DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();
        // Initialize default values
        $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('07:00:00');
        $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('18:30:00');
        $lateThreshold = $carbonDate->copy()->setTimeFromTimeString('09:30:00');
        $location = 'Onsite';
        // Set values for Work From Home (WFH) days
        if ($schedule) {
            $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
            if (in_array($dayOfWeek, $wfhDays)) {
                $location = 'WFH';
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('08:00:00');
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('17:00:00');
                $lateThreshold = $defaultStartTime;
            } else {
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_start_time);
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
            }
        }

        // Adjust late threshold for Monday and not WFH
        if ($dayOfWeek === 'Monday' &&  $location !== 'WFH' ) {
            $lateThreshold = $carbonDate->copy()->setTimeFromTimeString('09:00:00');
        }

        // Determine location
        // Default value
        // Initialize variables for actual punches and calculated times
        $actualMorningIn = null;
        $actualMorningOut = null;
        $actualAfternoonIn = null;
        $actualAfternoonOut = null;
        $morningIn = null;
        $morningOut = null;
        $afternoonIn = null;
        $afternoonOut = null;

        // Filter for morning in (before 12:00)
        $morningInTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $transaction->punch_state == 0 && $time->hour < 12;
        });

        // Only process morning out if there's a morning in
        if ($morningInTransactions->isNotEmpty()) {
            $actualMorningIn = Carbon::parse($morningInTransactions->first()->punch_time);
            $morningIn = $actualMorningIn->lt($defaultStartTime) ? $defaultStartTime : $actualMorningIn;

            // Filter for morning out (can be after 12:00, but must be after morning in)
            $morningOutTransactions = $transactions->filter(function ($transaction) use ($actualMorningIn) {
                $time = Carbon::parse($transaction->punch_time);
                return $transaction->punch_state == 1 && $time->gt($actualMorningIn) && $time->hour < 13;  // Ensure morning out is before 13:00
            });

            if ($morningOutTransactions->isNotEmpty()) {
                $actualMorningOut = Carbon::parse($morningOutTransactions->last()->punch_time);
                // Limit morning out to 13:00
                $morningOut = min($actualMorningOut, Carbon::parse($date)->setTime(13, 0, 0));
            }
        }

        // Filter for afternoon in (after 12:00, or first in if no morning in)
        $afternoonInTransactions = $transactions->filter(function ($transaction) use ($morningInTransactions, $morningOut) {
            $time = Carbon::parse($transaction->punch_time);
            if ($morningInTransactions->isEmpty()) {
                return $transaction->punch_state == 0 && $time->hour >= 12;  // Ensure it's afternoon (12:00 or later)
            }
            return $transaction->punch_state == 0 && $time->gt($morningOut ?? $time->copy()->setTime(12, 0, 0));
        });

        // Filter for afternoon out (after 13:00 to avoid overlap with morning)
        $afternoonOutTransactions = $transactions->filter(function ($transaction) use ($afternoonInTransactions) {
            $time = Carbon::parse($transaction->punch_time);
            return $transaction->punch_state == 1 && $time->hour >= 13 && $transaction->id > ($afternoonInTransactions->first()->id ?? 0);
        });

        // Process afternoon transactions
        if ($afternoonInTransactions->isNotEmpty()) {
            $actualAfternoonIn = Carbon::parse($afternoonInTransactions->first()->punch_time);
            $afternoonIn = $actualAfternoonIn;
        }
        if ($afternoonOutTransactions->isNotEmpty()) {
            $actualAfternoonOut = Carbon::parse($afternoonOutTransactions->last()->punch_time);
            $afternoonOut = $actualAfternoonOut;
        }


        $lunchBreakStart = $carbonDate->copy()->setTimeFromTimeString('12:00:00');
        $lunchBreakEnd = $carbonDate->copy()->setTimeFromTimeString('13:00:00');

        $totalMinutesRendered = 0;
        $expectedEndTime = $defaultEndTime;

        // Calculate total minutes rendered
        if ($morningIn && $morningOut) {
            $morningEnd = min($lunchBreakStart, $morningOut);
            $totalMinutesRendered += max(0, $morningIn->diffInMinutes($morningEnd));
        }

        if ($afternoonIn && $afternoonOut) {
            $afternoonStart = max($lunchBreakEnd, $afternoonIn);
            $afternoonEnd = min($defaultEndTime, $afternoonOut);
            $totalMinutesRendered += max(0, $afternoonStart->diffInMinutes($afternoonEnd));
        }

        $totalHoursRendered = Carbon::createFromTime(0, 0, 0)->addMinutes($totalMinutesRendered)->format('H:i');

        // Calculate expected time out based on first time in
        if ($morningIn) {
            // Calculate expected end time based on morning in time
            $expectedEndTime = $morningIn->copy()->addHours(9);
            if ($expectedEndTime->gt($defaultEndTime)) {
                $expectedEndTime = $defaultEndTime;
            }
        } elseif ($afternoonIn) {
            // Use afternoon in time if no morning in time is available
            if ($afternoonIn->lt($afternoonIn->copy()->setTime(13, 0))) {
                // If afternoon in is earlier than 13:00, set expected end time to 17:00
                $expectedEndTime = $carbonDate->copy()->setTime(17, 0);
            } elseif ($afternoonIn->gt($afternoonIn->copy()->setTime(14, 30))) {
                // If afternoon in is later than 14:30, use default end time
                $expectedEndTime = $defaultEndTime;
            } else {
                // Otherwise, expected end time is afternoon in + 4 hours
                $expectedEndTime = $afternoonIn->copy()->addHours(4);
                if ($expectedEndTime->gt($defaultEndTime)) {
                    $expectedEndTime = $defaultEndTime;
                }
            }
        }    

        // Calculate lateness
        $late = Carbon::createFromTime(0, 0, 0);
        $lunchEnd = $carbonDate->copy()->setTimeFromTimeString('13:00:00');
        if ($morningIn) {
            if ($morningIn->gt($lateThreshold)) {
                // Set expected time out to default end time if time in is greater than late threshold
                $expectedEndTime = $defaultEndTime;
            }
            if ($morningIn->gt($lateThreshold)) {
                $late = $late->addMinutes($morningIn->diffInMinutes($lateThreshold));
            }
        } else{
            $late = $late->addHours(4);
            if ($afternoonIn && $afternoonIn->gt($lunchEnd)){
                $late = $late->addMinutes($lunchEnd->diffInMinutes($afternoonIn));
            }
        }
        // Calculate undertime
        $undertime = Carbon::createFromTime(0, 0, 0);
        $lunchTime = $carbonDate->copy()->setTimeFromTimeString('12:00:00');

        if ($afternoonOut) {
            if ($afternoonOut->lt($expectedEndTime)) {
                $undertime = $undertime->addMinutes($expectedEndTime->diffInMinutes($afternoonOut));
            }
        } else {
            // If there's no afternoon out, add 4 hours of undertime
            $undertime = $undertime->addHours(4);
        }
        // Check if morning out is before 12:00
        if ($morningOut && $morningOut->lt($lunchTime)) {
            $undertime = $undertime->addMinutes($lunchTime->diffInMinutes($morningOut));
        }

        // Add undertime to lateness
        $late->addMinutes($undertime->diffInMinutes($carbonDate->copy()->setTimeFromTimeString('00:00:00')));

        // Calculate overtime if applicable
        $overtime = Carbon::createFromTime(0, 0, 0);
        if ($afternoonOut && $afternoonOut->gt($defaultEndTime)) {
            $overtime = $overtime->addMinutes($afternoonOut->diffInMinutes($defaultEndTime));
        }

        // Convert to time format
        $lateFormatted = $late->format('H:i');
        $undertimeFormatted = $undertime->format('H:i');
        $overtimeFormatted = $overtime->format('H:i');

        // Initialize remarks
        $remarks = '';

        // Add specific remarks for Saturday and Sunday
        if ($dayOfWeek === 'Saturday') {
            $remarks = 'Saturday';
        } elseif ($dayOfWeek === 'Sunday') {
            $remarks = 'Sunday';
        }


        // Adjust remarks based on presence or lateness
        if (!$actualMorningIn && !$actualAfternoonIn) {
            $remarks = 'Absent';
        } elseif ($lateFormatted !== '00:00') {
            $remarks = 'Late/Undertime';
        }
        // Check for holiday or leave
        $holiday = Holiday::whereDate('holiday_date', $date)->first();
        if ($holiday) {
            $remarks = 'Holiday';
        }
        $isOnLeave = $approvedLeaves->isNotEmpty();
        if ($isOnLeave) {
            $remarks = 'Leave';
        }

        return [
            'day_of_week' => $dayOfWeek,
            'location' => $location,
            'morning_in' => $actualMorningIn ? $actualMorningIn->format('H:i:s') : null,
            'morning_out' => $actualMorningOut ? $actualMorningOut->format('H:i:s') : null,
            'afternoon_in' => $actualAfternoonIn ? $actualAfternoonIn->format('H:i:s') : null,
            'afternoon_out' => $actualAfternoonOut ? $actualAfternoonOut->format('H:i:s') : null,
            'calculated_morning_in' => $morningIn ? $morningIn->format('H:i:s') : null,
            'late' => $lateFormatted,
            'overtime' => $overtimeFormatted,
            'total_hours_rendered' => $totalHoursRendered,
            'remarks' => $remarks,
        ];
    }
}
