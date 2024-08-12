<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DtrTable extends Component
{
    use WithPagination;

    public $searchTerm = '';
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

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

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
        $query = EmployeesDtr::query()->where('user_id', Auth::id());

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('date', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('day_of_week', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('location', 'like', '%'.$this->searchTerm.'%');
            });
        }

        if ($this->startDate) {
            $query->where('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('date', '<=', $this->endDate);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $dtrs = $query->paginate(30);

        return view('livewire.user.dtr-table', ['dtrs' => $dtrs]);
    }
    public function updateRemarks($dtrId, $remarks)
    {
        // Find the record by its ID
        $dtr = EmployeesDtr::find($dtrId);

        if ($dtr) {
            // Update the remarks field
            $dtr->remarks = $remarks;
            $dtr->save();

            // Dispatch SweetAlert notification
            $this->dispatch('swal', [
                'title' => 'Remarks updated successfully!',
                'icon' => 'success'
            ]);
        } else {
            // Dispatch SweetAlert notification for error
            $this->dispatch('swal', [
                'title' => 'Record not found!',
                'icon' => 'error'
            ]);
        }
    }

    public function exportToPdf($signatoryName)
    {
        $query = EmployeesDtr::query()
            ->where('user_id', Auth::id())
            ->whereBetween('date', [$this->startDate, $this->endDate]);

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('date', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('day_of_week', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('location', 'like', '%'.$this->searchTerm.'%');
            });
        }

        // Group by employee name
        $dtrs = $query->orderBy('date')->get()->groupBy('employee_name'); // Assuming 'employee_name' is a valid column

        if ($dtrs->isEmpty()) {
            $this->dispatch('swal', [
                'title' => 'No DTR records found for the selected date range.',
                'icon' => 'error'
            ]);
            return;
        }

        $pdf = Pdf::loadView('pdf.dtr', [
            'dtrs' => $dtrs,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'signatoryName' => $signatoryName,
            'userName' => Auth::user()->name,
            'empCode' => Auth::user()->emp_code,
        ]);

        $this->dispatch('swal', [
            'title' => 'DTR Exported Successfully!',
            'icon' => 'success'
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'dtr_report.pdf');
    }

}
