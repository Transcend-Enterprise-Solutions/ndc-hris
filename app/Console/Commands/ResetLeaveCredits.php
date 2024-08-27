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
    protected $description = 'Resets SPL claimable and claimed credits to 0 and adds 3 to VL claimable credits at the start of each year';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Reset SPL credits and add 3 to VL claimable credits
        LeaveCredits::query()->update([
            'spl_claimable_credits' => \DB::raw('spl_claimable_credits + 3'),
            'vl_claimable_credits' => \DB::raw('vl_claimable_credits + 3'),
        ]);

        $this->info('Leave credits have been reset successfully.');
        return Command::SUCCESS;
    }
}
