<?php
namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use App\Models\DTRSchedule;
use App\Models\EmployeesDtr;
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
            $today = Carbon::now();
            $startDate = $today->copy()->startOfMonth();
            $endDate = $today->copy()->endOfMonth();

            echo "Processing month from {$startDate->toDateString()} to {$endDate->toDateString()}\n";
            Log::info("Processing month from {$startDate->toDateString()} to {$endDate->toDateString()}");

            $users = User::all();

            foreach ($users as $user) {
                echo "Processing user: {$user->emp_code}\n";
                Log::info("Processing user: {$user->emp_code}");

                $transactions = Transaction::where('emp_code', $user->emp_code)
                    ->whereBetween('punch_time', [$startDate, $endDate])
                    ->orderBy('punch_time')
                    ->get();

                echo "Total transactions found for user {$user->emp_code}: " . $transactions->count() . "\n";
                Log::info("Total transactions found for user {$user->emp_code}: " . $transactions->count());

                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $dateString = $currentDate->toDateString();
                    $dateTransactions = $transactions->filter(function ($transaction) use ($dateString) {
                        return Carbon::parse($transaction->punch_time)->toDateString() === $dateString;
                    });

                    echo "Processing date: {$dateString} for user: {$user->emp_code}\n";
                    Log::info("Processing date: {$dateString} for user: {$user->emp_code}");
                    echo "Transactions for this date: " . $dateTransactions->count() . "\n";
                    Log::info("Transactions for this date: " . $dateTransactions->count());

                    $calculatedData = $this->calculateTimeRecords($dateTransactions, $user->emp_code, $dateString);
                    echo "Calculated data for user {$user->emp_code} on {$dateString}: " . json_encode($calculatedData) . "\n";
                    Log::info("Calculated data for user {$user->emp_code} on {$dateString}: " . json_encode($calculatedData));

                    try {
                        $record = EmployeesDtr::updateOrCreate(
                            ['user_id' => $user->id, 'date' => $dateString],
                            array_merge(['emp_code' => $user->emp_code], $calculatedData)
                        );
                        echo "DTR record saved/updated for user {$user->emp_code} on {$dateString}. Record ID: " . $record->id . "\n";
                        Log::info("DTR record saved/updated for user {$user->emp_code} on {$dateString}. Record ID: " . $record->id);
                    } catch (\Exception $e) {
                        echo "Error saving DTR record for user {$user->emp_code} on {$dateString}: " . $e->getMessage() . "\n";
                        Log::error("Error saving DTR record for user {$user->emp_code} on {$dateString}: " . $e->getMessage());
                    }

                    $currentDate->addDay();
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


    private function calculateTimeRecords($transactions, $empCode, $date)
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

        $latenessThreshold = $dayOfWeek === 'Monday' ? '09:00:00' : '09:30:00';
        $latenessThreshold = ($location === 'WFH') ? '08:00:00' : $latenessThreshold;
        $latenessThresholdTime = $carbonDate->copy()->setTimeFromTimeString($latenessThreshold);

        $morningTransactions = $transactions->filter(function ($transaction) {
            return Carbon::parse($transaction->punch_time)->hour < 13;
        });
        $afternoonTransactions = $transactions->filter(function ($transaction) {
            return Carbon::parse($transaction->punch_time)->hour >= 13;
        });

        $morningIn = $this->getFirstInPunch($morningTransactions);
        $morningOut = $this->getLastOutPunch($morningTransactions);
        $afternoonIn = $this->getFirstInPunch($afternoonTransactions);
        $afternoonOut = $this->getLastOutPunch($afternoonTransactions);

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

    private function getFirstInPunch($transactions)
    {
        $firstIn = $transactions->where('punch_state', '0')->sortBy('punch_time')->first();
        return $firstIn ? Carbon::parse($firstIn->punch_time) : null;
    }

    private function getLastOutPunch($transactions)
    {
        $lastOut = $transactions->where('punch_state', '1')->sortByDesc('punch_time')->first();
        return $lastOut ? Carbon::parse($lastOut->punch_time) : null;
    }
}
