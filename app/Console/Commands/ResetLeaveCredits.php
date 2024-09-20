<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeaveCredits;

class ResetLeaveCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave-credits:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets SPL claimable to 3, SPL claimed to 0, and FL claimable to 5 at the start of each year. Deducts remaining FL claimable credits from VL claimable credits if applicable.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all users' leave credits
        $leaveCreditsList = LeaveCredits::all();

        foreach ($leaveCreditsList as $leaveCredits) {
            // Check remaining FL claimable credits
            $remainingFL = $leaveCredits->fl_claimable_credits;

            // If there are FL credits left, deduct them from VL
            if ($remainingFL > 0) {
                $leaveCredits->vl_claimable_credits = max(0, $leaveCredits->vl_claimable_credits - $remainingFL);
            }

            // Reset SPL and FL credits
            $leaveCredits->spl_claimable_credits = 3;
            $leaveCredits->spl_claimed_credits = 0;
            $leaveCredits->fl_claimable_credits = 5;
            $leaveCredits->fl_claimed_credits = 0;

            // Save the updated leave credits
            $leaveCredits->save();
        }

        $this->info('Leave credits have been reset successfully, and remaining FL credits have been deducted from VL credits.');
        return Command::SUCCESS;
    }
}
