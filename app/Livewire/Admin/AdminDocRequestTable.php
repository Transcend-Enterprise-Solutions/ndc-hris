<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DocRequest;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdminDocRequestTable extends Component
{
    use WithFileUploads;

    public $requests;
    public $uploadedFile = [];
    public $documentTypes = [];
    public $selectedDocumentTypes = [];
    public $selectAll = false;

    public function mount()
    {
        $this->loadRequests();
        $this->loadDocumentTypes();
    }

    public function loadDocumentTypes()
    {
        $this->documentTypes = DocRequest::distinct('document_type')->pluck('document_type')->toArray();
    }

    public function loadRequests()
    {
        $query = DocRequest::with('user');

        if (!empty($this->selectedDocumentTypes)) {
            $query->whereIn('document_type', $this->selectedDocumentTypes);
        }

        $this->requests = $query->get();
    }

    public function updatedSelectedDocumentTypes()
    {
        $this->selectAll = count($this->selectedDocumentTypes) === count($this->documentTypes);
        $this->loadRequests();
    }

    public function updatedSelectAll($value)
    {
        $this->selectedDocumentTypes = $value ? $this->documentTypes : [];
        $this->loadRequests();
    }

    public function approveRequest($id)
    {
        $this->changeStatus($id, 'preparing', 'Document request approved successfully.');
    }

    public function rejectRequest($id)
    {
        $this->changeStatus($id, 'rejected', 'Document request rejected!');
    }

    private function changeStatus($id, $status, $message)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = $status;
            if ($status === 'rejected') {
                $request->date_completed = now();
            }
            $request->save();
            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);
            $this->loadRequests();
        } else {
            $this->dispatch('notify', [
                'message' => 'Document request not found!',
                'type' => 'error'
            ]);
        }
    }

    public function uploadDocument($requestId)
    {
        $this->validate([
            "uploadedFile.$requestId" => 'required|file|max:10240', // 10MB Max
        ]);

        $request = DocRequest::find($requestId);
        if (!$request) {
            $this->dispatch('notify', [
                'message' => 'Document request not found!',
                'type' => 'error'
            ]);
            return;
        }

        $path = $this->uploadedFile[$requestId]->store('documents', 'public');
        $request->file_path = $path;
        $request->filename = $this->uploadedFile[$requestId]->getClientOriginalName();
        $request->status = 'completed';
        $request->date_completed = now();
        $request->save();

        $this->dispatch('notify', [
            'message' => 'Document uploaded successfully!',
            'type' => 'success'
        ]);

        $this->uploadedFile[$requestId] = null;
        $this->loadRequests();
    }

    public function downloadDocument($id)
    {
        $request = DocRequest::find($id);
        if (!$request || !$request->file_path) {
            $this->dispatch('notify', [
                'message' => 'Document not found!',
                'type' => 'error'
            ]);
            return;
        }

        $filePath = storage_path('app/public/' . $request->file_path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $request->filename);
        } else {
            $this->dispatch('notify', [
                'message' => 'File not found!',
                'type' => 'error'
            ]);
        }
    }

    public function deleteRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            if ($request->file_path && Storage::disk('public')->exists($request->file_path)) {
                Storage::disk('public')->delete($request->file_path);
            }
            $request->delete();
            $this->dispatch('notify', [
                'message' => 'Document Request Deleted!',
                'type' => 'success'
            ]);
            $this->loadRequests();
        } else {
            $this->dispatch('notify', [
                'message' => 'Document request not found!',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.admin-doc-request-table');
    }
}
