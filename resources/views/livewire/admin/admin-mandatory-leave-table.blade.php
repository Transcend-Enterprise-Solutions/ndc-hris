<div class="w-full">
    <style>
        .scrollbar-thin1::-webkit-scrollbar {
            width: 5px;
        }

        .scrollbar-thin1::-webkit-scrollbar-thumb {
            background-color: #1a1a1a4b;
            /* cursor: grab; */
            border-radius: 0 50px 50px 0;
        }

        .scrollbar-thin1::-webkit-scrollbar-track {
            background-color: #ffffff23;
            border-radius: 0 50px 50px 0;
        }

        @media (max-width: 1024px) {
            .custom-d {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .m-scrollable {
                width: 100%;
                overflow-x: scroll;
            }
        }

        @media (min-width:1024px) {
            .custom-p {
                padding-bottom: 14px !important;
            }
        }

        @-webkit-keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(0, 255, 42);
        }
    </style>
    {{-- Table --}}
    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 pt-4 sm:pt-1">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Mandatory Leave Schedule
                </h1>
            </div>

            <div class="flex flex-col mb-4">
                <div>
                    <label for="search"
                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Search</label>
                </div>

                <div class="flex flex-col justify-between sm:flex-row sm:items-center sm:space-x-4">
                    <div class="w-full sm:w-auto">
                        <input type="search" id="search" wire:model.live="search" placeholder="Enter employee name"
                            class="py-2 px-3 mt-1 block w-full sm:w-80 shadow-sm text-sm font-medium border-gray-400 dark:border-gray-600 rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                    </div>
                    {{-- <div
                        class="flex w-full sm:w-56 justify-between mt-2 sm:mt-0 sm:justify-start sm:space-x-4 whitespace-nowrap">
                        <button wire:click="openModal"
                            class="text-sm px-2 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                            Set Mandatory Leave
                        </button>
                    </div> --}}
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                <tr class="whitespace-nowrap">
                                    <th scope="col" style="width: 30%"
                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                        Name</th>
                                    <th scope="col" style="width: 30%"
                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                        Department</th>
                                    <th scope="col" style="width: 30%"
                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                        Schedule</th>
                                    <th scope="col" style="width: 10%"
                                        class="px-5 py-3 text-gray-100 text-sm font-medium text-center sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                @forelse($mandatoryLeaves as $leave)
                                    <tr class="whitespace-nowrap">
                                        <td class="px-4 py-2 text-center">
                                            {{ $leave->name }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $leave->office_or_department }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <ul class="list-disc inline-block text-left space-y-1">
                                                @foreach (explode(',', $leave->approved_dates) as $date)
                                                    <li>
                                                        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td
                                            class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                            <div class="flex flex-col items-center space-y-2">
                                                <button
                                                    class="peer inline-flex items-center justify-center px-4
                                                    text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none"
                                                    wire:click="openEditModal({{ $leave->id }})">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button
                                                    class="peer inline-flex items-center justify-center px-4 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none"
                                                    wire:click="exportToExcel({{ $leave->id }})"
                                                    wire:loading.attr="disabled">
                                                    <img class="flex" src="/images/icons8-xls-export-light.png"
                                                        width="16" alt="Export to Excel"
                                                        wire:target="exportToExcel({{ $leave->id }})"
                                                        wire:loading.remove>
                                                    <div wire:loading wire:target="exportToExcel({{ $leave->id }})">
                                                        <span class="spinner-border"></span>
                                                    </div>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center">
                                            No mandatory leaves found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                        {{ $mandatoryLeaves->links() }}
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <x-modal id="showModal" maxWidth="xl" wire:model="showModal" centered>
                <div class="p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Set
                                Mandatory Leave</h3>

                            <!-- Employee Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select
                                    Employee</label>
                                <select wire:model="selectedUser"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select an employee</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Selection -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add
                                    Date</label>
                                <div class="mt-1 flex">
                                    <input type="date" wire:model="new_date"
                                        class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        min="{{ date('Y-m-d') }}">
                                    <button wire:click="addDate"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selected:
                                    {{ count($list_of_dates) }}/5 dates</p>
                            </div>

                            <!-- Selected Dates List -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected Dates:</h4>
                                <div class="mt-2 space-y-2">
                                    @foreach ($list_of_dates as $index => $date)
                                        <div
                                            class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                            </span>
                                            <button wire:click="removeDate({{ $index }})"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button wire:click="submitMandatoryLeave"
                                    class="inline-flex w-full justify-center rounded-md border px-4 py-2 text-base font-medium shadow-sm focus:outline-none sm:ml-3 sm:w-auto sm:text-sm border-transparent bg-green-600 text-white hover:bg-green-700">
                                    Submit
                                </button>
                                <button wire:click="closeModal"
                                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
            </x-modal>


        </div>
    </div>

    <!-- Edit Modal -->
    <x-modal id="showEditModal" maxWidth="xl" wire:model="showEditModal" centered>
        <div class="p-6">
            <div class="absolute right-0 top-0 pr-4 pt-4">
                <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="sm:flex sm:items-start">
                <div class="w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4">Edit Mandatory
                        Leave</h3>

                    <!-- Employee Information (Read-only) -->
                    @if ($editingLeave)
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Employee:</strong> {{ $editingLeave->name }}
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                <strong>Department:</strong> {{ $editingLeave->office_or_department }}
                            </p>
                        </div>
                    @endif

                    <!-- Date Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add
                            Date</label>
                        <div class="mt-1 flex">
                            <input type="date" wire:model="new_date"
                                class="block w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                min="{{ date('Y-m-d') }}">
                            <button wire:click="addDate"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selected:
                            {{ count($list_of_dates) }}/5 dates</p>
                    </div>

                    <!-- Selected Dates List -->
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected Dates:</h4>
                        <div class="mt-2 space-y-2">
                            @foreach ($list_of_dates as $index => $date)
                                <div
                                    class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                                    </span>
                                    <button wire:click="removeDate({{ $index }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="updateMandatoryLeave"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Update
                        </button>
                        <button wire:click="closeEditModal"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-modal>
</div>
