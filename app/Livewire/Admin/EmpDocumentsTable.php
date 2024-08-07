<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\EmployeeDocument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class EmpDocumentsTable extends Component
{
    public $documentsByType = [];
    public $employeesWithoutUpload = [];
    public $tabs;
    public $documentToDelete = null;
    public $isDeleting = false;

    public function mount()
    {
        $this->tabs = [
            '201_Documents' => '201 Documents',
            'SALN' => 'SALN',
            'IPCR' => 'IPCR',
            'BIR1902' => 'BIR Form 1902',
            'BIR1905' => 'BIR Form 1905',
            'BIR2316' => 'BIR Form 2316',
            'COE' => 'Certificate of Employment',
            'Service Record' => 'Service Record',
        ];

        $this->loadDocuments();
        $this->loadEmployeesWithoutUpload();
    }

    public function loadDocuments()
    {
        $documents = EmployeeDocument::with('user')->get();
        $this->documentsByType = [];
        foreach ($documents as $document) {
            $this->documentsByType[$document->document_type][] = $document;
        }
    }
    public function loadEmployeesWithoutUpload()
    {
        $this->employeesWithoutUpload = [];
        $allEmployees = User::where('user_role', 'emp')->get();

        foreach ($this->tabs as $key => $label) {
            $employeesWithDocument = EmployeeDocument::where('document_type', $key)
                                        ->pluck('user_id')
                                        ->toArray();

            $this->employeesWithoutUpload[$key] = $allEmployees->reject(function ($employee) use ($employeesWithDocument) {
                return in_array($employee->id, $employeesWithDocument);
            });
        }
    }

    public function confirmDelete($id)
    {
        $this->documentToDelete = $id;
        $this->dispatch('show-delete-modal');
    }

    public function deleteRequest()
    {
        if ($this->documentToDelete) {
            $this->isDeleting = true;

            try {
                $document = EmployeeDocument::findOrFail($this->documentToDelete);

                if (Storage::exists($document->file_path)) {
                    Storage::delete($document->file_path);
                }

                $document->delete();

                $this->loadDocuments();
                $this->loadEmployeesWithoutUpload(); // Reload this as well
                $this->dispatch('swal', [
                    'title' => 'Document deleted successfully!',
                    'icon' => 'success'
                ]);
            } catch (\Exception $e) {
                $this->dispatch('swal', [
                    'title' => 'An error occurred while deleting the document.',
                    'icon' => 'error'
                ]);
            } finally {
                $this->isDeleting = false;
                $this->documentToDelete = null;
            }
        } else {
            $this->dispatch('swal', [
                'title' => 'No document selected for deletion!',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.emp-documents-table', [
            'documentsByType' => $this->documentsByType,
            'employeesWithoutUpload' => $this->employeesWithoutUpload,
            'tabs' => $this->tabs
        ]);
    }
}
