<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ currentView: 'default' }">
        <div class="pb-4 pt-4 sm:pt-1">
            <button @click="currentView = currentView === 'default' ? 'alternate' : 'default'"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                <span
                    x-text="currentView === 'default' ? 'Switch to Mandatory Leave Schedule' : 'Back to Leave Request'"></span>
            </button>
        </div>

        <div x-show="currentView === 'default'">
            @livewire('admin.admin-leave-request-table')
        </div>

        <div x-show="currentView === 'alternate'">
            @livewire('admin.admin-mandatory-leave-table')
        </div>
    </div>
</x-app-layout>
