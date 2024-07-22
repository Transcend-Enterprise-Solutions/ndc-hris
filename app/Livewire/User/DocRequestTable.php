<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DocRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocRequestTable extends Component
{
    public $documentType;
    public $availableDocumentTypes = [
        'employment' => 'Certificate of Employment',
        'employmentCompensation' => 'Certificate of Employment with Compensation',
        'leaveCredits' => 'Certificate of Leave Credits',
        'ipcrRatings' => 'Certificate of IPCR Ratings',
    ];

    public function requestDocument()
    {
        $this->validate([
            'documentType' => 'required',
        ]);

        DocRequest::create([
            'user_id' => Auth::id(),
            'document_type' => $this->documentType,
            'date_requested' => now(),
            'status' => 'pending',
        ]);

        $this->dispatch('notify', [
            'message' => 'Document Request sent successfully!',
            'type' => 'success'
        ]);
        $this->documentType = null;
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
        $this->dispatch('notify', [
            'message' => 'Document downloaded successfully!',
            'type' => 'success'
        ]);
    }

    public function getRequestsProperty()
    {
        return DocRequest::where('user_id', Auth::id())
            ->orderBy('date_requested', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.doc-request-table', [
            'requests' => $this->requests,
        ]);
    }
}
