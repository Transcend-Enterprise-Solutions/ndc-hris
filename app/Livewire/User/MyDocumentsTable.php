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
    public $isDeleting = false;
    public $confirmDeleteModal = false;
    public $documentToDelete = null;
    public $fileSelected = false;

    protected $rules = [
        'file' => 'required|file|mimes:pdf|max:5020',
        'documentType' => 'required|string|max:255',
    ];

    protected $listeners = ['file-dropped' => 'handleDroppedFile'];

    public function mount()
    {
        $this->file = null;
    }

    public function handleDroppedFile($fileData)
    {
        $this->droppedFile = $fileData;
        $this->fileSelected = true;
        $this->dispatch('notify', ['message' => 'Document selected successfully!', 'type' => 'success']);
    }

    public function uploadDocument()
    {
        if ($this->droppedFile) {
            $this->file = $this->droppedFile;
        }
        $this->validate([
            'file' => 'required',
            'documentType' => 'required|string|max:255',
        ]);

        if ($this->documentAlreadyUploaded()) {
            $this->error = 'Document type "' . $this->documentType . '" has already been uploaded.';
            $this->isUploading = false;
            return;
        }

        $this->isUploading = true;

        try {
            if ($this->file instanceof \Illuminate\Http\UploadedFile) {
                $fileName = $this->file->getClientOriginalName();
                $filePath = $this->file->storeAs('public/upload/employee_document', $fileName);
                $mimeType = $this->file->getMimeType();
                $fileSize = $this->file->getSize();
            } else {
                $fileData = base64_decode(preg_replace('#^data:.*?;base64,#', '', $this->file));
                $fileName = 'NYC' . time() . '.pdf';
                $filePath = 'public/upload/employee_document/' . $fileName;
                Storage::put($filePath, $fileData);
                $mimeType = 'application/pdf';
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
            $this->fileSelected = false;
            $this->dispatch('notify', ['message' => 'Document uploaded successfully!', 'type' => 'success']);
            $this->dispatch('documentUploaded');
        } catch (\Exception $e) {
            $this->error = 'Error uploading document: ' . $e->getMessage();
        } finally {
            $this->isUploading = false;
        }
    }

    public function clearDroppedFile()
    {
        $this->droppedFile = null;
        $this->fileSelected = false;
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
        $this->isDeleting = true;

        $document = EmployeeDocument::find($this->documentToDelete);

        if ($document && $document->user_id === Auth::id()) {
            Storage::delete($document->file_path);
            $document->delete();
            $this->dispatch('notify', ['message' => 'Document deleted successfully!', 'type' => 'success']);
        } else {
            $this->dispatch('notify', ['message' => 'Document not found or unauthorized!', 'type' => 'error']);
        }

        $this->isDeleting = false;
        $this->confirmDeleteModal = false;
        $this->documentToDelete = null;
    }

    public function downloadDocument($documentId)
    {
        $document = EmployeeDocument::findOrFail($documentId);
        return Storage::download($document->file_path, $document->file_name, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $document->file_name . '"',
        ]);
    }

    public function availableDocumentTypes()
    {
        $allDocumentTypes = [
            '201_Documents' => '201 Documents',
            'SALN' => 'Statement of Assets, Liabilities and Net Worth (SALN)',
            'IPCR' => 'Individual Performance Commitment Review (IPCR)',
            'BIR1902' => 'BIR Form 1902',
            'BIR1905' => 'BIR Form 1905',
            'BIR2316' => 'BIR Form 2316',
            'COE' => 'Certificate of Employment',
            'Service Record' => 'Service Record',
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
            'isDeleting' => $this->isDeleting,
            'confirmDeleteModal' => $this->confirmDeleteModal,
        ]);
    }
}