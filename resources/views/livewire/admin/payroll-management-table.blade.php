<div class="w-full flex justify-center">
    <div class="flex justify-center w-full">
        <div class="w-full bg-white rounded-2xl p3 sm:p-8 shadow dark:bg-gray-800 overflow-x-visible">
            <div class="pb-4 pt-4 sm:pt-1 mb-4">
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    Payroll for the month of {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F') : '' }} {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('Y') : '' }}
                </h1>
                <h1 class="text-lg font-bold text-center text-slate-800 dark:text-white">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d') : '' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d') : '' }}
                </h1>
            </div>

            <div class="block sm:flex items-center justify-between">

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
                </style>


                <div class="relative inline-block text-left">
                    <input type="search" id="search" wire:model.live="search" 
                    placeholder="Search..."
                    class="py-2 px-3 block w-full shadow-sm text-sm font-medium border-gray-400 
                    wire:text-neutral-800 dark:text-neutral-200 
                    dark:hover:bg-slate-600 dark:border-slate-600 mb-4
                    rounded-md dark:text-gray-300 dark:bg-gray-800 outline-none focus:outline-none">
                </div>

                <div class="block sm:flex items-center">

                    <!-- Start Date -->
                    <div class="col-span-2 sm:col-span-1 mr-0 sm:mr-4">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">Start Date</label>
                        <input type="date" id="startDate" wire:model.live='startDate' value="{{ $startDate }}"
                        class="mb-10 mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                     <!-- End Date -->
                    <div class="col-span-2 sm:col-span-1 mr-0 sm:mr-4">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-slate-400">End Date</label>
                        <input type="date" id="endDate" wire:model.live='endDate' value="{{ $endDate }}"
                        class="mb-10 mt-1 px-2 py-1.5 block w-full shadow-sm sm:text-sm border border-gray-400 hover:bg-gray-300 rounded-md 
                            dark:hover:bg-slate-600 dark:border-slate-600
                            dark:text-gray-300 dark:bg-gray-800">
                    </div>

                    <!-- Sort Dropdown -->
                    {{-- <div class="relative inline-block text-left mr-0 sm:mr-4">
                        <button wire:click="toggleDropdown"
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-2 mb-4 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            Sort Column
                            <i class="bi bi-chevron-down w-5 h-5 ml-2"></i>
                        </button>
                        @if($sortColumn)
                            <div
                                class="absolute z-20 w-56 p-3 border border-gray-400 bg-white rounded-lg 
                                shadow-2xl dark:bg-gray-700 max-h-60 overflow-y-auto scrollbar-thin1">
                                <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Category</h6>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-center" wire:click='toggleAllColumn'>
                                        <input id="allCol" type="checkbox" wire:model.live="allCol"
                                            class="h-4 w-4">
                                        <label for="allCol" class="ml-2 text-gray-900 dark:text-gray-300">Select All</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="employee_number" type="checkbox" wire:model.live="columns.employee_number"
                                            class="h-4 w-4">
                                        <label for="employee_number" class="ml-2 text-gray-900 dark:text-gray-300">Employee Number</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="position" type="checkbox" wire:model.live="columns.position"
                                            class="h-4 w-4">
                                        <label for="position" class="ml-2 text-gray-900 dark:text-gray-300">Position</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="salary_grade" type="checkbox" wire:model.live="columns.salary_grade" class="h-4 w-4">
                                        <label for="salary_grade" class="ml-2 text-gray-900 dark:text-gray-300">Salary Grade</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="daily_salary_rate" type="checkbox" wire:model.live="columns.daily_salary_rate"
                                            class="h-4 w-4">
                                        <label for="daily_salary_rate"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Daily Salary Rate</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="no_of_days_covered" type="checkbox" wire:model.live="columns.no_of_days_covered"
                                        class="h-4 w-4">
                                        <label for="no_of_days_covered" class="ml-2 text-gray-900 dark:text-gray-300">No. of Days Covered</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gross_salary" type="checkbox" wire:model.live="columns.gross_salary"
                                            class="h-4 w-4">
                                        <label for="gross_salary"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Gross Salary</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="absences_days" type="checkbox" wire:model.live="columns.absences_days" class="h-4 w-4">
                                        <label for="absences_days" class="ml-2 text-gray-900 dark:text-gray-300">Absences (Days)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="absences_amount" type="checkbox" wire:model.live="columns.absences_amount" class="h-4 w-4">
                                        <label for="absences_amount" class="ml-2 text-gray-900 dark:text-gray-300">Absences (Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_hours" type="checkbox" wire:model.live="columns.late_undertime_hours"
                                            class="h-4 w-4">
                                        <label for="late_undertime_hours" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Hours)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_hours_amount" type="checkbox" wire:model.live="columns.late_undertime_hours_amount" class="h-4 w-4">
                                        <label for="late_undertime_hours_amount" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Hours -Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_mins" type="checkbox" wire:model.live="columns.late_undertime_mins"
                                            class="h-4 w-4">
                                        <label for="late_undertime_mins" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Minutes)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="late_undertime_mins_amount" type="checkbox" wire:model.live="columns.late_undertime_mins_amount"
                                            class="h-4 w-4">
                                        <label for="late_undertime_mins_amount" class="ml-2 text-gray-900 dark:text-gray-300">Late/Undertime (Mins - Amount)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="gross_salary_less" type="checkbox" wire:model.live="columns.gross_salary_less" class="h-4 w-4">
                                        <label for="gross_salary_less" class="ml-2 text-gray-900 dark:text-gray-300">Gross Salary (Less)</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="withholding_tax" type="checkbox" wire:model.live="columns.withholding_tax" class="h-4 w-4">
                                        <label for="withholding_tax" class="ml-2 text-gray-900 dark:text-gray-300">Withholding Tax</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="nycempc" type="checkbox"
                                            wire:model.live="columns.nycempc" class="h-4 w-4">
                                        <label for="nycempc" class="ml-2 text-gray-900 dark:text-gray-300">NYCEMPC</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="total_deductions" type="checkbox"
                                            wire:model.live="columns.total_deductions" class="h-4 w-4">
                                        <label for="total_deductions"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Total Deduction</label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="net_amount_due" type="checkbox"
                                            wire:model.live="columns.net_amount_due" class="h-4 w-4">
                                        <label for="net_amount_due"
                                            class="ml-2 text-gray-900 dark:text-gray-300">Net Amount Due</label>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div> --}}

                    <!-- Export to Excel -->
                    @if($hasPayroll ===  false)
                        <div class="relative inline-block text-left mr-0 sm:mr-4">
                            <button wire:click="recordPayroll"
                                class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                                justify-center px-4 pt-2 pb-1.5 mb-4 text-sm font-medium tracking-wide 
                                text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                                rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                                type="button">
                                Save Payroll
                            </button>
                        </div>
                    @endif

                    <!-- Export to Excel -->
                    <div class="relative inline-block text-left">
                        <button wire:click=""
                            class="inline-flex items-center dark:hover:bg-slate-600 dark:border-slate-600
                            justify-center px-4 py-1.5 mb-4 text-sm font-medium tracking-wide 
                            text-neutral-800 dark:text-neutral-200 transition-colors duration-200 
                            rounded-lg border border-gray-400 hover:bg-gray-300 focus:outline-none"
                            type="button">
                            <img class="flex dark:hidden" src="/images/export-excel.png" width="25" alt="">
                            <img class="hidden dark:block" src="/images/export-excel-dark.png" width="25" alt="">
                        </button>
                    </div>

                </div>
            </div>

            <!-- Table -->

            <div class="flex flex-col p-3">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block w-full py-2 align-middle">
                        <div class="overflow-hidden border dark:border-gray-700 rounded-lg">
                            <div class="overflow-x-auto">

                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-200 dark:bg-gray-700 rounded-xl">
                                        <tr class="whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-sm font-medium text-left uppercase">Name</th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Employee Number
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Position
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Salary Grade
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Daily Salary Rate
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                No. of Days Covered
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Gross Salary
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Absences (Days)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Absences (Amount)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Late/Undertime (Hours)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Late/Undertime (Hours -Amount)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Late/Undertime (Minutes)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Late/Undertime (Mins - Amount)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Gross Salary Less<br>(Absences/Lates/Undertime)
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Withholding Tax
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                NYCEMPC
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Total Deduction
                                            </th>
                                            <th scope="col" class="px-5 py-3 text-center text-sm font-medium text-left uppercase">
                                                Net Amount Due
                                            </th>
                                            <th class="px-5 py-3 text-gray-100 text-sm font-medium text-right uppercase sticky right-0 z-10 bg-gray-600 dark:bg-gray-600">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-gray-400">
                                        @foreach($payrolls as $payroll)
                                            <tr class="text-neutral-800 dark:text-neutral-200">
                                                @foreach($columns as $column)
                                                    <td class="px-5 py-4 text-center text-sm font-medium whitespace-nowrap">
                                                        @if(in_array($column, [
                                                            'daily_salary_rate',
                                                            'gross_salary',
                                                            'absences_amount',
                                                            'late_undertime_hours_amount',
                                                            'late_undertime_mins_amount',
                                                            'gross_salary_less',
                                                            'withholding_tax',
                                                            'nycempc',
                                                            'total_deductions',
                                                            'net_amount_due',
                                                        ]))
                                                            {{ currency_format($payroll[$column]) }}
                                                        @else
                                                            {{ $payroll[$column] ?? '' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="px-5 py-4 text-sm font-medium text-center whitespace-nowrap sticky right-0 z-10 bg-gray-100 dark:bg-gray-900">
                                                    <button wire:click="" class="inline-flex items-center justify-center px-4 py-2 -m-5 -mr-2 text-sm font-medium tracking-wide text-blue-500 hover:text-blue-600 focus:outline-none">
                                                        <i class="fas fa-file-export ml-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                
                            </div>
                            {{-- <div class="p-5 border-t border-gray-200 dark:border-slate-600 text-neutral-500 dark:text-neutral-200 bg-gray-200 dark:bg-gray-700">
                                {{ $payrolls->links() }}
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
