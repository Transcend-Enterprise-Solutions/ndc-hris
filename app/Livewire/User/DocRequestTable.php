<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\DocRequest;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocRequestTable extends Component
{

    public $preparingCount = 0;
    public $completedCount = 0;
    public $rejectedCount = 0;
    public $documentType;
    public $availableDocumentTypes = [
        'certificateOfEmployment' => 'Certificate of Employment',
        'employmentCompensation' => 'Certificate of Employment with Compensation',
        'leaveCredits' => 'Certificate of Leave Credits',
        'ipcrRatings' => 'Certificate of IPCR Ratings',
    ];

    public $showRatingModal = false;
    public $currentDocRequestId;
    public $ratings = [
        'responsiveness' => null,
        'reliability' => null,
        'access_facilities' => null,
        'communication' => null,
        'cost' => null,
        'integrity' => null,
        'assurance' => null,
        'outcome' => null,
    ];

    public $descriptions = [
        'responsiveness' => 'I spent a reasonable amount of time for my transaction',
        'reliability' => 'The office followed the transaction\'s requirements and steps based on the information provided',
        'access_facilities' => 'The steps I needed to do for my transaction were easy and simple',
        'communication' => 'I easily found information about my transaction from the office or its website',
        'cost' => 'I paid a reasonable amount of fees for my transaction',
        'integrity' => 'I feel the office was fair to everyone, or "walang palakasan", during my transaction',
        'assurance' => 'I was treated courteously by the staff, and (if asked for help) the staff was helpful',
        'outcome' => 'I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me',
    ];
    public function updateNotificationCounts()
    {
    $userId = Auth::id();

    $this->preparingCount = Notification::where('user_id', $userId)
        ->where('type', 'approved')
        ->where('read', 0)
        ->count();

    $this->completedCount = Notification::where('user_id', $userId)
        ->where('type', 'completed')
        ->where('read', 0)
        ->count();

    $this->rejectedCount = Notification::where('user_id', $userId)
        ->where('type', 'rejected')
        ->where('read', 0)
        ->count();
    }
    public function markNotificationsAsRead($type)
    {
    Notification::where('user_id', Auth::id())
        ->where('type', $type)
        ->where('read', 0)
        ->update(['read' => 1]);

    $this->updateNotificationCounts();
    }
    public function mount()
    {
    $this->updateNotificationCounts();
    }

    public function requestDocument()
    {
        $this->validate([
            'documentType' => 'required',
        ]);

        $docRequest = DocRequest::create([
            'user_id' => Auth::id(),
            'document_type' => $this->documentType,
            'date_requested' => now(),
            'status' => 'pending',
        ]);

        // Create a notification entry
        Notification::create([
            'user_id' => Auth::id(),
            'doc_request_id' => $docRequest->id,
            'type' => 'request',
            'notif' => 'document',
            'read' => 0,
        ]);

        $this->dispatch('swal', [
            'title' => 'Document Request sent successfully!',
            'icon' => 'success'
        ]);
        $this->documentType = null;
    }


    public function downloadDocument($id)
    {
        $request = DocRequest::find($id);
        if (!$request || !$request->file_path) {
            $this->dispatch('swal', [
                'title' => 'Document not found.',
                'icon' => 'error'
            ]);
            return;
        }

        $rating = Rating::where('user_id', Auth::id())
            ->where('doc_request_id', $id)
            ->first();

        if (!$rating) {
            $this->currentDocRequestId = $id;
            $this->showRatingModal = true;

        } else {
            return $this->performDownload($request);

        }
    }
    public function performDownload($request)
    {
        $filePath = 'public/' . $request->file_path;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $request->filename);
            $this->dispatch('swal', [
                'title' => 'Document downloaded successfully',
                'icon' => 'success'
            ]);
        } else {
            $this->dispatch('swal', [
                'title' => 'File not found on the server.',
                'icon' => 'error'
            ]);
        }
    }

    public function submitRating()
    {
        $this->validate([
            'ratings.*' => 'required|integer|between:1,5',
        ], [
            'ratings.*.required' => 'Please rate all the criteria in the form!',
        ]);
        // Calculate the overall rating
        $overallRating = array_sum($this->ratings) / count($this->ratings);
        Rating::create([
            'user_id' => Auth::id(),
            'doc_request_id' => $this->currentDocRequestId,
            'responsiveness' => $this->ratings['responsiveness'],
            'reliability' => $this->ratings['reliability'],
            'access_facilities' => $this->ratings['access_facilities'],
            'communication' => $this->ratings['communication'],
            'cost' => $this->ratings['cost'],
            'integrity' => $this->ratings['integrity'],
            'assurance' => $this->ratings['assurance'],
            'outcome' => $this->ratings['outcome'],
            'overall' => $overallRating,
        ]);

        $this->showRatingModal = false;
        $request = DocRequest::find($this->currentDocRequestId);
        if ($request) {
            return $this->performDownload($request);
        }
    }



    public function getRequestsProperty()
    {
        return DocRequest::where('user_id', Auth::id())
            ->with('rating')
            ->orderBy('date_requested', 'desc')
            ->get();
    }

    public function render()
    {
    return view('livewire.user.doc-request-table', [
        'requests' => $this->requests,
        'preparingCount' => $this->preparingCount,
        'completedCount' => $this->completedCount,
        'rejectedCount' => $this->rejectedCount,
    ]);
    }
}
