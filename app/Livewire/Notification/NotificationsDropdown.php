<?php
namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;

class NotificationsDropdown extends Component
{
    public $notifications;
    public $unreadCount;

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $user = Auth::user();
        $query = NotificationModel::with('docRequest')
            ->where('read', false)
            ->latest();

        if ($user->user_role === 'sa') {
            // 'sa' users see only notifications with type 'request'
            $this->notifications = $query->where('type', 'request')->get();
            $this->unreadCount = $this->notifications->count();
        } else {
            // Non-'sa' users see only their own notifications, excluding 'request' type
            $notifications = $query->where('user_id', $user->id)
                ->where('type', '!=', 'request')
                ->get();

            $this->notifications = $notifications->groupBy('type')
                ->map(function ($group) {
                    return [
                        'type' => $group->first()->type,
                        'count' => $group->count(),
                        'latest' => $group->first(),
                        'ids' => $group->pluck('id')->toArray(),
                    ];
                });
            $this->unreadCount = $notifications->count();
        }
    }

    public function markGroupAsRead($type)
    {
        $user = Auth::user();
        $query = NotificationModel::where('type', $type)
            ->where('read', false);

        if ($user->user_role === 'sa') {
            $query->where('type', 'request');
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
            $query->where('type', 'request');
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