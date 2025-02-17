<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminLeaveRequest extends Component
{
    public $currentView = 'default';

    public function toggleView()
    {
        $this->currentView = $this->currentView === 'default' ? 'alternate' : 'default';
    }
    
    public function render()
    {
        return view('livewire.admin.admin-leave-request');
    }
}
