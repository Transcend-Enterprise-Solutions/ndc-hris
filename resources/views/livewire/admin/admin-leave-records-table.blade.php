<div class="w-full">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Leave Records</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 text-center">Name</th>
                            <th scope="col" class="px-4 py-2 text-center">Date of Filing</th>
                            <th scope="col" class="px-4 py-2 text-center">Type of Leave</th>
                            <th scope="col" class="px-4 py-2 text-center">Details of Leave</th>
                            <th scope="col" class="px-4 py-2 text-center">Number of Days</th>
                            <th scope="col" class="px-4 py-2 text-center">Start Date</th>
                            <th scope="col" class="px-4 py-2 text-center">End Date</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveApplications as $leaveApplication)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->user->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->date_of_filing }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->type_of_leave }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->details_of_leave }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->number_of_days }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->start_date }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->end_date }}</td>
                            <td class="px-4 py-2 text-center">
                                <span
                                    class="inline-block px-3 py-1 text-sm font-semibold 
                                {{ str_starts_with($leaveApplication->status, 'Approved') ? 'text-green-800 bg-green-200' : 
                                (str_starts_with($leaveApplication->status, 'Disapproved') ? 'text-red-800 bg-red-200' : 'text-yellow-800 bg-yellow-200') }} rounded-full">
                                    {{ $leaveApplication->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-5 border-t border-neutral-500 dark:border-neutral-200">
                {{ $leaveApplications->links() }}
            </div>
        </div>
    </div>
</div>