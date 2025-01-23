<?php
namespace App\Livewire\Notification;

use App\Models\WfhLocationRequests;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;
use App\Models\OfficialBusiness;

class NotificationsDropdown extends Component
{
    public $notifications;
    public $unreadCount;
    public $locRequestCount;
    public $docRequestCount;
    public $obRequestCount;
    public $latestDocNotifDate;
    public $latestLocNotifDate;
    public $latestObNotifDate;

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        if ($user->user_role === 'sa') {
            $this->locRequestCount = WfhLocationRequests::where('status', 0)->count();
            $this->obRequestCount = OfficialBusiness::where('status', 0)->count();
            $this->docRequestCount = NotificationModel::where('read', 0)->where('type', 'request')->get()->count();

            $this->latestDocNotifDate = NotificationModel::where('read', 0)->where('type', 'request')->latest()->first();
            $this->latestLocNotifDate = NotificationModel::where('read', 0)->where('type', 'locrequest')->latest()->first();
            $this->latestObNotifDate = NotificationModel::where('read', 0)->where('type', 'obrequest')->latest()->first();

            // $this->notifications = NotificationModel::with('docRequest')
            //                             ->where('read', 0)
            //                             ->where('type', 'request')
            //                             ->latest()
            //                             ->get();

            $this->notifications = NotificationModel::where('read', 0)
                                        ->whereIn('type', ['request', 'locrequest', 'obrequest'])
                                        ->selectRaw('type, count(*) as unread_count')
                                        ->groupBy('type')
                                        ->latest()
                                        ->get();
                                    
            $this->unreadCount = NotificationModel::where('read', 0)
                                        ->whereIn('type', ['request', 'locrequest', 'obrequest'])
                                        ->get()->count();
        } else {
            $notifications = NotificationModel::with('docRequest')
                                        ->where('read', 0)
                                        ->where('user_id', $user->id)
                                        ->where('type',  'completed')
                                        ->orWhere('type',  'approvedlocrequest')
                                        ->orWhere('type',  'disapprovedlocrequest')
                                        ->latest()
                                        ->get();

            $this->notifications = $notifications->groupBy('type')
                ->map(function ($group) {
                    return [
                        'type' => $group->first()->type,
                        'count' => $group->count(),
                        'read' => $group->first()->read,
                        'latest' => $group->first(),
                        'ids' => $group->pluck('id')->toArray(),
                    ];
                });
            $this->unreadCount = $this->notifications->where('read', 0)->count();
        }
    }

    public function markGroupAsRead($type)
    {
        $user = Auth::user();
        $query = NotificationModel::where('type', $type)
            ->where('read', false);

        if ($user->user_role === 'sa') {
            $query->where('type', 'request')
            ->orWhere('type', 'locrequest')
            ->orWhere('type', 'obrequest');
        } else {
            $query->where('user_id', $user->id);
        }

        $query->update(['read' => true]);
        $this->refreshNotifications();
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $query = NotificationModel::where('read', false);

        if ($user->user_role === 'sa') {
            $query->where('type', 'request')
            ->orWhere('type', 'locrequest')
            ->orWhere('type', 'obrequest');
        } else {
            $query->where('user_id', $user->id);
        }

        $query->update(['read' => true]);
        $this->refreshNotifications();
    }

    private function getDocumentTypeLabel($documentType)
    {
        $documentTypes = [
            'employment' => 'Certificate of Employment',
            'employmentCompensation' => 'Certificate of Employment with Compensation',
            'leaveCredits' => 'Certificate of Leave Credits',
            'ipcrRatings' => 'Certificate of IPCR Ratings',
        ];
        return $documentTypes[$documentType] ?? $documentType;
    }

    private function getDocRequestMessage(){
        if ($this->docRequestCount === 1) {
            return '1 new document request pending for approval';
        }
        return ($this->docRequestCount) . ' pending document request for approval';
    }

    private function getLocRequestMessage(){
        if ($this->locRequestCount === 1) {
            return '1 new WFH location request pending for approval';
        }
        return ($this->locRequestCount) . ' pending WFH location request for approval';
    }

    private function getOBRequestMessage(){
        if ($this->obRequestCount === 1) {
            return '1 new OB location request pending for approval';
        }
        return ($this->obRequestCount) . ' pending OB request for approval';
    }

    public function render(){
        if (Auth::user()->user_role === 'sa') {
            return view('livewire.notification.notifications-dropdown', [
                'notifications' => $this->notifications,
                'unreadCount' => $this->unreadCount,
            ]);
        } else {
            return view('livewire.notification.notifications-dropdown', [
                'groupedNotifications' => $this->notifications,
                'unreadCount' => $this->unreadCount,
            ]);
        }
    }
}