@props([
    'align' => 'left'
])

<div x-data="{ open: false }" class="relative" wire:poll.10s='refreshNotifications'>
    <!-- Button for triggering the dropdown -->
    <button
        class="relative w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600/80 rounded-full"
        :class="{ 'bg-slate-200': open }"
        aria-haspopup="true"
        @click.prevent="open = !open"
        :aria-expanded="open"
    >
        <span class="sr-only">Notifications</span>
        <svg class="w-4 h-4" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
            <path class="fill-current text-slate-500 dark:text-slate-400" d="M6.5 0C2.91 0 0 2.462 0 5.5c0 1.075.37 2.074 1 2.922V12l2.699-1.542A7.454 7.454 0 006.5 11c3.59 0 6.5-2.462 6.5-5.5S10.09 0 6.5 0z" />
            <path class="fill-current text-slate-400 dark:text-slate-500" d="M16 9.5c0-.987-.429-1.897-1.147-2.639C14.124 10.348 10.66 13 6.5 13c-.103 0-.202-.018-.305-.021C7.231 13.617 8.556 14 10 14c.449 0 .886-.04 1.307-.11L15 16v-4h-.012C15.627 11.285 16 10.425 16 9.5z" />
        </svg>
        <!-- Notification counter -->
        @if($unreadCount > 0)
            <div class="absolute top-0 right-0 w-3 h-3 bg-rose-500 text-white text-xs flex items-center justify-center rounded-full">
                {{ $unreadCount }}
            </div>
        @endif
    </button>

    <!-- Dropdown menu -->
    <div
        class="origin-top-right absolute z-10 mt-2 w-80 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 py-1.5 rounded shadow-lg overflow-hidden"
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
        :class="{ 'right-0': align === 'right', 'left-0': align === 'left' }"
        style="top: 100%; {{ $align === 'right' ? 'right: 0;' : 'left: 0;' }}"
    >
        <!-- Dropdown header -->
        <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase pt-1.5 pb-2 px-4">Notifications</div>

        <!-- Dropdown items -->
        <ul class="max-h-64 overflow-y-auto">
            @forelse ($groupedNotifications as $type => $group)
                <li class="border-b border-slate-200 dark:border-slate-700 last:border-0">
                    <div class="block py-2 px-4 hover:bg-slate-50 dark:hover:bg-slate-700/20">
                        <div class="flex justify-between items-start">

                            <a wire:navigate wire:click="markGroupAsRead('{{ $type }}')" href="{{ route('/my-records/doc-request') }}"
                            class="flex-grow">
                                <span class="block text-sm mb-1">
                                    📣 <span class="font-medium text-slate-800 dark:text-slate-100">
                                        {{ $group['count'] }} New Document Request {{ $type }}
                                    </span>
                                </span>
                                <span class="block text-xs font-medium text-slate-400 dark:text-slate-500">
                                    {{ $group['latest']->created_at->diffForHumans() }}
                                </span>
                            </a>
                            <button
                                class="text-xs text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                                wire:click="markGroupAsRead('{{ $type }}')"
                            >
                                Mark as read
                            </button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-2 px-4 text-sm text-slate-500 dark:text-slate-400">No new notifications</li>
            @endforelse
        </ul>

        <!-- Mark all as read button -->
        @if($unreadCount > 0)
            <div class="border-t border-slate-200 dark:border-slate-700 mt-2 pt-2 px-4">
                <button
                    class="w-full text-center text-sm text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300"
                    wire:click="markAllAsRead"
                >
                    Mark all as read
                </button>
            </div>
        @endif
    </div>
</div>
