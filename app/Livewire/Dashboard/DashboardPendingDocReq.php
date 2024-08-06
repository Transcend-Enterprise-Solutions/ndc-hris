<?php

namespace App\Livewire\Dashboard;

use App\Models\DocRequest;
use Livewire\Component;

class DashboardPendingDocReq extends Component
{
    public $totalRequests;
    public $pendingRequests;
    public $completedRequests;
    public $requestsByType;
    public $requestsOverTime;
    public $averageCompletionTime;

    public function mount()
    {
        $this->totalRequests = DocRequest::count();
        $this->pendingRequests = DocRequest::where('status', 'pending')->count();
        $this->completedRequests = DocRequest::where('status', 'completed')->count();

        $this->requestsByType = DocRequest::selectRaw('document_type, COUNT(*) as count')
            ->groupBy('document_type')
            ->pluck('count', 'document_type')
            ->toArray();

        $this->requestsOverTime = DocRequest::selectRaw('DATE(date_requested) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $this->averageCompletionTime = DocRequest::whereNotNull('date_completed')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, date_requested, date_completed)) as avg_time')
            ->value('avg_time');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-pending-doc-req');
    }
}
