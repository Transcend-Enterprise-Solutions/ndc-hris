@props([
    'align' => 'right'
])

<div x-data="{ open: false }" class="relative" wire:poll.10s='refreshNotifications'>
    <style>
        .notif-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .notif-scrollbar::-webkit-scrollbar-thumb {
            background-color: #1a1a1a4b;
            border-radius: 0 !important;
        }
        .notif-scrollbar::-webkit-scrollbar-track {
            background-color: #ffffff23;
            border-radius: 0 !important;
        }
    </style>

    <!-- Button for triggering the dropdown -->
    <button
        class="relative w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600/80 rounded-full"
        :class="{ 'bg-slate-200': open }"
        aria-haspopup="true"
        @click.prevent="open = !open"
        :aria-expanded="open"
    >
        <span class="sr-only">Notifications</span>
        <i class="bi bi-bell-fill"></i>
        <!-- Notification counter -->
        @if($unreadCount > 0)
            <div class="absolute top-0 right-0 w-3 h-3 bg-rose-500 text-white text-xs flex items-center justify-center rounded-full">
                {{ $unreadCount }}
            </div>
        @endif
    </button>

    <!-- Dropdown menu -->
    <div
        class="fixed top-16 right-4 z-50 w-80 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 py-1.5 rounded-2xl shadow-lg overflow-hidden max-h-[calc(100vh-5rem)] overflow-y-auto"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-show="open"
        x-transition:enter="transition ease-out duration-200 transform"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
    >
        <!-- Dropdown header -->
        <div class="flex items-center justify-between text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase pt-1.5 pb-2 px-4">
            <span>Notifications</span>
            @if($unreadCount > 0)
                <button
                    class="text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                    wire:click="markAllAsRead"
                >
                    Mark all as read
                </button>
            @endif
        </div>
        <!-- Dropdown items -->
        <ul class="max-h-64 overflow-y-auto notif-scrollbar" x-auto-animate>
            @if (Auth::user()->user_role === 'sa' || Auth::user()->user_role === 'hr')
                @forelse ($notifications as $notification)
                    @if($notification->type === 'request' && !$notification->read)
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a wire:navigate href="{{ route('/employee-management/admin-doc-request') }}" class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            📣 <span class="font-medium text-slate-800 dark:text-slate-100">
                                                New document {{ $notification->type }}
                                            </span>
                                        </span>
                                        <!-- Display the name and document type -->
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            {{ $this->getDocRequestMessage() }}
                                            {{-- {{ $notification->docRequest ? $notification->docRequest->user->name : '' }} requested {{ $this->getDocumentTypeLabel(($notification->docRequest ? $notification->docRequest->document_type : null)) }} --}}
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ \Carbon\Carbon::parse($latestDocNotifDate ? $latestDocNotifDate->created_at : '' )->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $notification->type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($notification->type === 'locrequest' && !$notification->read)
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    {{-- <a href="{{ route('/employee-management/wfh-management', 
                                            [
                                                'tab' => 'requests',
                                            ]
                                            ) }}" 
                                            class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            <i class="fas fa-map-marker-alt text-red-500"></i>
                                            <span class="font-medium text-slate-800 dark:text-slate-100">
                                                WFH change location request
                                            </span>
                                        </span>
                                        <!-- Display the name and document type -->
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            {{ $this->getLocRequestMessage() }}     
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ \Carbon\Carbon::parse($latestLocNotifDate ? $latestLocNotifDate->created_at : '' )->diffForHumans() }}
                                        </span>
                                    </a> --}}
                                    {{-- <div x-data="{ open: false }" class="relative inline-block {{ $notification->read ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $notification->type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </li>
                    @elseif($notification->type === 'obrequest' && !$notification->read)
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('/employee-management/admin-official-business', 
                                            [
                                                'tab' => 'requests',
                                            ]
                                            ) }}" 
                                            class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            <i class="fas fa-clipboard text-blue-500"></i>
                                                <span class="font-medium text-slate-800 dark:text-slate-100">
                                                Official Business Request
                                            </span>
                                        </span>
                                        <!-- Display the name and document type -->
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            {{ $this->getOBRequestMessage() }}     
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ \Carbon\Carbon::parse($latestObNotifDate ? $latestObNotifDate->created_at : '' )->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block {{ $notification->read ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $notification->type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif 
                @empty
                @endforelse
                {{-- @foreach ($mapReqNotifications as $notification)
                    @if($notification->type === 'locrequest' && !$notification->read)
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('/employee-management/wfh-management', 
                                            [
                                                'tab' => 'requests',
                                            ]
                                            ) }}" 
                                            class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            <i class="fas fa-map-marker-alt text-red-500"></i>
                                            <span class="font-medium text-slate-800 dark:text-slate-100">
                                                WFH change location request
                                            </span>
                                        </span>
                                        <!-- Display the name and document type -->
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            {{ $this->getLocRequestMessage() }}     
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block {{ $notification->read ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $notification->type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($notification->type === 'obrequest' && !$notification->read)
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('/employee-management/admin-official-business', 
                                            [
                                                'tab' => 'requests',
                                            ]
                                            ) }}" 
                                            class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            <i class="fas fa-clipboard text-blue-500"></i>
                                                <span class="font-medium text-slate-800 dark:text-slate-100">
                                                Official Business Request
                                            </span>
                                        </span>
                                        <!-- Display the name and document type -->
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            {{ $this->getOBRequestMessage() }}     
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block {{ $notification->read ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $notification->type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif      
                @endforeach --}}
                
                @if($unreadCount == 0)
                    <li class="py-2 px-4 text-sm text-slate-500 dark:text-slate-400">No new notifications</li>
                @endif
            @elseif(Auth::user()->user_role === 'emp')
                @forelse ($groupedNotifications as $type => $group)
                    @if($type === 'request' && !$group['read'])
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a wire:navigate href="{{ route('/my-records/doc-request') }}" class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            📣 <span class="font-medium text-slate-800 dark:text-slate-100">
                                                {{ $group['count'] }} New Document Request {{ $type }}
                                            </span>
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ $group['latest']->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block  {{ $group['read'] ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                            class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($type === 'approvedlocrequest' && !$group['read'])
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('home', [
                                                'showWFHLocHistory' => true,
                                            ])
                                            }}" class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            📣 <span class="font-medium text-slate-800 dark:text-slate-100">
                                                WFH change location request
                                            </span>
                                        </span>
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            Your request to change WFH location has been <span class="text-green-500 uppercase">approved</span>
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ $group['latest']->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block  {{ $group['read'] ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                                class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($type === 'disapprovedlocrequest' && !$group['read'])
                        <li>
                            <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                                <div class="flex justify-between items-start">
                                    <a href="{{ route('home', [
                                            'showWFHLocHistory' => true,
                                        ])
                                        }}" class="flex-grow">
                                        <span class="block text-sm mb-1">
                                            📣 <span class="font-medium text-slate-800 dark:text-slate-100">
                                                WFH change location request
                                            </span>
                                        </span>
                                        <span class="block text-xs text-slate-600 dark:text-slate-300">
                                            Your request to change WFH location was <span class="text-red-500 uppercase">disapproved</span> <br>
                                            Contact your supervisor for more info
                                        </span>
                                        <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                            {{ $group['latest']->created_at->diffForHumans() }}
                                        </span>
                                    </a>
                                    <div x-data="{ open: false }" class="relative inline-block  {{ $group['read'] ? 'hidden' : '' }}">
                                        <!-- Three dots icon button -->
                                        <button @click="open = !open" @click.away="open = false"
                                                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.75c.69 0 1.25-.56 1.25-1.25S12.69 4.25 12 4.25 10.75 4.81 10.75 5.5 11.31 6.75 12 6.75zM12 13.25c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25zM12 19.75c.69 0 1.25-.56 1.25-1.25s-.56-1.25-1.25-1.25-1.25.56-1.25 1.25.56 1.25 1.25 1.25z"/>
                                            </svg>
                                        </button>
                                        <!-- Dropdown for "Mark as read" -->
                                        <div x-show="open" x-transition
                                                class="absolute right-0 z-10 mt-2 w-36 bg-white dark:bg-gray-800 shadow-lg rounded-md">
                                            <button class="block w-full px-4 py-2 text-left text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                                    wire:click="markGroupAsRead('{{ $type }}')">
                                                Mark as read
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @empty
                @endforelse
                @if($unreadCount == 0)
                    <li class="py-2 px-4 text-sm text-slate-500 dark:text-slate-400">No new notifications</li>
                @endif
            @endif
        </ul>
    </div>
</div>
