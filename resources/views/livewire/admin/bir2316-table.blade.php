<div class="w-full flex flex-col justify-center">

    <style>
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
            color: rgb(255, 255, 255);
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
            color: rgb(0, 0, 0);
        }
    </style>

    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p-3 sm:p-6 shadow dark:bg-gray-800 overflow-x-visible">

            <div class="pb-4 mb-3 pt-4 sm:pt-0">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">BIR 2316</h1>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row items-end justify-between">

                {{-- Search COS Input --}}
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
                                                    Employee No.
                                                </th>
                                                <th scope="col"
                                                    class="px-5 py-3 text-sm font-medium text-center uppercase">
                                                    Date Employed
                                                </th>
                                                <th
                                                    class="px-5 py-3 text-gray-100 text-sm font-medium text-center sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                            @foreach ($employees as $user)
                                                <tr class="text-sm whitespace-nowrap">
                                                    <td class="px-4 py-2 text-left"> 
                                                        {{ $user->surname }}, {{ $user->first_name }}{{ $user->middle_name ? ' ' . $user->middle_name : ' ' }}{{ $user->name_extention ? ' ' . $user->name_extention : ' ' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->emp_code }}
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $user->date_hired ? \Carbon\Carbon::parse($user->date_hired)->format('F d, Y') : '' }}
                                                    </td>
            
                                                    <td
                                                        class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-white dark:bg-gray-800">
                                                        <button wire:click="showPDF({{ $user->user_id }})"
                                                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                            <i class="fas fa-eye" title="Show Details"></i>
                                                        </button>

                                                        <div class="relative mt-2" style="margin-right: -2px;">
                                                            <button
                                                                wire:click="toggleExportOption({{ $user->user_id }})"
                                                                class="peer inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2
                                                                text-sm font-medium tracking-wide text-green-500 hover:text-green-600 focus:outline-none"
                                                                title="Export Service Record" wire:target="toggleExportOption({{ $user->user_id }})">
                                                                <img class="flex dark:hidden ml-3"
                                                                    src="/images/icons8-xls-export-dark.png"
                                                                    width="18" height="18" alt="">
                                                                <img class="hidden dark:block ml-3"
                                                                    src="/images/icons8-xls-export-light.png"
                                                                    width="18" height="18" alt="">
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($employees->isEmpty())
                                        <div class="p-4 text-center text-gray-500 dark:text-gray-300">
                                            No records!
                                        </div> 
                                    @endif
                                </div>
                                <div class="p-5 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                    {{ $employees->links() }}
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- export option modal --}}
    <x-modal id="personalInfoModal" maxWidth="2xl" wire:model="exportId" centered>
        <div class="p-4">
            <div class="bg-slate-800 rounded-lg mb-4 dark:bg-gray-200 p-4 text-gray-50 dark:text-slate-900 font-bold">
                BIR 2316 - Choose a Year and Month Range
                <button @click="show = false" class="float-right focus:outline-none" wire:click='resetVariables'>
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Form fields --}}
            <form wire:submit.prevent='exportRecord'>
                <div class="grid grid-cols-2 gap-2">
                    
                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Employee Name</label>
                        <p class="text-gray-800 dark:text-gray-50 text-md">{{ $employee ? $employee->name : '' }}</p>
                    </div>

                    <div class="col-span-1">
                        <label for="userId" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Date Hired</label>
                        <p class="text-gray-800 dark:text-gray-50 text-md">{{ $employee ? \Carbon\Carbon::parse($employee->date_hired)->format('F d, Y') : '' }}</p>
                    </div>

                    <div class="col-span-2">
                        <label for="year" class="text-sm font-medium text-gray-700 dark:text-slate-400">Year</label>
                        <input type="number" id="year" wire:model.live='year' value="{{ $year }}"
                        class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                     <!-- Start Date -->
                     <div class="col-span-1">
                        <label for="startDate" class="text-sm font-medium text-gray-700 dark:text-slate-400">Start Month</label>
                        <input type="month" id="startDate" wire:model.live='startDate' value="{{ $startMonth }}" 
                            x-bind:disabled="!{{ $year }}" x-bind:min="{{ $year }} + '-01'" x-bind:max="{{ $year }} + '-12'"
                            class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                     <!-- End Date -->
                    <div class="col-span-1">
                        <label for="endDate" class="text-sm font-medium text-gray-700 dark:text-slate-400">End Month</label>
                        <input type="month" id="endDate" wire:model.live='endDate' value="{{ $endMonth }}"
                            x-bind:disabled="!{{ $year }}" x-bind:min="{{ $year }} + '-01'" x-bind:max="{{ $year }} + '-12'"                            class="mt-4 sm:mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>


                    <div class="mt-4 flex justify-end col-span-2">
                        <button class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex gap-2 items-center">
                            <span>Export</span>
                            <div wire:loading  class="w-full flex justify-end items-center" style="margin-top: -4px"
                                wire:target="exportRecord">
                                <div class="spinner-border small text-primary"
                                    role="status">
                                </div>
                            </div>
                        </button>
                        <p @click="show = false" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded cursor-pointer" wire:click='resetVariables'>
                            Cancel
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

</div>
