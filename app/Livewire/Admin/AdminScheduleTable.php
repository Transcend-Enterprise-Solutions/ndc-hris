<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DTRSchedule; // Import the model

class AdminScheduleTable extends Component
{
    public $schedules; // Property to hold schedules
    public $scheduleId; // To hold the ID of the schedule being edited
    public $emp_code, $wfh_days, $default_start_time, $default_end_time, $start_date, $end_date; // Properties for form inputs

    public function mount()
    {
        // Fetch all schedules when the component mounts
        $this->schedules = DTRSchedule::all();
    }

    public function render()
    {
        return view('livewire.admin.admin-schedule-table', [
            'schedules' => $this->schedules, // Pass schedules to the view
        ]);
    }

    public function edit($id)
    {
        $schedule = DTRSchedule::find($id);
        $this->scheduleId = $id;
        $this->emp_code = $schedule->emp_code;
        $this->wfh_days = $schedule->wfh_days;
        $this->default_start_time = $schedule->default_start_time;
        $this->default_end_time = $schedule->default_end_time;
        $this->start_date = $schedule->start_date;
        $this->end_date = $schedule->end_date;
    }

    public function update()
    {
        $this->validate([
            'emp_code' => 'required|string',
            'wfh_days' => 'nullable|string',
            'default_start_time' => 'nullable|date_format:H:i',
            'default_end_time' => 'nullable|date_format:H:i',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $schedule = DTRSchedule::find($this->scheduleId);
        $schedule->update([
            'emp_code' => $this->emp_code,
            'wfh_days' => $this->wfh_days,
            'default_start_time' => $this->default_start_time,
            'default_end_time' => $this->default_end_time,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->resetInputFields();
        $this->schedules = DTRSchedule::all(); // Refresh the schedule list
    }

    public function delete($id)
    {
        DTRSchedule::find($id)->delete();
        $this->schedules = DTRSchedule::all(); // Refresh the schedule list
    }

    private function resetInputFields()
    {
        $this->emp_code = '';
        $this->wfh_days = '';
        $this->default_start_time = '';
        $this->default_end_time = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->scheduleId = null; // Reset schedule ID
    }
}
