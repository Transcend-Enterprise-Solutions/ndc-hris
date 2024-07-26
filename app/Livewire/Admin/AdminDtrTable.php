<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use App\Models\User;

class AdminDtrTable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $startDate;
    public $endDate;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function render()
    {
        $query = EmployeesDtr::query();

        if ($this->searchTerm) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('emp_code', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        $dtrs = $query->with('user')
                      ->orderBy('date', 'asc')
                      ->paginate(30);

        return view('livewire.admin.admin-dtr-table', [
            'dtrs' => $dtrs,
        ]);
    }
}
