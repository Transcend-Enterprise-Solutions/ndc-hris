<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class WfhManagementTable extends Component
{
    public $registeredLatitude;
    public $registeredLongitude;
    
    public function render()
    {
        return view('livewire.admin.wfh-management-table');
    }
}
