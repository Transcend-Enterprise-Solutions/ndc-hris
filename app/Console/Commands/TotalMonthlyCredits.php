<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveCreditsCalculation;
use App\Models\LeaveCredits;
use App\Models\MonthlyCredits;
use Carbon\Carbon;
use DB;

class TotalMonthlyCredits extends Command
{
    // Define the signature of the Artisan command
    protected $signature = 'credits:calculate-monthly';

    // Command description
    protected $description = 'Calculate and update monthly VL and SL credits for all users using leave_credits_earned value.';

    public function handle()
    {
        // Fetch distinct combinations of users, months, and years from the leave_credits_calculation table
        $creditsEntries = LeaveCreditsCalculation::select('user_id', 'month', 'year')
            ->distinct()
            ->get();
    
        foreach ($creditsEntries as $entry) {
            $userId = $entry->user_id;
            $month = $entry->month;
            $year = $entry->year;
    
            // Step 1: Sum the leave_credits_earned for each user for the specific month and year
            $leaveCredits = LeaveCreditsCalculation::where('user_id', $userId)
                ->where('month', $month)
                ->where('year', $year)
                ->sum('leave_credits_earned');
    
            // Step 2: Check if vlbalance_brought_forward and slbalance_brought_forward should be added from the leave_credits table
            $leaveCreditsForwarded = LeaveCredits::where('user_id', $userId)
                ->whereYear('date_forwarded', $year)
                ->whereMonth('date_forwarded', Carbon::createFromFormat('Y-m', $year . '-' . $month)->month)
                ->where('credits_inputted', 1)
                ->first();
    
            $vlBroughtForward = $leaveCreditsForwarded ? $leaveCreditsForwarded->vlbalance_brought_forward : 0;
            $slBroughtForward = $leaveCreditsForwarded ? $leaveCreditsForwarded->slbalance_brought_forward : 0;
    
            if ($leaveCredits || $vlBroughtForward || $slBroughtForward) {
                // Step 3: Check if a record for this user, month, and year exists in the monthly_credits table
                $monthlyCredits = MonthlyCredits::firstOrCreate(
                    ['user_id' => $userId, 'month' => $month, 'year' => $year],
                    ['vl_latest_credits' => 0, 'sl_latest_credits' => 0]
                );
    
                // Step 4: Add leave_credits_earned to vl_latest_credits and sl_latest_credits
                $monthlyCredits->vl_latest_credits += $leaveCredits + $vlBroughtForward;
                $monthlyCredits->sl_latest_credits += $leaveCredits + $slBroughtForward;
    
                // Step 5: Subtract approved days from vl_latest_credits if conditions are met
                $approvedDays = DB::table('leave_application')
                    ->where('user_id', $userId)
                    ->where('remarks', 'With Pay')
                    ->where('status', 'Approved')
                    ->whereIn('type_of_leave', ['Vacation Leave']) // array of leave types
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('approved_days'); // Summing up approved days in the given month/year
    
                // Subtract approved days from VL credits if there are any
                if ($approvedDays > 0) {
                    $monthlyCredits->vl_latest_credits -= $approvedDays;
                }

                // Step 6: Subtract approved days for Sick Leave
                $approvedSickLeaveDays = DB::table('leave_application')
                    ->where('user_id', $userId)
                    ->where('remarks', 'With Pay')
                    ->where('status', 'Approved')
                    ->whereIn('type_of_leave', ['Sick Leave']) // Sick Leave type
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('approved_days');
    
                // Subtract approved Sick Leave days from SL credits if there are any
                if ($approvedSickLeaveDays > 0) {
                    $monthlyCredits->sl_latest_credits -= $approvedSickLeaveDays;
                }
    
                // Step 7: Save the updated credits
                $monthlyCredits->save();
    
                $this->info("Monthly credits updated for user_id: $userId for $month $year. Approved VL days deducted: $approvedVacationLeaveDays, Approved SL days deducted: $approvedSickLeaveDays.");
            } else {
                $this->info("No credits found for user_id: $userId for $month $year.");
            }
        }
    
        $this->info('Monthly credits calculation completed.');
    }
    
}
