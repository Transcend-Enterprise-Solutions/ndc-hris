<div x-data="{ open: false }" class="w-full">
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

    @if (!$showPDFPreview)
        {{-- Leave Application Table --}}
        <div class="w-full flex justify-center">
            <div class="flex justify-center w-full">
                <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
                    <div class="pb-4 pt-4 sm:pt-1 flex items-center justify-between relative">
                        <!-- Title -->
                        <div class="w-full text-center">
                            <h1 class="text-lg font-bold text-slate-800 dark:text-white">Leave Application</h1>
                        </div>

                        <!-- Three Dots Button -->
                        <div class="relative">
                            <!-- Button to toggle dropdown -->
                            <button wire:click="toggleDropdown"
                                class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 focus:outline-none">
                                <i class="bi bi-three-dots-vertical text-slate-800 dark:text-white"></i>
                            </button>

                            <!-- Dropdown Menu (Hidden by default) -->
                            <div wire:click.away="closeDropdown"
                                class="absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-slate-700 ring-1 ring-black ring-opacity-5 z-50 {{ $showDropdown ? 'block' : 'hidden' }}">
                                <div class="py-1">
                                    <!-- Request Button -->
                                    <button
                                        class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600">
                                        Request Mandatory Leave Form
                                    </button>
                                    <!-- Export Button -->
                                    <button
                                        class="block w-full whitespace-nowrap px-4 py-2 text-xs text-slate-800 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-600">
                                        <span class="text-green-600 text-lg"><i class="bi bi-filetype-xlsx"></i></span>
                                        Export
                                        Mandatory Leave Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- @php
                        $isDisabled = empty($startDate) || empty($endDate);
                    @endphp --}}

                    <div class="flex flex-col justify-between items-center sm:flex-row">
                        <div class="flex flex-col md:flex-row items-center w-60">
                            <!-- Apply for Leave Button -->
                            <div class="flex items-center mb-4 md:mb-0 w-full">
                                <button wire:click="openLeaveForm"
                                    class="text-sm mt-4 sm:mt-1 px-2 py-1.5 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none dark:bg-gray-700 w-full dark:hover:bg-green-600 dark:text-gray-300 dark:hover:text-white">
                                    Apply For Leave
                                </button>
                            </div>
                        </div>

                        <!-- Year Selection and Export Button -->
                        <div class="flex justify-center items-center gap-4 w-64">
                            <!-- Year Selection -->
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">
                                    Select Year:
                                </label>
                                <select wire:model.live="selectedYear"
                                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 
                rounded-md dark:hover:bg-slate-600 dark:border-slate-600 dark:text-gray-300 dark:bg-gray-800">
                                    @for ($year = now()->year; $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Export Button -->
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">
                                    Generate Leave Card
                                </label>
                                <button wire:click="exportExcel"
                                    class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-medium tracking-wide
                text-neutral-800 dark:text-neutral-200 transition-colors duration-200 rounded-lg border border-gray-400 
                hover:bg-gray-300 dark:hover:bg-slate-600 dark:border-slate-600 focus:outline-none 
                {{ $isDisabled ? 'opacity-50 cursor-not-allowed' : 'opacity-100 cursor-pointer' }}"
                                    type="button" title="Export Leave Card" {{ $isDisabled ? 'disabled' : '' }}>
                                    <img class="flex dark:hidden" src="/images/export-excel.png" width="22"
                                        alt="exportExcel" wire:target="exportExcel" wire:loading.remove>
                                    <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22"
                                        alt="exportExcel" wire:target="exportExcel" wire:loading.remove>
                                    <div wire:loading wire:target="exportExcel">
                                        <div class="spinner-border small text-primary" role="status"></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ activeTab: @entangle('activeTab') }" class="flex flex-col">
                        <!-- Tabs for Status -->
                        <div class="flex gap-2 overflow-x-auto border-b border-slate-300 dark:border-slate-700 mt-4">
                            <button @click="$wire.setActiveTab('pending')"
                                :class="{
                                    'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': $wire
                                        .activeTab === 'pending',
                                    'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': $wire
                                        .activeTab !== 'pending'
                                }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Pending
                            </button>
                            <button @click="$wire.setActiveTab('approvedByHR')"
                                :class="{
                                    'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': $wire
                                        .activeTab === 'approvedByHR',
                                    'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': $wire
                                        .activeTab !== 'approvedByHR'
                                }"
                                class="h-min px-4 py-2 text-sm mb-4 whitespace-nowrap">
                                Approved by HR
                            </button>
                            <button @click="$wire.setActiveTab('approvedBySupervisor')"
                                :class="{
                                    'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': $wire
                                        .activeTab === 'approvedBySupervisor',
                                    'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': $wire
                                        .activeTab !== 'approvedBySupervisor'
                                }"
                                class="h-min px-4 py-2 text-sm mb-4 whitespace-nowrap">
                                Approved by Supervisor
                            </button>
                            <button @click="$wire.setActiveTab('approved')"
                                :class="{
                                    'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': $wire
                                        .activeTab === 'approved',
                                    'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': $wire
                                        .activeTab !== 'approved'
                                }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Approved
                            </button>
                            <button @click="$wire.setActiveTab('disapproved')"
                                :class="{
                                    'font-bold text-violet-700 border-b-2 border-violet-700 dark:border-blue-600 dark:text-blue-600': $wire
                                        .activeTab === 'disapproved',
                                    'text-slate-700 font-bold dark:text-slate-300 dark:hover:border-b-slate-300 dark:hover:text-white hover:border-b-2 hover:border-b-slate-800 hover:text-black': $wire
                                        .activeTab !== 'disapproved'
                                }"
                                class="h-min px-4 py-2 text-sm mb-4">
                                Disapproved
                            </button>
                        </div>

                        <!-- Table for Leave Applications -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800">
                                <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                    <tr class="whitespace-nowrap">
                                        <th scope="col"
                                            class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Date of Filing</th>
                                        <th scope="col"
                                            class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Type of Leave</th>
                                        <th scope="col"
                                            class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Details of Leave</th>
                                        @if ($activeTab === 'pending')
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                Requested Day/s
                                            </th>
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                Requested Date/s
                                            </th>
                                        @else
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                Approved Day/s
                                            </th>
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                                Approved Date/s
                                            </th>
                                        @endif
                                        {{-- <th scope="col"
                                            class="px-5 py-3 text-sm font-medium text-left uppercase text-center">
                                            Status</th> --}}
                                        <th
                                            class="px-5 py-3 text-gray-100 text-sm font-medium text-center sticky top-0 right-0 z-10 bg-gray-600 dark:bg-gray-600 uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Filtering based on selected tab -->
                                    {{-- @foreach ($activeTab === 'pending' ? $pendingApplications : ($activeTab === 'approved' ? $approvedApplications : $disapprovedApplications) as $leaveApplication) --}}
                                    @foreach ($activeTab === 'pending' ? $pendingApplications : ($activeTab === 'approvedByHR' ? $approvedByHRApplications : ($activeTab === 'approvedBySupervisor' ? $approvedBySupervisorApplications : ($activeTab === 'approved' ? $approvedApplications : $disapprovedApplications))) as $leaveApplication)
                                        <tr class="whitespace-nowrap">
                                            <td class="px-4 py-2 text-center">
                                                {{ $leaveApplication->date_of_filing }}</td>
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
                                            @if ($activeTab === 'pending')
                                                <td class="px-4 py-2 text-center">
                                                    {{ $leaveApplication->number_of_days }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ \Illuminate\Support\Str::limit($leaveApplication->list_of_dates, 10, '...') }}
                                                </td>
                                            @else
                                                <td class="px-4 py-2 text-center">
                                                    {{ $leaveApplication->approved_days ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ \Illuminate\Support\Str::limit($leaveApplication->approved_dates, 10, '...') ?? 'N/A' }}
                                                </td>
                                            @endif
                                            {{-- <td class="px-4 py-2 text-center">
                                                <span
                                                    class="inline-block px-3 py-1 text-sm font-semibold 
                                                {{ in_array($leaveApplication->status, ['Approved by HR', 'Approved by Supervisor', 'Approved'])
                                                    ? 'text-green-800 bg-green-200'
                                                    : ($leaveApplication->status === 'Disapproved'
                                                        ? 'text-red-800 bg-red-200'
                                                        : 'text-yellow-800 bg-yellow-200') }} rounded-full">
                                                    {{ $leaveApplication->status }}
                                                </span>
                                            </td> --}}
                                            <td
                                                class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                {{-- @if (in_array($leaveApplication->status, ['Approved by HR', 'Approved by Supervisor', 'Approved', 'Disapproved'])) --}}
                                                <div class="relative">
                                                    <button type="button"
                                                        wire:click.prevent="exportPDF({{ $leaveApplication->id }})"
                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                -mr-2 text-sm font-medium tracking-wide text-red-500 hover:text-red-600 
                                                                focus:outline-none">
                                                        <i class="bi bi-file-earmark-arrow-down"
                                                            title="Export in PDF"></i>
                                                    </button>
                                                    <button wire:click="showPDF({{ $leaveApplication->id }})"
                                                        class="text-blue-500 hover:text-blue-600">
                                                        <i class="bi bi-eye" title="Show Details"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-5 border-t border-neutral-500 dark:border-neutral-200">
                            @if ($activeTab === 'pending')
                                {{ $pendingApplications->links() }}
                            @elseif ($activeTab === 'approvedByHR')
                                {{ $approvedByHRApplications->links() }}
                            @elseif ($activeTab === 'approvedBySupervisor')
                                {{ $approvedBySupervisorApplications->links() }}
                            @elseif ($activeTab === 'approved')
                                {{ $approvedApplications->links() }}
                            @else
                                {{ $disapprovedApplications->links() }}
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            {{-- Leave Application Form --}}
            <x-modal id="applyForLeave" maxWidth="5xl" wire:model="applyForLeave">
                <div class="p-4">
                    <div
                        class="bg-slate-800 rounded-t-lg dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold flex justify-between items-center">
                        <span>Basic Information</span>
                        <div class="relative group">
                            <i class="bi bi-info-circle cursor-pointer"></i>
                            <div
                                class="absolute right-0 w-64 bg-gray-700 text-white text-sm p-3 rounded border border-slate-50 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 z-50">
                                <strong>📌 Instructions:</strong><br><br>
                                <strong>Basic Information:</strong> This section is automatically filled with your
                                details.<br><br>
                                <strong>Details of Application:</strong><br>
                                - In <strong>Part A</strong>, select one leave type.<br>
                                - In <strong>Part B</strong>, choose one option that applies to your leave.<br>
                                - In <strong>Part C</strong>, select your leave dates. If you're sure, click "Add" to
                                confirm, or close it to make changes. The total days will be calculated
                                automatically.<br>
                                - In <strong>Part D</strong>, you must select one required option.<br><br>
                                <strong>Upload File:</strong> This step is optional. You may attach supporting documents
                                if needed.
                            </div>
                        </div>
                    </div>


                    <div class="border p-4">
                        <form>
                            <div class="gap-4">
                                <div class="gap-2 columns-1 w-full">
                                    <label for="surname"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-100">Fullname</label>
                                    <input type="text" id="surname" wire:model='name' disabled
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                </div>

                                <div class="gap-2 lg:columns-2 sm:columns-1 mt-2">
                                    <label for="office_or_department"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-100">Office/Division</label>
                                    <input type="text" id="office_or_department" wire:model="office_or_department"
                                        disabled
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                    @error('office_or_department')
                                        <span class="text-red-500 text-sm">This field is
                                            required!</span>
                                    @enderror

                                    <label for="date_of_filing"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-100">Date of
                                        Filing</label>
                                    <input type="date" id="date_of_filing" wire:model="date_of_filing" disabled
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                </div>

                                <div class="gap-2 lg:columns-2 sm:columns-1 mt-2">
                                    <label for="position"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-100">Position</label>
                                    <input type="text" id="position" wire:model="position" disabled
                                        class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                    @error('position')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror

                                    <label for="salary"
                                        class="block text-sm font-medium text-gray-700 dark:text-slate-100">Salary</label>
                                    <div class="mt-1 relative flex items-center">
                                        <span style="font-family: 'Arial', sans-serif; font-weight: bold;"
                                            class="absolute left-3">&#8369;</span>
                                        <input type="number" id="salary" wire:model="salary" disabled
                                            class="p-2 pl-8 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700">
                                    </div>
                                    @error('salary')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                            </div>
                        </form>
                    </div>

                    {{-- Form fields --}}
                    <div class="bg-gray-800 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                        Details of Application
                    </div>

                    <div class="border p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- A. --}}
                        <fieldset
                            class="border border-gray-300 p-4 rounded-lg overflow-y-auto w-full h-96 mb-4 md:mb-0">
                            <legend class="text-gray-700 dark:text-slate-100">A. Type of Leave to be availed of
                            </legend>
                            @error('type_of_leave')
                                <span class="text-red-500 text-sm">Please choose one!</span>
                            @enderror
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Vacation Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Vacation Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Mandatory/Forced Leave"
                                    wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Mandatory/Forced Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Sick Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Sick Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Maternity Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Maternity Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Paternity Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Paternity Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Special Privilege Leave"
                                    wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Special Privilege
                                    Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Solo Parent Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Solo Parent Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Study Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Study Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="10-Day VAWC Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">10-Day VAWC Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Rehabilitation Privilege"
                                    wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Rehabilitation
                                    Privilege</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Special Leave Benefits for Women"
                                    wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Special Leave Benefits for
                                    Women</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Special Emergency (Calamity) Leave"
                                    wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Special Emergency (Calamity)
                                    Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Adoption Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Adoption Leave</label>
                            </div>
                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="CTO Leave" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">CTO Leave</label>
                            </div>

                            <div class="gap-2 columns-1">
                                <input type="checkbox" value="Others" wire:model.live="type_of_leave">
                                <label class="text-md text-gray-700 dark:text-slate-100">Others (Please
                                    specify):</label>

                                @if (in_array('Others', $type_of_leave))
                                    <input type="text" id="other_leave" wire:model="other_leave"
                                        placeholder="Please specify"
                                        class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                @endif
                            </div>

                        </fieldset>

                        {{-- B. --}}
                        <fieldset
                            class="border border-gray-300 p-4 rounded-lg w-full h-96 mb-4 md:mb-0 overflow-y-auto">
                            <legend class="text-gray-700 dark:text-slate-100">B. Details of Leave</legend>

                            @error('details_of_leave')
                                <span class="text-red-500 text-sm">Please choose one!</span>
                            @enderror

                            <div
                                class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto">
                                <h6
                                    class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                    Other purpose:
                                </h6>
                                <div class="gap-2 columns-1">
                                    <input type="checkbox" class="ml-1" value="Monetization of Leave Credits"
                                        wire:model="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">Monetization of Leave
                                        Credits</label>
                                </div>
                                <div class="gap-2 columns-1 mt-4">
                                    <input type="checkbox" class="ml-1" value="Terminal Leave"
                                        wire:model="details_of_leave">
                                    <label class="text-md text-gray-700 dark:text-slate-100">Terminal Leave</label>
                                </div>
                            </div>

                            @if (in_array('Vacation Leave', $type_of_leave) || in_array('Special Privilege Leave', $type_of_leave))
                                <div
                                    class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                                    <h6
                                        class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                        In
                                        case of Vacation/Special Privilege Leave:</h6>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="gap-2 columns-1">
                                            <input type="checkbox" class="ml-1" value="Within the Philippines"
                                                wire:model.live="details_of_leave">
                                            <label class="text-md text-gray-700 dark:text-slate-100">Within the
                                                Philippines</label>
                                            @if (in_array('Within the Philippines', $details_of_leave))
                                                <input type="text" id="within_the_ph" wire:model="philippines"
                                                    placeholder="Please specify"
                                                    class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                            @endif
                                        </div>
                                        <div class="gap-2 columns-1">
                                            <input type="checkbox" class="ml-1" value="Abroad"
                                                wire:model.live="details_of_leave">
                                            <label class="text-md text-gray-700 dark:text-slate-100">Abroad
                                                (Specify)</label>
                                            @if (in_array('Abroad', $details_of_leave))
                                                <input type="text" id="abroad_value" wire:model="abroad"
                                                    placeholder="Please specify"
                                                    class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('Sick Leave', $type_of_leave))
                                <div
                                    class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                                    <h6
                                        class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                        In
                                        case of Sick Leave:</h6>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="gap-2 columns-1">
                                            <input type="checkbox" class="ml-1" value="In Hospital"
                                                wire:model.live="details_of_leave">
                                            <label class="text-md text-gray-700 dark:text-slate-100">In Hospital
                                                (Special
                                                Illness)</label>
                                            @if (in_array('In Hospital', $details_of_leave))
                                                <input type="text" id="in_hospital" wire:model="inHospital"
                                                    placeholder="Please specify"
                                                    class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                            @endif
                                        </div>
                                        <div class="gap-2 columns-1">
                                            <input type="checkbox" class="ml-1" value="Out Patient"
                                                wire:model.live="details_of_leave">
                                            <label class="text-md text-gray-700 dark:text-slate-100">Out Patient
                                                (Special
                                                Illness)</label>
                                            @if (in_array('Out Patient', $details_of_leave))
                                                <input type="text" id="out_patient" wire:model="outPatient"
                                                    placeholder="Please specify"
                                                    class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('Special Leave Benefits for Women', $type_of_leave))
                                <div
                                    class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                                    <h6
                                        class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                        In
                                        case of Special Leave Benefits for Women:</h6>
                                    <div class="gap-2 columns-1">
                                        <input type="checkbox" class="ml-1" value="Women Special Illness"
                                            wire:model.live="details_of_leave">
                                        <label class="text-md text-gray-700 dark:text-slate-100">(Special
                                            Illness)</label>
                                        @if (in_array('Women Special Illness', $details_of_leave))
                                            <input type="text" id="women_leave"
                                                wire:model="specialIllnessForWomen" placeholder="Please specify"
                                                class="mt-2 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (in_array('Study Leave', $type_of_leave))
                                <div
                                    class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700 max-h-60 overflow-y-auto mt-4">
                                    <h6
                                        class="mb-3 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                        In
                                        case of Study Leave:</h6>
                                    <div class="gap-2 columns-1">
                                        <input type="checkbox" class="ml-1" value="Completion of Masters Degree"
                                            wire:model="details_of_leave">
                                        <label class="text-md text-gray-700 dark:text-slate-100">Completion of Master's
                                            Degree</label>
                                    </div>
                                    <div class="gap-2 columns-1 mt-4">
                                        <input type="checkbox" class="ml-1" value="BAR/Board Examination Review"
                                            wire:model="details_of_leave">
                                        <label class="text-md text-gray-700 dark:text-slate-100">BAR/Board Examination
                                            Review</label>
                                    </div>
                                </div>
                            @endif
                        </fieldset>

                        {{-- C. --}}
                        {{-- <fieldset
                            class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                            <legend class="text-gray-700 dark:text-slate-100">C. Number of Working Days Applied for
                            </legend>
                            <div class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700">
                                <div class="gap-2 columns-1">
                                    <label class="text-sm text-gray-700 dark:text-slate-100">Days</label>
                                    <input type="number" id="number_of_days" wire:model="number_of_days"
                                        class="mt-1 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full">
                                    @error('number_of_days')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                @if (in_array('Vacation Leave', $type_of_leave) || in_array('Sick Leave', $type_of_leave) || in_array('Paternity Leave', $type_of_leave) || in_array('Special Privilege Leave', $type_of_leave) || in_array('Mandatory/Forced Leave', $type_of_leave) || in_array('Solo Parent Leave', $type_of_leave) || in_array('10-Day VAWC Leave', $type_of_leave) || in_array('Special Emergency (Calamity) Leave', $type_of_leave) || in_array('Adoption Leave', $type_of_leave) || in_array('CTO Leave', $type_of_leave))
                                    <div class="gap-2 columns-1 mt-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-100">List
                                            of
                                            Dates</label>
                                        <div class="gap-2 columns-2">
                                            <input type="date" wire:model="new_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                            <button><i
                                                    class="bi bi-plus-square dark:text-slate-50 text-slate-900 hover:text-slate-400"
                                                    wire:click="addDate" style="font-size: 2rem;"></i></button>
                                        </div>
                                        @error('list_of_dates')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="gap-2 columns-1 mt-2">
                                    <ul>
                                        @foreach ($list_of_dates as $index => $date)
                                            <li class="dark:text-slate-50 text-slate-900 flex items-center">
                                                <i class="bi bi-check-lg pr-4 text-green-600"></i>{{ $date }}
                                                <button wire:click="removeDate({{ $index }})"
                                                    class="ml-4 text-red-600">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @error('new_date')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <fieldset
                                    class="border border-red-400 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0 mt-2">
                                    <div class="gap-2 columns-1">
                                        <h6
                                            class="mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                            In case of Study Leave, Maternity Leave, Special Leave Benefits for Women,
                                            and
                                            Rehabilitation
                                            Leave:</h6>

                                        <div class="gap-2 columns-1 mt-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Start
                                                date</label>
                                            <input type="date" id="start_date" wire:model="start_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                        </div>

                                        <div class="gap-2 columns-1 mt-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-100 mt-2">End
                                                date</label>
                                            <input type="date" id="end_date" wire:model="end_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                        </fieldset> --}}
                        <fieldset
                            class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                            <legend class="text-gray-700 dark:text-slate-100">C. Number of Working Days Applied for
                            </legend>
                            <div class="w-full p-3 bg-slate-100 rounded-lg shadow-sm dark:bg-gray-700">
                                <div class="gap-2 columns-1">
                                    <label class="text-sm text-gray-700 dark:text-slate-100">Days</label>
                                    <input type="number" id="number_of_days" wire:model="number_of_days" readonly
                                        class="mt-1 p-2 block w-1/2 shadow-sm text-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 w-full bg-gray-100">
                                    @error('number_of_days')
                                        <span class="text-red-500 text-sm">This field is required!</span>
                                    @enderror
                                </div>

                                @if (in_array('Vacation Leave', $type_of_leave) ||
                                        in_array('Sick Leave', $type_of_leave) ||
                                        in_array('Paternity Leave', $type_of_leave) ||
                                        in_array('Special Privilege Leave', $type_of_leave) ||
                                        in_array('Mandatory/Forced Leave', $type_of_leave) ||
                                        in_array('Solo Parent Leave', $type_of_leave) ||
                                        in_array('10-Day VAWC Leave', $type_of_leave) ||
                                        in_array('Special Emergency (Calamity) Leave', $type_of_leave) ||
                                        in_array('Adoption Leave', $type_of_leave) ||
                                        in_array('CTO Leave', $type_of_leave))
                                    <div class="gap-2 columns-1 mt-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-100">List
                                            of Dates</label>
                                        <div class="gap-2 columns-2">
                                            <input type="date" wire:model="new_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                            <button wire:click="addDate"><i
                                                    class="bi bi-plus-square dark:text-slate-50 text-slate-900 hover:text-slate-400"
                                                    style="font-size: 2rem;"></i></button>
                                        </div>
                                        @error('list_of_dates')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                <div class="gap-2 columns-1 mt-2">
                                    <ul>
                                        @foreach ($list_of_dates as $index => $date)
                                            <li class="dark:text-slate-50 text-slate-900 flex items-center">
                                                <i class="bi bi-check-lg pr-4 text-green-600"></i>{{ $date }}
                                                <button wire:click="removeDate({{ $index }})"
                                                    class="ml-4 text-red-600">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @error('new_date')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <fieldset
                                    class="border border-red-400 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0 mt-2">
                                    <div class="gap-2 columns-1">
                                        <h6
                                            class="mt-2 mb-2 text-sm font-medium text-gray-900 dark:text-white italic bg-red-400 pl-1">
                                            In case of Study Leave, Maternity Leave, Special Leave Benefits for Women,
                                            and Rehabilitation Leave:</h6>

                                        <div class="gap-2 columns-1 mt-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-100">Start
                                                date</label>
                                            <input type="date" id="start_date" wire:model="start_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                        </div>

                                        <div class="gap-2 columns-1 mt-2">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-slate-100 mt-2">End
                                                date</label>
                                            <input type="date" id="end_date" wire:model="end_date"
                                                class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:bg-gray-100">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </fieldset>

                        {{-- D. --}}
                        <fieldset
                            class="border border-gray-300 p-4 rounded-lg overflow-hidden w-full h-full mb-4 md:mb-0">
                            <legend class="text-gray-700 dark:text-slate-100">D. Commutation</legend>
                            <div class="gap-2 columns-1">
                                <input type="radio" value="Requested" wire:model.live="commutation">
                                <label class="text-md text-gray-700 dark:text-slate-100">Requested</label>
                            </div>
                            <div class="gap-2 columns-1 mt-4">
                                <input type="radio" value="Not Requested" wire:model.live="commutation">
                                <label class="text-md text-gray-700 dark:text-slate-100">Not Requested</label>
                            </div>
                            @error('commutation')
                                <span class="text-red-500 text-sm">Please choose one!</span>
                            @enderror
                        </fieldset>

                        <!-- File upload section -->
                        <div
                            class="flex flex-col items-center justify-center w-full col-span-1 md:col-span-2 mt-4 md:mt-0">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Click
                                            to upload</span></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">JPEG, JPG, PNG or PDF</p>
                                </div>
                                <input id="dropzone-file" type="file" wire:model="files" multiple
                                    class="hidden" />
                            </label>

                            <!-- Display selected files -->
                            @if ($files)
                                <div class="mt-4">
                                    <ul class="list-disc list-inside">
                                        @foreach ($files as $index => $file)
                                            <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                {{ $file->getClientOriginalName() }}
                                                <button type="button" wire:click="removeFile({{ $index }})"
                                                    class="ml-2 text-red-500">
                                                    &times;
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @error('files')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            @error('files.*')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="bg-gray-800 dark:bg-gray-200 p-2 text-white flex justify-center rounded-b-lg border">
                        <button wire:click="submitLeaveApplication" role="status"
                            class="btn bg-emerald-200 dark:bg-emerald-500 hover:bg-emerald-600 text-gray-800 dark:text-white whitespace-nowrap mx-2">
                            Submit
                        </button>
                        <button wire:click="closeLeaveForm"
                            class="mr-2 bg-gray-500 text-white px-4 py-2 rounded mx-2">
                            Close
                        </button>
                    </div>
                </div>
            </x-modal>
        </div>
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('leaveType', () => ({
            vacationLeave: false,
            specialPrivilegeLeave: false,
            sickLeave: false,
            specialLeaveBenefitsForWomen: false,
            studyLeave: false,
        }));
    });

    function resizeIframe() {
        const iframe = document.getElementById('pdfIframe');
        const pdfDocument = iframe.contentDocument || iframe.contentWindow.document;

        if (pdfDocument) {
            // Set the iframe height based on the content
            iframe.style.height = pdfDocument.body.scrollHeight + 'px';
        }
    }

    // Adjust iframe size when the PDF is loaded
    document.getElementById('pdfIframe').onload = resizeIframe;

    // Optional: Adjust iframe size when the window is resized
    window.onresize = resizeIframe;
</script>
