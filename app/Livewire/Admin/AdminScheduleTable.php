<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DTRSchedule;
use App\Models\User;

class AdminScheduleTable extends Component
{
    public $schedules;
    public $employees;
    public $scheduleId;
    public $emp_code, $wfh_days = [], $default_start_time = '07:00', $default_end_time = '18:30';
    public $start_date, $end_date;
    public $isModalOpen = false;

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
        $this->loadSchedules();
        $this->employees = User::all();
    }

    public function render()
    {
        return view('livewire.admin.admin-schedule-table');
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function saveSchedule()
    {
        $this->validate();

        DTRSchedule::updateOrCreate(
            ['id' => $this->scheduleId],
            [
                'emp_code' => $this->emp_code,
                'wfh_days' => implode(',', $this->wfh_days),
                'default_start_time' => $this->default_start_time,
                'default_end_time' => $this->default_end_time,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]
        );

        $this->closeModal();
        $this->loadSchedules();
    }

    public function edit($id)
    {
        $schedule = DTRSchedule::find($id);
        if ($schedule) {
            $this->scheduleId = $schedule->id;
            $this->emp_code = $schedule->emp_code;
            $this->wfh_days = explode(',', $schedule->wfh_days);
            $this->default_start_time = $schedule->default_start_time;
            $this->default_end_time = $schedule->default_end_time;
            $this->start_date = $schedule->start_date;
            $this->end_date = $schedule->end_date;
            $this->openModal();
        }
    }

    public function delete($id)
    {
        DTRSchedule::find($id)->delete();
        $this->loadSchedules();
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
    }

    private function loadSchedules()
    {
        $this->schedules = DTRSchedule::with('user')->get();
    }
}
