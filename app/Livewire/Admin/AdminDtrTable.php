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

    // Added for Edit Modal
    public $showEditModal = false;
    public $editData = [
        'morning_in' => '',
        'morning_out' => '',
        'afternoon_in' => '',
        'afternoon_out' => '',
        'late' => '',
        'ut' => '',
        'overtime' => '',
        'total_hours_rendered' => '',
        'effective_remarks' => '',
    ];
    public $editId;

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

    public function openEditModal($id)
    {
        $dtr = EmployeesDtr::findOrFail($id);

        $this->editId = $id;
        $this->editData = [
            'morning_in' => $dtr->morning_in,
            'morning_out' => $dtr->morning_out,
            'afternoon_in' => $dtr->afternoon_in,
            'afternoon_out' => $dtr->afternoon_out,
            'late' => $dtr->late,
            'ut' => $dtr->ut,
            'overtime' => $dtr->overtime,
            'total_hours_rendered' => $dtr->total_hours_rendered,
            'effective_remarks' => $dtr->up_remarks ?? $dtr->remarks,
        ];

        $this->showEditModal = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'editData.morning_in' => 'nullable',
            'editData.morning_out' => 'nullable',
            'editData.afternoon_in' => 'nullable',
            'editData.afternoon_out' => 'nullable',
            'editData.late' => 'nullable|string',
            'editData.ut' => 'nullable|string',
            'editData.overtime' => 'nullable|string',
            'editData.total_hours_rendered' => 'nullable|string',
            'editData.effective_remarks' => 'nullable|string',
        ]);

        $dtr = EmployeesDtr::findOrFail($this->editId);
        $dtr->update([
            'morning_in' => $this->editData['morning_in'],
            'morning_out' => $this->editData['morning_out'],
            'afternoon_in' => $this->editData['afternoon_in'],
            'afternoon_out' => $this->editData['afternoon_out'],
            'late' => $this->editData['late'],
            'ut' => $this->editData['ut'],
            'overtime' => $this->editData['overtime'],
            'total_hours_rendered' => $this->editData['total_hours_rendered'],
            'up_remarks' => $this->editData['effective_remarks'],
        ]);

        $this->showEditModal = false;
        $this->dispatch('swal', [
            'title' => 'DTR Updated Successfully!',
            'icon' => 'success'
        ]);
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
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
        // Validate date range
        if (!$this->startDate || !$this->endDate) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Please select a valid date range.',
                'icon' => 'error'
            ]);
            return null;
        }

        // Prepare the base query
        $query = EmployeesDtr::query()
        ->join('users', 'employees_dtr.user_id', '=', 'users.id')
        ->join('user_data', 'users.id', '=', 'user_data.user_id')
        ->leftJoin('office_divisions', 'users.office_division_id', '=', 'office_divisions.id')
        ->leftJoin('positions', 'users.position_id', '=', 'positions.id')
        ->select(
            'employees_dtr.*',
            'users.name as user_name',
            'positions.position as user_position',
            'office_divisions.office_division as user_department',
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

        // Order the results
        $dtrs = $query->orderBy('users.name')
                      ->orderBy('employees_dtr.date')
                      ->get()
                      ->groupBy('user_name');

        // Prepare DTRs with summary
        $dtrsWithSummary = [];

        foreach ($dtrs as $employeeName => $employeeDtrs) {
            // Ensure effective_remarks is accessible
            $processedDtrs = $employeeDtrs->map(function ($dtr) {
                $dtr->effective_remarks = $dtr->effective_remarks ?? $dtr->remarks;
                return $dtr;
            });

            // Calculate days with time entries
            $daysWithTimeEntries = $processedDtrs->filter(function($dtr) {
                return $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;
            })->count();

            // Calculate days worked - if has time entries, consider it as a day worked
            // regardless of remarks (except for absences)
            $daysWorked = $processedDtrs->filter(function($dtr) {
                // If there are time entries, consider it as worked
                $hasTimeEntries = $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;

                // Only consider as not worked if explicitly marked as absent
                if (strtolower($dtr->effective_remarks) === 'absent') {
                    return false;
                }

                return $hasTimeEntries;
            })->count();

            // Calculate absences (records explicitly marked as absent)
            $absences = $processedDtrs->filter(function($dtr) {
                return strtolower($dtr->effective_remarks) === 'absent';
            })->count();

            // Calculate leave days
            $leaveDays = $processedDtrs->filter(function($dtr) {
                return str_contains(strtolower($dtr->effective_remarks), 'leave');
            })->count();

            // Calculate holidays
            $holidays = $processedDtrs->filter(function($dtr) {
                return str_contains(strtolower($dtr->effective_remarks), 'holiday');
            })->count();

            // Calculate overtime hours
            $totalOvertimeMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                if (!empty($dtr->overtime) && $dtr->overtime !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->overtime);
                    $totalOvertimeMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $overtime = sprintf("%02d:%02d", floor($totalOvertimeMinutes / 60), $totalOvertimeMinutes % 60);

            // Calculate late hours - only for days with time entries
            $totalLateMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                $hasTimeEntries = $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;
                if ($hasTimeEntries && !empty($dtr->late) && $dtr->late !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->late);
                    $totalLateMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $late = sprintf("%02d:%02d", floor($totalLateMinutes / 60), $totalLateMinutes % 60);

            // Calculate undertime hours - only for days with time entries
            $totalUndertimeMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                $hasTimeEntries = $dtr->morning_in || $dtr->morning_out || $dtr->afternoon_in || $dtr->afternoon_out;
                if ($hasTimeEntries && !empty($dtr->ut) && $dtr->ut !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->ut);
                    $totalUndertimeMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $undertime = sprintf("%02d:%02d", floor($totalUndertimeMinutes / 60), $totalUndertimeMinutes % 60);

            // Calculate total tardiness (late + undertime)
            $totalTardinessMinutes = $totalLateMinutes + $totalUndertimeMinutes;
            $tardiness = sprintf("%02d:%02d", floor($totalTardinessMinutes / 60), $totalTardinessMinutes % 60);

            // Store the DTRs and summary for this employee
            $dtrsWithSummary[$employeeName] = [
                'dtrs' => $processedDtrs,
                'summary' => [
                    'days_worked' => $daysWorked,
                    'absences' => $absences,
                    'overtime' => $overtime,
                    'late' => $late,
                    'undertime' => $undertime,
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

        // Get the authenticated user's e-signature path
        $this->eSignaturePath = auth()->user()->esignature_path ?? null;

        // Generate PDF
        try {
            $pdf = Pdf::loadView('pdf.dtr', [
                'dtrsWithSummary' => $dtrsWithSummary,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'eSignaturePath' => $this->eSignaturePath,
                'divisionName' => $divisionName,
                'userPosition' => $employeeDtrs->first()->user_position ?? '',
                'userDepartment' => $employeeDtrs->first()->user_department ?? ''
            ])->setPaper('legal', 'portrait');

            // Dispatch success notification
            $this->dispatch('swal', [
                'title' => 'DTR Exported Successfully!',
                'icon' => 'success'
            ]);

            // Stream the PDF download
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'dtr_report_'.now()->format('YmdHis').'.pdf');

        } catch (\Exception $e) {
            // Handle any PDF generation errors
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Failed to generate PDF: ' . $e->getMessage(),
                'icon' => 'error'
            ]);

            return null;
        }
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
