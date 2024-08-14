<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\DocRequest;
use App\Models\Notification;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Rating;

class AdminDocRequestTable extends Component
{
    use WithFileUploads;

    public $requests;
    public $uploadedFile = [];
    public $documentTypes = [];
    public $selectedDocumentTypes = [];
    public $selectAll = false;
    public $pendingCount = 0;
    public $preparingCount = 0;

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
        $query = DocRequest::with(['user', 'rating']);

        if (!empty($this->selectedDocumentTypes)) {
            $query->whereIn('document_type', $this->selectedDocumentTypes);
        }

        $this->requests = $query->get();
        $this->pendingCount = $this->requests->where('status', 'pending')->count();
        $this->preparingCount = $this->requests->where('status', 'preparing')->count();
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
        $this->changeStatus($id, 'preparing', 'Document request approved successfully.', 'approved');
    }

    public function rejectRequest($id)
    {
        $this->changeStatus($id, 'rejected', 'Document request rejected!', 'rejected');
    }

    private function changeStatus($id, $status, $message, $notificationType)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = $status;
            if ($status === 'rejected') {
                $request->date_completed = now();
            }
            $request->save();

            // Use updateOrCreate for notifications
            Notification::updateOrCreate(
                [
                    'doc_request_id' => $request->id,
                    'user_id' => $request->user_id,
                ],
                [
                    'type' => $notificationType,
                    'read' => false,
                ]
            );

            $this->dispatch('swal', [
                'title' => $message,
                'icon' => 'success'
            ]);
            $this->loadRequests();
        } else {
            $this->dispatch('swal', [
                'title' => 'Document request not found!',
                'icon' => 'error'
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
            $this->dispatch('swal', [
                'title' => 'Document request not found!',
                'icon' => 'error'
            ]);
            return;
        }

        $path = $this->uploadedFile[$requestId]->store('documents', 'public');
        $request->file_path = $path;
        $request->filename = $this->uploadedFile[$requestId]->getClientOriginalName();
        $request->status = 'completed';
        $request->date_completed = now();
        $request->save();

        // Use updateOrCreate for notifications
        Notification::updateOrCreate(
            [
                'doc_request_id' => $request->id,
                'user_id' => $request->user_id,
            ],
            [
                'type' => 'completed',
                'read' => false,
            ]
        );

        $this->dispatch('swal', [
            'title' => 'Document uploaded successfully!',
            'icon' => 'success'
        ]);

        $this->uploadedFile[$requestId] = null;
        $this->loadRequests();
    }

    public function deleteRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            if ($request->file_path && Storage::disk('public')->exists($request->file_path)) {
                Storage::disk('public')->delete($request->file_path);
            }
            $request->delete();



            $this->dispatch('swal', [
                'title' => 'Document Request Deleted!',
                'icon' => 'success'
            ]);
            $this->loadRequests();
        } else {
            $this->dispatch('swal', [
                'title' => 'Document request not found!',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.admin-doc-request-table');
    }
}
