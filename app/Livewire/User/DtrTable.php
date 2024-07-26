<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use Illuminate\Support\Facades\Auth;

class DtrTable extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function render()
    {
        $query = EmployeesDtr::query()->where('emp_code', Auth::user()->emp_code);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        $dtrs = $query->orderBy('date', 'asc')->paginate(16);

        return view('livewire.user.dtr-table', [
            'dtrs' => $dtrs,
        ]);
    }
}
