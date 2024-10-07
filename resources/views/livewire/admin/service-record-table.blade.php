<div class="w-full">
    
    <style>
        .scrollbar-thin1::-webkit-scrollbar {
                width: 5px;
            }

        .scrollbar-thin1::-webkit-scrollbar-thumb {
            background-color: #c0c0c04b;
        }

        .scrollbar-thin1::-webkit-scrollbar-track {
            background-color: #ffffff23;
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
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">Service Records</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">
                
                <div class="w-full sm:w-1/3 sm:mr-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" id="search" wire:model.live="search"
                        class="px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800"
                        placeholder="Enter employee name or ID">
                </div>

            </div>


            <!-- Table -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-left uppercase">
                                                Name
                                            </th>
                                            <th scope="col"
                                                class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                Employee Number
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-sm font-medium uppercase text-center">
                                                Years in Government Service
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach ($users as $user)
                                            <tr class="text-sm whitespace-nowrap">
                                                <td class="px-4 py-2 text-left">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $user->emp_code }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $user->formatted_gov_service }}
                                                </td>
                                                <td
                                                    class="px-5 py-4 text-sm font-medium text-right whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                    <button wire:click="toggleViewRecord({{ $user->id }})"
                                                        class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-eye" title="Show Details"></i>
                                                    </button>

                                                    <div class="relative mt-2" style="margin-right: -2px;">
                                                        <button
                                                            wire:click="exportRecord({{ $user->id }})"
                                                            class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2
                                                            text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                            title="Export Service Record" wire:target="exportRecord({{ $user->id }})"
                                                            wire:loading.remove>
                                                            <img class="flex dark:hidden ml-3"
                                                                src="/images/icons8-xls-export-dark.png"
                                                                width="18" height="18" alt="">
                                                            <img class="hidden dark:block ml-3"
                                                                src="/images/icons8-xls-export-light.png"
                                                                width="18" height="18" alt="">
                                                        </button>
                                                        <div wire:loading  class="w-full flex justify-end items-center" style="padding-right: 10px; margin-top: -5px"
                                                            wire:target="exportRecord({{ $user->id }})">
                                                            <div class="spinner-border small text-primary"
                                                                role="status">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    {{-- View Modal --}}
    <x-modal id="roleModal" maxWidth="4xl" wire:model="recordId">
        <div class="p-4">
            <div class="bg-slate-800 rounded-t-lg dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                {{ $thisRecord ? $thisRecord->name : '' }}'s Service Record
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="w-full overflow-x-hidden">
                
                <div class="w-full overflow-x-auto">
                    <div class="grid grid-cols-12 gap-0 text-xs bg-gray-200 dark:bg-gray-700" style="width: 1000px">
                        <div class="col-span-2 flex flex-col justify-center text-center border border-gray-400">
                            <div class="w-full text-center">
                                SERVICE <br>
                                ( Inclusive Dates )
                            </div> 
                            <div class="flex w-full">
                                <span class="w-full text-center border-r border-t border-gray-400">From</span>
                                <span class="w-full text-center border-t border-gray-400">To</span>
                            </div>
                        </div>
                        <div class="col-span-5 flex flex-col justify-center" style="margin-left: -1px;">
                            <div class="w-full text-center border border-gray-400">RECORD OF APPOINTMENT</div> 
                            <div class="w-full grid grid-cols-5 gap-0">
                                <div class="col-span-2 text-center flex items-center justify-center border-r border-b border-gray-400">
                                    Designation
                                </div>
                                <div class="col-span-1 text-center border-r border-b border-gray-400">
                                    Status <br>
                                    of Appt.
                                </div>
                                <div class="col-span-1 text-center border-r border-b border-gray-400">
                                    Basic <br>
                                    Salary
                                </div>
                                <div class="col-span-1 text-center border-r border-b border-gray-400">
                                    Longevity <br>
                                    Pay/Allow.
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1 flex justify-center text-center border-r border-b border-t border-gray-400">
                            OFFICE <br>
                            Place of <br>
                            Assignment
                        </div>
                        <div class="col-span-1 flex justify-center text-center border-r border-b border-t border-gray-400">
                            Branch <br>
                            of <br>
                            Service
                        </div>
                        <div class="col-span-1 flex justify-center text-center border-r border-b border-t border-gray-400">
                            LEAVE <br>
                            ABSENCES <br>
                            W/O PAY
                        </div>
                        <div class="col-span-2 flex justify-center items-center text-center border-r border-b border-t border-gray-400">
                            <div class="w-full text-center">REMARKS</div>
                        </div>
                    </div>
                    @if($serviceRecord)
                        @foreach ($serviceRecord as $record)
                            <div class="grid grid-cols-12 gap-0 text-xs border-b border-gray-400" style="width: 1000px">
                                <div class="col-span-2 flex flex-col justify-center text-center border-l border-r border-gray-400">
                                    <div class="flex w-full py-2">
                                        <span class="w-full text-center">{{ \Carbon\Carbon::parse($record->start_date)->format('m/d/y') }}</span>
                                        <strong>-</strong>
                                        <span class="w-full text-center">{{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('m/d/y') : $record->toPresent }}</span>
                                    </div>
                                </div>
                                <div class="col-span-5 flex flex-col justify-center" style="margin-left: -1px;">
                                    <div class="w-full grid grid-cols-5 gap-0">
                                        <div class="col-span-2 text-center flex items-center justify-center border-r border-gray-400 py-2">
                                            {{ $record->position }}
                                        </div>
                                        <div class="col-span-1 text-center flex items-center justify-center  border-r border-gray-400 py-2">
                                            {{ $record->status_of_appointment }}
                                        </div>
                                        <div class="col-span-1 text-center flex items-center justify-center  border-r border-gray-400 py-2">
                                            {{ $record->monthly_salary }}
                                        </div>
                                        <div class="col-span-1 text-center flex items-center justify-center  border-r border-gray-400 py-2">
                                            {{ $record->pera }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-1 text-center flex items-center justify-center border-r border-gray-400 py-2">
                                    {{ $record->place_of_assignment }}
                                </div>
                                <div class="col-span-1 text-center flex items-center justify-center border-r border-gray-400 py-2">
                                    {{ $record->branch }}
                                </div>
                                <div class="col-span-1 text-center flex items-center justify-center border-r border-gray-400 py-2">
                                    {{ $record->leave_absence_wo_pay }}
                                </div>
                                <div class="col-span-2 text-center flex items-center justify-center border-r border-gray-400 py-2">
                                    {{ $record->remarks }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                

                <div class="mt-4 flex justify-end w-full text-sm">
                    <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click='exportRecord'>
                        <div wire:loading wire:target="exportRecord" class="spinner-border small text-primary" role="status">
                        </div>
                        Export
                    </button>
                    <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                        Cancel
                    </p>
                </div>
            </div>

        </div>
    </x-modal>



</div>
