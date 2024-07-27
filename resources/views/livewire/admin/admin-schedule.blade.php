<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <div>

            @livewire('admin.admin-schedule-table')

        </div>
        <br>
        <div>
            @livewire('admin.holiday-schedule-table')
        </div>

    </div>
</x-app-layout>
