<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class EmployeeTable extends Component
{
    public  $test = "Test test";
    public function render()
    {
        return view('livewire.admin.employee-table', [
            'test' => $this->test,
        ]);
    }
}
