<div class="w-full flex flex-col justify-center"
x-data="{ 
    selectedTab: '{{ request()->query('tab', 'ob') }}',
}" 
x-cloak>

    <div id="ob-details"></div>

    <style>
        html {
            scroll-behavior: smooth;
        }

         #map {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }
        
        #map:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

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

        @media (max-width: 1024px){
            .custom-d{
                display: block;
            }
        }

        @media (max-width: 768px){
            .m-scrollable{
                width: 100%;
                overflow-x: scroll;
            }
        }

        @media (min-width:1024px){
            .custom-p{
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

        .spinner-border-2 {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            vertical-align: text-bottom;
            border: 2px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite;
            color: rgb(255, 255, 255);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Official Business Management</h1>
            </div>

            {{-- <div class="flex-col justify-center w-full bg-gray-200 dark:bg-slate-700 border border-gray-300 dark:border-gray-800 mb-3" style="border-radius: 8px;">
                <div wire:ignore class="w-full">
                    <div id="map" style="height: 250px; width: 100%; border-radius: 8px 8px 0 0; margin: 0;"></div>
                </div>
                <div class="text-sm grid grid-cols-2 mt-2 px-4 mb-2">
                    <div class="col-span-2 sm:col-span-1">
                        Employee: <span class="text-gray-800 dark:text-gray-50">{{ $employeeName ?? '...' }}</span><br>
                        Official Business: <span class="text-gray-800 dark:text-gray-50">{{ $company ?? '...' }}</span><br>
                        Address: <span class="text-gray-800 dark:text-gray-50">{{ $address ?? '...' }}</span> <br>
                        Date: <span class="text-gray-800 dark:text-gray-50">{{ $obDate ? \Carbon\Carbon::parse($obDate)->format('F d, Y') : '...' }}</span> <br>
                        Time: <span class="text-gray-800 dark:text-gray-50">{{ $obStartTime && $obEndTime ? ($obStartTime . ' - ' . $obEndTime) : '...' }}</span>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        Attendance: <span class="text-gray-800 dark:text-gray-50">{{ $obTimeIn && $obTimeOut ? ($obTimeIn . ' - ' . $obTimeOut) : '...' }}</span><br>
                        Purpose: <span class="text-gray-800 dark:text-gray-50">{{ $obPurpose ?? '...' }}</span><br>
                        <span class="{{ $approveOnly ? 'hidden' : '' }}">
                            Approved By: <span class="text-gray-800 dark:text-gray-50">{{ $approvedBy ?? '...' }}</span><br>
                            Date Approved: <span class="text-gray-800 dark:text-gray-50">{{ $approvedDate ?? '...' }}</span>
                        </span>
                        <span class="{{ $approveOnly ? '' : 'hidden' }}">
                            Disapproved By: <span class="text-gray-800 dark:text-gray-50">{{ $disapprovedBy ?? '...' }}</span><br>
                            Date Disapproved: <span class="text-gray-800 dark:text-gray-50">{{ $disapprovedDate ?? '...' }}</span>
                        </span>
                    </div>
                </div>

                @if($thisObId)
                    <div class="relative mt-1 flex gap-4 w-full mb-4 justify-center items-center">
                        <button wire:click="toogleConfirmModal({{ $thisObId }}, 'approve')" 
                            class="px-3 py-1 text-white rounded-md 
                            text-sm bg-green-500 hover:bg-green-600  
                            focus:outline-none" title="Approve">
                            Approve
                        </button>
                        <button wire:click="toogleConfirmModal({{ $thisObId }}, 'disapprove')" 
                            class="px-3 py-1 text-white rounded-md 
                            text-sm bg-red-500 hover:bg-red-600  
                            focus:outline-none {{ $approveOnly ? 'hidden' : '' }}" title="Disapprove">
                            Disapprove
                        </button>
                        <button wire:click="resetVariables" 
                            class="px-3 py-1 text-white rounded-md 
                            text-sm bg-gray-500 hover:bg-gray-600  
                            focus:outline-none" title="Cancel">
                            Cancel
                        </button>
                    </div>
                @endif
            </div> --}}

            <div class="w-full sm:w-1/3 sm:mr-4 mb-4" x-show="selectedTab === 'ob'">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                <input type="text" id="search" wire:model.live="search"
                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                    placeholder="Enter employee name or reference number">
            </div>

            <div class="w-full sm:w-1/3 sm:mr-4 mb-4" x-show="selectedTab === 'requests'">
                <label for="search2" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                <input type="text" id="search2" wire:model.live="search2"
                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                    placeholder="Enter employee name or reference number">
            </div>

            <div class="w-full sm:w-1/3 sm:mr-4 mb-4" x-show="selectedTab === 'disapproved'">
                <label for="search3" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                <input type="text" id="search3" wire:model.live="search3"
                    class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                        dark:hover:bg-slate-600 dark:border-slate-600
                        dark:text-gray-300 dark:bg-gray-800"
                    placeholder="Enter employee name or reference number">
            </div>

             <!-- Table -->
             <div class="flex flex-col">
                <div class="flex gap-2 overflow-x-auto -mb-2">
                    <button @click="selectedTab = 'ob'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'ob', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'ob' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Approved OB
                    </button>
                    <button @click="selectedTab = 'requests'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'requests', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'requests' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        OB Request
                    </button>
                    <button @click="selectedTab = 'disapproved'" 
                            :class="{ 'font-bold dark:text-gray-300 dark:bg-gray-700 bg-gray-200 rounded-t-lg': selectedTab === 'disapproved', 'text-slate-700 font-medium dark:text-slate-300 dark:hover:text-white hover:text-black': selectedTab !== 'disapproved' }" 
                            class="h-min px-4 pt-2 pb-4 text-sm text-nowrap">
                        Disapproved OB
                    </button>
                </div>
                <div class="-my-2 overflow-x-auto">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div x-show="selectedTab === 'ob'">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Reference No.
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Company
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Address
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Date
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Time
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Attendance
                                                </th>
                                                <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                @foreach($obs as $employee)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->reference_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->name_extension ?? '' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->company }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->address ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->date }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_start }} - {{ $employee->time_end }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_in }} - {{ $employee->time_out }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <a href="#ob-details">
                                                                <div class="relative">
                                                                    <button wire:click="viewThisOB({{ $employee->id }} , 'ob')" 
                                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                        -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                        focus:outline-none" title="View">
                                                                        <i class="fas fa-eye ml-3"></i>
                                                                    </button>
                                                                </div>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                    @if ($obs->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            No records!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $obs->links() }}
                                </div>
                             </div>
                            <div x-show="selectedTab === 'requests'">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Reference No.
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Company
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Address
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Date
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Time
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Attendance
                                                </th>
                                                <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                @foreach($obRequests as $employee)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->reference_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->name_extension ?? '' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->company }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->address ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->date }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_start }} - {{ $employee->time_end }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_in }} - {{ $employee->time_out }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <a href="#ob-details">
                                                                <div class="relative">
                                                                    <button wire:click="viewThisOB({{ $employee->id }}, 'request')" 
                                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                        -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                        focus:outline-none" title="View">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </a>
                                                            <div class="relative mt-1">
                                                                <button wire:click="toogleConfirmModal({{ $employee->id }}, 'approve')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-green-500 hover:text-green-600  
                                                                    focus:outline-none" title="Approve">
                                                                    <i class="bi bi-check-square"></i>
                                                                </button>
                                                            </div>
                                                            <div class="relative mt-1">
                                                                <button wire:click="toogleConfirmModal({{ $employee->id }}, 'disapprove')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-red-500 hover:text-red-600  
                                                                    focus:outline-none" title="Disapprove">
                                                                    <i class="bi bi-x-square"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                    @if ($obRequests->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            No requests!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $obRequests->links() }}
                                </div>
                             </div>
                            <div x-show="selectedTab === 'disapproved'">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-full">
                                        <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                            <tr class="whitespace-nowrap">
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Reference No.
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-left text-sm font-medium uppercase">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Company
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Address
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Date
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Time
                                                </th>
                                                <th scope="col" class="px-5 py-3 text-center text-sm font-medium uppercase">
                                                    Attendance
                                                </th>
                                                <th class="px-5 py-3 text-gray-100 text-sm font-medium text-center uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                                @foreach($disapprovedObs as $employee)
                                                    <tr class="text-neutral-800 dark:text-neutral-200">
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->reference_number }}
                                                        </td>
                                                        <td class="px-5 py-4 text-left text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->surname }}, {{ $employee->first_name }} {{ $employee->middle_name ?? '' }} {{ $employee->name_extension ?? '' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->company }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->address ?? 'None' }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->date }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_start }} - {{ $employee->time_end }}
                                                        </td>
                                                        <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                            {{ $employee->time_in }} - {{ $employee->time_out }}
                                                        </td>
                                                        <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                            <a href="#ob-details">
                                                                <div class="relative">
                                                                    <button wire:click="viewThisOB({{ $employee->id }}, 'disapproved')" 
                                                                        class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                        -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 
                                                                        focus:outline-none" title="View">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </a>
                                                            <div class="relative mt-1">
                                                                <button wire:click="toogleConfirmModal({{ $employee->id }}, 'approve')" 
                                                                    class="peer inline-flex items-center justify-center px-4 py-2 -m-5 
                                                                    -mr-2 text-sm font-medium tracking-wide text-green-500 hover:text-green-600  
                                                                    focus:outline-none" title="Approve">
                                                                    <i class="bi bi-check-square"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                    @if ($disapprovedObs->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            No records!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $disapprovedObs->links() }}
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- Confirm Modal --}}
   <x-modal id="confirmModal" maxWidth="md" wire:model="confirmId" centered>
        <div class="p-4">
            <div class="mb-4 text-slate-900 dark:text-gray-100 font-bold">
                {{ $confirmMessage == 'approve' ? 'Confirm approval' : 'Confirm disapproval' }}
                <button @click="show = false" class="float-right focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                Are you sure you want to {{ $confirmMessage == 'approve' ? 'approve' : 'disapprove' }} this request?
            </label>

            <div class="mt-4 flex justify-end col-span-1 sm:col-span-1">
                @if($confirmMessage == 'approve')
                    <button wire:click='approveEmployeeLocation' class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        <div wire:loading wire:target="approveEmployeeLocation" style="margin-bottom: 5px;">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        Approve
                    </button>
                @else
                    <button wire:click='disapproveEmployeeLocation' class="mr-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <div wire:loading wire:target="disapproveEmployeeLocation" style="margin-bottom: 5px;">
                            <div class="spinner-border small text-primary" role="status">
                            </div>
                        </div>
                        Disapprove
                    </button>
                @endif
                <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer">
                    Cancel
                </p>
            </div>

        </div>
    </x-modal>

    {{-- View OB Modal --}}
    <x-modal id="obModal" maxWidth="2xl" wire:model="viewOB">
    <div class="p-4">
        <div class="rounded-lg mb-4 p-4 dark:text-gray-50 text-slate-900 font-bold text-lg">
            Official Business: {{ $company}}
            <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label for="company" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Company</label>
                <input type="text" id="company" wire:model='company' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Address</label>
                <input type="text" id="address" wire:model='address' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2">
                <label for="obDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date</label>
                <input type="obDate" id="obDate" wire:model='obDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="obStartTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start Time</label>
                <input type="time" id="obStartTime" wire:model='obStartTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="obEndTime" class="block text-sm font-medium text-gray-700 dark:text-slate-400">End Time</label>
                <input type="time" id="obEndTime" wire:model='obEndTime' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2">
                <label for="obPurpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Purpose</label>
                <textarea type="text" id="obPurpose" cols="30" rows="4" wire:model='obPurpose' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly></textarea>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="approvedBy" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved By</label>
                <input type="text" id="approvedBy" wire:model='approvedBy' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
            <div class="col-span-2 sm:col-span-1">
                <label for="approvedDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Approved Date</label>
                <input type="text" id="approvedDate" wire:model='approvedDate' class="mt-1 p-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700" readonly>
            </div>
        </div>

        {{-- <div class="mt-4  mb-1">
            <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Geolocation</label>
        </div>
        <div class="flex-col justify-center w-full bg-gray-200 dark:bg-slate-700 border border-gray-300 mb-2" style="border-radius: 8px;">
            <div wire:ignore class="w-full">
                <div id="map3" style="height: 250px; width: 100%; margin: 0;"></div>
            </div>

            <div class="text-sm flex mt-2 px-4">
                <div class="w-1/2 mb-2">
                    Lat: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLatitude ?? '...' }}</span> <br>
                    Lng: <span class="text-gray-800 dark:text-gray-50">{{ $registeredLongitude ?? '...' }}</span>
                </div>
            </div>
        </div> --}}

        <div class="mt-6 flex justify-end col-span-2 gap-4">
            @if($thisObId)
                <button wire:click="toogleConfirmModal({{ $thisObId }}, 'approve')" 
                    class="px-3 py-1 text-white rounded-md 
                    text-sm bg-green-500 hover:bg-green-600  
                    focus:outline-none" title="Approve">
                    Approve
                </button>
                <button wire:click="toogleConfirmModal({{ $thisObId }}, 'disapprove')" 
                    class="px-3 py-1 text-white rounded-md 
                    text-sm bg-red-500 hover:bg-red-600  
                    focus:outline-none {{ $approveOnly ? 'hidden' : '' }}" title="Disapprove">
                    Disapprove
                </button>
            @endif
            <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                Close
            </p>
        </div>
        
    </div>
    </x-modal>


</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLp1y5i3ftfv5O_BN0_YSMd0VrXUht-Bs"></script>
<script>
    let map;
    let marker;
    
    function initMap() {
        const defaultLocation = { lat: 14.5995, lng: 120.9842 };
        
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: defaultLocation,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });
    }

    function updateMap() {
        const lat = @this.registeredLatitude;
        const lng = @this.registeredLongitude;
        
        if (lat && lng) {
            if (!map) {
                initMap();
            }
            const newLocation = { lat: parseFloat(lat), lng: parseFloat(lng) };
            map.setCenter(newLocation);
            if (marker) {
                marker.setPosition(newLocation);
            } else {
                marker = new google.maps.Marker({
                    position: newLocation,
                    map: map,
                    title: 'Your Location',
                    animation: google.maps.Animation.DROP
                });
            }
        }
    }
    

    document.addEventListener('DOMContentLoaded', initMap); 
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('location-updated', () => {
            updateMap();
        });
    });

    Alpine.effect(() => {
        if (selectedTab !== 'employees') {
            if (marker) {
                marker.setMap(null);
                marker = null;
            }
        }
    });

</script>