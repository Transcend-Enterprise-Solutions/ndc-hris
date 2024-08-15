<div x-data="{
    isModalOpen: @entangle('isModalOpen'),
    isEditMode: @entangle('isEditMode'),
    confirmingHolidayDeletion: @entangle('confirmingHolidayDeletion')
}" x-cloak>
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Holidays</h1>

        <!-- Modal -->
        <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
            <div @click.away="isModalOpen = false" x-show="isModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-4xl rounded-2xl">
                <div class="flex items-center justify-between pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                        {{ $isEditMode ? 'Edit Holiday' : 'Add Holiday' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveHoliday" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Holiday Name</label>
                            <input id="description" type="text" wire:model="description" placeholder="Enter holiday name" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                            @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="holiday_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                            <input id="holiday_date" type="date" wire:model="holiday_date" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                            @error('holiday_date') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select id="type" wire:model="type" class="w-full p-2 border rounded text-gray-700 dark:text-gray-300 dark:bg-gray-700">
                                <option value="">Select Type</option>
                                <option value="Regular">Regular</option>
                                <option value="Special">Special</option>
                            </select>
                            @error('type') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:text-gray-300">
                            <span class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 px-4 py-2 rounded-md">
                            Cancel
                            </span>
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 dark:text-gray-300">
                            <span class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-600 px-4 py-2 rounded-md">
                                {{ $isEditMode ? 'Update' : 'Save' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 overflow-x-auto">
            <button wire:click="openModal" class="mb-4 px-4 py-2 bg-green-500 text-gray-100 rounded-md hover:bg-green-600 focus:outline-none dark:text-white">
                Add Holiday
            </button>

            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">Holiday Name</th>
                        <th class="px-4 py-2 text-center">Date</th>
                        <th class="px-4 py-2 text-center">Type</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($holidays as $holiday)
                        <tr class="border-b dark:border-gray-600 whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $holiday->description }}</td>
                            <td class="px-4 py-2 text-center">{{ $holiday->holiday_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-center">{{ $holiday->type }}</td>
                            <td class="px-4 py-2 text-center">
                                <button wire:click="edit({{ $holiday->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-blue-900 dark:hover:text-blue-800" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $holiday->id }})" class="ml-2 text-red-600 hover:text-red-900 dark:text-red-600 dark:hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $holidays->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="confirmingHolidayDeletion" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div @click.away="confirmingHolidayDeletion = false" x-show="confirmingHolidayDeletion" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" class="relative bg-white dark:bg-gray-800 p-6 mx-auto max-w-lg rounded-2xl">
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">Confirm Deletion</h3>
                <button wire:click="closeConfirmationModal" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <p class="text-gray-800 dark:text-gray-300">Are you sure you want to delete this holiday? This action cannot be undone.</p>

            <div class="mt-4 flex justify-end">
                <button wire:click="deleteConfirmed" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:bg-gray-700 dark:hover:bg-red-600 dark:text-gray-300 dark:hover:text-white">
                    Confirm
                </button>
                <button wire:click="closeConfirmationModal" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 dark:hover:text-white">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
