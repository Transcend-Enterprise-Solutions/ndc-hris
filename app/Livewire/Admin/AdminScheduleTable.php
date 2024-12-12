<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DTRSchedule;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class AdminScheduleTable extends Component
{
    use WithPagination;

    public $schedules;
    public $employees;
    public $scheduleId;
    public $thisEmployeeName, $emp_code, $wfh_days = [], $default_start_time = '07:00', $default_end_time = '18:30';
    public $start_date, $end_date;
    public $isModalOpen = false;
    public $isEditMode = false;
    public $confirmingScheduleDeletion = false;
    public $scheduleToDelete;
    public $selectedTab = 'current';
    public $perPage = 10;
    public $search = ''; 

    protected $queryString = ['search'];

    protected $rules = [
        'emp_code' => 'required|string',
        'wfh_days' => 'nullable|array',
        'default_start_time' => 'required|date_format:H:i',
        'default_end_time' => 'required|date_format:H:i',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function mount()
    {
        $this->employees = User::where('user_role', 'emp')
            ->leftJoin('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.*', 
                DB::raw("CASE 
                    WHEN user_data.appointment = 'cos' THEN CONCAT('D-', SUBSTRING(users.emp_code, 2))
                    ELSE users.emp_code 
                END as display_emp_code"))
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-schedule-table', [
            'filteredSchedules' => $this->filterSchedules() 
        ]);
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function filterSchedules()
    {
        $now = Carbon::now()->startOfDay();
        $search = '%' . $this->search . '%';
    
        return DTRSchedule::with(['user' => function ($query) {
                $query->leftJoin('user_data', 'users.id', '=', 'user_data.user_id')
                    ->select('users.*', 'user_data.appointment');
            }])
            ->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', $search);
            })
            ->when($this->selectedTab, function ($query) use ($now) {
                switch ($this->selectedTab) {
                    case 'current':
                        return $query->where('start_date', '<=', $now)
                                     ->where('end_date', '>=', $now);
                    case 'incoming':
                        return $query->where('start_date', '>', $now);
                    case 'expired':
                        return $query->where('end_date', '<', $now);
                }
            })
            ->when($this->selectedTab === 'expired', function ($query) {
                return $query->orderBy('end_date', 'desc');
            }, function ($query) {
                return $query->orderBy('start_date', 'asc');
            })
            ->paginate($this->perPage);
    }
    public function getDisplayEmpCode($empCode, $appointment)
    {
        if ($appointment === 'cos' && strpos($empCode, '1') === 0) {
            return 'D-' . substr($empCode, 1);
        }
        return $empCode;
    }
 

    public function getSortedWfhDays($wfhDays)
    {
        $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $wfhDaysArray = explode(',', $wfhDays);
        usort($wfhDaysArray, function ($a, $b) use ($dayOrder) {
            return array_search($a, $dayOrder) - array_search($b, $dayOrder);
        });

        return implode(', ', $wfhDaysArray);
    }

    public function setTab($tab)
    {
        $this->selectedTab = $tab;
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
        $this->isEditMode = false;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function saveSchedule()
    {
        $this->default_start_time = date('H:i', strtotime($this->default_start_time));
        $this->default_end_time = date('H:i', strtotime($this->default_end_time));
    
        $this->validate();
    
        $wfhDaysString = !empty($this->wfh_days) ? implode(',', $this->wfh_days) : null;
    
        // Use the original emp_code (starting with '1' for COS) for database operations
        $originalEmpCode = $this->emp_code;
        if (strpos($this->emp_code, 'D-') === 0) {
            $originalEmpCode = '1' . substr($this->emp_code, 2);
        }
    
        $overlappingSchedule = DTRSchedule::where('emp_code', $originalEmpCode)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($q) {
                        $q->where('start_date', '<=', $this->start_date)
                          ->where('end_date', '>=', $this->end_date);
                    });
            })
            ->when($this->scheduleId, function ($query) {
                return $query->where('id', '!=', $this->scheduleId);
            })
            ->first();
    
        if ($overlappingSchedule) {
            $this->addError('date_range', 'This schedule overlaps with an existing schedule for this employee.');
            return;
        }
    
        DTRSchedule::updateOrCreate(
            ['id' => $this->scheduleId],
            [
                'emp_code' => $originalEmpCode,
                'wfh_days' => $wfhDaysString,
                'default_start_time' => $this->default_start_time,
                'default_end_time' => $this->default_end_time,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]
        );
    
        $this->dispatch('swal', [
            'title' => $this->scheduleId ? 'Schedule updated successfully.' : 'Schedule created successfully.',
            'icon' => 'success'
        ]);
    
        $this->closeModal();
    }

    public function edit($id)
    {
        $schedule = DTRSchedule::findOrFail($id);
        $this->scheduleId = $id;
        
        // Convert emp_code to display format (D- for COS)
        $user = User::where('emp_code', $schedule->emp_code)
            ->leftJoin('user_data', 'users.id', '=', 'user_data.user_id')
            ->select('users.*', 
                DB::raw("CASE 
                    WHEN user_data.appointment = 'cos' THEN CONCAT('D-', SUBSTRING(users.emp_code, 2))
                    ELSE users.emp_code 
                END as display_emp_code"))
            ->first();
        
        $this->emp_code = $user->display_emp_code;
        $this->thisEmployeeName = $user->name;
        $this->wfh_days = !empty($schedule->wfh_days) ? explode(',', $schedule->wfh_days) : [];
        $this->default_start_time = date('H:i', strtotime($schedule->default_start_time));
        $this->default_end_time = date('H:i', strtotime($schedule->default_end_time));
        $this->start_date = $schedule->start_date->format('Y-m-d');
        $this->end_date = $schedule->end_date->format('Y-m-d');
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->scheduleToDelete = $id;
        $this->confirmingScheduleDeletion = true;
    }

    public function deleteConfirmed()
    {
        DTRSchedule::find($this->scheduleToDelete)->delete();
        $this->confirmingScheduleDeletion = false;
        $this->dispatch('swal', [
            'title' => 'Schedule deleted successfully!',
            'icon' => 'success'
        ]);
    }

    public function closeConfirmationModal()
    {
        $this->confirmingScheduleDeletion = false;
    }

    private function resetInputFields()
    {
        $this->scheduleId = null;
        $this->emp_code = '';
        $this->wfh_days = [];
        $this->default_start_time = '07:00';
        $this->default_end_time = '18:30';
        $this->start_date = null;
        $this->end_date = null;
        $this->isEditMode = false;
    }
}
