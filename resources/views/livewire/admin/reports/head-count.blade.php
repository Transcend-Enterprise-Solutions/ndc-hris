<div
    class="flex flex-col col-span-full sm:col-span-full bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-slate-200 dark:border-slate-700">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Total Employees Card -->
            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200">Total Employees</h3>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-300">{{ $totalEmployees }}</p>
            </div>
            
            <!-- New Employees This Month Card -->
            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">New Employees This Month</h3>
                <p class="text-3xl font-bold text-green-600 dark:text-green-300">{{ $newEmployeesThisMonth }}</p>
            </div>
            
            <!-- Department Distribution Card -->
            <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-purple-800 dark:text-purple-200">Department Distribution</h3>
                <ul class="mt-2">
                    @foreach($departmentCounts as $dept)
                        <li class="text-sm text-purple-600 dark:text-purple-300">
                            {{ $dept->department }}: {{ $dept->count }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</div>