<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Holiday;
use Livewire\WithPagination;
use Carbon\Carbon;

class HolidayScheduleTable extends Component
{
    use WithPagination;

    public $holidayId;
    public $description;
    public $holiday_date;
    public $type;
    public $isModalOpen = false;
    public $isEditMode = false;
    public $confirmingHolidayDeletion = false;
    public $holidayToDelete;

    protected $rules = [
        'description' => 'required|string',
        'holiday_date' => 'required|date',
        'type' => 'required|string',
    ];

    public function mount()
    {
        $this->resetPage(); // Ensure page resets when component is mounted
    }

    public function render()
    {
        return view('livewire.admin.holiday-schedule-table', [
            'holidays' => $this->loadHolidays(),
        ]);
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

    public function saveHoliday()
    {
        $this->validate();

        Holiday::updateOrCreate(
            ['id' => $this->holidayId],
            [
                'description' => $this->description,
                'holiday_date' => $this->holiday_date,
                'type' => $this->type,
            ]
        );

        $this->dispatch('swal', [
            'title' => $this->holidayId ? 'Holiday updated successfully.' : 'Holiday created successfully.',
            'icon' => 'success'
        ]);

        $this->closeModal();
    }

    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        $this->holidayId = $id;
        $this->description = $holiday->description;
        $this->holiday_date = $holiday->holiday_date->format('Y-m-d');
        $this->type = $holiday->type;
        $this->isEditMode = true;
        $this->isModalOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->holidayToDelete = $id;
        $this->confirmingHolidayDeletion = true;
    }

    public function deleteConfirmed()
    {
        Holiday::find($this->holidayToDelete)->delete();
        $this->confirmingHolidayDeletion = false;
        $this->dispatch('swal', [
            'title' => 'Holiday deleted successfully!',
            'icon' => 'success'
        ]);
    }

    public function closeConfirmationModal()
    {
        $this->confirmingHolidayDeletion = false;
    }

    private function resetInputFields()
    {
        $this->holidayId = null;
        $this->description = '';
        $this->holiday_date = null;
        $this->type = '';
        $this->isEditMode = false;
    }

    private function loadHolidays()
    {
        $currentYear = Carbon::now()->year;

        return Holiday::whereYear('holiday_date', '>=', $currentYear)
            ->orderBy('holiday_date', 'asc')
            ->paginate(10);
    }
}
