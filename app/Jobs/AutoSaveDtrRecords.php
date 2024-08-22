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

        // Set values for Work From Home (WFH) days
        if ($schedule) {
            $wfhDays = array_map('ucfirst', array_map('trim', explode(',', $schedule->wfh_days)));
            if (in_array($dayOfWeek, $wfhDays)) {
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString('08:00:00');
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString('17:00:00');
                $lateThreshold = $defaultStartTime;
            } else {
                $defaultStartTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_start_time);
                $defaultEndTime = $carbonDate->copy()->setTimeFromTimeString($schedule->default_end_time);
            }
        }

        // Adjust late threshold for Monday
        if ($dayOfWeek === 'Monday') {
            $lateThreshold = $carbonDate->copy()->setTimeFromTimeString('09:00:00');
        }

        // Check for holiday or leave
        $holiday = Holiday::whereDate('holiday_date', $date)->first();
        if ($holiday) {
            return [
                'day_of_week' => $dayOfWeek,
                'location' => 'Onsite', // Assuming Onsite for holidays
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

        $isOnLeave = $approvedLeaves->isNotEmpty();
        if ($isOnLeave) {
            return [
                'day_of_week' => $dayOfWeek,
                'location' => 'Onsite', // Assuming Onsite for leaves
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

        // Determine location
        $location = 'Onsite'; // Default value, adjust as necessary based on your application

        // Initialize variables for punches
        $morningIn = null;
        $morningOut = null;
        $afternoonIn = null;
        $afternoonOut = null;

        $morningTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $time->hour < 13 || ($time->hour == 13 && $time->minute == 0);
        });

        $afternoonTransactions = $transactions->filter(function ($transaction) {
            $time = Carbon::parse($transaction->punch_time);
            return $time->hour >= 12;
        });

        $morningIns = $morningTransactions->where('punch_state', 0);
        $morningOuts = $morningTransactions->where('punch_state', 1);

        if ($morningIns->isNotEmpty()) {
            $morningIn = Carbon::parse($morningIns->first()->punch_time);
            $morningIn = $morningIn->lt($defaultStartTime) ? $defaultStartTime : $morningIn;
        }
        if ($morningOuts->isNotEmpty()) {
            $morningOut = Carbon::parse($morningOuts->last()->punch_time);
        }

        $afternoonIns = $afternoonTransactions->where('punch_state', 0);
        $afternoonOuts = $afternoonTransactions->where('punch_state', 1);

        if ($afternoonIns->isNotEmpty()) {
            $afternoonIn = Carbon::parse($afternoonIns->first()->punch_time);
        }
        if ($afternoonOuts->isNotEmpty()) {
            $afternoonOut = Carbon::parse($afternoonOuts->last()->punch_time);
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
            $expectedEndTime = $morningIn->copy()->addHours(9);
            if ($expectedEndTime->gt($defaultEndTime)) {
                $expectedEndTime = $defaultEndTime;
            }
        }

        // Calculate lateness
        $late = Carbon::createFromTime(0, 0, 0);
        if ($morningIn) {
            if ($morningIn->gt($lateThreshold)) {
                // Set expected time out to default end time if time in is greater than late threshold
                $expectedEndTime = $defaultEndTime;
            }
            if ($morningIn->gt($lateThreshold)) {
                $late = $late->addMinutes($morningIn->diffInMinutes($lateThreshold));
            }
        }

        // Calculate undertime
        $undertime = Carbon::createFromTime(0, 0, 0);
        if ($afternoonOut && $afternoonOut->lt($expectedEndTime)) {
            $undertime = $undertime->addMinutes($expectedEndTime->diffInMinutes($afternoonOut));
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
        if (!$morningIn && !$afternoonIn) {
            $remarks = 'Absent';
        } elseif ($lateFormatted !== '00:00') {
            $remarks = 'Late';
        }

        return [
            'day_of_week' => $dayOfWeek,
            'location' => $location,
            'morning_in' => $morningIn ? $morningIn->format('H:i:s') : null,
            'morning_out' => $morningOut ? $morningOut->format('H:i:s') : null,
            'afternoon_in' => $afternoonIn ? $afternoonIn->format('H:i:s') : null,
            'afternoon_out' => $afternoonOut ? $afternoonOut->format('H:i:s') : null,
            'late' => $lateFormatted,
            'overtime' => $overtimeFormatted,
            'total_hours_rendered' => $totalHoursRendered,
            'remarks' => $remarks,
        ];
    }

}
