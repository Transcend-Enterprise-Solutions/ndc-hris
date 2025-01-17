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
    public $obRequestCount;

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        $query = NotificationModel::with('docRequest')
            ->where('read', 0)
            ->latest();

        if ($user->user_role === 'sa') {
            // 'sa' users see only notifications
            $this->locRequestCount = WfhLocationRequests::where('status', 0)->count();
            $this->obRequestCount = OfficialBusiness::where('status', 0)->count();

            $this->notifications = $query->where('type', 'request')
                                        ->orWhere('type', 'locrequest')    
                                        ->orWhere('type', 'obrequest')    
                                        ->get();
            $this->unreadCount = $this->notifications->where('read', 0)->count();
        } else {
            // Non-'sa' users see only their own notifications, excluding 'request' type
            $notifications = $query->where('user_id', $user->id)
                ->where('type',  'completed')
                ->orWhere('type',  'approvedlocrequest')
                ->orWhere('type',  'disapprovedlocrequest')
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

    // Add method to get notification message for Loc Request
    private function getLocRequestMessage(){
        if ($this->locRequestCount === 1) {
            return '1 new WFH location request pending for approval';
        }
        return ($this->locRequestCount) . ' pending WFH location request approval';
    }

    // Add method to get notification message for OB Request
    private function getOBRequestMessage(){
        if ($this->obRequestCount === 1) {
            return '1 new OB location request pending for approval';
        }
        return ($this->obRequestCount) . ' pending OB request approval';
    }

    public function render()
    {
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