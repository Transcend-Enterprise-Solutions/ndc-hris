<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveCredits;
use App\Models\LeaveCreditsCalculation;
use Carbon\Carbon;

class TransferLeaveCredits extends Command
{
    protected $signature = 'leave-credits:transfer';
    protected $description = 'Transfer leave credits from leave_credits_calculation to leave_credits for the current month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Fetch leave credits calculation for the current month
        $leaveCreditsCalculations = LeaveCreditsCalculation::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->get();

        foreach ($leaveCreditsCalculations as $calculation) {
            $userId = $calculation->user_id;
            $leaveCreditsEarned = $calculation->leave_credits_earned;
            $totalCreditsEarned = $calculation->total_credits_earned;

            // Fetch or create LeaveCredits record for the user
            $leaveCredits = LeaveCredits::firstOrCreate(
                ['user_id' => $userId],
                [
                    'vl_total_credits' => 0,
                    'sl_total_credits' => 0,
                    'vl_claimable_credits' => 0,
                    'sl_claimable_credits' => 0,
                    'vl_claimed_credits' => 0,
                    'sl_claimed_credits' => 0,
                ]
            );

            // Update total credits
            $leaveCredits->vl_total_credits += $leaveCreditsEarned;
            $leaveCredits->sl_total_credits += $leaveCreditsEarned;

            // Subtract total_credits_earned from claimable credits
            $leaveCredits->vl_claimable_credits = max(0, $leaveCredits->vl_claimable_credits + $leaveCreditsEarned - $totalCreditsEarned);
            $leaveCredits->sl_claimable_credits = max(0, $leaveCredits->sl_claimable_credits + $leaveCreditsEarned - $totalCreditsEarned);

            // Add subtracted credits to claimed credits
            $leaveCredits->vl_claimed_credits += min($totalCreditsEarned, $leaveCredits->vl_claimable_credits + $leaveCreditsEarned);
            $leaveCredits->sl_claimed_credits += min($totalCreditsEarned, $leaveCredits->sl_claimable_credits + $leaveCreditsEarned);

            $leaveCredits->save();
        }

        $this->info('Leave credits have been successfully transferred and adjusted for the current month.');
    }
}