<?php

namespace App\Livewire\EmployeeManagement\Table;

use Livewire\Component;

class EmployeeTable extends Component
{
    public  $test = "Test test";
    public function render()
    {
        return view('livewire.employee-management.table.employee-table', [
            'test' => $this->test,
        ]);
    }
}
