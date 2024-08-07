<div
    class="flex flex-col col-span-full sm:col-span-full bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Head Count Report</h2>
            <div class="relative inline-flex" x-data="{ open: false }">
                <button class="rounded-full"
                    :class="open ? 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400': 'text-slate-400 hover:text-slate-500 dark:text-slate-500 dark:hover:text-slate-400'"
                    aria-haspopup="true" @click.prevent="open = !open" :aria-expanded="open">
                    <span class="sr-only">Menu</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                        <circle cx="16" cy="16" r="2" />
                        <circle cx="10" cy="16" r="2" />
                        <circle cx="22" cy="16" r="2" />
                    </svg>
                </button>
            </div>
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
                </div>
            </div>
            
            <!-- New Employees This Month Card -->
            <div class="col-span-full sm:col-span-6 bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow flex justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-yellow-800 dark:text-gray-100">New Employees This Month</h3>
                    <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-100">{{ $newEmployeesThisMonth }}</p>
                </div>

                 <!-- Export to Excel -->
                 <div class="w-1/5 sm:w-auto flex justify-center items-center h-full">
                    <button wire:click="exportUsers" 
                        class="inline-flex items-center focus:outline-none"
                        type="button" title="Export to Excel">
                        <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                        <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                    </button>
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
                                    <button wire:click="exportUsers" 
                                        class="inline-flex items-center focus:outline-none"
                                        type="button" title="Export to Excel">
                                        <img class="flex dark:hidden" src="/images/export-excel.png" width="22" alt="">
                                        <img class="hidden dark:block" src="/images/export-excel-dark.png" width="22" alt="">
                                    </button>
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
                        <img class="flex dark:hidden" src="/images/calendar.png" alt="">
                        <h3 class="text-sm font-semibold text-teal-800 dark:text-gray-100">Daily Attendance</h3>
                    </div>
                    <div class="w-full sm:w-auto flex items-center relative">
                        <input type="date" id="endDate" wire:model.live='date' value="{{ $date }}"
                        class="px-2 py-1.5 block w-4/5 sm:text-sm border border-teal-800 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600
                            text-teal-800 dark:bg-teal-400">

                         <!-- Export to Excel -->
                        <div class="flex justify-center items-center w-1/5 h-full ml-2" style="width: 32px; height: 32px;">
                            <button wire:click="exportUsers" 
                                class="inline-flex items-center focus:outline-none"
                                type="button" title="Export to Excel">
                                <img class="flex dark:hidden" src="/images/export-excel.png" alt="">
                                <img class="hidden dark:block" src="/images/export-excel-dark.png" alt="">
                            </button>
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