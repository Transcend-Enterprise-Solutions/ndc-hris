<div class="w-full">
    {{-- Table --}}
    @if (!$showPDFPreview)
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
                                                        Number of day/s</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        List of Date</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                        Uploaded File</th>
                                                    <th scope="col"
                                                        class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($leaveApplications as $leaveApplication)
                                                    <tr
                                                        class="whitespace-nowrap border-b border-gray-400 dark:text-neutral-200">
                                                        <td class="px-4 py-2 text-center">{{ $leaveApplication->name }}
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $leaveApplication->date_of_filing }}</td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $leaveApplication->type_of_leave }}</td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $leaveApplication->details_of_leave ?? 'N/A' }}</td>
                                                        <td class="px-4 py-2 text-center">
                                                            {{ $leaveApplication->number_of_days }}</td>
                                                        <td class="px-4 py-2 text-center">
                                                            @if (Str::contains($leaveApplication->list_of_dates, ' - '))
                                                                {{ $leaveApplication->list_of_dates }}
                                                            @else
                                                                <div class="flex flex-col">
                                                                    @foreach (explode(',', $leaveApplication->list_of_dates) as $date)
                                                                        <span>{{ trim($date) }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            @if ($leaveApplication->file_name && $leaveApplication->file_path)
                                                                @php
                                                                    $fileNames = explode(
                                                                        ',',
                                                                        $leaveApplication->file_name,
                                                                    );
                                                                    $filePaths = explode(
                                                                        ',',
                                                                        $leaveApplication->file_path,
                                                                    );
                                                                @endphp

                                                                @foreach ($fileNames as $index => $fileName)
                                                                    @if (isset($filePaths[$index]))
                                                                        <div class="mb-1">
                                                                            <button
                                                                                wire:click="downloadFile('{{ $filePaths[$index] }}')"
                                                                                class="text-blue-500 hover:underline bg-transparent border-none cursor-pointer p-0 m-0">
                                                                                {{ Str::limit($fileName, 10) }}
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <p>No file</p>
                                                            @endif
                                                        </td>
                                                        <td
                                                            class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <button
                                                                @click="$wire.openApproveModal({{ $leaveApplication->id }})"
                                                                class="text-blue-500">
                                                                <i class="bi bi-check-lg" title="Approve"></i>
                                                            </button>
                                                            <button
                                                                @click="$wire.openDisapproveModal({{ $leaveApplication->id }})"
                                                                class="text-red-500">
                                                                <i class="bi bi-x" title="Disapprove"></i>
                                                            </button>

                                                            <button wire:click="showPDF({{ $leaveApplication->id }})"
                                                                class="text-blue-500 hover:text-blue-600">
                                                                <i class="bi bi-eye" title="Show Details"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($leaveApplications->isEmpty())
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                                No pending leave request!
                                            </div>
                                        @endif
                                    </div>
                                    <div
                                        class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                        {{ $leaveApplications->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-modal id="approveLeave" maxWidth="md" centered wire:model="showApproveModal">
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
                            <label for="otherReason" class="block text-gray-700 dark:text-gray-300">Please
                                specify</label>
                            <input type="text" wire:model="otherReason" id="otherReason"
                                class="form-control mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700">
                            @error('otherReason')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    @if ($status === 'With Pay' || $status === 'Without Pay')
                        <div class="mb-4">
                            <label for="days"
                                class="block text-sm font-medium text-gray-700 dark:text-slate-400">Number of
                                Days</label>
                            <input type="number" wire:model="days" id="days"
                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm rounded-md dark:text-gray-300 dark:bg-gray-700 bg-gray-100"
                                readonly>
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
                                        <input type="checkbox" wire:model.live="selectedDates"
                                            value="{{ $date }}" class="mr-2">
                                        <span>{{ $date }}</span>
                                    </li>
                                @endforeach
                            </ul>
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

        <x-modal id="disapproveLeave" maxWidth="md" centered wire:model="showDisapproveModal">
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
    @else
        <div class="flex justify-center w-full">
            <div
                class="overflow-x-auto w-full h-full overflow-y-auto bg-white rounded-2xl p-3 shadow dark:bg-gray-800 relative">
                <button wire:click="closeLeaveDetails"
                    class="absolute top-2 right-2 text-black dark:text-white whitespace-nowrap mx-2">
                    <i class="bi bi-x-circle" title="Close"></i>
                </button>

                <div class="pt-4 pb-4">
                    <h1 class="text-3xl font-bold text-center text-slate-800 dark:text-white">Leave Application Details
                    </h1>
                </div>

                <div class="mt-2" style="overflow: hidden;">
                    <iframe id="pdfIframe" src="data:application/pdf;base64,{{ $pdfContent }}"
                        style="width: 100%; max-height: 80vh; min-height: 500px;" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    @endif


</div>
