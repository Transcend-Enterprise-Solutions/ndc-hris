<div class="w-full flex justify-center">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Daily Time Record</h1>

        <!-- Date Range Picker -->
        <div class="mb-6 flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0">
            <!-- Date Range Picker -->
            <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:space-x-4">
                <div class="w-full sm:w-auto">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Start Date</label>
                    <input type="date" id="startDate" wire:model.live="startDate"
                        class="px-4 py-2 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                </div>

                <div class="w-full sm:w-auto">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">End Date</label>
                    <input type="date" id="endDate" wire:model.live="endDate"
                        class="px-4 py-2 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
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
                        <th class="px-4 py-2 text-center">Date</th>
                        <th class="px-4 py-2 text-center">Day</th>
                        <th class="px-4 py-2 text-center">Location</th>
                        <th class="px-4 py-2 text-center">Morning In</th>
                        <th class="px-4 py-2 text-center">Noon Out</th>
                        <th class="px-4 py-2 text-center">Noon In</th>
                        <th class="px-4 py-2 text-center">Afternoon Out</th>
                        <th class="px-4 py-2 text-center">Late</th>
                        <th class="px-4 py-2 text-center">Overtime</th>
                        <th class="px-4 py-2 text-center">Hours Rendered</th>
                        <th class="px-4 py-2 text-center">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtrs as $dtr)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $dtr->date }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->day_of_week }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->location }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->morning_in ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->morning_out ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->afternoon_in ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->afternoon_out ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->late ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->overtime ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->total_hours_rendered ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">@switch(strtolower($dtr->remarks))
                                    @case('absent')
                                        <span class="w-fit inline-flex overflow-hidden rounded-2xl border border-red-600 bg-white text-xs font-medium text-red-600 dark:border-red-600 dark:bg-slate-900 dark:text-red-600">
                                            <span class="px-2 py-1 bg-red-600/10 dark:bg-red-600/10">Absent</span>
                                        </span>
                                        @break
                                    @case('leave')
                                        <span class="w-fit inline-flex overflow-hidden rounded-2xl border border-sky-600 bg-white text-xs font-medium text-sky-600 dark:border-sky-600 dark:bg-slate-900 dark:text-sky-600">
                                            <span class="px-2 py-1 bg-sky-600/10 dark:bg-sky-600/10">Leave</span>
                                        </span>
                                        @break
                                    @case('holiday')
                                        <span class="w-fit inline-flex overflow-hidden rounded-2xl border border-green-600 bg-white text-xs font-medium text-green-600 dark:border-green-600 dark:bg-slate-900 dark:text-green-600">
                                            <span class="px-2 py-1 bg-green-600/10 dark:bg-green-600/10">Holiday</span>
                                        </span>
                                        @break
                                    @case('late')
                                        <span class="w-fit inline-flex overflow-hidden rounded-2xl border border-amber-500 bg-white text-xs font-medium text-amber-500 dark:border-amber-500 dark:bg-slate-900 dark:text-amber-500">
                                            <span class="px-2 py-1 bg-amber-500/10 dark:bg-amber-500/10">Late</span>
                                        </span>
                                        @break
                                    @default
                                        <span class="w-fit inline-flex overflow-hidden rounded-2xl border border-slate-300 bg-white text-xs font-medium text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                                            <span class="px-2 py-1 bg-slate-100/10 dark:bg-slate-800/10">{{ $dtr->remarks }}</span>
                                        </span>
                                @endswitch
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">No transactions available for the selected date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $dtrs->links() }}
        </div>
    </div>
</div>
