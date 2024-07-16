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
    public $uploadRequestId;

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->requests = DocRequest::with('user')->get();
    }

    public function updateStatus($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $statusOrder = ['pending', 'preparing', 'completed', 'rejected'];
            $currentIndex = array_search($request->status, $statusOrder);
            $nextIndex = ($currentIndex + 1) % count($statusOrder);
            $request->status = $statusOrder[$nextIndex];
            $request->save();
            $this->loadRequests();
        }
    }

    public function approveRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = 'preparing';
            $request->save();
            session()->flash('message', 'Document request approved successfully.');
            $this->loadRequests();
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function rejectRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = 'rejected';
            $request->save();
            session()->flash('message', 'Document request rejected.');
            $this->loadRequests();
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function uploadDocument($requestId)
    {
        $this->uploadRequestId = $requestId;

        if (empty($this->uploadRequestId)) {
            session()->flash('error', 'No document request selected.');
            return;
        }

        $request = DocRequest::find($this->uploadRequestId);

        if (!$request) {
            session()->flash('error', 'Document request not found.');
            return;
        }

        if (!isset($this->uploadedFile[$requestId]) || !$this->uploadedFile[$requestId]) {
            session()->flash('error', 'No file uploaded.');
            return;
        }

        $path = $this->uploadedFile[$requestId]->store('documents', 'public');
        $request->file_path = $path;
        $request->filename = $this->uploadedFile[$requestId]->getClientOriginalName();
        $request->status = 'completed';
        $request->date_completed = now();
        $request->save();

        session()->flash('message', 'Document uploaded successfully.');
        $this->resetUploadFields($requestId);
        $this->loadRequests();
    }

    public function downloadDocument($id)
    {
        $request = DocRequest::find($id);

        if (!$request || !$request->file_path) {
            session()->flash('error', 'Document not found.');
            return;
        }

        if (Storage::disk('public')->exists($request->file_path)) {
            return response()->download(Storage::disk('public')->path($request->file_path), $request->filename);
        } else {
            session()->flash('error', 'File not found on the server.');
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
            session()->flash('message', 'Document request deleted.');
            $this->loadRequests();
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    private function resetUploadFields($requestId)
    {
        unset($this->uploadedFile[$requestId]);
        $this->uploadRequestId = null;
    }

    public function render()
    {
        return view('livewire.admin.admin-doc-request-table');
    }
}
