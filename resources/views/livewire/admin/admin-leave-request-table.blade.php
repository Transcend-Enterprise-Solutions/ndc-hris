<div class="w-full">
    {{-- Table --}}
    <div class="w-full flex justify-center">
        <div class="flex justify-center w-full">
            <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
                <div class="pb-4 pt-4 sm:pt-1">
                    <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Leave Request</h1>
                </div>
                <div class="flex flex-col p-3">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block w-full py-2 align-middle">
                            <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Name</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Date of Filing</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Type of Leave</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Details of Leave</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Number of days</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    List of Date</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Uploaded File</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Remarks</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Approved Days</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                    Status</th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leaveApplications as $leaveApplication)
                                                <tr class="whitespace-nowrap">
                                                    <td class="px-4 py-2 text-center">{{ $leaveApplication->name }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->date_of_filing }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->type_of_leave }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->details_of_leave }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->number_of_days }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->list_of_dates }}</td>
                                                    <td class="px-4 py-2 text-center">
                                                        @if ($leaveApplication->file_name)
                                                            @php
                                                                $fileNames = explode(',', $leaveApplication->file_name);
                                                                $filePaths = explode(',', $leaveApplication->file_path);
                                                            @endphp

                                                            @foreach ($fileNames as $index => $fileName)
                                                                @if (isset($filePaths[$index]))
                                                                    <div class="mb-1">
                                                                        <a href="{{ Storage::url($filePaths[$index]) }}"
                                                                            download
                                                                            class="text-blue-500 hover:underline">
                                                                            {{ strlen($fileName) > 10 ? substr($fileName, 0, 10) . '...' : $fileName }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            No file
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-center">{{ $leaveApplication->remarks }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $leaveApplication->approved_days }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        <span
                                                            class="inline-block px-3 py-1 text-sm font-semibold
                                                        {{ $leaveApplication->status === 'Approved'
                                                            ? 'text-green-800 bg-green-200'
                                                            : ($leaveApplication->status === 'Disapproved'
                                                                ? 'text-red-800 bg-red-200'
                                                                : ($leaveApplication->status === 'Pending'
                                                                    ? 'text-yellow-800 bg-yellow-200'
                                                                    : '')) }} rounded-lg">
                                                            {{ $leaveApplication->status }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                        @if ($leaveApplication->isHR)
                                                            <!-- HR buttons (enabled until status is Approved by HR) -->
                                                            <button
                                                                @click="$wire.openApproveModal({{ $leaveApplication->id }})"
                                                                class="text-blue-500 {{ !$leaveApplication->isPending ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                :disabled="{{ !$leaveApplication->isPending ? 'true' : 'false' }}">
                                                                <i class="bi bi-check-lg" title="Approve"></i>
                                                            </button>
                                                            <button
                                                                @click="$wire.openDisapproveModal({{ $leaveApplication->id }})"
                                                                class="text-red-500 {{ !$leaveApplication->isPending ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                :disabled="{{ !$leaveApplication->isPending ? 'true' : 'false' }}">
                                                                <i class="bi bi-x" title="Disapprove"></i>
                                                            </button>
                                                        @elseif ($leaveApplication->stage == 1)
                                                            <!-- Endorser 1 buttons -->
                                                            @if ($leaveApplication->isEndorser1)
                                                                <button
                                                                    @click="$wire.openEndorserApproveModal({{ $leaveApplication->id }})"
                                                                    class="text-blue-500 {{ $leaveApplication->isEndorser1Approved ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                    :disabled="{{ $leaveApplication->isEndorser1Approved ? 'true' : 'false' }}">
                                                                    <i class="bi bi-check-lg" title="Approve"></i>
                                                                </button>
                                                            @endif

                                                            <!-- Endorser 2 buttons -->
                                                            @if ($leaveApplication->isEndorser2)
                                                                <button
                                                                    @click="$wire.openEndorserApproveModal({{ $leaveApplication->id }})"
                                                                    class="text-blue-500 {{ !$leaveApplication->isEndorser1Approved || $leaveApplication->isEndorser2Approved ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                    :disabled="{{ !$leaveApplication->isEndorser1Approved || $leaveApplication->isEndorser2Approved ? 'true' : 'false' }}">
                                                                    <i class="bi bi-check-lg" title="Approve"></i>
                                                                </button>
                                                            @endif
                                                        @elseif ($leaveApplication->stage == 2)
                                                            <!-- Endorser 2 buttons -->
                                                            @if ($leaveApplication->isEndorser2)
                                                                <button
                                                                    @click="$wire.openEndorserApproveModal({{ $leaveApplication->id }})"
                                                                    class="text-blue-500 {{ $leaveApplication->isEndorser2Approved ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                    :disabled="{{ $leaveApplication->isEndorser2Approved ? 'true' : 'false' }}">
                                                                    <i class="bi bi-check-lg" title="Approve"></i>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $leaveApplications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="approveLeave" maxWidth="md" wire:model="showApproveModal">
        <div class="p-4">
            <form wire:submit.prevent="updateStatus">
                <div class="mb-4">
                    <label for="status"
                        class="block text-sm font-medium text-gray-700 dark:text-slate-400">Status</label>
                    <select wire:model.live="status" id="status"
                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700">
                        <option value="">Select Status</option>
                        <option value="With Pay">With Pay</option>
                        <option value="Without Pay">Without Pay</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('status')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                @if ($status === 'Other')
                    <div class="mb-4">
                        <label for="otherReason" class="block text-gray-700 dark:text-gray-300">Please specify</label>
                        <input type="text" wire:model="otherReason" id="otherReason"
                            class="form-control mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700">
                        @error('otherReason')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                @if ($status === 'With Pay' || $status === 'Without Pay')
                    <div class="mb-4">
                        <label for="days" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number
                            of Days</label>
                        <input type="number" wire:model="days" id="days"
                            class="mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700"
                            min="1">
                        @error('days')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="list_of_dates"
                            class="block text-sm font-medium text-gray-700 dark:text-slate-400">
                            Approved Dates
                        </label>
                        <ul class="list-disc">
                            @foreach ($listOfDates as $date)
                                <li class="flex items-center text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" wire:model="selectedDates" value="{{ $date }}"
                                        class="mr-2">
                                    <span>{{ $date }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- New Section for Endorsers --}}
                <div class="mb-4">
                    <label for="endorser1" class="block text-sm font-medium text-gray-700 dark:text-slate-400">
                        Select First Endorser
                    </label>
                    <select wire:model="endorser1" id="endorser1"
                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700">
                        <option value="">Select Endorser</option>
                        @foreach ($nonEmployeeUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('endorser1')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="endorser2" class="block text-sm font-medium text-gray-700 dark:text-slate-400">
                        Select Second Endorser
                    </label>
                    <select wire:model="endorser2" id="endorser2"
                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700">
                        <option value="">Select Endorser</option>
                        @foreach ($nonEmployeeUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('endorser2')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

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
                    @error('disapproveReason')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="$wire.closeDisapproveModal()"
                        class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Disapprove</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Endorser Approve Modal -->
    <x-modal maxWidth="lg" wire:model="showEndorserApprove" centered>
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Are you sure you want to approve this request?
                </h3>
                <button wire:click="closeEndorserApproveModal"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form class="space-y-6" wire:submit.prevent="endorserApproveLeave">
                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        Yes
                    </button>
                    <button type="button" wire:click="closeEndorserApproveModal"
                        class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                        No
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Endorser Disapprove Modal -->
    <x-modal maxWidth="lg" wire:model="showEndorserDisapprove" centered>
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">
                    Are you sure you want to disapprove this request?
                </h3>
                <button wire:click="closeEndorserDisapproveModal"
                    class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 focus:outline-none">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form class="space-y-6" wire:submit.prevent="endorserDisapproveLeave">
                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-red-500 hover:bg-red-600 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                        Yes
                    </button>
                    <button type="button" wire:click="closeEndorserDisapproveModal"
                        class="px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800">
                        No
                    </button>
                </div>
            </form>
        </div>
    </x-modal>


</div>
