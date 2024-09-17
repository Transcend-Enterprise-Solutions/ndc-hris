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
    protected $description = 'Resets spl claimable to 3, spl claimed to 0 and fl claimable to 5 at the start of each year';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Reset SPL credits and add 3 to VL claimable credits
        LeaveCredits::query()->update([
            'spl_claimable_credits' => 3,
            'spl_claimed_credits' => 0,
            'fl_claimable_credits' => 5,
        ]);

        $this->info('Leave credits have been reset successfully.');
        return Command::SUCCESS;
    }
}
