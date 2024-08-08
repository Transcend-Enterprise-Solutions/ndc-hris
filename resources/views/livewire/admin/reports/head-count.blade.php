<div class="flex flex-col col-span-full sm:col-span-full bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">

    <style>
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
            color: rgb(0, 196, 75);
        }
    </style>

    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Head Count Report</h2>
        </header>
    </div>
    
    <div class="p-4">
        <div class="grid grid-cols-12 gap-4">

            <!-- Total Employees Card -->
            <div class="col-span-full sm:col-span-6 bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow flex justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-blue-800 dark:text-gray-200">Total Employees</h3>
                    <p class="text-3xl font-bold text-blue-600 dark:text-gray-200">{{ $totalEmployees }}</p>
                </div>

                <!-- Export to Excel -->
                <div class="w-1/5 sm:w-auto flex justify-center items-center h-full">
                    <button wire:click="exportTotalEmployee" 
                        class="inline-flex items-center focus:outline-none"
                        type="button" title="Export to Excel">
                        <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                        <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                    </button>
                    <div wire:loading wire:target="exportTotalEmployee" style="margin-right: 5px">
                        <div class="spinner-border small text-primary" role="status">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- New Employees This Month Card -->
            <div class="col-span-full sm:col-span-6 bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow block">
                <div class="block sm:flex justify-between items-center">
                    <div class="flex justify-left items-center">
                        <h3 class="text-sm font-semibold text-yellow-800 dark:text-gray-100">
                            @if($month)
                                Employees for the month of {{ $month ? \Carbon\Carbon::parse($month)->format('F') : '' }} {{ $month ? \Carbon\Carbon::parse($month)->format('Y') : '' }}
                            @else
                                New Employees This Month
                            @endif
                        </h3>
                    </div>
                    <div class="w-full sm:w-auto flex items-center relative">
                        <input type="month" id="month" wire:model.live='month' value="{{ $month }}"
                        class="px-2 py-1.5 block w-32 sm:text-sm border border-teal-800 rounded-md 
                            text-teal-800 dark:bg-gray-200 cursor-pointer">

                        <!-- Export to Excel -->
                        <div class="flex justify-center items-center w-1/5 h-full ml-2" style="width: 32px; height: 32px;">
                            <button wire:click="exportTotalEmployeeThisMonth" 
                                class="inline-flex items-center focus:outline-none"
                                type="button" title="Export to Excel" wire:loading.remove>
                                <img class="flex dark:hidden" src="/images/export-excel.png" alt="">
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" alt="">
                            </button>
                            <div wire:loading wire:target="exportTotalEmployeeThisMonth" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-100">{{ $newEmployeesThisMonth }}</p>
                </div>
            </div>
                        
            <!-- Department Distribution Card -->
            <div class="col-span-full sm:col-span-6 bg-purple-100 dark:bg-purple-800 p-4 rounded-lg shadow">
                <h3 class="text-sm font-semibold text-purple-800 dark:text-gray-100">Department Distribution</h3>
                <ul class="mt-2">
                    @foreach($departmentCounts as $dept)
                        <li class="text-sm text-purple-600 dark:text-purple-300">
                            <div class="flex bg-purple-50 dark:bg-purple-900 px-4 py-2 rounded-sm justify-between mb-2">
                                <div>
                                    <p class="text-sm text-purple-800 dark:text-purple-200">
                                        {{ $dept->department }}: {{ $dept->count }}
                                    </p>
                                </div>

                                <!-- Export to Excel -->
                                <div class="w-1/5 sm:w-auto flex justify-center items-center h-full">
                                    <button wire:click="exportTotalEmployeeInDepartment('{{ $dept->department }}')" 
                                        class="inline-flex items-center focus:outline-none"
                                        type="button" title="Export to Excel">
                                        <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                                        <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                                    </button>
                                    <div wire:loading wire:target="exportTotalEmployeeInDepartment('{{ $dept->department }}')" style="margin-right: 5px">
                                        <div class="spinner-border small text-primary" role="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Daily Attendance -->
            <div class="col-span-full sm:col-span-6 bg-teal-50 dark:bg-teal-800 p-4 rounded-lg shadow flex flex-col items-left">
                <div class="block sm:flex justify-between items-center">
                    <div class="flex justify-left items-center">
                        <img class="flex" src="/images/calendar.png" alt="">
                        <h3 class="text-sm font-semibold text-teal-800 dark:text-gray-100">Daily Attendance</h3>
                    </div>
                    <div class="w-full sm:w-auto flex items-center relative">
                        <input type="date" id="date" wire:model.live='date' value="{{ $date }}"
                        class="px-2 py-1.5 block w-32 sm:text-sm border border-teal-800 rounded-md 
                            text-teal-800 dark:bg-gray-200 cursor-pointer">

                         <!-- Export to Excel -->
                        <div class="flex justify-center items-center w-1/5 h-full ml-2" style="width: 32px; height: 32px;">
                            <button wire:click="exportTotalEmployeeDaily" 
                                class="inline-flex items-center focus:outline-none"
                                type="button" title="Export to Excel" wire:loading.remove>
                                <img class="flex dark:hidden" src="/images/export-excel.png" alt="">
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" alt="">
                            </button>
                            <div wire:loading wire:target="exportTotalEmployeeDaily" style="margin-right: 5px">
                                <div class="spinner-border small text-primary" role="status">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 sm:mt-0">
                    <h3 class="text-xs text-teal-800 dark:text-gray-100">Total Present/Late</h3>
                    <p class="text-3xl font-bold text-teal-700 dark:text-teal-100">{{ $dailyAttendance }}</p>
                </div>
                
            </div>

        </div>
    </div>

</div>