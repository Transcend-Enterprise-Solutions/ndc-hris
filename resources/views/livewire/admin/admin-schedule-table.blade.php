<div>
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Schedule</h1>

        <!-- Success/Error Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Modal -->
        @if($isModalOpen)
            <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
                <div class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-lg rounded-2xl">
                    <div class="flex items-center justify-between pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                            {{ $isEditMode ? 'Edit Schedule' : 'Add Schedule' }}
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
                            <select id="emp_code" wire:model="emp_code" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700" {{ $isEditMode ? 'disabled' : '' }}>
                                <option value="" disabled selected>Select an employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->emp_code }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            @error('emp_code') <span class="text-red-500">{{ $message }}</span> @enderror
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
                                @if ($isEditMode)
                                    <span class="bg-blue-500 hover:bg-blue-600 dark:bg-gray-700 dark:hover:bg-blue-600 px-4 py-2 rounded-md">{{ 'Update' }}</span>
                                @else
                                    <span class="bg-blue-500 hover:bg-blue-600 dark:bg-gray-700 dark:hover:bg-blue-600 px-4 py-2 rounded-md">{{ 'Save' }}</span>
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="mt-8 overflow-x-auto">
            <!-- Button to Open the Modal -->
            <button wire:click="openModal" class="mb-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                Add Schedule
            </button>

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
                    @foreach ($schedules as $schedule)
                        <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $schedule->emp_code }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->user->name }}</td>
                            <td class="px-4 py-2 text-center">{{ implode(', ', explode(',', $schedule->wfh_days)) }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->default_start_time }} - {{ $schedule->default_end_time }}</td>
                            <td class="px-4 py-2 text-center">{{ $schedule->start_date->format('Y-m-d') }} - {{ $schedule->end_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">
                                <!-- Edit Button -->
                                <button wire:click="edit({{ $schedule->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-gray-400 dark:hover:text-white" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>

                                <!-- Delete Button -->
                                <button wire:click="confirmDelete({{ $schedule->id }})" class="ml-2 text-red-600 hover:text-red-900 dark:text-gray-400 dark:hover:text-white" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmingScheduleDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
            <div class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-lg rounded-2xl">
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
    @endif
</div>
