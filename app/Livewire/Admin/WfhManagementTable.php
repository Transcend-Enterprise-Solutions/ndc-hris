<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class WfhManagementTable extends Component
{
    public $latitude = null;
    public $longitude = null;
    public $registeredLatitude;
    public $registeredLongitude;

    public function render()
    {
        return view('livewire.admin.wfh-management-table');
    }
}
