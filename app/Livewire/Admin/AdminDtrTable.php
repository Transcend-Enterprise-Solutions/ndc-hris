<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use App\Models\OfficeDivisions;
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
    public $signatoryName = '';
    public $eSignaturePath = '';
    public $pageSize = 10;
    public $pageSizes = [10, 20, 30, 50, 100];

    public $selectedDivision = null;
    public $signName = '';
    public $signPos = '';
    public $showSignatoryModal = false;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'asc'],
        'pageSize' => ['except' => 30],
    ];

    public function openSignatoryModal($divisionId)
    {
        $this->selectedDivision = $divisionId;
        $division = OfficeDivisions::find($divisionId);

        if ($division) {
            $this->signName = $division->sign_name;
            $this->signPos = $division->sign_pos;
        }

        $this->showSignatoryModal = true;
    }

    public function saveSignatory()
    {
        $this->validate([
            'signName' => 'required',
            'signPos' => 'required',
            'selectedDivision' => 'required'
        ]);

        $division = OfficeDivisions::find($this->selectedDivision);
        $division->update([
            'sign_name' => $this->signName,
            'sign_pos' => $this->signPos
        ]);

        $this->showSignatoryModal = false;
        $this->dispatch('swal', [
            'title' => 'Signatory Updated Successfully!',
            'icon' => 'success'
        ]);
    }

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updatedPageSize()
    {
        $this->resetPage();
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
        $query = EmployeesDtr::query()
            ->join('users', 'employees_dtr.user_id', '=', 'users.id')
            ->join('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('employees_dtr.*', 'users.name as user_name',
                DB::raw("CASE
                    WHEN user_data.appointment = 'cos' THEN CONCAT('D-', SUBSTRING(users.emp_code, 2))
                    ELSE users.emp_code
                END as emp_code"),
                DB::raw("COALESCE(employees_dtr.up_remarks, employees_dtr.remarks) as effective_remarks")
            );

        // Apply search filter
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('users.emp_code', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('users.name', 'like', '%'.$this->searchTerm.'%');
            });
        }

        // Apply office division filter
        if ($this->selectedDivision) {
            $query->where('users.office_division_id', $this->selectedDivision);
        }

        // Apply date filters
        if ($this->startDate) {
            $query->where('employees_dtr.date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('employees_dtr.date', '<=', $this->endDate);
        }

        // Apply sorting
        if ($this->sortField === 'date') {
            $query->orderBy('employees_dtr.date', $this->sortDirection)
                  ->orderBy('users.name', 'asc');
        } elseif ($this->sortField === 'user.name') {
            $query->orderBy('users.name', $this->sortDirection);
        } elseif ($this->sortField === 'emp_code') {
            $query->orderByRaw("CASE
                WHEN user_data.appointment = 'cos' THEN CONCAT('D-', SUBSTRING(users.emp_code, 2))
                ELSE users.emp_code
            END " . $this->sortDirection);
        } else {
            $query->orderBy('employees_dtr.' . $this->sortField, $this->sortDirection);
        }

        $dtrs = $query->paginate($this->pageSize);
        $officeDivisions = OfficeDivisions::all();

        return view('livewire.admin.admin-dtr-table', [
            'dtrs' => $dtrs,
            'officeDivisions' => $officeDivisions
        ]);
    }

    public function exportToPdf()
    {
        $query = EmployeesDtr::query()
            ->join('users', 'employees_dtr.user_id', '=', 'users.id')
            ->join('user_data', 'users.id', '=', 'user_data.user_id')
            ->leftJoin('office_divisions', 'users.office_division_id', '=', 'office_divisions.id')
            ->select(
                'employees_dtr.*',
                'users.name as user_name',
                'office_divisions.sign_name',
                'office_divisions.sign_pos',
                DB::raw("CASE
                    WHEN user_data.appointment = 'cos' THEN CONCAT('D-', SUBSTRING(users.emp_code, 2))
                    ELSE users.emp_code
                END as emp_code"),
                DB::raw("COALESCE(employees_dtr.up_remarks, employees_dtr.remarks) as effective_remarks")
            )
            ->whereBetween('employees_dtr.date', [$this->startDate, $this->endDate]);

        // Apply search filter
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('users.emp_code', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('users.name', 'like', '%'.$this->searchTerm.'%');
            });
        }

        // Apply office division filter
        if ($this->selectedDivision) {
            $query->where('users.office_division_id', $this->selectedDivision);
        }

        $dtrs = $query->orderBy('users.name')
                      ->orderBy('employees_dtr.date')
                      ->get()
                      ->groupBy('user_name');

        // Transform the data to ensure effective_remarks is always available
        // and calculate summary data for each employee
        $dtrsWithSummary = [];

        foreach ($dtrs as $employeeName => $employeeDtrs) {
            // Make sure the effective_remarks attribute is accessible in the view
            $processedDtrs = $employeeDtrs->map(function ($dtr) {
                $dtr->effective_remarks = $dtr->effective_remarks ?? $dtr->remarks;
                return $dtr;
            });

            // Calculate summary statistics
            $daysWorked = $processedDtrs->filter(function($dtr) {
                return in_array($dtr->effective_remarks, ['Present', 'Late/Undertime']);
            })->count();

            $absences = $processedDtrs->filter(function($dtr) {
                return $dtr->effective_remarks === 'Absent';
            })->count();

            $leaveDays = $processedDtrs->filter(function($dtr) {
                return str_contains(strtolower($dtr->effective_remarks), 'leave');
            })->count();

            $holidays = $processedDtrs->filter(function($dtr) {
                return str_contains(strtolower($dtr->effective_remarks), 'holiday');
            })->count();

            // Calculate total overtime hours
            $totalOvertimeMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                if (!empty($dtr->overtime) && $dtr->overtime !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->overtime);
                    $totalOvertimeMinutes += ($hours * 60) + $minutes;
                }
            }
            $overtime = sprintf("%02d:%02d", floor($totalOvertimeMinutes / 60), $totalOvertimeMinutes % 60);

            // Calculate total tardiness in hours (changed from minutes to hours format)
            $totalTardinessMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                if (!empty($dtr->late) && $dtr->late != '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->late);
                    $totalTardinessMinutes += ($hours * 60) + $minutes;
                }
            }
            $tardiness = sprintf("%02d:%02d", floor($totalTardinessMinutes / 60), $totalTardinessMinutes % 60);

            // Store the DTRs and summary for this employee
            $dtrsWithSummary[$employeeName] = [
                'dtrs' => $processedDtrs,
                'summary' => [
                    'days_worked' => $daysWorked,
                    'absences' => $absences,
                    'overtime' => $overtime,
                    'tardiness' => $tardiness,
                    'leave_days' => $leaveDays,
                    'holidays' => $holidays
                ]
            ];
        }

        // Get division name for PDF title if division is selected
        $divisionName = '';
        if ($this->selectedDivision) {
            $division = OfficeDivisions::find($this->selectedDivision);
            if ($division) {
                $divisionName = $division->office_division;
            }
        }

        $pdf = Pdf::loadView('pdf.dtr', [
            'dtrsWithSummary' => $dtrsWithSummary,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'eSignaturePath' => $this->eSignaturePath,
            'divisionName' => $divisionName
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
