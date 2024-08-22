<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LeaveCredits;
use Livewire\WithPagination;

class AdminLeaveCreditsTable extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        // Ensure proper use of pagination
        $leaveCredits = LeaveCredits::with('user')
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.admin-leave-credits-table', [
            'leaveCredits' => $leaveCredits,
        ]);
    }
}


