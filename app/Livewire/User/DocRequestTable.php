<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DocRequest; // Ensure you have the correct model imported
use Illuminate\Support\Facades\Auth;

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

        // Logic to handle the document request
        DocRequest::create([
            'user_id' => Auth::id(),
            'document_type' => $this->documentType,
            'date_requested' => now(),
            'status' => 'pending',
        ]);

        session()->flash('message', 'Document request submitted successfully.');

        // Reset the document type for further requests
        $this->documentType = null;
    }

    public function getRequestsProperty()
    {
        return DocRequest::where('user_id', Auth::id())->get();
    }

    public function render()
    {
        return view('livewire.user.doc-request-table', [
            'requests' => $this->requests,
        ]);
    }
}
