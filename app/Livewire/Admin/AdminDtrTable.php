<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use Illuminate\Support\Facades\DB;

class AdminDtrTable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $startDate;
    public $endDate;
    public $sortField = 'date';
    public $sortDirection = 'asc';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function render()
    {
        $query = EmployeesDtr::query()
            ->join('users', 'employees_dtr.user_id', '=', 'users.id')
            ->select('employees_dtr.*', 'users.name as user_name', 'users.emp_code');

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('users.emp_code', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('users.name', 'like', '%'.$this->searchTerm.'%');
            });
        }

        if ($this->startDate) {
            $query->where('employees_dtr.date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('employees_dtr.date', '<=', $this->endDate);
        }

        if ($this->sortField === 'date') {
            $query->orderBy('employees_dtr.date', $this->sortDirection)
                  ->orderBy('users.name', 'asc');
        } elseif ($this->sortField === 'user.name') {
            $query->orderBy('users.name', $this->sortDirection);
        } elseif ($this->sortField === 'emp_code') {
            $query->orderBy('users.emp_code', $this->sortDirection);
        } else {
            $query->orderBy('employees_dtr.' . $this->sortField, $this->sortDirection);
        }

        $dtrs = $query->paginate(30);

        return view('livewire.admin.admin-dtr-table', ['dtrs' => $dtrs]);
    }
}
