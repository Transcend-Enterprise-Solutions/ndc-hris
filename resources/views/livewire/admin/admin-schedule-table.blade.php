<div x-data="{
    isModalOpen: @entangle('isModalOpen'),
    isEditMode: @entangle('isEditMode'),
    confirmingScheduleDeletion: @entangle('confirmingScheduleDeletion'),
    selectedTab: @entangle('selectedTab')
}" x-cloak>
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Employee Schedule</h1>

        <!-- Tabs -->
        <button wire:click="openModal" class="mb-4 px-4 py-2 bg-green-500 text-gray-100 rounded-md hover:bg-green-600 focus:outline-none dark:text-white">
            Add Schedule
        </button>
        <div class="w-full mb-4">
            <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700" role="tablist">
                <button wire:click="setTab('current')" :class="{'font-bold text-violet-700 border-b-2 border-violet-700': selectedTab === 'current', 'text-slate-700 font-medium dark:text-white': selectedTab !== 'current'}" class="h-min px-4 py-2 text-sm" role="tab">Current</button>
                <button wire:click="setTab('incoming')" :class="{'font-bold text-violet-700 border-b-2 border-violet-700': selectedTab === 'incoming', 'text-slate-700 font-medium dark:text-white': selectedTab !== 'incoming'}" class="h-min px-4 py-2 text-sm" role="tab">Incoming</button>
                <button wire:click="setTab('expired')" :class="{'font-bold text-violet-700 border-b-2 border-violet-700': selectedTab === 'expired', 'text-slate-700 font-medium dark:text-white': selectedTab !== 'expired'}" class="h-min px-4 py-2 text-sm" role="tab">Expired</button>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">Employee ID</th>
                        <th class="px-4 py-2 text-center">Employee</th>
                        <th class="px-4 py-2 text-center">WFH Days</th>
                        <th class="px-4 py-2 text-center">Default Time</th>
                        <th class="px-4 py-2 text-center">Dates</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($filteredSchedules as $schedule)
                        <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $schedule->emp_code }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->user?->name ?? 'No User Assigned' }}</td>
                            <td class="px-4 py-2 text-center">{{ implode(', ', explode(',', $schedule->wfh_days)) }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->default_start_time }} - {{ $schedule->default_end_time }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->start_date->format('Y-m-d') }} - {{ $schedule->end_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">
                                <button wire:click="edit({{ $schedule->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-blue-900 dark:hover:text-blue-800" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $schedule->id }})" class="ml-2 text-red-600 hover:text-red-900 dark:text-red-600 dark:hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div @click.away="isModalOpen = false" x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-lg rounded-2xl">
            <!-- Modal content -->
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    <span x-text="isEditMode ? 'Edit Schedule' : 'Add Schedule'"></span>
                </h3>
                <button wire:click="closeModal" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveSchedule" class="space-y-4">
                <div>
                    <label for="emp_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
                    <select id="emp_code" wire:model="emp_code" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700" :disabled="isEditMode">
                        <option value="" disabled selected>Select an employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->emp_code }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('emp_code') <span class="text-red-500">{{ "Employee Field is required!" }}</span> @enderror
                </div>

                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="default_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Start Time</label>
                        <input id="default_start_time" type="time" wire:model="default_start_time" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                        @error('default_start_time') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-1/2">
                        <label for="default_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default End Time</label>
                        <input id="default_end_time" type="time" wire:model="default_end_time" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                        @error('default_end_time') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input id="start_date" type="date" wire:model="start_date" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                        @error('start_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-1/2">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                        <input id="end_date" type="date" wire:model="end_date" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                        @error('end_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    @error('date_range') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="wfh_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WFH Days</label>
                    <div class="mt-1 flex space-x-2">
                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="wfh_days" value="{{ $day }}" class="form-checkbox text-blue-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $day }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('wfh_days') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Save Button -->
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:text-gray-300">
                        <span class="bg-blue-500 hover:bg-blue-600 dark:bg-gray-700 dark:hover:bg-blue-600 px-4 py-2 rounded-md">
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="confirmingScheduleDeletion" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div @click.away="confirmingScheduleDeletion = false" x-show="confirmingScheduleDeletion"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-lg rounded-2xl">
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Confirm Deletion</h3>
                <button wire:click="closeConfirmationModal" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300">Are you sure you want to delete this schedule?</p>
            <div class="mt-4 flex justify-end space-x-2">
                <button wire:click="deleteConfirmed" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-red-600 dark:text-gray-300 dark:hover:text-white">Delete</button>
                <button wire:click="closeConfirmationModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-400 dark:hover:text-white">Cancel</button>
            </div>
        </div>
    </div>
</div>
