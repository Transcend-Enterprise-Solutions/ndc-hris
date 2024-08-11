<?php

namespace App\Livewire\Notification;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification as NotificationModel;

class NotificationsDropdown extends Component
{
    public $groupedNotifications;
    public $unreadCount;

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $notifications = NotificationModel::where('user_id', Auth::id())
            ->where('read', false)
            ->latest()
            ->get();

        $this->groupedNotifications = $notifications->groupBy('type')
            ->map(function ($group) {
                return [
                    'type' => $group->first()->type,
                    'count' => $group->count(),
                    'latest' => $group->first(),
                    'ids' => $group->pluck('id')->toArray(),
                ];
            });

        $this->unreadCount = $this->groupedNotifications->count();
    }

    public function markGroupAsRead($type)
    {
        NotificationModel::where('user_id', Auth::id())
            ->where('type', $type)
            ->update(['read' => true]);

        $this->refreshNotifications();
    }


    public function markAllAsRead()
    {
        NotificationModel::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification.notifications-dropdown');
    }
}
