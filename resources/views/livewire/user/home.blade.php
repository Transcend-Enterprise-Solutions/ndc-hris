<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <x-dashboard.welcome-banner-emp />

        <div>

            @livewire('user.wfh-attendance-table')

        </div>

        <div>

            @livewire('dashboard.dashboard-leave-credits')

        </div>

    </div>
</x-app-layout>
