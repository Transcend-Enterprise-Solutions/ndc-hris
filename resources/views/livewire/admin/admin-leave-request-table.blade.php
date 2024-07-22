<div class="w-full">
    {{-- Table --}}
    <div class="w-full flex justify-center">
        <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
            <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Leave Request</h1>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                        <tr class="whitespace-nowrap">
                            <th scope="col" class="px-4 py-2 text-center">Name</th>
                            <th scope="col" class="px-4 py-2 text-center">Date of Filing</th>
                            <th scope="col" class="px-4 py-2 text-center">Type of Leave</th>
                            <th scope="col" class="px-4 py-2 text-center">Details of Leave</th>
                            <th scope="col" class="px-4 py-2 text-center">Number of days</th>
                            <th scope="col" class="px-4 py-2 text-center">Start Date</th>
                            <th scope="col" class="px-4 py-2 text-center">End Date</th>
                            <th scope="col" class="px-4 py-2 text-center">Status</th>
                            <th scope="col" class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveApplications as $leaveApplication)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->date_of_filing }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->type_of_leave }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->details_of_leave }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->number_of_days }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->start_date }}</td>
                            <td class="px-4 py-2 text-center">{{ $leaveApplication->end_date }}</td>
                            <td class="px-4 py-2 text-center">
                                @if (str_contains($leaveApplication->status, 'Disapproved'))
                                <span class="text-red-500">{{ $leaveApplication->status }}</span>
                                @else
                                <span class="text-green-500">{{ $leaveApplication->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button @click="$wire.openApproveModal({{ $leaveApplication->id }})"
                                    class="bg-blue-500 text-white px-4 py-2 rounded">Approve</button>
                                <button @click="$wire.openDisapproveModal({{ $leaveApplication->id }})"
                                    class="bg-red-500 text-white px-4 py-2 rounded">Disapprove</button>
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

    <x-modal id="approveLeave" maxWidth="md" wire:model="showApproveModal">
        <div class="p-4">
            <form wire:submit.prevent="updateStatus">
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 dark:text-gray-300">Status</label>
                    <select wire:model.live="status" id="status" class="form-input mt-1 block w-full">
                        <option value="">Select Status</option>
                        <option value="With Pay">With Pay</option>
                        <option value="Without Pay">Without Pay</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                @if ($status === 'Other')
                <div class="mb-4">
                    <label for="otherReason" class="block text-gray-700 dark:text-gray-300">Please specify</label>
                    <input type="text" wire:model="otherReason" id="otherReason" class="form-input mt-1 block w-full">
                    @error('otherReason') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                @endif

                @if ($status === 'With Pay' || $status === 'Without Pay')
                <div class="mb-4">
                    <label for="days" class="block text-gray-700 dark:text-gray-300">Number of Days</label>
                    <input type="number" wire:model="days" id="days" class="form-input mt-1 block w-full" min="1">
                    @error('days') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="button" @click="$wire.closeApproveModal()"
                        class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal id="disapproveLeave" maxWidth="md" wire:model="showDisapproveModal">
        <div class="p-4">
            <form wire:submit.prevent="disapproveLeave">
                <div class="mb-4">
                    <label for="disapproveReason" class="block text-gray-700 dark:text-gray-300">Reason for
                        Disapproval</label>
                    <input type="text" wire:model="disapproveReason" id="disapproveReason"
                        class="form-input mt-1 block w-full">
                    @error('disapproveReason') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="$wire.closeDisapproveModal()"
                        class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Disapprove</button>
                </div>
            </form>
        </div>
    </x-modal>
</div>