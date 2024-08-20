<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeesLeaves;
use App\Models\User;
use Carbon\Carbon;

class ResetLeaveBalances extends Command
{
    protected $signature = 'leaves:reset';

    protected $description = 'Reset leave balances for all employees after one year';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            EmployeesLeaves::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'paternity' => 7,
                    'study' => Carbon::now()->addMonths(6)->diffInDays(Carbon::now()), // 6 months to days
                    'maternity' => 105,
                    'solo_parent' => 7,
                    'vawc' => 10,
                    'rehabilitation' => Carbon::now()->addMonths(6)->diffInDays(Carbon::now()), // 6 months to days
                    'leave_for_women' => Carbon::now()->addMonths(2)->diffInDays(Carbon::now()), // 2 months to days
                    'emergency_leave' => 5,
                ]
            );
        }

        $this->info('Leave balances have been reset successfully!');
    }
}
