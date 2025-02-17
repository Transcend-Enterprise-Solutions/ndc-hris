<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\LeaveApplication;
use App\Models\OfficeDivisions;
use App\Models\Positions;
use App\Models\LeaveCredits;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminMandatoryLeaveTable extends Component
{
    use WithPagination;

    public $showModal = false;
    public $showEditModal = false;
    public $selectedUser = null;
    public $new_date = '';
    public $list_of_dates = [];
    public $users;
    public $pageSize = 5; 
    public $pageSizes = [5, 10, 20, 30, 50, 100];
    public $editingLeave = null;
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openEditModal($leaveId)
    {
        $this->editingLeave = LeaveApplication::find($leaveId);
        $this->selectedUser = $this->editingLeave->user_id;
        $this->list_of_dates = explode(',', $this->editingLeave->list_of_dates);
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    // public function addDate()
    // {
    //     $this->validate([
    //         'new_date' => 'required|date',
    //     ]);
        
    //     if (!in_array($this->new_date, $this->list_of_dates)) {
    //         if (count($this->list_of_dates) >= 5) {
    //             session()->flash('error', 'Maximum 5 dates can be selected.');
    //             return;
    //         }
    //         $this->list_of_dates[] = $this->new_date;
    //     }
        
    //     $this->new_date = '';
    // }
    public function addDate()
    {
        $this->validate([
            'new_date' => 'required|date',
        ]);
        
        if (!in_array($this->new_date, $this->list_of_dates)) {
            // Check total mandatory leaves for the year
            $totalMandatoryLeavesThisYear = LeaveApplication::where('user_id', $this->selectedUser)
                ->where('details_of_leave', 'Mandatory Leave')
                ->whereYear('created_at', now()->year)
                ->sum('number_of_days');
                
            $currentSelectionCount = count($this->list_of_dates);
            
            if (($totalMandatoryLeavesThisYear + $currentSelectionCount + 1) > 5) {
                session()->flash('error', 'Maximum 5 mandatory leave days per year allowed.');
                return;
            }
            
            $this->list_of_dates[] = $this->new_date;
        }
        
        $this->new_date = '';
    }

    public function removeDate($index)
    {
        unset($this->list_of_dates[$index]);
        $this->list_of_dates = array_values($this->list_of_dates);
    }

    // public function updateMandatoryLeave()
    // {
    //     $this->validate([
    //         'selectedUser' => 'required',
    //         'list_of_dates' => 'required|array|size:5',
    //     ], [
    //         'list_of_dates.size' => 'Please select exactly 5 dates.'
    //     ]);

    //     sort($this->list_of_dates);
    //     $formattedDates = implode(',', $this->list_of_dates);

    //     $this->editingLeave->update([
    //         'list_of_dates' => $formattedDates,
    //         'approved_dates' => $formattedDates,
    //     ]);

    //     $this->dispatch('swal', [
    //         'title' => "Mandatory leave has been updated successfully!",
    //         'icon' => 'success'
    //     ]);
    //     $this->closeEditModal();
    // }
    // public function updateMandatoryLeave()
    // {
    //     $this->validate([
    //         'selectedUser' => 'required',
    //         'list_of_dates' => 'required|array',
    //     ]);

    //     sort($this->list_of_dates);
    //     $formattedDates = implode(',', $this->list_of_dates);
    //     $numberOfDays = count($this->list_of_dates);

    //     // Update leave credits
    //     $user = User::find($this->selectedUser);
        
    //     // Verify sufficient credits
    //     if ($user->fl_claimable_credits < $numberOfDays || $user->vl_claimable_credits < $numberOfDays) {
    //         $this->dispatch('swal', [
    //             'title' => "Error!",
    //             'text' => "Insufficient leave credits.",
    //             'icon' => 'error'
    //         ]);
    //         return;
    //     }

    //     // Update the credits
    //     $user->update([
    //         'fl_claimable_credits' => $user->fl_claimable_credits - $numberOfDays,
    //         'vl_claimable_credits' => $user->vl_claimable_credits - $numberOfDays,
    //         'fl_claimed_credits' => $user->fl_claimed_credits + $numberOfDays
    //     ]);

    //     $this->editingLeave->update([
    //         'list_of_dates' => $formattedDates,
    //         'approved_dates' => $formattedDates,
    //         'number_of_days' => $numberOfDays
    //     ]);

    //     $this->dispatch('swal', [
    //         'title' => "Mandatory leave has been updated successfully!",
    //         'icon' => 'success'
    //     ]);
    //     $this->closeEditModal();
    // }
    // public function updateMandatoryLeave()
    // {
    //     $this->validate([
    //         'selectedUser' => 'required',
    //         'list_of_dates' => 'required|array',
    //     ]);

    //     sort($this->list_of_dates);
    //     $formattedDates = implode(',', $this->list_of_dates);
    //     $numberOfDays = count($this->list_of_dates);

    //     // Get leave credits
    //     $leaveCredits = LeaveCredits::where('user_id', $this->selectedUser)->first();
        
    //     // Verify sufficient credits
    //     if ($leaveCredits->fl_claimable_credits < $numberOfDays || $leaveCredits->vl_claimable_credits < $numberOfDays) {
    //         $this->closeEditModal();
    //         $this->dispatch('swal', [
    //             'title' => "Insufficient leave credits.",
    //             'icon' => 'error'
    //         ]);
    //         return;
    //     }

    //     // Update the credits in LeaveCredits table
    //     $leaveCredits->update([
    //         'fl_claimable_credits' => $leaveCredits->fl_claimable_credits - $numberOfDays,
    //         'vl_claimable_credits' => $leaveCredits->vl_claimable_credits - $numberOfDays,
    //         'fl_claimed_credits' => $leaveCredits->fl_claimed_credits + $numberOfDays
    //     ]);

    //     $this->editingLeave->update([
    //         'list_of_dates' => $formattedDates,
    //         'approved_dates' => $formattedDates,
    //         'number_of_days' => $numberOfDays
    //     ]);

    //     $this->dispatch('swal', [
    //         'title' => "Mandatory leave has been updated successfully!",
    //         'icon' => 'success'
    //     ]);
    //     $this->closeEditModal();
    // }
    public function updateMandatoryLeave()
{
    $this->validate([
        'selectedUser' => 'required',
        'list_of_dates' => 'required|array',
    ]);

    sort($this->list_of_dates);
    $formattedDates = implode(',', $this->list_of_dates);
    $numberOfDays = count($this->list_of_dates);

    // Get the original dates from the editing leave record
    $originalDates = explode(',', $this->editingLeave->list_of_dates);
    $originalNumberOfDays = count($originalDates);

    // Calculate the difference in days
    $additionalDays = $numberOfDays - $originalNumberOfDays;

    // Get leave credits
    $leaveCredits = LeaveCredits::where('user_id', $this->selectedUser)->first();
    
    // If adding more days, check if user has enough credits
    if ($additionalDays > 0) {
        if ($leaveCredits->fl_claimable_credits < $additionalDays || 
            $leaveCredits->vl_claimable_credits < $additionalDays) {
            $this->closeEditModal();
            $this->dispatch('swal', [
                'title' => "You can't add another date because you don't have enough credits.",
                'icon' => 'error'
            ]);
            return;
        }
    }

    // Check for date conflicts with other leave applications
    $conflictingDates = LeaveApplication::where('user_id', $this->selectedUser)
        ->where('id', '!=', $this->editingLeave->id) // Exclude current record
        ->where('status', '!=', 'Cancelled')
        ->get()
        ->flatMap(function($leave) {
            return explode(',', $leave->list_of_dates);
        })
        ->toArray();

    // Check if any of the new dates conflict with existing leaves
    $newDates = array_diff($this->list_of_dates, $originalDates);
    $dateConflicts = array_intersect($newDates, $conflictingDates);

    if (!empty($dateConflicts)) {
        $conflictingDatesString = implode(', ', $dateConflicts);
        $this->closeEditModal();
        $this->dispatch('swal', [
            'title' => "The following dates are already used in other leave applications: " . $conflictingDatesString,
            'icon' => 'error'
        ]);
        return;
    }

    // Check total mandatory leaves for the year
    $totalMandatoryLeavesThisYear = LeaveApplication::where('user_id', $this->selectedUser)
        ->where('details_of_leave', 'Mandatory Leave')
        ->where('id', '!=', $this->editingLeave->id) // Exclude current record
        ->whereYear('created_at', now()->year)
        ->sum('number_of_days');

    if (($totalMandatoryLeavesThisYear + $numberOfDays) > 5) {
        $this->closeEditModal();
        $this->dispatch('swal', [
            'title' => "Maximum 5 mandatory leave days per year allowed.",
            'icon' => 'error'
        ]);
        return;
    }

    DB::beginTransaction();
    try {
        // Update the credits in LeaveCredits table if there are additional days
        if ($additionalDays != 0) {
            $leaveCredits->update([
                'fl_claimable_credits' => $leaveCredits->fl_claimable_credits - $additionalDays,
                'vl_claimable_credits' => $leaveCredits->vl_claimable_credits - $additionalDays,
                'fl_claimed_credits' => $leaveCredits->fl_claimed_credits + $additionalDays
            ]);
        }

        $this->editingLeave->update([
            'list_of_dates' => $formattedDates,
            'approved_dates' => $formattedDates,
            'number_of_days' => $numberOfDays
        ]);

        DB::commit();

        $this->dispatch('swal', [
            'title' => "Mandatory leave has been updated successfully!",
            'icon' => 'success'
        ]);
        $this->closeEditModal();
    } catch (\Exception $e) {
        DB::rollBack();
        $this->closeEditModal();
        $this->dispatch('swal', [
            'title' => "An error occurred while updating the leave application.",
            'icon' => 'error'
        ]);
    }
}

    // public function submitMandatoryLeave()
    // {
    //     $this->validate([
    //         'selectedUser' => 'required',
    //         'list_of_dates' => 'required|array|size:5',
    //     ], [
    //         'list_of_dates.size' => 'Please select exactly 5 dates.'
    //     ]);

    //     // Check if user already has mandatory leave for current year
    //     $existingLeave = LeaveApplication::where('user_id', $this->selectedUser)
    //         ->where('details_of_leave', 'Mandatory Leave')
    //         ->whereYear('created_at', now()->year)
    //         ->first();

    //     if ($existingLeave) {
    //         $this->dispatch('swal', [
    //             'title' => "Error!",
    //             'text' => "This employee already has mandatory leave scheduled for this year. Please edit the existing leave.",
    //             'icon' => 'error'
    //         ]);
    //         return;
    //     }
    
    //     // Get user basic info
    //     $user = User::find($this->selectedUser);
        
    //     // Get office division from office_divisions table
    //     $officeDepartment = 'N/A';
    //     if ($user->office_division_id) {
    //         $officeDivision = OfficeDivisions::find($user->office_division_id);
    //         $officeDepartment = $officeDivision ? $officeDivision->office_division : 'N/A';
    //     }
        
    //     // Get position from positions table
    //     $positionName = 'N/A';
    //     if ($user->position_id) {
    //         $position = Positions::find($user->position_id);
    //         $positionName = $position ? $position->position : 'N/A';
    //     }
        
    //     // Get salary - check all possible payroll tables
    //     $salary = 0;
    //     if ($user->payroll) {
    //         $salary = $user->payroll->rate_per_month;
    //     } elseif ($user->cosSkPayroll) {
    //         $salary = $user->cosSkPayroll->rate_per_month;
    //     } elseif ($user->cosRegPayroll) {
    //         $salary = $user->cosRegPayroll->rate_per_month;
    //     }
    
    //     sort($this->list_of_dates);
    //     $formattedDates = implode(',', $this->list_of_dates);
    
    //     LeaveApplication::create([
    //         'user_id' => $this->selectedUser,
    //         'name' => $user->name,
    //         'office_or_department' => $officeDepartment,
    //         'position' => $positionName,
    //         'salary' => $salary,
    //         'date_of_filing' => now(),
    //         'type_of_leave' => 'Mandatory Leave',
    //         'details_of_leave' => 'Mandatory Leave',
    //         'number_of_days' => 5,
    //         'list_of_dates' => $formattedDates,
    //         'commutation' => 'Not Requested',
    //         'status' => 'Approved',
    //         'approved_days' => 5,
    //         'approved_dates' => $formattedDates,
    //         'remarks' => 'With Pay'
    //     ]);

    //     $this->dispatch('swal', [
    //         'title' => "Mandatory leave has been set successfully!",
    //         'icon' => 'success'
    //     ]);
    //     $this->closeModal();
    // }
    // public function submitMandatoryLeave()
    // {
    //     $this->validate([
    //         'selectedUser' => 'required',
    //         'list_of_dates' => 'required|array',
    //     ]);
    
    //     $numberOfDays = count($this->list_of_dates);
    
    //     // Get user and verify credits
    //     $user = User::find($this->selectedUser);
        
    //     // Check if user has enough credits
    //     if ($user->fl_claimable_credits < $numberOfDays || $user->vl_claimable_credits < $numberOfDays) {
    //         $this->closeModal();
    //         $this->dispatch('swal', [
    //             'title' => "Insufficient leave credits.",
    //             'icon' => 'error'
    //         ]);
    //         return;
    //     }
    
    //     // Check total mandatory leaves for the year
    //     $totalMandatoryLeavesThisYear = LeaveApplication::where('user_id', $this->selectedUser)
    //         ->where('details_of_leave', 'Mandatory Leave')
    //         ->whereYear('created_at', now()->year)
    //         ->sum('number_of_days');
    
    //     if (($totalMandatoryLeavesThisYear + $numberOfDays) > 5) {
    //         $this->closeModal();
    //         $this->dispatch('swal', [
    //             'title' => "Maximum 5 mandatory leave days per year allowed.",
    //             'icon' => 'error'
    //         ]);
    //         return;
    //     }
        
    //     // Update the credits
    //     $user->update([
    //         'fl_claimable_credits' => $user->fl_claimable_credits - $numberOfDays,
    //         'vl_claimable_credits' => $user->vl_claimable_credits - $numberOfDays,
    //         'fl_claimed_credits' => $user->fl_claimed_credits + $numberOfDays
    //     ]);
        
    //     // Get office division from office_divisions table
    //     $officeDepartment = 'N/A';
    //     if ($user->office_division_id) {
    //         $officeDivision = OfficeDivisions::find($user->office_division_id);
    //         $officeDepartment = $officeDivision ? $officeDivision->office_division : 'N/A';
    //     }
        
    //     // Get position from positions table
    //     $positionName = 'N/A';
    //     if ($user->position_id) {
    //         $position = Positions::find($user->position_id);
    //         $positionName = $position ? $position->position : 'N/A';
    //     }
        
    //     // Get salary
    //     $salary = 0;
    //     if ($user->payroll) {
    //         $salary = $user->payroll->rate_per_month;
    //     } elseif ($user->cosSkPayroll) {
    //         $salary = $user->cosSkPayroll->rate_per_month;
    //     } elseif ($user->cosRegPayroll) {
    //         $salary = $user->cosRegPayroll->rate_per_month;
    //     }
    
    //     sort($this->list_of_dates);
    //     $formattedDates = implode(',', $this->list_of_dates);
    
    //     LeaveApplication::create([
    //         'user_id' => $this->selectedUser,
    //         'name' => $user->name,
    //         'office_or_department' => $officeDepartment,
    //         'position' => $positionName,
    //         'salary' => $salary,
    //         'date_of_filing' => now(),
    //         'type_of_leave' => 'Mandatory Leave',
    //         'details_of_leave' => 'Mandatory Leave',
    //         'number_of_days' => $numberOfDays,
    //         'list_of_dates' => $formattedDates,
    //         'commutation' => 'Not Requested',
    //         'status' => 'Approved',
    //         'approved_days' => $numberOfDays,
    //         'approved_dates' => $formattedDates,
    //         'remarks' => 'With Pay'
    //     ]);
    
    //     $this->dispatch('swal', [
    //         'title' => "Mandatory leave has been set successfully!",
    //         'icon' => 'success'
    //     ]);
    //     $this->closeModal();
    // }
    public function submitMandatoryLeave()
    {
        $this->validate([
            'selectedUser' => 'required',
            'list_of_dates' => 'required|array',
        ]);

        $numberOfDays = count($this->list_of_dates);

        // Get user and leave credits
        $user = User::find($this->selectedUser);
        $leaveCredits = LeaveCredits::where('user_id', $this->selectedUser)->first();
        
        // Check if user has enough credits
        if ($leaveCredits->fl_claimable_credits < $numberOfDays || $leaveCredits->vl_claimable_credits < $numberOfDays) {
            $this->closeModal();
            $this->dispatch('swal', [
                'title' => "Insufficient leave credits.",
                'icon' => 'error'
            ]);
            return;
        }

        // Check total mandatory leaves for the year
        $totalMandatoryLeavesThisYear = LeaveApplication::where('user_id', $this->selectedUser)
            ->where('details_of_leave', 'Mandatory Leave')
            ->whereYear('created_at', now()->year)
            ->sum('number_of_days');

        if (($totalMandatoryLeavesThisYear + $numberOfDays) > 5) {
            $this->closeModal();
            $this->dispatch('swal', [
                'title' => "Maximum 5 mandatory leave days per year allowed.",
                'icon' => 'error'
            ]);
            return;
        }
        
        // Update the credits in LeaveCredits table
        $leaveCredits->update([
            'fl_claimable_credits' => $leaveCredits->fl_claimable_credits - $numberOfDays,
            'vl_claimable_credits' => $leaveCredits->vl_claimable_credits - $numberOfDays,
            'fl_claimed_credits' => $leaveCredits->fl_claimed_credits + $numberOfDays
        ]);
        
        // Get office division from office_divisions table
        $officeDepartment = 'N/A';
        if ($user->office_division_id) {
            $officeDivision = OfficeDivisions::find($user->office_division_id);
            $officeDepartment = $officeDivision ? $officeDivision->office_division : 'N/A';
        }
        
        // Get position from positions table
        $positionName = 'N/A';
        if ($user->position_id) {
            $position = Positions::find($user->position_id);
            $positionName = $position ? $position->position : 'N/A';
        }
        
        // Get salary
        $salary = 0;
        if ($user->payroll) {
            $salary = $user->payroll->rate_per_month;
        } elseif ($user->cosSkPayroll) {
            $salary = $user->cosSkPayroll->rate_per_month;
        } elseif ($user->cosRegPayroll) {
            $salary = $user->cosRegPayroll->rate_per_month;
        }

        sort($this->list_of_dates);
        $formattedDates = implode(',', $this->list_of_dates);

        LeaveApplication::create([
            'user_id' => $this->selectedUser,
            'name' => $user->name,
            'office_or_department' => $officeDepartment,
            'position' => $positionName,
            'salary' => $salary,
            'date_of_filing' => now(),
            'type_of_leave' => 'Mandatory Leave',
            'details_of_leave' => 'Mandatory Leave',
            'number_of_days' => $numberOfDays,
            'list_of_dates' => $formattedDates,
            'commutation' => 'Not Requested',
            'status' => 'Approved',
            'approved_days' => $numberOfDays,
            'approved_dates' => $formattedDates,
            'remarks' => 'With Pay'
        ]);

        $this->dispatch('swal', [
            'title' => "Mandatory leave has been set successfully!",
            'icon' => 'success'
        ]);
        $this->closeModal();
    }

    public function resetForm()
    {
        $this->reset([
            'selectedUser', 
            'new_date', 
            'list_of_dates'
        ]);
    }

    public function resetVariables()
    {
        $this->selectedUser = null;
        $this->list_of_dates = [];
    }

    public function updatedPageSize()
    {
        $this->resetPage();
    }

    public function exportToExcel($leaveId)
    {
        $leave = LeaveApplication::find($leaveId);
        
        if (!$leave) {
            $this->dispatch('swal', [
                'title' => "Error!",
                'text' => "Leave record not found.",
                'icon' => 'error'
            ]);
            return;
        }

        try {
            // Load the template file
            $templatePath = storage_path('app/public/leave_template/Mandatory Leave Form.xls');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();

            // Get the year from date_of_filing
            $year = Carbon::parse($leave->date_of_filing)->format('Y');

            // Map data to specific cells
            $worksheet->setCellValue('A8', "FOR CALENDAR YEAR " . $year);
            $worksheet->setCellValue('B11', $leave->name);
            $worksheet->setCellValue('B12', $leave->office_or_department);
            
            // Format the dates for display
            $dates = explode(',', $leave->approved_dates);
            foreach ($dates as $index => $date) {
                $formattedDate = ($index + 1) . ". " . Carbon::parse($date)->format('F d, Y');
                $cellRow = 16 + $index; // Assuming dates start at row 15
                $worksheet->setCellValue("A{$cellRow}", $formattedDate);
            }

            // Add logged in user name in C24 with bold formatting
            $loggedInUser = auth()->user()->name; // Assuming you're using Laravel's authentication
            $worksheet->setCellValue('C24', $loggedInUser);
            $worksheet->getStyle('C24')->getFont()->setBold(true);

            // Generate unique filename
            $fileName = 'Mandatory_Leave_' . $leave->name . '_' . $year . '.xlsx';
            
            // Create response
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            
            $response = new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
            $response->headers->set('Cache-Control', 'max-age=0');

            return $response;

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => "Error!",
                'text' => "An error occurred while generating the Excel file: " . $e->getMessage(),
                'icon' => 'error'
            ]);
            return null;
        }
    }

    public function mount()
    {
        // $this->users = User::where('user_role', 'emp')->get();
        $usersWithMandatoryLeave = LeaveApplication::where('details_of_leave', 'Mandatory Leave')
            ->whereYear('created_at', now()->year)
            ->pluck('user_id')
            ->toArray();
        
        $this->users = User::where('user_role', 'emp')
            ->whereNotIn('id', $usersWithMandatoryLeave)
            ->get();
    }

    public function render()
    {
        $mandatoryLeaves = LeaveApplication::where('details_of_leave', 'Mandatory Leave')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->pageSize);

        return view('livewire.admin.admin-mandatory-leave-table', [
            'mandatoryLeaves' => $mandatoryLeaves
        ]);
    }
}