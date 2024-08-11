<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\LeaveCredits;
use Illuminate\Support\Facades\Auth;

class LeaveCreditsTable extends Component
{
    public function render()
    {
        $leaveCredits = LeaveCredits::where('user_id', Auth::id())->first();

        return view('livewire.user.leave-credits-table', [
            'total_credits' => $leaveCredits->total_credits ?? 0,
            'vl_claimable_credits' => $leaveCredits->vl_claimable_credits ?? 0,
            'vl_claimed_credits' => $leaveCredits->vl_claimed_credits ?? 0,
            'sl_claimable_credits' => $leaveCredits->sl_claimable_credits ?? 0,
            'sl_claimed_credits' => $leaveCredits->sl_claimed_credits ?? 0,
            'spl_claimable_credits' => $leaveCredits->spl_claimable_credits ?? 0,
            'spl_claimed_credits' => $leaveCredits->spl_claimed_credits ?? 0,
        ]);
    }
}
