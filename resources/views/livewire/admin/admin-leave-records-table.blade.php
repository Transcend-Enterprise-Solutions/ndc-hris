<div class="w-full">
    <div class="w-full flex justify-center">
        {{-- <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md"> --}}
            <div class="flex justify-center w-full">
                <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                    <div class="pb-4 pt-4 sm:pt-1">
                        <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Records</h1>
                    </div>
                    {{-- <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Leave Records</h1>
                    --}}
                    
                    
                    <div class="flex flex-col p-3">
                        
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="relative inline-block text-left">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                                <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                                    class="py-2 px-3 block w-full shadow-sm text-sm font-medium border-gray-400
                                    wire:text-neutral-800 dark:text-neutral-200 mb-2 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                            </div>
                            <div class="inline-block w-full py-2 align-middle">
                                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-full">
                                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                                <tr class="whitespace-nowrap">
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Name
                                                    </th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Date
                                                        of Filing</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Type
                                                        of Leave</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Details of Leave</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Number
                                                        of Days</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Start
                                                        Date</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        End
                                                        Date</th>
                                                    <th
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Status
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leaveApplications as $leaveApplication)
                                                <tr class="whitespace-nowrap">
                                                    <td class="px-4 py-2 text-center">{{ $leaveApplication->user->name
                                                        }}</td>
                                                    <td class="px-4 py-2 text-center">{{
                                                        $leaveApplication->date_of_filing }}</td>
                                                    <td class="px-4 py-2 text-center">{{
                                                        $leaveApplication->type_of_leave }}</td>
                                                    <td class="px-4 py-2 text-center">{{
                                                        $leaveApplication->details_of_leave }}</td>
                                                    <td class="px-4 py-2 text-center">{{
                                                        $leaveApplication->number_of_days }}</td>
                                                    <td class="px-4 py-2 text-center">{{ $leaveApplication->start_date
                                                        }}</td>
                                                    <td class="px-4 py-2 text-center">{{ $leaveApplication->end_date }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span
                                                            class="inline-block px-3 py-1 text-sm font-semibold 
                                                        {{ str_starts_with($leaveApplication->status, 'Approved') ? 'text-green-800 bg-green-200' : 
                                                        (str_starts_with($leaveApplication->status, 'Disapproved') ? 'text-red-800 bg-red-200' : 'text-yellow-800 bg-yellow-200') }} rounded-lg">
                                                            {{ $leaveApplication->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div
                                        class="p-5 border-t border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                        {{ $leaveApplications->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--
        </div> --}}
    </div>
</div>