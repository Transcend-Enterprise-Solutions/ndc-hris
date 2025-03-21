<div class="w-full flex justify-center">
    <div class="w-full bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md">
        <h1 class="text-lg font-bold text-center text-black dark:text-white mb-6">Daily Time Record</h1>

        <!-- Search and Date Range Picker -->
        <div class="mb-6 flex flex-col sm:flex-row items-end justify-between space-y-4 sm:space-y-0">
            <!-- Search Input -->
            <div class="w-full sm:w-1/3 sm:mr-4">
                {{-- <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                <input type="text" id="search" wire:model.live="searchTerm"
                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                    placeholder="Search..."> --}}
            </div>

            <!-- Date Range Picker -->
            <div class="w-full sm:w-2/3 flex flex-col sm:flex-row sm:justify-end sm:space-x-4">
                <div class="w-full sm:w-auto">
                    <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Start Date</label>
                    <input type="date" id="startDate" wire:model.live="startDate"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                </div>

                <div class="w-full sm:w-auto mt-4 sm:mt-0">
                    <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">End Date</label>
                    <input type="date" id="endDate" wire:model.live="endDate"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                </div>
            </div>
            <div x-data="{ showModal: false, signatoryName: '' }" x-cloak>
                <!-- Trigger Button -->
                {{-- <button @click="showModal = true" class="p-2 flex items-center justify-center">
                    <img src="{{ asset('images/icons8-export-pdf-60.png') }}" alt="Export to PDF" class="w-8 h-8" wire:loading.remove wire:target="exportToPdf">
                    <div wire:loading wire:target="exportToPdf">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="size-6 fill-red-600 motion-safe:animate-spin dark:fill-red-600">
                            <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
                            <path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
                        </svg>
                    </div>
                </button> --}}

                <!-- Modal -->
                <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
                    <div @click.away="showModal = false"
                         x-show="showModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-4"
                         class="bg-white dark:bg-gray-800 rounded-lg p-4 sm:p-6 w-11/12 sm:w-3/4 lg:w-1/3 mx-4">
                        <h2 class="text-lg font-semibold mb-4 text-left text-gray-900 dark:text-gray-100">Enter Signatory Details</h2>
                        <input type="text" x-model="signatoryName" placeholder="Signatory Name"
                            class="w-full mb-4 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-300 dark:border-slate-600">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload E-Signature</label>
                            <input wire:loading.remove wire:target='eSignature' type="file" wire:model="eSignature" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-200
                                dark:hover:file:bg-gray-700
                                dark:file:bg-gray-600 dark:file:text-gray-200">
                            <svg wire:loading wire:target='eSignature' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="size-6 fill-slate-700 motion-safe:animate-spin dark:fill-slate-300">
                                <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
                                <path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" />
                            </svg>

                            @error('eSignature') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex flex-col sm:flex-row justify-end mt-5">
                            <button @click="showModal = false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mb-2 sm:mb-0 sm:mr-2 dark:bg-gray-600 dark:text-gray-200">Cancel</button>
                            <button @click="showModal = false; $wire.exportToPdf(signatoryName)" class="bg-blue-500 text-white px-4 py-2 rounded-md" wire:loading.attr="disabled">Generate PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-800 overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                    <tr class="whitespace-nowrap">
                        <th class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center">
                                <button wire:click="sortBy('date')" class="{{ $sortField === 'date' ? 'text-blue-600' : 'text-gray-400' }}">
                                    <i class="bi bi-arrow-down-up"></i>
                                </button>
                                <span class="ml-2">Date</span>
                            </div>
                        </th>
                        <th class="px-4 py-2 text-center">Day</th>
                        <th class="px-4 py-2 text-center">Location</th>
                        <th class="px-4 py-2 text-center">Morning In</th>
                        <th class="px-4 py-2 text-center">Noon Out</th>
                        <th class="px-4 py-2 text-center">Noon In</th>
                        <th class="px-4 py-2 text-center">Afternoon Out</th>
                        <th class="px-4 py-2 text-center">
                            <div class="flex items-center justify-center">
                                <button wire:click="sortBy('late')" class="{{ $sortField === 'late' ? 'text-blue-600' : 'text-gray-400' }}">
                                    <i class="bi bi-arrow-down-up"></i>
                                </button>
                                <span class="ml-2">Late/Undertime</span>
                            </div>
                        </th>
                        <th class="px-4 py-2 text-center">Overtime</th>
                        <th class="px-4 py-2 text-center">Hours Rendered</th>
                        <th class="px-4 py-2 text-center">Attachment</th>
                        <th class="px-4 py-2 text-center">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dtrs as $dtr)
                        <tr class="whitespace-nowrap">
                            <td class="px-4 py-2 text-center">{{ $dtr->date }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->day_of_week }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->location }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->morning_in ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->morning_out ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->afternoon_in ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->afternoon_out ?? '--:--' }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->late }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->overtime }}</td>
                            <td class="px-4 py-2 text-center">{{ $dtr->total_hours_rendered }}</td>
                            <td class="px-4 py-2 text-center">
                                @if($dtr->attachment)
                                    <a href="#" wire:click.prevent="downloadFile({{ $dtr->id }})" class="text-blue-600 hover:underline">
                                        {{ $dtr->date }} (Download)
                                    </a>
                                @else
                                    No file
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                @php
                                    // Use up_remarks if available, otherwise fallback to remarks
                                    $remarksText = $dtr->up_remarks ?? $dtr->remarks;

                                    // Determine background and text color based on remarksText
                                    $bgColor = 'bg-gray-200';
                                    $textColor = 'text-gray-800';

                                    switch ($remarksText) {
                                        case 'Present':
                                            $bgColor = 'bg-green-400';
                                            $textColor = 'text-green-800';
                                            break;
                                        case 'Holiday':
                                        case 'Leave':
                                            $bgColor = 'bg-blue-400';
                                            $textColor = 'text-blue-800';
                                            break;
                                        case 'Absent':
                                            $bgColor = 'bg-red-400';
                                            $textColor = 'text-red-800';
                                            break;
                                        case 'Late/Undertime':
                                            $bgColor = 'bg-yellow-400';
                                            $textColor = 'text-yellow-800';
                                            break;
                                    }
                                @endphp

                                <button
                                    title="Edit"
                                    class="px-4 py-1 text-sm font-semibold rounded-full cursor-pointer {{ $bgColor }} {{ $textColor }}"
                                    wire:click="openModal({{ $dtr->id }})"
                                >
                                    {{ $remarksText }}
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr class="whitespace-nowrap">
                            <td colspan="11" class="px-4 py-2 text-center">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Remarks Modal -->
        <div x-data="{
            show: false,
            remarks: @entangle('remarks').defer,
            fileName: @entangle('fileName').defer
        }"
             @modal-opened.window="
                show = true;
                remarks = $event.detail.remarks;
                fileName = $event.detail.fileName;
             "
             @modal-updated.window="fileName = $event.detail.fileName"
             x-show="show"
             class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center"
             x-cloak>
            <div @click.away="show = false"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md sm:w-1/2 md:w-1/3 shadow-xl">
                <h2 class="text-lg font-semibold mb-4 text-left text-gray-900 dark:text-gray-100">Update Remarks</h2>
                <textarea wire:model.defer="remarks" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 dark:bg-gray-700 dark:text-gray-300 dark:border-slate-600 mb-4"></textarea>

                <div class="mb-4">
                    <label for="file-upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Attachment</label>
                    <input type="file" id="file-upload" wire:model="attachment" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                    <div wire:loading wire:target="attachment" class="mt-2 text-sm text-gray-500 dark:text-gray-400">Uploading...</div>
                </div>

                <div x-show="fileName" class="mb-4 flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-2 rounded">
                    <span x-text="fileName" class="text-sm text-gray-600 dark:text-gray-300"></span>
                    <button wire:click="downloadFile" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                        Download
                    </button>
                </div>

                <div class="flex justify-end mt-5">
                    <button @click="show = false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2 dark:bg-gray-600 dark:text-gray-200">Cancel</button>
                    <button wire:click="updateRemarks" @click="show = false" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $dtrs->links() }}
        </div>
    </div>
</div>
