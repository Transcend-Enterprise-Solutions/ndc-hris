<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class AdminDtrTable extends Component
{
    use WithPagination;

    public $searchTerm;
    public $startDate;
    public $endDate;
    public $sortField = 'date';
    public $sortDirection = 'asc';
    public $signatoryName='';
    public $eSignaturePath='';

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

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex w-full flex-col gap-2">
            <livewire:skeleton/>
        </div>
        HTML;
    }

    public function render()
    {

        //sleep(3);
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

    public function exportToPdf($signatoryName)
    {
        $this->signatoryName = $signatoryName;
        $query = EmployeesDtr::query()
            ->join('users', 'employees_dtr.user_id', '=', 'users.id')
            ->select('employees_dtr.*', 'users.name as user_name', 'users.emp_code')
            ->whereBetween('employees_dtr.date', [$this->startDate, $this->endDate]);

        // Apply the search term if it's set
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('users.emp_code', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('users.name', 'like', '%'.$this->searchTerm.'%');
            });
        }

        $dtrs = $query->orderBy('users.name')
                      ->orderBy('employees_dtr.date')
                      ->get()
                      ->groupBy('user_name');

        $pdf = Pdf::loadView('pdf.dtr', [
            'dtrs' => $dtrs,
            'startDate' => $this->startDate,
            'eSignaturePath' => $this->eSignaturePath,
            'endDate' => $this->endDate,
            'signatoryName' => $this->signatoryName,
        ]);
        $this->dispatch('swal', [
            'title' => 'DTR Exported Successfully!',
            'icon' => 'success'
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'dtr_report.pdf');

    }
    public function downloadFile($dtrId)
    {
        $dtr = EmployeesDtr::find($dtrId);
        if ($dtr && $dtr->attachment) {
            $originalExtension = pathinfo($dtr->attachment, PATHINFO_EXTENSION);
            $friendlyFilename = "DTR_" . $dtr->date . "." . $originalExtension;
            return Storage::download($dtr->attachment, $friendlyFilename);
        } else {
            $this->dispatch('swal', [
                'title' => 'File not found!',
                'icon' => 'error'
            ]);
        }
    }


}
