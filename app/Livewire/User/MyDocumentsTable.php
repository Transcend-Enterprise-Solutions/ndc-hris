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
    public $confirmDeleteModal = false; // Modal state
    public $documentToDelete = null; // Store document ID for deletion

    protected $rules = [
        'files.*' => 'required|file|max:10240', // 10MB Max
        'documentType' => 'required|string',
    ];

    public function uploadDocuments()
    {
        $this->validate(); // Validate files and document type
        $this->isUploading = true; // Start the upload process

        if ($this->documentAlreadyUploaded()) {
            $this->error = 'Document type "' . $this->documentType . '" has already been uploaded.';
            $this->isUploading = false; // End the upload process
            return; // Stop further processing if document type exists
        }

        foreach ($this->files as $file) {
            $this->processFileUpload($file);
        }

        $this->reset(['files', 'documentType']);
        $this->message = 'Documents uploaded successfully.';
        $this->isUploading = false; // End the upload process
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

            $fileName = $file->getClientOriginalName(); // Use original file name
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
            $this->isUploading = false; // End the upload process
        }
    }

    public function confirmDelete($documentId)
    {
        $this->documentToDelete = $documentId; // Store document ID
        $this->confirmDeleteModal = true; // Show the modal
    }

    public function deleteDocument()
    {
        $document = EmployeeDocument::find($this->documentToDelete);

        if ($document && $document->user_id === Auth::id()) {
            Storage::delete($document->file_path); // Delete the file from storage
            $document->delete(); // Delete the document from the database
            $this->message = 'Document deleted successfully.';
        } else {
            $this->error = 'Document not found or permission denied.';
        }

        $this->confirmDeleteModal = false; // Hide the modal
        $this->documentToDelete = null; // Reset document ID
    }

    public function updateDocument($documentId, $newDocumentType)
    {
        $document = EmployeeDocument::find($documentId);

        if ($document && $document->user_id === Auth::id()) {
            $document->document_type = $newDocumentType;
            $document->save();
            $this->message = 'Document updated successfully.';
        } else {
            $this->error = 'Document not found or permission denied.';
        }
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
