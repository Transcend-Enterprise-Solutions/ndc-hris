<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use App\Models\OfficeDivisions;
use App\Models\OfficeDivisionUnits;
use Illuminate\Support\Facades\Auth;
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
    public $selectedAppointment = '';

    // Division Signatory Properties
    public $selectedDivision = null;
    public $signName = '';
    public $signPos = '';
    public $showSignatoryModal = false;

    // Unit Signatory Properties
    public $selectedUnit = null;
    public $unitSignName = '';
    public $unitSignPos = '';
    public $showUnitSignatoryModal = false;

    // Edit Modal Properties
    public $showEditModal = false;
    public $editId;
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

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'asc'],
        'pageSize' => ['except' => 30],
        'selectedAppointment' => ['except' => ''],
    ];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    // Division Signatory Methods
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
            'title' => 'Division Signatory Updated Successfully!',
            'icon' => 'success'
        ]);
    }

    // Unit Signatory Methods
    public function openUnitSignatoryModal($unitId)
    {
        $this->selectedUnit = $unitId;
        $unit = OfficeDivisionUnits::find($unitId);

        if ($unit) {
            $this->unitSignName = $unit->sign_name;
            $this->unitSignPos = $unit->sign_pos;
        }

        $this->showUnitSignatoryModal = true;
    }

    public function saveUnitSignatory()
    {
        $this->validate([
            'unitSignName' => 'required',
            'unitSignPos' => 'required',
            'selectedUnit' => 'required'
        ]);

        $unit = OfficeDivisionUnits::find($this->selectedUnit);
        $unit->update([
            'sign_name' => $this->unitSignName,
            'sign_pos' => $this->unitSignPos
        ]);

        $this->showUnitSignatoryModal = false;
        $this->dispatch('swal', [
            'title' => 'Unit Signatory Updated Successfully!',
            'icon' => 'success'
        ]);
    }

    // Helper method to convert time format
    private function convertTimeFormat($time)
    {
        if (empty($time)) return '';

        // If it's already in H:i format, return as is
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time;
        }

        // Try to parse and convert from various formats
        try {
            return date('H:i', strtotime($time));
        } catch (\Exception $e) {
            return '';
        }
    }

    // Edit Modal Methods
    public function openEditModal($id)
    {
        $dtr = EmployeesDtr::findOrFail($id);

        $this->editId = $id;
        $this->editData = [
            'morning_in' => $this->convertTimeFormat($dtr->up_morning_in ?? $dtr->morning_in),
            'morning_out' => $this->convertTimeFormat($dtr->up_morning_out ?? $dtr->morning_out),
            'afternoon_in' => $this->convertTimeFormat($dtr->up_afternoon_in ?? $dtr->afternoon_in),
            'afternoon_out' => $this->convertTimeFormat($dtr->up_afternoon_out ?? $dtr->afternoon_out),
            'late' => $dtr->up_late ?? $dtr->late,
            'ut' => $dtr->up_ut ?? $dtr->ut,
            'overtime' => $dtr->up_ot ?? $dtr->overtime,
            'total_hours_rendered' => $dtr->up_total_hours_rendered ?? $dtr->total_hours_rendered,
            'effective_remarks' => $dtr->up_remarks ?? $dtr->effective_remarks,
        ];

        $this->showEditModal = true;
    }

    public function saveEdit()
    {
        $this->validate([
            'editData.morning_in' => 'nullable|date_format:H:i',
            'editData.morning_out' => 'nullable|date_format:H:i',
            'editData.afternoon_in' => 'nullable|date_format:H:i',
            'editData.afternoon_out' => 'nullable|date_format:H:i',
            'editData.late' => 'nullable|string|max:255',
            'editData.ut' => 'nullable|string|max:255',
            'editData.overtime' => 'nullable|string|max:255',
            'editData.total_hours_rendered' => 'nullable|string|max:255',
            'editData.effective_remarks' => 'nullable|string|max:255',
        ], [
            'editData.morning_in.date_format' => 'Morning In must be in HH:MM format',
            'editData.morning_out.date_format' => 'Morning Out must be in HH:MM format',
            'editData.afternoon_in.date_format' => 'Afternoon In must be in HH:MM format',
            'editData.afternoon_out.date_format' => 'Afternoon Out must be in HH:MM format',
        ]);

        try {
            $dtr = EmployeesDtr::findOrFail($this->editId);

            $dtr->update([
                'up_morning_in' => $this->editData['morning_in'],
                'up_morning_out' => $this->editData['morning_out'],
                'up_afternoon_in' => $this->editData['afternoon_in'],
                'up_afternoon_out' => $this->editData['afternoon_out'],
                'up_late' => $this->editData['late'],
                'up_ut' => $this->editData['ut'],
                'up_ot' => $this->editData['overtime'],
                'up_remarks' => $this->editData['effective_remarks'],
                'up_total_hours_rendered' => $this->editData['total_hours_rendered'],
                'updated_by' => Auth::user()->name,
                'updated_at' => now(),
            ]);

            $this->showEditModal = false;
            $this->reset(['editData', 'editId']);

            $this->dispatch('swal', [
                'title' => 'DTR Updated Successfully!',
                'icon' => 'success'
            ]);

            // Refresh the component to show updated data
            $this->dispatch('$refresh');

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Failed to update DTR: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->reset(['editData', 'editId']);
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

        // Apply appointment filter
        if ($this->selectedAppointment) {
            $query->where('user_data.appointment', $this->selectedAppointment);
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
        $officeDivisions = OfficeDivisions::with('units')->get();

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

        // Prepare the base query with unit join
        $query = EmployeesDtr::query()
            ->join('users', 'employees_dtr.user_id', '=', 'users.id')
            ->join('user_data', 'users.id', '=', 'user_data.user_id')
            ->leftJoin('office_divisions', 'users.office_division_id', '=', 'office_divisions.id')
            ->leftJoin('office_division_units', 'users.unit_id', '=', 'office_division_units.id')
            ->leftJoin('positions', 'users.position_id', '=', 'positions.id')
            ->select(
                'employees_dtr.*',
                'users.name as user_name',
                'users.unit_id',
                'positions.position as user_position',
                'office_divisions.office_division as user_department',
                'office_divisions.sign_name as division_sign_name',
                'office_divisions.sign_pos as division_sign_pos',
                'office_division_units.sign_name as unit_sign_name',
                'office_division_units.sign_pos as unit_sign_pos',
                'user_data.appointment',
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

        // Apply appointment filter
        if ($this->selectedAppointment) {
            $query->where('user_data.appointment', $this->selectedAppointment);
        }

        // Order the results
        $dtrs = $query->orderBy('users.name')
                    ->orderBy('employees_dtr.date')
                    ->get()
                    ->groupBy('user_name');

        // Prepare DTRs with summary
        $dtrsWithSummary = [];
        $today = Carbon::today();

        foreach ($dtrs as $employeeName => $employeeDtrs) {
            // Get the employee's appointment type
            $appointment = $employeeDtrs->first()->appointment ?? null;

            // Process each DTR record to use updated values when available
            $processedDtrs = $employeeDtrs->map(function ($dtr) use ($appointment) {
                // Use updated values if available, otherwise use original values
                $dtr->effective_morning_in = $dtr->up_morning_in ?: $dtr->morning_in;
                $dtr->effective_morning_out = $dtr->up_morning_out ?: $dtr->morning_out;
                $dtr->effective_afternoon_in = $dtr->up_afternoon_in ?: $dtr->afternoon_in;
                $dtr->effective_afternoon_out = $dtr->up_afternoon_out ?: $dtr->afternoon_out;
                $dtr->effective_late = $dtr->up_late ?: $dtr->late;
                $dtr->effective_ut = $dtr->up_ut ?: $dtr->ut;

                // Handle overtime based on appointment type
                $overtime = $dtr->up_ot ?: $dtr->overtime;
                if ($overtime && $overtime !== '00:00') {
                    list($hours, $minutes) = explode(':', $overtime);
                    $totalMinutes = (intval($hours) * 60) + intval($minutes);

                    if ($appointment === 'cos' && $totalMinutes <= 60) {
                        $overtime = '00:00'; // COS with 1 hour or less
                    } elseif ($appointment !== 'cos' && $totalMinutes <= 120) {
                        $overtime = '00:00'; // Plantilla with 2 hours or less
                    }
                }
                $dtr->effective_overtime = $overtime;

                $dtr->effective_total_hours_rendered = $dtr->up_total_hours_rendered ?: $dtr->total_hours_rendered;
                $dtr->effective_remarks = $dtr->up_remarks ?: $dtr->remarks;
                $dtr->effective_updated_by = $dtr->updated_by;

                return $dtr;
            });

            // Calculate days with time entries (only past or present dates)
            $daysWithTimeEntries = $processedDtrs->filter(function($dtr) use ($today) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    return false;
                }
                return $dtr->effective_morning_in || $dtr->effective_morning_out ||
                    $dtr->effective_afternoon_in || $dtr->effective_afternoon_out;
            })->count();

            // Calculate days worked (only past or present dates)
            $daysWorked = $processedDtrs->filter(function($dtr) use ($today) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    return false;
                }

                $hasTimeEntries = $dtr->effective_morning_in || $dtr->effective_morning_out ||
                                $dtr->effective_afternoon_in || $dtr->effective_afternoon_out;

                if (strtolower($dtr->effective_remarks) === 'absent') {
                    return false;
                }

                return $hasTimeEntries;
            })->count();

            // Calculate absences (only past or present dates)
            $absences = $processedDtrs->filter(function($dtr) use ($today) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    return false;
                }
                return strtolower($dtr->effective_remarks) === 'absent';
            })->count();

            // Calculate leave days (only past or present dates)
            $leaveDays = $processedDtrs->filter(function($dtr) use ($today) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    return false;
                }
                return str_contains(strtolower($dtr->effective_remarks), 'leave');
            })->count();

            // Calculate holidays (only past or present dates)
            $holidays = $processedDtrs->filter(function($dtr) use ($today) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    return false;
                }
                return str_contains(strtolower($dtr->effective_remarks), 'holiday');
            })->count();

            // Calculate overtime hours (only past or present dates)
            $totalOvertimeMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    continue;
                }

                if (!empty($dtr->effective_overtime) && $dtr->effective_overtime !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->effective_overtime);
                    $totalOvertimeMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $overtime = sprintf("%02d:%02d", floor($totalOvertimeMinutes / 60), $totalOvertimeMinutes % 60);

            // Calculate late hours (only past or present dates)
            $totalLateMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    continue;
                }

                $hasTimeEntries = $dtr->effective_morning_in || $dtr->effective_morning_out ||
                                $dtr->effective_afternoon_in || $dtr->effective_afternoon_out;
                if ($hasTimeEntries && !empty($dtr->effective_late) && $dtr->effective_late !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->effective_late);
                    $totalLateMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $late = sprintf("%02d:%02d", floor($totalLateMinutes / 60), $totalLateMinutes % 60);

            // Calculate undertime hours (only past or present dates)
            $totalUndertimeMinutes = 0;
            foreach ($processedDtrs as $dtr) {
                $date = Carbon::parse($dtr->date);
                if ($date->isFuture()) {
                    continue;
                }

                $hasTimeEntries = $dtr->effective_morning_in || $dtr->effective_morning_out ||
                                $dtr->effective_afternoon_in || $dtr->effective_afternoon_out;
                if ($hasTimeEntries && !empty($dtr->effective_ut) && $dtr->effective_ut !== '00:00') {
                    list($hours, $minutes) = explode(':', $dtr->effective_ut);
                    $totalUndertimeMinutes += (intval($hours) * 60) + intval($minutes);
                }
            }
            $undertime = sprintf("%02d:%02d", floor($totalUndertimeMinutes / 60), $totalUndertimeMinutes % 60);

            // Calculate total tardiness
            $totalTardinessMinutes = $totalLateMinutes + $totalUndertimeMinutes;
            $tardiness = sprintf("%02d:%02d", floor($totalTardinessMinutes / 60), $totalTardinessMinutes % 60);

            // Determine the correct signatory
            $employee = $employeeDtrs->first();
            if ($employee->unit_id) {
                // Use unit signatory if employee belongs to a unit
                $signName = $employee->unit_sign_name ?? '';
                $signPos = $employee->unit_sign_pos ?? '';
            } else {
                // Use division signatory if no unit
                $signName = $employee->division_sign_name ?? '';
                $signPos = $employee->division_sign_pos ?? '';
            }

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
                ],
                'signatory' => [
                    'name' => $signName,
                    'position' => $signPos
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
