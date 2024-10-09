<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EmployeesDtr;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class DtrTable extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $searchTerm = '';
    public $startDate;
    public $endDate;
    public $sortField = 'date';
    public $sortDirection = 'asc';
    public $eSignature;
    public $attachment;
    public $fileName;
    public $remarks;
    public $selectedDtrId;
    public $currentAttachment;
    public $pageSize = 15; 
    public $pageSizes = [10, 20, 30, 50, 100]; 

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'sortField' => ['except' => 'date'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = ['openModal'];

    public function placeholder()
    {
        return <<<'HTML'
        <div class="flex w-full flex-col gap-2">
            <livewire:skeleton/>
        </div>
        HTML;
    }

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

        $dtrs = $query->paginate($this->pageSize);

        return view('livewire.user.dtr-table', ['dtrs' => $dtrs]);
    }

    public function openModal($dtrId)
    {
        $dtr = EmployeesDtr::find($dtrId);
        if ($dtr) {
            $this->selectedDtrId = $dtrId;
            $this->remarks = $dtr->remarks;
            $this->currentAttachment = $dtr->attachment;
            $this->fileName = $dtr->attachment ? basename($dtr->attachment) : '';
            $this->dispatch('modal-opened', [
                'dtrId' => $this->selectedDtrId,
                'remarks' => $this->remarks,
                'fileName' => $this->fileName
            ]);
        }
    }

    public function updatedAttachment()
    {
        $this->validate([
            'attachment' => 'file|max:10240', // 10MB Max
        ]);

        $this->fileName = $this->attachment->getClientOriginalName();
    }

    public function updateRemarks()
    {
        $dtr = EmployeesDtr::find($this->selectedDtrId);
        if ($dtr) {
            $dtr->remarks = $this->remarks;
            
            if ($this->attachment) {
                // Delete old file if exists
                if ($dtr->attachment) {
                    Storage::delete($dtr->attachment);
                }
                
                $path = $this->attachment->store('public/upload/employee_document');
                $dtr->attachment = $path;
                $this->currentAttachment = $path;
                $this->fileName = $this->attachment->getClientOriginalName();
            }
            
            $dtr->save();

            $this->dispatch('swal', [
                'title' => 'Remarks and attachment updated successfully!',
                'icon' => 'success'
            ]);

            $this->dispatch('modal-updated', [
                'fileName' => $this->fileName
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'Record not found!',
                'icon' => 'error'
            ]);
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


    public function exportToPdf($signatoryName)
    {
        $user = Auth::user();

        $this->validate([
            'eSignature' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $query = EmployeesDtr::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$this->startDate, $this->endDate]);

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('date', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('day_of_week', 'like', '%'.$this->searchTerm.'%')
                  ->orWhere('location', 'like', '%'.$this->searchTerm.'%');
            });
        }

        $dtrs = $query->orderBy('date')->get();

        if ($dtrs->isEmpty()) {
            $this->dispatch('swal', [
                'title' => 'No DTR records found for the selected date range.',
                'icon' => 'error'
            ]);
            return;
        }

        $eSignaturePath = null;
        if ($this->eSignature) {
            $eSignaturePath = $this->eSignature->store('temp', 'public');
        }

        // Create a collection with a single key-value pair
        $groupedDtrs = collect([$user->name => $dtrs]);

        $pdf = Pdf::loadView('pdf.dtr', [
            'dtrs' => $groupedDtrs,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'signatoryName' => $signatoryName,
            'eSignaturePath' => $eSignaturePath,
            'userName' => $user->name,
            'empCode' => $user->emp_code,
        ]);

        $this->dispatch('swal', [
            'title' => 'DTR Exported Successfully!',
            'icon' => 'success'
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'dtr_report.pdf');

        if ($eSignaturePath) {
            Storage::disk('public')->delete($eSignaturePath);
        }
    }
}