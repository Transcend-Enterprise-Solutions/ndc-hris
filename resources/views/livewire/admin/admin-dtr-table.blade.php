<div class="w-full flex justify-center">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Admin Daily Time Record</h1>

        <!-- Search and Date Range Picker -->
        <div class="mb-6 flex flex-col sm:flex-row items-end justify-between space-y-4 sm:space-y-0">
            <!-- Search Input -->
            <div class="w-full sm:w-1/3 sm:mr-4">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                <input type="text" id="search" wire:model.live="searchTerm"
                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                    placeholder="Enter employee name or ID">
            </div>

            <!-- Date Range Picker -->
            <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">
                <div class="w-full sm:w-auto">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Start Date</label>
                    <input type="date" id="startDate" wire:model.live="startDate"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                </div>

                <div class="w-full sm:w-auto mt-4 sm:mt-0">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">End Date</label>
                    <input type="date" id="endDate" wire:model.live="endDate"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">Employee ID</th>
                        <th class="px-4 py-2 text-center">Employee Name</th>
                        <th class="px-4 py-2 text-center">Date</th>
                        <th class="px-4 py-2 text-center">Day</th>
                        <th class="px-4 py-2 text-center">Location</th>
                        <th class="px-4 py-2 text-center">Morning In</th>
                        <th class="px-4 py-2 text-center">Noon Out</th>
                        <th class="px-4 py-2 text-center">Noon In</th>
                        <th class="px-4 py-2 text-center">Afternoon Out</th>
                        <th class="px-4 py-2 text-center">Late/Undertime</th>
                        <th class="px-4 py-2 text-center">Overtime</th>
                        <th class="px-4 py-2 text-center">Hours Rendered</th>
                        <th class="px-4 py-2 text-center">Remarks</th>
                    </tr>
                    <tr class="whitespace-nowrap bg-gray-100 dark:bg-gray-600">
                        <th colspan="5"></th> <!-- Empty cells to align with columns -->
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center">(HH:MM)</th>
                        <th class="px-4 py-2 text-center"></th> <!-- Empty cell for alignment -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @foreach ($transactions[$user->emp_code] as $date => $dateTransactions)
                            @php
                                $timeRecords = $this->calculateTimeRecords($dateTransactions, $user->emp_code, $date);
                            @endphp
                            <tr class="whitespace-nowrap">
                                <td class="px-4 py-2 text-center">{{ $user->emp_code }}</td>
                                <td class="px-4 py-2 text-center">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $date }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['dayOfWeek'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['location'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['morningIn'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['morningOut'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['afternoonIn'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['afternoonOut'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['late'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['overtime'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['totalHoursRendered'] }}</td>
                                <td class="px-4 py-2 text-center">{{ $timeRecords['remarks'] }}</td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">No transactions available for the selected date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
