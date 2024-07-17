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
        $this->changeStatus($id, 'preparing', 'Document request approved successfully.');
    }

    public function rejectRequest($id)
    {
        $this->changeStatus($id, 'rejected', 'Document request rejected.');
    }

    private function changeStatus($id, $status, $message)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = $status;
            $request->save();
            session()->flash('message', $message);
            $this->loadRequests();
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function uploadDocument($requestId)
    {
        if (empty($requestId)) {
            session()->flash('error', 'No document request selected.');
            return;
        }

        $request = DocRequest::find($requestId);
        if (!$request) {
            session()->flash('error', 'Document request not found.');
            return;
        }

        if (empty($this->uploadedFile[$requestId])) {
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

        $filePath = storage_path('app/public/' . $request->file_path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $request->filename);
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
