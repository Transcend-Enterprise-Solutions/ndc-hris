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

        $schedule = DTRSchedule::where('emp_code', $empCode)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->first();

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

        // Check if the date is a holiday
        $holiday = Holiday::whereDate('holiday_date', $date)->first();
        if ($holiday) {
            return [
                'day_of_week' => $dayOfWeek,
                'location' => $location,
                'morning_in' => null,
                'morning_out' => null,
                'afternoon_in' => null,
                'afternoon_out' => null,
                'late' => '00:00',
                'overtime' => '00:00',
                'total_hours_rendered' => '08:00',
                'remarks' => 'Holiday',
            ];
        }

        // Check if the date is within an approved leave period
        $isOnLeave = $approvedLeaves->isNotEmpty();

        if ($isOnLeave) {
            return [
                'day_of_week' => $dayOfWeek,
                'location' => $location,
                'morning_in' => null,
                'morning_out' => null,
                'afternoon_in' => null,
                'afternoon_out' => null,
                'late' => '00:00',
                'overtime' => '00:00',
                'total_hours_rendered' => '00:00',
                'remarks' => 'Leave',
            ];
        }

        $latenessThreshold = $dayOfWeek === 'Monday' ? '09:00:00' : '09:30:00';
        $latenessThreshold = ($location === 'WFH') ? '08:00:00' : $latenessThreshold;
        $latenessThresholdTime = $carbonDate->copy()->setTimeFromTimeString($latenessThreshold);

        $sortedTransactions = $transactions->sortBy('punch_time');

        $morningIn = null;
        $morningOut = null;
        $afternoonIn = null;
        $afternoonOut = null;

        foreach ($sortedTransactions as $transaction) {
            $time = Carbon::parse($transaction->punch_time);

            if (!$morningIn) {
                $morningIn = $time;
            } elseif (!$morningOut && $time->hour <= 13) {
                $morningOut = $time;
            } elseif (!$afternoonIn) {
                $afternoonIn = $time;
            } elseif (!$afternoonOut) {
                $afternoonOut = $time;
            }
        }

        // Correct any misalignments
        if ($afternoonIn && $afternoonOut && $afternoonIn->gt($afternoonOut)) {
            // Swap afternoon in and out if they're in the wrong order
            $temp = $afternoonIn;
            $afternoonIn = $afternoonOut;
            $afternoonOut = $temp;
        }

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
        $undertime = max(0, $requiredMinutes - $totalMinutesRendered);

        $late = max($late, $undertime);
        $totalMinutesRendered = min($totalMinutesRendered, $requiredMinutes);

        $formatTime = function($minutes) {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            return sprintf('%02d:%02d', $hours, $remainingMinutes);
        };

        $remarks = '';
        if (in_array($dayOfWeek, ['Saturday', 'Sunday'])) {
            $remarks = $dayOfWeek;
        } elseif ($transactions->isEmpty()) {
            $remarks = 'Absent';
        } elseif ($late > 0) {
            $remarks = 'Late';
        } else {
            $remarks = 'Present';
        }

        return [
            'day_of_week' => $dayOfWeek,
            'location' => $location,
            'morning_in' => $morningIn ? $morningIn->format('H:i:s') : null,
            'morning_out' => $morningOut ? $morningOut->format('H:i:s') : null,
            'afternoon_in' => $afternoonIn ? $afternoonIn->format('H:i:s') : null,
            'afternoon_out' => $afternoonOut ? $afternoonOut->format('H:i:s') : null,
            'late' => $formatTime($late),
            'overtime' => $formatTime($overtime),
            'total_hours_rendered' => $formatTime($totalMinutesRendered),
            'remarks' => $remarks,
        ];
    }
}
