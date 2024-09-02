<div x-data="{ activeTab: @entangle('activeTab') }" class="w-full">
    <div class="w-full flex justify-center">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 pt-4 sm:pt-1">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Records</h1>
            </div>

            {{-- <div class="p-4"> --}}

            <div class="">
                <!-- Search input -->
                <div class="relative inline-block text-left mb-4 w-full">
                    <label for="search"
                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                    <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                        class="py-2 px-3 block w-full sm:w-80 shadow-sm text-sm font-medium border-gray-400
                                   dark:text-neutral-200 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                </div>


                <!-- Tabs -->
                <div class="mb-2">
                    <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700"
                        role="tablist">
                        <button @click="$wire.setActiveTab('pending')"
                            :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': activeTab === 'pending', 'text-slate-700 font-bold dark:text-white': activeTab !== 'pending' }"
                            class="h-min px-4 py-2 text-sm" role="tab">Pending</button>
                        <button @click="$wire.setActiveTab('approved')"
                            :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': activeTab === 'approved', 'text-slate-700 font-bold dark:text-white': activeTab !== 'approved' }"
                            class="h-min px-4 py-2 text-sm" role="tab">Approved</button>
                        <button @click="$wire.setActiveTab('disapproved')"
                            :class="{ 'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': activeTab === 'disapproved', 'text-slate-700 font-bold dark:text-white': activeTab !== 'disapproved' }"
                            class="h-min px-4 py-2 text-sm" role="tab">Disapproved</button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:p-4">
                <!-- Table -->
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-x-auto border dark:border-gray-700 rounded-t-lg">
                            <table class="w-full min-w-full">
                                <!-- Table head -->
                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                    <tr class="whitespace-nowrap">
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Name
                                        </th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Date
                                            of Filing</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Type
                                            of Leave</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Details of Leave</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Number
                                            of Days</th>
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            List
                                            of Dates</th>
                                        {{-- <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">End
                                            Date</th> --}}
                                        <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Status
                                        </th>
                                        @if ($activeTab === 'approved')
                                            <th class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                Action
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <!-- Table body -->
                                <tbody>
                                    @foreach ($leaveApplications as $leaveApplication)
                                        <tr class="whitespace-nowrap">
                                            <td class="px-4 py-2 text-center">{{ $leaveApplication->user->name }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $leaveApplication->date_of_filing }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    $typeOfLeave = $leaveApplication->type_of_leave;
                                                    $truncatedTypeOfLeave = \Illuminate\Support\Str::limit(
                                                        $typeOfLeave,
                                                        10,
                                                        '...',
                                                    );
                                                @endphp
                                                <span
                                                    @if (strlen($typeOfLeave) > 10) title="{{ $typeOfLeave }}" @endif>
                                                    {{ $truncatedTypeOfLeave }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    $detailsOfLeave = $leaveApplication->details_of_leave;
                                                    $truncatedDetailsOfLeave = \Illuminate\Support\Str::limit(
                                                        $detailsOfLeave,
                                                        10,
                                                        '...',
                                                    );
                                                @endphp
                                                <span
                                                    @if (strlen($detailsOfLeave) > 10) title="{{ $detailsOfLeave }}" @endif>
                                                    {{ $truncatedDetailsOfLeave }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                {{ $leaveApplication->number_of_days }}
                                            </td>
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    $listOfDates = $leaveApplication->list_of_dates;
                                                    $truncatedListOfDates = \Illuminate\Support\Str::limit(
                                                        $listOfDates,
                                                        10,
                                                        '...',
                                                    );
                                                @endphp
                                                <span
                                                    @if (strlen($listOfDates) > 10) title="{{ $listOfDates }}" @endif>
                                                    {{ $truncatedListOfDates }}
                                                </span>
                                            </td>
                                            {{-- <td class="px-4 py-2 text-center">{{ $leaveApplication->end_date }}</td> --}}
                                            <td class="px-4 py-2 text-center">
                                                <span
                                                    class="inline-block px-3 py-1 text-sm font-semibold 
                                            {{ $leaveApplication->status === 'Approved'
                                                ? 'text-green-800 bg-green-200'
                                                : ($leaveApplication->status === 'Disapproved'
                                                    ? 'text-red-800 bg-red-200'
                                                    : 'text-yellow-800 bg-yellow-200') }} rounded-lg">
                                                    {{ $leaveApplication->status }}
                                                </span>
                                            </td>
                                            @if ($activeTab === 'approved')
                                                <td class="px-4 py-2 text-center">
                                                    <button type="button"
                                                        class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                                                        wire:click.prevent="exportExcel({{ $leaveApplication->id }})">
                                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div
                            class="p-5 border-t rounded-b-lg border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                            {{ $leaveApplications->links() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- </div> --}}
        </div>
    </div>
</div>
