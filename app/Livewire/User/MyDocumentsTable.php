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

    public $file;
    public $droppedFile;
    public $documentType = '';
    public $error = '';
    public $message = '';
    public $isUploading = false;
    public $isDeleting = false; // Separate state for deleting
    public $confirmDeleteModal = false;
    public $documentToDelete = null;

    protected $rules = [
        'file' => 'required|file|max:10240', // 10MB Max
        'documentType' => 'required|string',
    ];

    protected $listeners = ['file-dropped' => 'handleDroppedFile'];

    public function mount()
    {
        $this->file = null;
    }

    public function handleDroppedFile($fileData)
    {
        $this->droppedFile = $fileData;
        $this->dispatch('notify', ['message' => 'File dropped: ' . substr($this->droppedFile, 0, 20) . '...', 'type' => 'success']);
    }

    public function uploadDocument()
    {
        if ($this->droppedFile) {
            $this->file = $this->droppedFile;
        }

        $this->validate([
            'file' => 'required',
            'documentType' => 'required|string',
        ]);

        if ($this->documentAlreadyUploaded()) {
            $this->error = 'Document type "' . $this->documentType . '" has already been uploaded.';
            $this->isUploading = false;
            return;
        }

        $this->isUploading = true;

        try {
            if ($this->file instanceof \Livewire\TemporaryUploadedFile) {
                $fileName = $this->file->getClientOriginalName();
                $filePath = $this->file->storeAs('public/upload/employee_document', $fileName);
                $mimeType = $this->file->getMimeType();
                $fileSize = $this->file->getSize();
            } else {
                $fileData = base64_decode(preg_replace('#^data:.*?;base64,#', '', $this->file));
                $fileName = 'dropped_file_' . time() . '.txt';
                $filePath = 'public/upload/employee_document/' . $fileName;
                Storage::put($filePath, $fileData);
                $mimeType = mime_content_type(Storage::path($filePath));
                $fileSize = strlen($fileData);
            }

            EmployeeDocument::create([
                'user_id' => Auth::id(),
                'document_type' => $this->documentType,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
            ]);

            $this->reset(['file', 'droppedFile', 'documentType']);
            $this->dispatch('notify', ['message' => 'Document uploaded successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->error = 'Error uploading document: ' . $e->getMessage();
        } finally {
            $this->isUploading = false;
        }

        $this->dispatch('refreshDocuments');
    }

    protected function documentAlreadyUploaded()
    {
        return EmployeeDocument::where('user_id', Auth::id())
            ->where('document_type', $this->documentType)
            ->exists();
    }

    public function confirmDelete($documentId)
    {
        $this->documentToDelete = $documentId;
        $this->confirmDeleteModal = true;
    }

    public function deleteDocument()
    {
        $this->isDeleting = true; // Set deleting state to true

        $document = EmployeeDocument::find($this->documentToDelete);

        if ($document && $document->user_id === Auth::id()) {
            Storage::delete($document->file_path);
            $document->delete();
            $this->dispatch('notify', ['message' => 'Document deleted successfully!', 'type' => 'success']);
        } else {
            $this->dispatch('notify', ['message' => 'Document not found or unauthorized!', 'type' => 'error']);
        }

        $this->isDeleting = false; // Reset deleting state after process
        $this->confirmDeleteModal = false;
        $this->documentToDelete = null;

        // Refresh the documents list
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
            'isDeleting' => $this->isDeleting, // Pass the new property to the view
            'confirmDeleteModal' => $this->confirmDeleteModal,
        ]);
    }
}
