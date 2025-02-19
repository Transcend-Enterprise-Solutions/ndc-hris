<?php
namespace App\Jobs;

use App\Models\Transaction;
use App\Models\TransactionWFH;
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

                // Get the user's schedule for the current date
                $schedule = DTRSchedule::where('emp_code', $user->emp_code)
                    ->whereDate('start_date', '<=', $currentDate)
                    ->whereDate('end_date', '>=', $currentDate)
                    ->first();

                $isWFH = false;
                if ($schedule) {
                    $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
                    $dayOfWeek = Carbon::parse($currentDate)->format('l');
                    $isWFH = in_array($dayOfWeek, $wfhDays);
                }

                // Determine which transaction model to use
                $transactionModel = $isWFH ? TransactionWFH::class : Transaction::class;

                // Get the transactions for the current date
                $transactions = $transactionModel::where('emp_code', $user->emp_code)
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
        $lateThreshold = Carbon::createFromTimeString($schedule->default_start_time);

        // Initialize default values
        $location = 'Onsite';
        $isWFH = false;
        $morningIn = null;
        $morningOut = null;
        $afternoonIn = null;
        $afternoonOut = null;

        // Set values for Work From Home (WFH) days
        if ($schedule) {
            $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
            if (in_array($dayOfWeek, $wfhDays)) {
                $location = 'WFH';
                $isWFH = true;
            }

            // Use exact times from the schedule
            $defaultStartTime = Carbon::parse($date)->setTimeFromTimeString($schedule->default_start_time);
            $defaultEndTime = Carbon::parse($date)->setTimeFromTimeString($schedule->default_end_time);

            // If the user has morning in before 12:00, automate lunch break times
            $lunchBreakStart = $carbonDate->copy()->setTimeFromTimeString('12:00:00');
            $lunchBreakEnd = $carbonDate->copy()->setTimeFromTimeString('13:00:00');

            // Get the transactions for the current date
            $transactionModel = $isWFH ? TransactionWFH::class : Transaction::class;
            $transactions = $transactionModel::where('emp_code', $empCode)
                ->whereDate('punch_time', $date)
                ->orderBy('punch_time')
                ->get();

            // Check for morning in (before 12:00), otherwise assign it to afternoon in if it's after 12:00
            $morningInTransactions = $transactions->filter(function ($transaction) {
                $time = Carbon::parse($transaction->punch_time);
                return $transaction->punch_state == 0 && $time->hour < 12;
            });

            if ($morningInTransactions->isNotEmpty()) {
                $morningIn = Carbon::parse($morningInTransactions->first()->punch_time);
            } else {
                // If no morning in, check if the first punch is 12:00 PM or later, assign it to afternoon in
                $firstPunch = $transactions->first();
                if ($firstPunch) {
                    $firstPunchTime = Carbon::parse($firstPunch->punch_time);
                    if ($firstPunchTime->hour >= 12) {
                        $afternoonIn = $firstPunchTime; // Record as afternoon in
                    }
                }
            }

            // Automate morning out and afternoon in times based on punch
            if ($morningIn) {
                $morningOutTransactions = $transactions->filter(function ($transaction) use ($morningIn) {
                    $time = Carbon::parse($transaction->punch_time);
                    return $transaction->punch_state == 1 && $time->gt($morningIn) && $time->hour < 13;
                });

                if ($morningOutTransactions->isNotEmpty()) {
                    $morningOut = Carbon::parse($morningOutTransactions->last()->punch_time);
                }

                // Automate lunch break times if morning out is before 12
                if ($morningIn->lt($lunchBreakStart)) {
                    $morningOut = $morningOut ?? $lunchBreakStart; // Set lunch out time if no morning out
                    $afternoonIn = $lunchBreakEnd; // Automate afternoon in time
                }
            }

            // Only process afternoon in if there is no morning out
            if (!$morningOut && !$afternoonIn) {
                $afternoonInTransactions = $transactions->filter(function ($transaction) {
                    $time = Carbon::parse($transaction->punch_time);
                    return $transaction->punch_state == 0 && $time->hour >= 13;  // Afternoon in after 1 PM
                });

                if ($afternoonInTransactions->isNotEmpty()) {
                    $afternoonIn = Carbon::parse($afternoonInTransactions->first()->punch_time);
                }
            }

            // Filter for afternoon out
            $afternoonOutTransactions = $transactions->filter(function ($transaction) {
                $time = Carbon::parse($transaction->punch_time);
                return $transaction->punch_state == 1 && $time->hour >= 13;
            });

            if ($afternoonOutTransactions->isNotEmpty()) {
                $afternoonOut = Carbon::parse($afternoonOutTransactions->last()->punch_time);
            }
        }

        // Calculate total hours rendered
        $totalMinutesRendered = 0;
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

        // Calculate lateness based on the schedule start time
        $late = Carbon::createFromTime(0, 0, 0);

        // If no morning in but there's afternoon in, add 4 hours to lateness
        if (!$morningIn && $afternoonIn) {
            $late = $late->addMinutes(4 * 60); // Automatically add 4 hours to lateness
        }

        // If morning in exists, calculate lateness based on default start time
        if ($morningIn && $morningIn->gt($lateThreshold)) {
            $late = $late->addMinutes($morningIn->diffInMinutes($lateThreshold));
        }

        // Add 4 hours to lateness if no afternoon out exists but there is a morning in
        if ($morningIn && !$afternoonOut) {
            $late = $late->addMinutes(4 * 60); // Add 4 hours
        }

        // Handle lateness for afternoon in if there's no morning in
        if (!$morningIn && $afternoonIn) {
            // Only calculate lateness from 13:00 onward
            $afternoonThreshold = Carbon::parse($date)->setTime(13, 0, 0); // 13:00 threshold for afternoon
            if ($afternoonIn->gt($afternoonThreshold)) {
                $late = $late->addMinutes($afternoonIn->diffInMinutes($afternoonThreshold));
            }
        }

        // Calculate overtime and undertime based on the schedule end time
        $undertime = Carbon::createFromTime(0, 0, 0);
        $overtime = Carbon::createFromTime(0, 0, 0);

        if ($afternoonOut && $afternoonOut->gt($defaultEndTime)) {
            $overtime = $overtime->addMinutes($afternoonOut->diffInMinutes($defaultEndTime));
        } elseif ($afternoonOut && $afternoonOut->lt($defaultEndTime)) {
            $undertime = $undertime->addMinutes($defaultEndTime->diffInMinutes($afternoonOut));
        }

        // Format output values
        $lateFormatted = $late->format('H:i');
        $undertimeFormatted = $undertime->format('H:i');
        $overtimeFormatted = $overtime->format('H:i');

        // Remarks logic
        $remarks = '';
        if (!$morningIn && !$afternoonIn) {
            $remarks = 'Absent';
        } elseif (($morningIn && !$morningOut) || ($afternoonIn && !$afternoonOut)) {
            $remarks = 'Incomplete';
        } elseif ($lateFormatted !== '00:00') {
            $remarks = 'Late/Undertime';
        } else {
            $remarks = 'Present';
        }

        // Check for holidays or leaves
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
            'morning_in' => $morningIn ? $morningIn->format('H:i:s') : null,
            'morning_out' => $morningOut ? $morningOut->format('H:i:s') : null,
            'afternoon_in' => $afternoonIn ? $afternoonIn->format('H:i:s') : null,
            'afternoon_out' => $afternoonOut ? $afternoonOut->format('H:i:s') : null,
            'total_hours_rendered' => $totalHoursRendered,
            'late' => $lateFormatted,
            'overtime' => $overtimeFormatted,
            'remarks' => $remarks,
        ];
    }



}
