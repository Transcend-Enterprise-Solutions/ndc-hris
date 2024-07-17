<?php

namespace App\Livewire\EmployeeManagement\Admin;

use Livewire\Component;
use App\Models\DocRequest;
use Livewire\WithFileUploads;

class AdminDocRequestTable extends Component
{
    use WithFileUploads;

    public $requests;
    public $selectedRequestId;
    public $uploadedFile;

    public function mount()
    {
        $this->requests = DocRequest::with('user')->get(); // Fetch all document requests
    }

    public function openStatusOptions($id)
    {
        $this->selectedRequestId = ($this->selectedRequestId === $id) ? null : $id; // Toggle visibility
    }

    public function approveRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = 'preparing';
            $request->save(); // Save updated status

            session()->flash('message', 'Document request approved successfully.');
            $this->mount(); // Refresh the requests
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function rejectRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->status = 'rejected';
            $request->save(); // Save updated status

            session()->flash('message', 'Document request rejected successfully.');
            $this->mount(); // Refresh the requests
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function uploadDocument()
    {
        $request = DocRequest::find($this->selectedRequestId);

        if (!$request) {
            session()->flash('error', 'Document request not found.');
            return;
        }

        if (!$this->uploadedFile) {
            session()->flash('error', 'No file uploaded.');
            return;
        }

        // Save the uploaded file
        $path = $this->uploadedFile->store('documents');

        // Update the request with the file path and set status to completed
        $request->file_path = $path;
        $request->status = 'completed';
        $request->date_completed = now(); // Set completion date
        $request->save(); // Save changes

        session()->flash('message', 'Document uploaded and status updated to completed.');
        $this->mount(); // Refresh the requests
        $this->selectedRequestId = null; // Reset selected ID
    }

    public function deleteRequest($id)
    {
        $request = DocRequest::find($id);
        if ($request) {
            $request->delete();
            session()->flash('message', 'Document request deleted successfully.');
            $this->mount(); // Refresh the requests
        } else {
            session()->flash('error', 'Document request not found.');
        }
    }

    public function render()
    {
        return view('livewire.employee-management.admin.admin-doc-request-table');
    }
}
