<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyDocumentsTable extends Component
{
    use WithFileUploads;

    public $files = [];
    public $documentType = '';
    public $error = '';
    public $message = '';
    public $isUploading = false;
    public $confirmDeleteModal = false;
    public $documentToDelete = null;

    protected $rules = [
        'files.*' => 'required|file|max:10240', // 10MB Max
        'documentType' => 'required|string',
    ];

    public function uploadDocuments()
    {
        $this->validate();
        $this->isUploading = true;

        if ($this->documentAlreadyUploaded()) {
            $this->error = 'Document type "' . $this->documentType . '" has already been uploaded.';
            $this->isUploading = false;
            return;
        }

        foreach ($this->files as $file) {
            $this->processFileUpload($file);
        }

        $this->reset(['files', 'documentType']);
        $this->message = 'Documents uploaded successfully.';
        $this->isUploading = false;

        // Dispatch an event to refresh documents
        $this->dispatch('refreshDocuments');
    }

    protected function documentAlreadyUploaded()
    {
        return EmployeeDocument::where('user_id', Auth::id())
            ->where('document_type', $this->documentType)
            ->exists();
    }

    protected function processFileUpload($file)
    {
        try {
            if (!$file->isValid()) {
                throw new \Exception('One or more files are invalid.');
            }

            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('public/upload/employee_document', $fileName);

            EmployeeDocument::create([
                'user_id' => Auth::id(),
                'document_type' => $this->documentType,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            $this->error = 'Error uploading document: ' . $e->getMessage();
            $this->isUploading = false;
        }
    }

    public function confirmDelete($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->confirmDeleteModal = true;
    }

    public function deleteDocument()
    {
        $document = EmployeeDocument::find($this->documentToDelete);

        if ($document && $document->user_id === Auth::id()) {
            Storage::delete($document->file_path);
            $document->delete();
            $this->message = 'Document deleted successfully.';
        } else {
            $this->error = 'Document not found or permission denied.';
        }

        $this->confirmDeleteModal = false;
        $this->documentToDelete = null;

        // Dispatch an event to refresh documents
        $this->dispatch('refreshDocuments');
    }

    public function availableDocumentTypes()
    {
        $allDocumentTypes = [
            'saln' => 'Statement of Assets, Liabilities and Net Worth (SALN)',
            'ipcr' => 'Individual Performance Commitment Review (IPCR)',
            'bir1902' => 'BIR Form 1902',
            'bir1905' => 'BIR Form 1905',
            'bir2316' => 'BIR Form 2316',
            'employment_cert' => 'Certificate of Employment',
            'service_record' => 'Service Record',
        ];

        $uploadedDocumentTypes = EmployeeDocument::where('user_id', Auth::id())
            ->pluck('document_type')
            ->toArray();

        return array_diff_key($allDocumentTypes, array_flip($uploadedDocumentTypes));
    }

    public function render()
    {
        $documents = EmployeeDocument::where('user_id', Auth::id())->get();
        $availableDocumentTypes = $this->availableDocumentTypes();

        return view('livewire.user.my-documents-table', [
            'documents' => $documents,
            'availableDocumentTypes' => $availableDocumentTypes,
            'isUploading' => $this->isUploading,
            'confirmDeleteModal' => $this->confirmDeleteModal,
        ]);
    }
}
